<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components;

use AndyDefer\ConsoleWriter\Console\Abstracts\Component;
use AndyDefer\ConsoleWriter\Console\ValueObjects\CleanedTextVO;
use AndyDefer\DomainStructures\Utils\ListCollection;
use AndyDefer\PhpVo\ValueObjects\Types\StringVO;

/**
 * Affiche plusieurs blocs côte à côte dans la console
 * Chaque colonne a sa propre largeur basée sur son élément le plus long
 *
 * @example
 * Columns::render(
 *     ListCollection::from([
 *         ListCollection::from(['Users', '123']),
 *         ListCollection::from(['Servers', '5']),
 *         ListCollection::from(['Logs', '42'])
 *     ])
 * );
 *
 * // Sortie:
 * //  Users        Servers        Logs
 * //   123           5             42
 */
final class Columns extends Component
{
    private const DEFAULT_SEPARATOR = '   ';

    private const DEFAULT_WIDTH = 20;

    private const MIN_PADDING = 2;

    public static function render(
        ListCollection|array $columns,
        int $width = self::DEFAULT_WIDTH,
        string $separator = self::DEFAULT_SEPARATOR
    ): string {
        $columnsCollection = self::normalizeColumns($columns);

        if ($columnsCollection->isEmpty()) {
            return '<fg=yellow>⚠️  No data to display</fg=yellow>';
        }

        $cleanColumns = self::cleanEmojisFromColumns($columnsCollection);
        $vt = self::getVT();
        $vt->clear();

        $maxRows = $cleanColumns->reduce(
            fn ($carry, $column) => max($carry, $column->count()),
            0
        );

        // ✅ Largeur calculée par colonne (basée sur l'élément le plus long de chaque colonne)
        $columnWidths = self::calculateColumnWidths($cleanColumns, $width);

        for ($rowIndex = 0; $rowIndex < $maxRows; $rowIndex++) {
            $line = '';
            $colIndex = 0;

            foreach ($cleanColumns as $index => $column) {
                $cell = $column->get($rowIndex) ?? '';
                $colWidth = $columnWidths->get($index);

                $padded = self::padCenter($cell, $colWidth);

                if ($rowIndex === 0) {
                    $padded = self::bold($padded);
                }

                $line .= $padded;

                if ($colIndex < $cleanColumns->count() - 1) {
                    $line .= $separator;
                }
                $colIndex++;
            }

            $vt->add('line_'.$rowIndex, $line);
        }

        $vt->render();

        return $vt->getLines()->reduce(
            fn ($carry, $line) => $carry === '' ? $line : $carry.PHP_EOL.$line,
            ''
        );
    }

    public static function renderWithColors(
        ListCollection|array $columns,
        array $colors = [],
        int $width = self::DEFAULT_WIDTH,
        string $separator = self::DEFAULT_SEPARATOR
    ): string {
        $columnsCollection = self::normalizeColumns($columns);

        if ($columnsCollection->isEmpty()) {
            return '<fg=yellow>⚠️  No data to display</fg=yellow>';
        }

        $cleanColumns = self::cleanEmojisFromColumns($columnsCollection);
        $vt = self::getVT();
        $vt->clear();

        $maxRows = $cleanColumns->reduce(
            fn ($carry, $column) => max($carry, $column->count()),
            0
        );

        $columnWidths = self::calculateColumnWidths($cleanColumns, $width);

        for ($rowIndex = 0; $rowIndex < $maxRows; $rowIndex++) {
            $line = '';
            $colIndex = 0;

            foreach ($cleanColumns as $index => $column) {
                $cell = $column->get($rowIndex) ?? '';
                $color = $colors[$index] ?? 'white';
                $colWidth = $columnWidths->get($index);

                $padded = self::padCenter($cell, $colWidth);

                if ($rowIndex === 0) {
                    $padded = self::bold($padded);
                }

                $line .= self::fg($padded, $color);

                if ($colIndex < $cleanColumns->count() - 1) {
                    $line .= $separator;
                }
                $colIndex++;
            }

            $vt->add('line_'.$rowIndex, $line);
        }

        $vt->render();

        return $vt->getLines()->reduce(
            fn ($carry, $line) => $carry === '' ? $line : $carry.PHP_EOL.$line,
            ''
        );
    }

    /**
     * Affiche des colonnes avec des icônes
     */
    public static function renderWithIcons(
        ListCollection|array $columns,
        int $width = self::DEFAULT_WIDTH,
        string $separator = self::DEFAULT_SEPARATOR
    ): string {
        // renderWithIcons est un alias de render (les icônes sont déjà dans les données)
        return self::render($columns, $width, $separator);
    }

