<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components;

use AndyDefer\ConsoleWriter\Console\Abstracts\Component;
use AndyDefer\DomainStructures\Utils\ListCollection;
use AndyDefer\DomainStructures\Utils\MapCollection;
use AndyDefer\PhpVo\ValueObjects\Types\FloatVO;
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

    private const MAX_LINE_LENGTH = 120;

    private const WRAP_INDENT = 4;

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
        $maxKeyLength = self::calculateMaxKeyLength($data);
        $totalKeyWidth = $maxKeyLength->add(FloatVO::from(self::EXTRA_SPACES));
        $lines = ListCollection::from([]);

        $lines = $lines->add(self::fg('📊 Debug: maxLength='.$maxKeyLength->getValue().', totalWidth='.$totalKeyWidth->getValue(), 'yellow'));

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
        $maxKeyLength = self::calculateMaxKeyLength($data);
        $totalKeyWidth = $maxKeyLength->add(FloatVO::from($extraSpaces));
        $lines = ListCollection::from([]);

        foreach ($data as $key => $value) {
            $keyString = self::toSafeString($key);
            $valueString = self::toSafeString($value);

            $paddedKey = self::padKey($keyString, $totalKeyWidth);
            $keyDisplay = $keyColor !== null
                ? self::fg($paddedKey->getValue(), $keyColor)
                : $paddedKey->getValue();

            // Gérer le multiligne pour la valeur
            $valueLines = self::wrapText($valueString->getValue(), self::MAX_LINE_LENGTH - $totalKeyWidth->toInt() - mb_strlen($separator));

            foreach ($valueLines as $index => $valueLine) {
                if ($index === 0) {
                    $line = $padding
                        ->concat($keyDisplay)
                        ->concat($separator)
                        ->concat($valueColor !== null ? self::fg($valueLine, $valueColor) : $valueLine);
                } else {
                    $indentSpaces = str_repeat(' ', $totalKeyWidth->toInt() + mb_strlen($separator));
                    $line = $padding
                        ->concat(str_repeat(' ', $totalKeyWidth->toInt() + mb_strlen($separator)))
                        ->concat($valueColor !== null ? self::fg($valueLine, $valueColor) : $valueLine);
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
     * Calculates the maximum key length in the data.
     */
    private static function calculateMaxKeyLength(MapCollection $data): FloatVO
    {
        $maxLength = FloatVO::from(0);

        foreach ($data->keys() as $key) {
            $keyString = self::toSafeString($key);
            $length = FloatVO::from(mb_strlen($keyString->getValue()));
            $maxLength = $maxLength->max($length);
        }

        return $maxLength;
    }

    /**
     * Pads a key to the specified width.
     */
    private static function padKey(StringVO $key, FloatVO $totalWidth): StringVO
    {
        $keyLength = FloatVO::from(mb_strlen($key->getValue()));
        $paddingNeeded = $totalWidth->subtract($keyLength)->toInt();

        if ($paddingNeeded <= 0) {
            return $key;
        }

        return $key->concat(str_repeat(' ', $paddingNeeded));
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
