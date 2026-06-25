<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components;

use AndyDefer\ConsoleWriter\Console\ValueObjects\CleanedTextVO;
use AndyDefer\DomainStructures\Utils\ListCollection;
use AndyDefer\PhpVo\ValueObjects\Types\BoolVO;
use AndyDefer\PhpVo\ValueObjects\Types\FloatVO;
use AndyDefer\PhpVo\ValueObjects\Types\StringVO;

final class Table
{
    private const PADDING = 2;

    private const BORDER_LEFT = '│';

    private const BORDER_RIGHT = '│';

    private const SEPARATOR = ' │ ';

    /**
     * Coefficient pour compenser la largeur du caractère '─'
     */
    private static function getDashFactor(int $columnCount): float
    {
        return match ($columnCount) {
            1 => 1.05,
            2 => 1.06,
            3 => 1.06,
            4 => 1.066,
            default => 1.10,
        };
    }

    public static function render(ListCollection $headers, ListCollection $rows): string
    {
        if ($rows->isEmpty()) {
            return '<fg=yellow>⚠️  No data to display</fg=yellow>';
        }

        // ✅ Si plus de 5 colonnes → TableList
        if ($headers->count() > 5) {
            return TableList::render($headers, $rows);
        }

        // ✅ Nettoyer les émojis AVANT le calcul de la taille
        $cleanHeaders = self::cleanHeaders($headers);
        $cleanRows = self::cleanRows($rows);

        $maxCellWidth = self::findMaxCellWidth($cleanHeaders, $cleanRows);
        $cellWidthInt = self::getCellWidth($maxCellWidth);

        $columnCount = $cleanHeaders->count();
        $totalWidth = FloatVO::from($cellWidthInt)
            ->multiply(FloatVO::from($columnCount))
            ->add(FloatVO::from($columnCount + 1));
        $totalWidthInt = $totalWidth->toInt();

        $lines = ListCollection::from([]);

        $lines = $lines->add(self::topBorder($totalWidthInt, $columnCount));
        $lines = $lines->add(self::headerLine($cleanHeaders, $cellWidthInt));
        $lines = $lines->add(self::separator($totalWidthInt, $columnCount));

        foreach ($cleanRows as $row) {
            $rowCollection = $row instanceof ListCollection ? $row : ListCollection::from((array) $row);
            $lines = $lines->add(self::dataLine($rowCollection, $cellWidthInt));
        }

        $lines = $lines->add(self::bottomBorder($totalWidthInt, $columnCount));

        return $lines->reduce(
            fn ($carry, $line) => $carry === '' ? $line : $carry.PHP_EOL.$line,
            ''
        );
    }

    /**
     * ✅ Nettoie les en-têtes des émojis
     */
    private static function cleanHeaders(ListCollection $headers): ListCollection
    {
        $clean = [];
        foreach ($headers as $header) {
            $clean[] = (new CleanedTextVO(StringVO::from($header)->getValue()))->getCleanValue();
        }

        return ListCollection::from($clean);
    }

    /**
     * ✅ Nettoie les lignes des émojis
     */
    private static function cleanRows(ListCollection $rows): ListCollection
    {
        $cleanRows = [];
        foreach ($rows as $row) {
            $rowArray = $row instanceof ListCollection ? $row->toArray() : (array) $row;
            $cleanRow = [];
            foreach ($rowArray as $cell) {
                $cleanRow[] = (new CleanedTextVO(StringVO::from($cell)->getValue()))->getCleanValue();
            }
            $cleanRows[] = ListCollection::from($cleanRow);
        }

        return ListCollection::from($cleanRows);
    }

    /**
     * Calcule la largeur de cellule en s'assurant qu'elle est paire
     */
    private static function getCellWidth(FloatVO $maxCellWidth): int
    {
        $cellWidth = $maxCellWidth->add(FloatVO::from(self::PADDING * 2));
        $cellWidthInt = $cellWidth->toInt();

        $isEven = BoolVO::from($cellWidthInt % 2 === 0);

        if ($isEven->isFalse()->getValue()) {
            $cellWidthInt += 1;
        }

        return $cellWidthInt;
    }

