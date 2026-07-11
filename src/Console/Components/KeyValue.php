<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components;

use AndyDefer\ConsoleWriter\Console\Abstracts\Component;
use AndyDefer\DomainStructures\Utils\ListCollection;
use AndyDefer\DomainStructures\Utils\MapCollection;
use AndyDefer\PhpVo\ValueObjects\Types\StringVO;

/**
 * Displays a formatted list of key => value pairs with multiline support.
 *
 * @example
 * KeyValue::render(
 *     MapCollection::from(['Name' => 'John', 'Age' => 30, 'City' => 'Paris'])
 * )
 * // Output:
 * // Name   : John
 * // Age    : 30
 * // City   : Paris
 */
final class KeyValue extends Component
{
    private const SEPARATOR = ' : ';

    private const INDENT = '  ';

    private const EXTRA_SPACES = 3;

    /**
     * Maximum line length for values (180 characters).
     */
    private const MAX_LINE_LENGTH = 180;

    /**
     * Fixed maximum width for the key column (60 characters).
     * All keys will be padded to this width.
     * Keys longer than this will be wrapped recursively.
     */
    private const MAX_KEY_WIDTH = 60;

    /**
     * Minimum width for the value column.
     */
    private const MIN_VALUE_WIDTH = 40;

    /**
     * Renders key-value pairs with multiline support for long values.
     */
    public static function render(MapCollection $data, int $indent = 0): string
    {
        return self::renderWithOptions($data, 'cyan', null, self::SEPARATOR, $indent);
    }

    /**
     * Renders key-value pairs with colored keys.
     */
    public static function renderWithColor(MapCollection $data, string $keyColor = 'cyan', int $indent = 0): string
    {
        return self::renderWithOptions($data, $keyColor, null, self::SEPARATOR, $indent);
    }

    /**
     * Renders key-value pairs with colored values.
     */
    public static function renderWithValueColor(MapCollection $data, string $valueColor = 'green', int $indent = 0): string
    {
        return self::renderWithOptions($data, null, $valueColor, self::SEPARATOR, $indent);
    }

    /**
     * Renders key-value pairs with a custom separator.
     */
    public static function renderWithSeparator(MapCollection $data, string $separator = ' → ', int $indent = 0): string
    {
        return self::renderWithOptions($data, 'cyan', null, $separator, $indent);
    }

    /**
     * Renders key-value pairs with custom extra spaces.
     */
    public static function renderWithExtraSpaces(MapCollection $data, int $extraSpaces = 3, int $indent = 0): string
    {
        return self::renderWithOptions($data, 'cyan', null, self::SEPARATOR, $indent, $extraSpaces);
    }

    /**
     * Debug mode: shows key length information.
     */
    public static function debug(MapCollection $data, int $indent = 0): string
    {
        if ($data->isEmpty()) {
            return self::fg('⚠️  No data to display', 'yellow');
        }

        $padding = StringVO::from('')->concat(str_repeat(self::INDENT, $indent));
        $lines = ListCollection::from([]);

        $lines = $lines->add(self::fg('📊 Debug: MAX_KEY_WIDTH='.self::MAX_KEY_WIDTH, 'yellow'));

        foreach ($data->keys() as $key) {
            $keyString = self::toSafeString($key);
            $length = mb_strlen($keyString->getValue());
            $lines = $lines->add(self::fg('  key: "'.$keyString->getValue().'" length='.$length, 'gray'));
        }

        $lines = $lines->add('');

        $result = self::renderWithOptions($data, 'cyan', null, self::SEPARATOR, $indent);
        $lines = $lines->add($result);

        return $lines->reduce(
            fn ($carry, $line) => $carry === '' ? $line : $carry.PHP_EOL.$line,
            ''
        );
    }

