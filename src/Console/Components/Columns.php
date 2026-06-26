<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components;

use AndyDefer\ConsoleWriter\Console\Enums\FgColor;
use AndyDefer\ConsoleWriter\Console\Enums\Options;
use AndyDefer\ConsoleWriter\Console\Services\AnsiConverterService;
use AndyDefer\ConsoleWriter\Contracts\Services\AnsiConverterInterface;
use AndyDefer\DomainStructures\Utils\ListCollection;

/**
 * Affiche plusieurs blocs côte à côte dans la console
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
final class Columns
{
    private const DEFAULT_SEPARATOR = '   ';

    private const DEFAULT_WIDTH = 20;

    private static ?AnsiConverterInterface $ansi = null;

    private static function getAnsi(): AnsiConverterInterface
    {
        if (self::$ansi === null) {
            self::$ansi = new AnsiConverterService;
        }

        return self::$ansi;
    }

    /**
     * Affiche plusieurs colonnes avec centrage
     *
     * @param  ListCollection|array  $columns  Chaque colonne est un ListCollection ou un array
     * @param  int  $width  Largeur de chaque colonne
     * @param  string  $separator  Séparateur entre les colonnes
     */
    public static function render(ListCollection|array $columns, int $width = self::DEFAULT_WIDTH, string $separator = self::DEFAULT_SEPARATOR): string
    {
        $columnsCollection = self::normalizeColumns($columns);

        if ($columnsCollection->isEmpty()) {
            return '<fg=yellow>⚠️  No data to display</fg=yellow>';
        }

        $ansi = self::getAnsi();
        $lines = [];

        // Calculer le nombre maximal de lignes
        $maxRows = $columnsCollection->reduce(
            fn ($carry, $column) => max($carry, $column->count()),
            0
        );

        // Calculer les largeurs de chaque colonne
        $columnWidths = $columnsCollection->map(
            fn ($column) => $column->reduce(
                fn ($carry, $cell) => max($carry, mb_strlen($cell)),
                0
            ) + 2
        );

        // Largeur minimale
        $columnWidths = $columnWidths->map(
            fn ($w) => max($w, $width)
        );

        // Construire chaque ligne
        for ($rowIndex = 0; $rowIndex < $maxRows; $rowIndex++) {
            $line = '';
            $colIndex = 0;

            foreach ($columnsCollection as $index => $column) {
                $cell = $column->get($rowIndex) ?? '';
                $colWidth = $columnWidths->get($index);

                // Centrer le texte
                $padded = self::padCenter($cell, $colWidth);

                // Appliquer le style bold au premier élément de chaque colonne
                if ($rowIndex === 0) {
                    $padded = $ansi->option($padded, Options::BOLD);
                }

                $line .= $padded;

                if ($colIndex < $columnsCollection->count() - 1) {
                    $line .= $separator;
                }
                $colIndex++;
            }

            $lines[] = $line;
        }

        return implode(PHP_EOL, $lines);
    }

    /**
     * Affiche des colonnes avec icônes (centré)
     *
     * @param  ListCollection|array  $columns  Chaque colonne est un ListCollection ou un array
     */
    public static function renderWithIcons(ListCollection|array $columns, int $width = self::DEFAULT_WIDTH, string $separator = self::DEFAULT_SEPARATOR): string
    {
        $columnsCollection = self::normalizeColumns($columns);

        if ($columnsCollection->isEmpty()) {
            return '<fg=yellow>⚠️  No data to display</fg=yellow>';
        }

        $ansi = self::getAnsi();
        $lines = [];

        $maxRows = $columnsCollection->reduce(
            fn ($carry, $column) => max($carry, $column->count()),
            0
        );

        $columnWidths = $columnsCollection->map(
            fn ($column) => $column->reduce(
                fn ($carry, $cell) => max($carry, mb_strlen($cell)),
                0
            ) + 2
        );

        $columnWidths = $columnWidths->map(
            fn ($w) => max($w, $width)
        );

        for ($rowIndex = 0; $rowIndex < $maxRows; $rowIndex++) {
            $line = '';
            $colIndex = 0;

            foreach ($columnsCollection as $index => $column) {
                $cell = $column->get($rowIndex) ?? '';
                $colWidth = $columnWidths->get($index);

                $padded = self::padCenter($cell, $colWidth);

                if ($rowIndex === 0) {
                    $padded = $ansi->option($padded, Options::BOLD);
                }

                $line .= $padded;

                if ($colIndex < $columnsCollection->count() - 1) {
                    $line .= $separator;
                }
                $colIndex++;
            }

            $lines[] = $line;
        }

        return implode(PHP_EOL, $lines);
    }

    /**
     * Affiche des colonnes avec des couleurs personnalisées (centré)
     *
     * @param  ListCollection|array  $columns  Chaque colonne est un ListCollection ou un array
     * @param  array  $colors  Couleurs par colonne (ex: ['cyan', 'green', 'yellow'])
     */
    public static function renderWithColors(ListCollection|array $columns, array $colors = [], int $width = self::DEFAULT_WIDTH, string $separator = self::DEFAULT_SEPARATOR): string
    {
        $columnsCollection = self::normalizeColumns($columns);

        if ($columnsCollection->isEmpty()) {
            return '<fg=yellow>⚠️  No data to display</fg=yellow>';
        }

        $ansi = self::getAnsi();
        $lines = [];

        $maxRows = $columnsCollection->reduce(
            fn ($carry, $column) => max($carry, $column->count()),
            0
        );

        $columnWidths = $columnsCollection->map(
            fn ($column) => $column->reduce(
                fn ($carry, $cell) => max($carry, mb_strlen($cell)),
                0
            ) + 2
        );

        $columnWidths = $columnWidths->map(
            fn ($w) => max($w, $width)
        );

        for ($rowIndex = 0; $rowIndex < $maxRows; $rowIndex++) {
            $line = '';
            $colIndex = 0;

            foreach ($columnsCollection as $index => $column) {
                $cell = $column->get($rowIndex) ?? '';
                $color = $colors[$index] ?? 'white';
                $fg = self::getFgColor($color);
                $colWidth = $columnWidths->get($index);

                $padded = self::padCenter($cell, $colWidth);

                if ($rowIndex === 0) {
                    $padded = $ansi->option($padded, Options::BOLD);
                }

                $line .= $ansi->colorEnum($padded, $fg);

                if ($colIndex < $columnsCollection->count() - 1) {
                    $line .= $separator;
                }
                $colIndex++;
            }

            $lines[] = $line;
        }

        return implode(PHP_EOL, $lines);
    }

    /**
     * Affiche des colonnes avec des en-têtes séparées (centré)
     *
     * @param  ListCollection|array  $columns  Chaque colonne est un ListCollection ou un array
     */
    public static function renderWithHeaders(ListCollection|array $columns, int $width = self::DEFAULT_WIDTH, string $separator = self::DEFAULT_SEPARATOR): string
    {
        $columnsCollection = self::normalizeColumns($columns);

        if ($columnsCollection->isEmpty()) {
            return '<fg=yellow>⚠️  No data to display</fg=yellow>';
        }

        $ansi = self::getAnsi();
        $lines = [];

        $maxRows = $columnsCollection->reduce(
            fn ($carry, $column) => max($carry, $column->count()),
            0
        );

        $columnWidths = $columnsCollection->map(
            fn ($column) => $column->reduce(
                fn ($carry, $cell) => max($carry, mb_strlen($cell)),
                0
            ) + 2
        );

        $columnWidths = $columnWidths->map(
            fn ($w) => max($w, $width)
        );

        // Ligne d'en-tête (centrée)
        $headerLine = '';
        $colIndex = 0;
        foreach ($columnsCollection as $index => $column) {
            $header = $column->get(0) ?? '';
            $colWidth = $columnWidths->get($index);
            $padded = self::padCenter($header, $colWidth);
            $headerLine .= $ansi->option($padded, Options::BOLD);

            if ($colIndex < $columnsCollection->count() - 1) {
                $headerLine .= $separator;
            }
            $colIndex++;
        }
        $lines[] = $headerLine;

        // Séparateur (avec des tirets)
        $sepLine = '';
        $colIndex = 0;
        foreach ($columnsCollection as $index => $column) {
            $colWidth = $columnWidths->get($index);
            $sepLine .= str_repeat('─', $colWidth);
            if ($colIndex < $columnsCollection->count() - 1) {
                $sepLine .= str_repeat(' ', mb_strlen($separator));
            }
            $colIndex++;
        }
        $lines[] = $sepLine;

        // Données (à partir de la ligne 1, centrées)
        for ($rowIndex = 1; $rowIndex < $maxRows; $rowIndex++) {
            $line = '';
            $colIndex = 0;

            foreach ($columnsCollection as $index => $column) {
                $cell = $column->get($rowIndex) ?? '';
                $colWidth = $columnWidths->get($index);

                $padded = self::padCenter($cell, $colWidth);
                $line .= $padded;

                if ($colIndex < $columnsCollection->count() - 1) {
                    $line .= $separator;
                }
                $colIndex++;
            }

            $lines[] = $line;
        }

        return implode(PHP_EOL, $lines);
    }

    /**
     * Affiche des colonnes en format compact (centré)
     *
     * @param  ListCollection|array  $columns  Chaque colonne est un ListCollection ou un array
     */
    public static function renderCompact(ListCollection|array $columns, string $separator = self::DEFAULT_SEPARATOR): string
    {
        $columnsCollection = self::normalizeColumns($columns);

        if ($columnsCollection->isEmpty()) {
            return '<fg=yellow>⚠️  No data to display</fg=yellow>';
        }

        $ansi = self::getAnsi();
        $lines = [];

        $maxRows = $columnsCollection->reduce(
            fn ($carry, $column) => max($carry, $column->count()),
            0
        );

        // Calculer les largeurs maximales par ligne
        $maxWidths = [];
        for ($rowIndex = 0; $rowIndex < $maxRows; $rowIndex++) {
            $maxWidth = 0;
            foreach ($columnsCollection as $column) {
                $cell = $column->get($rowIndex) ?? '';
                $maxWidth = max($maxWidth, mb_strlen($cell));
            }
            $maxWidths[$rowIndex] = $maxWidth + 2;
        }

        for ($rowIndex = 0; $rowIndex < $maxRows; $rowIndex++) {
            $line = '';
            $colIndex = 0;

            foreach ($columnsCollection as $column) {
                $cell = $column->get($rowIndex) ?? '';
                $width = $maxWidths[$rowIndex] ?? mb_strlen($cell) + 2;

                $padded = self::padCenter($cell, $width);

                if ($rowIndex === 0) {
                    $padded = $ansi->option($padded, Options::BOLD);
                }

                $line .= $padded;

                if ($colIndex < $columnsCollection->count() - 1) {
                    $line .= $separator;
                }
                $colIndex++;
            }

            $lines[] = $line;
        }

        return implode(PHP_EOL, $lines);
    }

    /**
     * Normalise les colonnes en ListCollection
     */
    private static function normalizeColumns(ListCollection|array $columns): ListCollection
    {
        if ($columns instanceof ListCollection) {
            // Si c'est déjà une ListCollection, on s'assure que chaque élément est une ListCollection
            $normalized = $columns->map(
                fn ($column) => $column instanceof ListCollection ? $column : ListCollection::from((array) $column)
            );

            return $normalized;
        }

        // Si c'est un array, on le convertit
        $result = [];
        foreach ($columns as $column) {
            $result[] = is_array($column) ? ListCollection::from($column) : ListCollection::from([$column]);
        }

        return ListCollection::from($result);
    }

    /**
     * Centre le texte dans une largeur donnée
     */
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

    private static function getFgColor(string $color): FgColor
    {
        return match ($color) {
            'black' => FgColor::BLACK,
            'red' => FgColor::RED,
            'green' => FgColor::GREEN,
            'yellow' => FgColor::YELLOW,
            'blue' => FgColor::BLUE,
            'magenta' => FgColor::MAGENTA,
            'cyan' => FgColor::CYAN,
            'white' => FgColor::WHITE,
            'gray' => FgColor::GRAY,
            default => FgColor::WHITE,
        };
    }
}