    private static function findMaxCellWidth(ListCollection $headers, ListCollection $rows): FloatVO
    {
        $max = FloatVO::from(0);

        foreach ($headers as $header) {
            $length = FloatVO::from(mb_strlen(StringVO::from($header)->getValue()));
            $isGreater = BoolVO::from($length->greaterThan($max)->getValue());

            if ($isGreater->isTrue()->getValue()) {
                $max = $length;
            }
        }

        foreach ($rows as $row) {
            $rowArray = $row instanceof ListCollection ? $row : ListCollection::from((array) $row);
            foreach ($rowArray as $cell) {
                $length = FloatVO::from(mb_strlen(StringVO::from($cell)->getValue()));
                $isGreater = BoolVO::from($length->greaterThan($max)->getValue());

                if ($isGreater->isTrue()->getValue()) {
                    $max = $length;
                }
            }
        }

        return $max;
    }

    private static function topBorder(int $totalWidth, int $columnCount): string
    {
        return '<fg=cyan>┌'.self::borderLine($totalWidth, $columnCount).'┐</fg=cyan>';
    }

    private static function bottomBorder(int $totalWidth, int $columnCount): string
    {
        return '<fg=cyan>└'.self::borderLine($totalWidth, $columnCount).'┘</fg=cyan>';
    }

    private static function separator(int $totalWidth, int $columnCount): string
    {
        return '<fg=cyan>├'.self::borderLine($totalWidth, $columnCount).'┤</fg=cyan>';
    }

    private static function borderLine(int $totalWidth, int $columnCount): string
    {
        $factor = self::getDashFactor($columnCount);
        $adjustedWidth = (int) ceil($totalWidth * $factor);

        return str_repeat('─', $adjustedWidth);
    }

    private static function headerLine(ListCollection $headers, int $cellWidth): string
    {
        $line = StringVO::from(self::BORDER_LEFT);

        foreach ($headers as $index => $header) {
            $padded = self::padCenter(StringVO::from($header), $cellWidth);
            $line = $line->concat($padded);

            $isNotLast = BoolVO::from($index < $headers->count() - 1);
            if ($isNotLast->isTrue()->getValue()) {
                $line = $line->concat(self::SEPARATOR);
            }
        }

        $hasThreeColumns = BoolVO::from($headers->count() === 3);

        if ($hasThreeColumns->isTrue()->getValue()) {
            $line = $line->concat(' '.self::BORDER_RIGHT);
        } else {
            $line = $line->concat('  '.self::BORDER_RIGHT);
        }

        return '<fg=cyan><options=bold>'.$line->getValue().'</options=bold></fg=cyan>';
    }

    private static function dataLine(ListCollection $row, int $cellWidth): string
    {
        $line = StringVO::from(self::BORDER_LEFT);

        $rowCount = $row->count();

        foreach ($row as $index => $cell) {
            $padded = self::padCenter(StringVO::from($cell), $cellWidth);
            $line = $line->concat($padded);

            if ($index < $rowCount - 1) {
                $line = $line->concat(self::SEPARATOR);
            }
        }

        // ✅ Pour 5 colonnes : ajouter un espace avant BORDER_RIGHT
        $hasFiveColumns = BoolVO::from($rowCount <= 3);
        if ($hasFiveColumns->isTrue()->getValue()) {
            $line = $line->concat(' '.self::BORDER_RIGHT);
        } else {
            $line = $line->concat(' '.self::BORDER_RIGHT);
        }

        return $line->getValue();
    }