    /**
     * Core rendering method with all options.
     */
    private static function renderWithOptions(
        MapCollection $data,
        ?string $keyColor = null,
        ?string $valueColor = null,
        string $separator = self::SEPARATOR,
        int $indent = 0,
        int $extraSpaces = self::EXTRA_SPACES
    ): string {
        if ($data->isEmpty()) {
            return self::fg('⚠️  No data to display', 'yellow');
        }

        $padding = StringVO::from('')->concat(str_repeat(self::INDENT, $indent));

        // ✅ Largeur fixe pour toutes les clés
        $keyWidth = self::MAX_KEY_WIDTH;
        $indentSpaces = str_repeat(' ', $keyWidth + mb_strlen($separator) + (mb_strlen(self::INDENT) * $indent));

        $lines = ListCollection::from([]);

        foreach ($data as $key => $value) {
            $keyString = self::toSafeString($key);
            $valueString = self::toSafeString($value);

            // ✅ Découper la clé sur la largeur fixe (récursif)
            $keyLines = self::wrapKeyToFixedWidth($keyString->getValue(), $keyWidth);

            // ✅ Découper la valeur
            $availableWidth = self::MAX_LINE_LENGTH - $keyWidth - mb_strlen($separator) - (mb_strlen(self::INDENT) * $indent);
            $valueLines = self::wrapText($valueString->getValue(), max(self::MIN_VALUE_WIDTH, $availableWidth));

            // ✅ Déterminer le nombre max de lignes (clé ou valeur)
            $maxLines = max(count($keyLines), count($valueLines));

            // ✅ Remplir les lignes manquantes
            $keyLines = array_pad($keyLines, $maxLines, '');
            $valueLines = array_pad($valueLines, $maxLines, '');

            for ($i = 0; $i < $maxLines; $i++) {
                $keyLine = $keyLines[$i];
                $valueLine = $valueLines[$i];

                // ✅ Si c'est la première ligne, afficher la clé avec séparateur
                if ($i === 0) {
                    $keyDisplay = $keyColor !== null
                        ? self::fg($keyLine, $keyColor)
                        : $keyLine;

                    // ✅ Padder la clé sur la largeur fixe (avec support multioctet)
                    $paddedKey = self::mbStrPad($keyDisplay, $keyWidth, ' ', STR_PAD_RIGHT);

                    $line = $padding
                        ->concat($paddedKey)
                        ->concat($separator)
                        ->concat($valueColor !== null ? self::fg($valueLine, $valueColor) : $valueLine);
                } else {
                    // ✅ Lignes suivantes : indentation correcte
                    if ($keyLine !== '') {
                        // La clé continue sur plusieurs lignes
                        $keyDisplay = $keyColor !== null
                            ? self::fg($keyLine, $keyColor)
                            : $keyLine;
                        $line = $padding
                            ->concat($keyDisplay)
                            ->concat(str_repeat(' ', mb_strlen($separator)))
                            ->concat($valueColor !== null ? self::fg($valueLine, $valueColor) : $valueLine);
                    } else {
                        // Seule la valeur continue
                        $line = $padding
                            ->concat($indentSpaces)
                            ->concat($valueColor !== null ? self::fg($valueLine, $valueColor) : $valueLine);
                    }
                }

                $lines = $lines->add($line->getValue());
            }
        }

        return $lines->reduce(
            fn ($carry, $line) => $carry === '' ? $line : $carry.PHP_EOL.$line,
            ''
        );
    }

    /**
     * Multibyte-safe string pad.
     *
     * @param  string  $input  The string to pad
     * @param  int  $padLength  The length to pad to
     * @param  string  $padString  The string to pad with
     * @param  int  $padType  The padding type (STR_PAD_RIGHT, STR_PAD_LEFT, STR_PAD_BOTH)
     * @return string The padded string
     */
    private static function mbStrPad(string $input, int $padLength, string $padString = ' ', int $padType = STR_PAD_RIGHT): string
    {
        $inputLength = mb_strlen($input);

        if ($inputLength >= $padLength) {
            return $input;
        }

        $diff = $padLength - $inputLength;
        $padStringLength = mb_strlen($padString);

        if ($padStringLength === 0) {
            return $input;
        }

        $repeatCount = (int) ceil($diff / $padStringLength);
        $padStringRepeated = str_repeat($padString, $repeatCount);
        $padStringRepeated = mb_substr($padStringRepeated, 0, $diff);

        return match ($padType) {
            STR_PAD_LEFT => $padStringRepeated.$input,
            STR_PAD_BOTH => mb_substr($padStringRepeated, 0, (int) floor($diff / 2)).$input.mb_substr($padStringRepeated, (int) floor($diff / 2)),
            default => $input.$padStringRepeated,
        };
    }