    public static function renderWithHeaders(
        ListCollection|array $columns,
        int $width = self::DEFAULT_WIDTH,
        string $separator = self::DEFAULT_SEPARATOR
    ): string {
        $columnsCollection = self::normalizeColumns($columns);

        if ($columnsCollection->isEmpty()) {
            return '<fg=yellow>⚠️  No data to display</fg=yellow>';
        }

        $cleanColumns = self::cleanEmojisFromColumns($columnsCollection);
        $vt = self::getVT();
        $vt->clear();

        $maxRows = $cleanColumns->reduce(
            fn ($carry, $column) => max($carry, $column->count()),
            0
        );

        $columnWidths = self::calculateColumnWidths($cleanColumns, $width);

        // Ligne d'en-tête
        $headerLine = '';
        $colIndex = 0;
        foreach ($cleanColumns as $index => $column) {
            $header = $column->get(0) ?? '';
            $colWidth = $columnWidths->get($index);
            $padded = self::padCenter($header, $colWidth);
            $headerLine .= self::bold($padded);

            if ($colIndex < $cleanColumns->count() - 1) {
                $headerLine .= $separator;
            }
            $colIndex++;
        }
        $vt->add('line_0', $headerLine);

        // Séparateur
        $sepLine = '';
        $colIndex = 0;
        foreach ($cleanColumns as $index => $column) {
            $colWidth = $columnWidths->get($index);
            $sepLine .= str_repeat('─', $colWidth);
            if ($colIndex < $cleanColumns->count() - 1) {
                $sepLine .= str_repeat(' ', mb_strlen($separator));
            }
            $colIndex++;
        }
        $vt->add('line_1', $sepLine);

        // Données
        for ($rowIndex = 1; $rowIndex < $maxRows; $rowIndex++) {
            $line = '';
            $colIndex = 0;

            foreach ($cleanColumns as $index => $column) {
                $cell = $column->get($rowIndex) ?? '';
                $colWidth = $columnWidths->get($index);

                $padded = self::padCenter($cell, $colWidth);
                $line .= $padded;

                if ($colIndex < $cleanColumns->count() - 1) {
                    $line .= $separator;
                }
                $colIndex++;
            }

            $vt->add('line_'.($rowIndex + 1), $line);
        }

        $vt->render();

        return $vt->getLines()->reduce(
            fn ($carry, $line) => $carry === '' ? $line : $carry.PHP_EOL.$line,
            ''
        );
    }

    public static function renderCompact(
        ListCollection|array $columns,
        string $separator = self::DEFAULT_SEPARATOR
    ): string {
        $columnsCollection = self::normalizeColumns($columns);

        if ($columnsCollection->isEmpty()) {
            return '<fg=yellow>⚠️  No data to display</fg=yellow>';
        }

        $cleanColumns = self::cleanEmojisFromColumns($columnsCollection);
        $vt = self::getVT();
        $vt->clear();

        $maxRows = $cleanColumns->reduce(
            fn ($carry, $column) => max($carry, $column->count()),
            0
        );

        // ✅ Largeur calculée par colonne pour le mode compact
        $columnWidths = self::calculateColumnWidths($cleanColumns, 0);

        for ($rowIndex = 0; $rowIndex < $maxRows; $rowIndex++) {
            $line = '';
            $colIndex = 0;

            foreach ($cleanColumns as $index => $column) {
                $cell = $column->get($rowIndex) ?? '';
                $colWidth = $columnWidths->get($index);

                $padded = self::padCenter($cell, $colWidth);

                if ($rowIndex === 0) {
                    $padded = self::bold($padded);
                }

                $line .= $padded;

                if ($colIndex < $cleanColumns->count() - 1) {
                    $line .= $separator;
                }
                $colIndex++;
            }

            $vt->add('line_'.$rowIndex, $line);
        }

        $vt->render();

        return $vt->getLines()->reduce(
            fn ($carry, $line) => $carry === '' ? $line : $carry.PHP_EOL.$line,
            ''
        );
    }

    /**
     * ✅ Calcule les largeurs par colonne
     * Chaque colonne a sa propre largeur basée sur son élément le plus long
     */
    private static function calculateColumnWidths(ListCollection $columns, int $minWidth): ListCollection
    {
        return $columns->map(
            function ($column) use ($minWidth) {
                $maxLength = $column->reduce(
                    fn ($carry, $cell) => max($carry, mb_strlen($cell)),
                    0
                );

                // Ajouter un padding minimum
                $width = $maxLength + self::MIN_PADDING;

                // Appliquer la largeur minimale si spécifiée
                if ($minWidth > 0) {
                    $width = max($width, $minWidth);
                }

                return $width;
            }
        );
    }

    /**
     * ✅ Nettoie les émojis des colonnes
     */
    private static function cleanEmojisFromColumns(ListCollection $columns): ListCollection
    {
        $cleanColumns = [];
        foreach ($columns as $column) {
            $columnArray = $column instanceof ListCollection ? $column->toArray() : (array) $column;
            $cleanColumn = [];
            foreach ($columnArray as $cell) {
                $cleanColumn[] = (new CleanedTextVO(StringVO::from($cell)->getValue()))->withoutEmojis()->getValue();
            }
            $cleanColumns[] = ListCollection::from($cleanColumn);
        }

        return ListCollection::from($cleanColumns);
    }

    private static function normalizeColumns(ListCollection|array $columns): ListCollection
    {
        if ($columns instanceof ListCollection) {
            $normalized = $columns->map(
                fn ($column) => $column instanceof ListCollection ? $column : ListCollection::from((array) $column)
            );

            return $normalized;
        }

        $result = [];
        foreach ($columns as $column) {
            $result[] = is_array($column) ? ListCollection::from($column) : ListCollection::from([$column]);
        }

        return ListCollection::from($result);
    }

    private static function padCenter(string $text, int $width): string
    {
        $textLength = mb_strlen($text);
        $padding = $width - $textLength;

        if ($padding <= 0) {
            return $text;
        }

        $left = (int) floor($padding / 2);
        $right = $padding - $left;

        return str_repeat(' ', $left).$text.str_repeat(' ', $right);
    }
}