    private static function padCenter(StringVO $text, int $width): StringVO
    {
        $textLength = mb_strlen($text->getValue());
        $padding = $width - $textLength;

        $left = (int) floor($padding / 2);
        $right = $padding - $left;

        return StringVO::from('')
            ->concat(str_repeat(' ', $left))
            ->concat($text)
            ->concat(str_repeat(' ', $right));
    }

    private static function padLeft(StringVO $text, int $width): StringVO
    {
        $textLength = mb_strlen($text->getValue());
        $padding = $width - $textLength;

        return StringVO::from('')
            ->concat($text)
            ->concat(str_repeat(' ', $padding));
    }

    private static function padRight(StringVO $text, int $width): StringVO
    {
        $textLength = mb_strlen($text->getValue());
        $padding = $width - $textLength;

        return StringVO::from('')
            ->concat(str_repeat(' ', $padding))
            ->concat($text);
    }

    public static function renderWithAlignment(
        ListCollection $headers,
        ListCollection $rows,
        string $defaultAlignment = 'center',
        ?array $columnAlignments = null
    ): string {
        if ($rows->isEmpty()) {
            return '<fg=yellow>⚠️  No data to display</fg=yellow>';
        }

        // ✅ Si plus de 5 colonnes → TableList
        if ($headers->count() > 5) {
            return TableList::renderWithColor($headers, $rows);
        }

        // ✅ Nettoyer les émojis AVANT le calcul de la taille
        $cleanHeaders = self::cleanHeaders($headers);
        $cleanRows = self::cleanRows($rows);

        $maxCellWidth = self::findMaxCellWidth($cleanHeaders, $cleanRows);
        $cellWidthInt = self::getCellWidth($maxCellWidth);

        $columnCount = $cleanHeaders->count();
        $totalWidth = FloatVO::from($cellWidthInt)
            ->multiply(FloatVO::from($columnCount))
            ->add(FloatVO::from($columnCount + 1));
        $totalWidthInt = $totalWidth->toInt();

        $lines = ListCollection::from([]);

        $lines = $lines->add(self::topBorder($totalWidthInt, $columnCount));
        $lines = $lines->add(self::headerLine($cleanHeaders, $cellWidthInt));
        $lines = $lines->add(self::separator($totalWidthInt, $columnCount));

        foreach ($cleanRows as $row) {
            $rowCollection = $row instanceof ListCollection ? $row : ListCollection::from((array) $row);
            $lines = $lines->add(self::dataLineWithAlignment($rowCollection, $cellWidthInt, $defaultAlignment, $columnAlignments));
        }

        $lines = $lines->add(self::bottomBorder($totalWidthInt, $columnCount));

        return $lines->reduce(
            fn ($carry, $line) => $carry === '' ? $line : $carry.PHP_EOL.$line,
            ''
        );
    }

    private static function dataLineWithAlignment(
        ListCollection $row,
        int $cellWidth,
        string $defaultAlignment = 'center',
        ?array $columnAlignments = null
    ): string {
        $line = StringVO::from(self::BORDER_LEFT);

        $rowCount = $row->count();

        foreach ($row as $index => $cell) {
            $alignment = $columnAlignments[$index] ?? $defaultAlignment;

            $padded = match ($alignment) {
                'left' => self::padLeft(StringVO::from($cell), $cellWidth),
                'right' => self::padRight(StringVO::from($cell), $cellWidth),
                default => self::padCenter(StringVO::from($cell), $cellWidth),
            };

            $line = $line->concat($padded);

            if ($index < $rowCount - 1) {
                $line = $line->concat(self::SEPARATOR);
            }
        }

        // ✅ Pour 5 colonnes : ajouter un espace avant BORDER_RIGHT
        $hasFiveColumns = BoolVO::from($rowCount === 5);
        if ($hasFiveColumns->isTrue()->getValue()) {
            $line = $line->concat(' '.self::BORDER_RIGHT);
        } else {
            $line = $line->concat(self::BORDER_RIGHT);
        }

        return $line->getValue();
    }
}