    /**
     * Wraps a key to a fixed width recursively.
     * If the key is longer than the fixed width, it is wrapped to multiple lines.
     * Each line is checked recursively to ensure it doesn't exceed the fixed width.
     *
     * @param  string  $key  The key text
     * @param  int  $fixedWidth  The fixed width for the key
     * @return array<int, string> The wrapped key lines
     */
    private static function wrapKeyToFixedWidth(string $key, int $fixedWidth): array
    {
        if (mb_strlen($key) <= $fixedWidth) {
            return [$key];
        }

        $lines = [];
        $words = explode(' ', $key);
        $currentLine = '';

        foreach ($words as $word) {
            // Si le mot seul dépasse la largeur fixe, on le coupe
            if (mb_strlen($word) > $fixedWidth) {
                if ($currentLine !== '') {
                    $lines[] = $currentLine;
                    $currentLine = '';
                }
                // Découper le mot trop long en plusieurs parties
                for ($i = 0; $i < mb_strlen($word); $i += $fixedWidth) {
                    $lines[] = mb_substr($word, $i, $fixedWidth);
                }

                continue;
            }

            $testLine = $currentLine === '' ? $word : $currentLine.' '.$word;

            if (mb_strlen($testLine) <= $fixedWidth) {
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

        // ✅ RÉCURSIF : Vérifier chaque ligne générée
        $result = [];
        foreach ($lines as $line) {
            if (mb_strlen($line) > $fixedWidth) {
                // ✅ Si une ligne dépasse encore la largeur fixe, on la retraite récursivement
                $subLines = self::wrapKeyToFixedWidth($line, $fixedWidth);
                $result = array_merge($result, $subLines);
            } else {
                $result[] = $line;
            }
        }

        return $result;
    }

    /**
     * Wraps text to a maximum line length.
     *
     * @return array<int, string> The wrapped lines
     */
    private static function wrapText(string $text, int $maxLength): array
    {
        if ($maxLength <= 0 || mb_strlen($text) <= $maxLength) {
            return [$text];
        }

        $lines = [];
        $words = explode(' ', $text);
        $currentLine = '';

        foreach ($words as $word) {
            if (mb_strlen($word) > $maxLength) {
                if ($currentLine !== '') {
                    $lines[] = $currentLine;
                    $currentLine = '';
                }
                for ($i = 0; $i < mb_strlen($word); $i += $maxLength) {
                    $lines[] = mb_substr($word, $i, $maxLength);
                }

                continue;
            }

            $testLine = $currentLine === '' ? $word : $currentLine.' '.$word;

            if (mb_strlen($testLine) <= $maxLength) {
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

        return $lines;
    }

    /**
     * Converts any value to a safe string representation.
     */
    private static function toSafeString(mixed $value): StringVO
    {
        if ($value === null) {
            return StringVO::from('');
        }

        if (is_bool($value)) {
            return StringVO::from($value ? 'true' : 'false');
        }

        if (is_array($value)) {
            return StringVO::from(json_encode($value, JSON_THROW_ON_ERROR));
        }

        if (is_object($value)) {
            if (method_exists($value, '__toString')) {
                return StringVO::from((string) $value);
            }

            return StringVO::from(json_encode($value, JSON_THROW_ON_ERROR));
        }

        return StringVO::from((string) $value);
    }
}
