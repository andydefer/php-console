<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components;

use AndyDefer\ConsoleWriter\Console\Abstracts\Component;
use AndyDefer\DomainStructures\Utils\ListCollection;
use AndyDefer\DomainStructures\Utils\MapCollection;
use AndyDefer\PhpVo\ValueObjects\Types\FloatVO;
use AndyDefer\PhpVo\ValueObjects\Types\StringVO;

/**
 * Affiche une liste de clés => valeurs formatée
 *
 * @example
 * KeyValue::render(
 *     MapCollection::from(['Name' => 'John', 'Age' => 30, 'City' => 'Paris'])
 * )
 * // Sortie:
 * // Name   : John
 * // Age    : 30
 * // City   : Paris
 */
final class KeyValue extends Component
{
    private const SEPARATOR = ' : ';

    private const INDENT = '  ';

    private const EXTRA_SPACES = 3;

    public static function render(MapCollection $data, int $indent = 0): string
    {
        if ($data->isEmpty()) {
            return self::fg('⚠️  No data to display', 'yellow');
        }

        $padding = StringVO::from('')->concat(str_repeat(self::INDENT, $indent));

        $maxKeyLength = self::calculateMaxKeyLength($data);
        $totalKeyWidth = $maxKeyLength->add(FloatVO::from(self::EXTRA_SPACES));

        $lines = ListCollection::from([]);

        foreach ($data as $key => $value) {
            $keyString = self::toSafeString($key);
            $valueString = self::toSafeString($value);

            $paddedKey = self::padKey($keyString, $totalKeyWidth);

            $line = $padding
                ->concat(self::fg($paddedKey->getValue(), 'cyan'))
                ->concat(self::SEPARATOR)
                ->concat($valueString);

            $lines = $lines->add($line->getValue());
        }

        return $lines->reduce(
            fn ($carry, $line) => $carry === '' ? $line : $carry.PHP_EOL.$line,
            ''
        );
    }

    public static function renderWithColor(MapCollection $data, string $keyColor = 'cyan', int $indent = 0): string
    {
        if ($data->isEmpty()) {
            return self::fg('⚠️  No data to display', 'yellow');
        }

        $padding = StringVO::from('')->concat(str_repeat(self::INDENT, $indent));
        $maxKeyLength = self::calculateMaxKeyLength($data);
        $totalKeyWidth = $maxKeyLength->add(FloatVO::from(self::EXTRA_SPACES));
        $lines = ListCollection::from([]);

        foreach ($data as $key => $value) {
            $keyString = self::toSafeString($key);
            $valueString = self::toSafeString($value);
            $paddedKey = self::padKey($keyString, $totalKeyWidth);

            $line = $padding
                ->concat(self::fg($paddedKey->getValue(), $keyColor))
                ->concat(self::SEPARATOR)
                ->concat($valueString);

            $lines = $lines->add($line->getValue());
        }

        return $lines->reduce(
            fn ($carry, $line) => $carry === '' ? $line : $carry.PHP_EOL.$line,
            ''
        );
    }

    public static function renderWithValueColor(MapCollection $data, string $valueColor = 'green', int $indent = 0): string
    {
        if ($data->isEmpty()) {
            return self::fg('⚠️  No data to display', 'yellow');
        }

        $padding = StringVO::from('')->concat(str_repeat(self::INDENT, $indent));
        $maxKeyLength = self::calculateMaxKeyLength($data);
        $totalKeyWidth = $maxKeyLength->add(FloatVO::from(self::EXTRA_SPACES));
        $lines = ListCollection::from([]);

        foreach ($data as $key => $value) {
            $keyString = self::toSafeString($key);
            $valueString = self::toSafeString($value);
            $paddedKey = self::padKey($keyString, $totalKeyWidth);

            $line = $padding
                ->concat($paddedKey)
                ->concat(self::SEPARATOR)
                ->concat(self::fg($valueString->getValue(), $valueColor));

            $lines = $lines->add($line->getValue());
        }

        return $lines->reduce(
            fn ($carry, $line) => $carry === '' ? $line : $carry.PHP_EOL.$line,
            ''
        );
    }

    public static function renderWithSeparator(MapCollection $data, string $separator = ' → ', int $indent = 0): string
    {
        if ($data->isEmpty()) {
            return self::fg('⚠️  No data to display', 'yellow');
        }

        $padding = StringVO::from('')->concat(str_repeat(self::INDENT, $indent));
        $maxKeyLength = self::calculateMaxKeyLength($data);
        $totalKeyWidth = $maxKeyLength->add(FloatVO::from(self::EXTRA_SPACES));
        $lines = ListCollection::from([]);

        foreach ($data as $key => $value) {
            $keyString = self::toSafeString($key);
            $valueString = self::toSafeString($value);
            $paddedKey = self::padKey($keyString, $totalKeyWidth);

            $line = $padding
                ->concat(self::fg($paddedKey->getValue(), 'cyan'))
                ->concat($separator)
                ->concat($valueString);

            $lines = $lines->add($line->getValue());
        }

        return $lines->reduce(
            fn ($carry, $line) => $carry === '' ? $line : $carry.PHP_EOL.$line,
            ''
        );
    }

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

    private static function padKey(StringVO $key, FloatVO $totalWidth): StringVO
    {
        $keyLength = FloatVO::from(mb_strlen($key->getValue()));
        $paddingNeeded = $totalWidth->subtract($keyLength)->toInt();

        if ($paddingNeeded <= 0) {
            return $key;
        }

        return $key->concat(str_repeat(' ', $paddingNeeded));
    }

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

    public static function renderWithExtraSpaces(MapCollection $data, int $extraSpaces = 3, int $indent = 0): string
    {
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

            $line = $padding
                ->concat(self::fg($paddedKey->getValue(), 'cyan'))
                ->concat(self::SEPARATOR)
                ->concat($valueString);

            $lines = $lines->add($line->getValue());
        }

        return $lines->reduce(
            fn ($carry, $line) => $carry === '' ? $line : $carry.PHP_EOL.$line,
            ''
        );
    }

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

        foreach ($data as $key => $value) {
            $keyString = self::toSafeString($key);
            $valueString = self::toSafeString($value);
            $paddedKey = self::padKey($keyString, $totalKeyWidth);

            $line = $padding
                ->concat(self::fg($paddedKey->getValue(), 'cyan'))
                ->concat(self::SEPARATOR)
                ->concat($valueString);

            $lines = $lines->add($line->getValue());
        }

        return $lines->reduce(
            fn ($carry, $line) => $carry === '' ? $line : $carry.PHP_EOL.$line,
            ''
        );
    }
}
