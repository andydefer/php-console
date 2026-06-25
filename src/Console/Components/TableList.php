<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components;

use AndyDefer\DomainStructures\Utils\ListCollection;
use AndyDefer\PhpVo\ValueObjects\Types\FloatVO;
use AndyDefer\PhpVo\ValueObjects\Types\StringVO;

/**
 * Transforme un tableau en liste KeyValue lisible
 * Utile pour les tableaux avec plus de 5 colonnes
 *
 * @example
 * TableList::render(
 *     ListCollection::from(['ID', 'Name', 'Description', 'Price']),
 *     ListCollection::from([
 *         ListCollection::from(['1', 'Laptop', 'High-performance laptop', '1299.99']),
 *     ])
 * )
 */
final class TableList
{
    private const INDENT = '  ';

    private const MAX_KEY_WIDTH = 25;

    private const WRAP_WIDTH = 60;

    public static function render(ListCollection $headers, ListCollection $rows): string
    {
        if ($rows->isEmpty()) {
            return '<fg=yellow>⚠️  No data to display</fg=yellow>';
        }

        $headersArray = $headers->toArray();
        $maxKeyLength = self::calculateMaxKeyLength($headersArray);
        $lines = [];

        $lines[] = self::infoHeader($headersArray);

        $rowNumber = 0;
        foreach ($rows as $row) {
            $rowNumber++;
            $rowArray = $row instanceof ListCollection ? $row->toArray() : (array) $row;

            if ($rowNumber > 1) {
                $lines[] = '';
            }

            $lines[] = self::topBorder($rowNumber);

            foreach ($headersArray as $index => $header) {
                $value = $rowArray[$index] ?? '';
                $lines[] = self::formatLine(
                    StringVO::from($header),
                    StringVO::from($value),
                    $maxKeyLength
                );
            }

            $lines[] = self::bottomBorder();
        }

        return implode(PHP_EOL, $lines);
    }

    public static function renderWithTitle(
        ListCollection $headers,
        ListCollection $rows,
        string $title
    ): string {
        if ($rows->isEmpty()) {
            return '<fg=yellow>⚠️  No data to display</fg=yellow>';
        }

        $headersArray = $headers->toArray();
        $maxKeyLength = self::calculateMaxKeyLength($headersArray);
        $lines = [];

        $lines[] = self::titleLine($title);

        $rowNumber = 0;
        foreach ($rows as $row) {
            $rowNumber++;
            $rowArray = $row instanceof ListCollection ? $row->toArray() : (array) $row;

            if ($rowNumber > 1) {
                $lines[] = '';
            }

            $lines[] = self::topBorder($rowNumber);

            foreach ($headersArray as $index => $header) {
                $value = $rowArray[$index] ?? '';
                $lines[] = self::formatLine(
                    StringVO::from($header),
                    StringVO::from($value),
                    $maxKeyLength
                );
            }

            $lines[] = self::bottomBorder();
        }

        return implode(PHP_EOL, $lines);
    }

    public static function renderWithColor(
        ListCollection $headers,
        ListCollection $rows,
        string $color = 'cyan'
    ): string {
        if ($rows->isEmpty()) {
            return '<fg=yellow>⚠️  No data to display</fg=yellow>';
        }

        $headersArray = $headers->toArray();
        $maxKeyLength = self::calculateMaxKeyLength($headersArray);
        $lines = [];

        $lines[] = self::infoHeader($headersArray, $color);

        $rowNumber = 0;
        foreach ($rows as $row) {
            $rowNumber++;
            $rowArray = $row instanceof ListCollection ? $row->toArray() : (array) $row;

            if ($rowNumber > 1) {
                $lines[] = '';
            }

            $lines[] = self::topBorder($rowNumber, $color);

            foreach ($headersArray as $index => $header) {
                $value = $rowArray[$index] ?? '';
                $lines[] = self::formatLineWithColor(
                    StringVO::from($header),
                    StringVO::from($value),
                    $maxKeyLength,
                    $color
                );
            }

            $lines[] = self::bottomBorder($color);
        }

        return implode(PHP_EOL, $lines);
    }

    public static function renderCompact(ListCollection $headers, ListCollection $rows): string
    {
        if ($rows->isEmpty()) {
            return '<fg=yellow>⚠️  No data to display</fg=yellow>';
        }

        $headersArray = $headers->toArray();
        $maxKeyLength = self::calculateMaxKeyLength($headersArray);
        $lines = [];
        $rowNumber = 0;

        foreach ($rows as $row) {
            $rowNumber++;
            $rowArray = $row instanceof ListCollection ? $row->toArray() : (array) $row;

            if ($rowNumber > 1) {
                $lines[] = '';
                $lines[] = '<fg=gray>─────────────────────────────────────────────────</fg=gray>';
                $lines[] = '';
            }

            foreach ($headersArray as $index => $header) {
                $value = $rowArray[$index] ?? '';
                $lines[] = self::formatLine(
                    StringVO::from($header),
                    StringVO::from($value),
                    $maxKeyLength
                );
            }
        }

        return implode(PHP_EOL, $lines);
    }

    // ========== MÉTHODES PRIVÉES ==========

    private static function calculateMaxKeyLength(array $headers): int
    {
        $max = 0;
        foreach ($headers as $header) {
            $length = FloatVO::from(mb_strlen(StringVO::from($header)->getValue()));
            if ($length->greaterThan(FloatVO::from($max))->getValue()) {
                $max = $length->toInt();
            }
        }

        // Limiter à MAX_KEY_WIDTH
        return min($max + 2, self::MAX_KEY_WIDTH);
    }

    private static function infoHeader(array $headers, string $color = 'yellow'): string
    {
        $count = count($headers);

        return '<fg='.$color.'>📋 '.$count.' colonnes → affichage en liste</fg='.$color.'>';
    }

    private static function titleLine(string $title): string
    {
        return '<fg=cyan><options=bold>📋 '.$title.'</options=bold></fg=cyan>';
    }

    private static function topBorder(int $rowNumber, string $color = 'cyan'): string
    {
        $width = 60;
        $line = str_repeat('─', $width - 2);

        return '<fg='.$color.'><options=bold>┌─ Item #'.$rowNumber.' '.$line.'</options=bold></fg='.$color.'>';
    }

    private static function bottomBorder(string $color = 'cyan'): string
    {
        $width = 60;
        $line = str_repeat('─', $width);

        return '<fg='.$color.'><options=bold>└'.$line.'</options=bold></fg='.$color.'>';
    }

    private static function formatLine(
        StringVO $key,
        StringVO $value,
        int $maxKeyLength
    ): string {
        $keyText = $key->getValue();
        $valueText = $value->getValue();

        $padding = FloatVO::from($maxKeyLength - mb_strlen($keyText));
        $padding = $padding->max(FloatVO::from(0))->toInt();
        $paddedKey = $keyText.str_repeat(' ', $padding);

        $wrappedValue = self::wrapText($valueText, self::WRAP_WIDTH - $maxKeyLength - 4);

        return self::INDENT.'<fg=cyan>'.$paddedKey.'</fg> : '.$wrappedValue;
    }

    private static function formatLineWithColor(
        StringVO $key,
        StringVO $value,
        int $maxKeyLength,
        string $color
    ): string {
        $keyText = $key->getValue();
        $valueText = $value->getValue();

        $padding = FloatVO::from($maxKeyLength - mb_strlen($keyText));
        $padding = $padding->max(FloatVO::from(0))->toInt();
        $paddedKey = $keyText.str_repeat(' ', $padding);

        $wrappedValue = self::wrapText($valueText, self::WRAP_WIDTH - $maxKeyLength - 4);

        return self::INDENT.'<fg='.$color.'>'.$paddedKey.'</fg> : '.$wrappedValue;
    }

    private static function wrapText(string $text, int $width): string
    {
        if (mb_strlen($text) <= $width) {
            return $text;
        }

        $words = explode(' ', $text);
        $lines = [];
        $currentLine = '';

        foreach ($words as $word) {
            $testLine = $currentLine === '' ? $word : $currentLine.' '.$word;
            if (mb_strlen($testLine) <= $width) {
                $currentLine = $testLine;
            } else {
                if ($currentLine !== '') {
                    $lines[] = $currentLine;
                }
                $currentLine = $word;
            }
        }

        if ($currentLine !== '') {
            $lines[] = $currentLine;
        }

        $indent = self::INDENT.str_repeat(' ', self::MAX_KEY_WIDTH + 4);

        return implode("\n".$indent, $lines);
    }
}
