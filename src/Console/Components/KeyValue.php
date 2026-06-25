<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components;

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
final class KeyValue
{
    private const SEPARATOR = ' : ';

    private const INDENT = '  ';

    private const EXTRA_SPACES = 3;

    public static function render(MapCollection $data, int $indent = 0): string
    {
        if ($data->isEmpty()) {
            return '<fg=yellow>⚠️  No data to display</fg=yellow>';
        }

        $padding = StringVO::from('')->concat(str_repeat(self::INDENT, $indent));

        // 1. Calculer la longueur maximale des clés (sans balises)
        $maxKeyLength = self::calculateMaxKeyLength($data);

        // 2. Largeur totale = max + extra spaces
        $totalKeyWidth = $maxKeyLength->add(FloatVO::from(self::EXTRA_SPACES));

        $lines = ListCollection::from([]);

        foreach ($data as $key => $value) {
            $keyString = self::toSafeString($key);
            $valueString = self::toSafeString($value);

            // 3. Padder la clé à la largeur totale
            $paddedKey = self::padKey($keyString, $totalKeyWidth);

            $line = $padding
                ->concat('<fg=cyan>')
                ->concat($paddedKey)
                ->concat('</fg>')
                ->concat(self::SEPARATOR)
                ->concat($valueString);

            $lines = $lines->add($line->getValue());
        }

        return $lines->reduce(
            fn ($carry, $line) => $carry === '' ? $line : $carry.PHP_EOL.$line,
            ''
        );
    }

    /**
     * Affiche les clés => valeurs avec une couleur personnalisée pour les clés
     */
    public static function renderWithColor(MapCollection $data, string $keyColor = 'cyan', int $indent = 0): string
    {
        if ($data->isEmpty()) {
            return '<fg=yellow>⚠️  No data to display</fg=yellow>';
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
                ->concat('<fg='.$keyColor.'>')
                ->concat($paddedKey)
                ->concat('</fg>')
                ->concat(self::SEPARATOR)
                ->concat($valueString);

            $lines = $lines->add($line->getValue());
        }

        return $lines->reduce(
            fn ($carry, $line) => $carry === '' ? $line : $carry.PHP_EOL.$line,
            ''
        );
    }

    /**
     * Affiche les clés => valeurs avec des valeurs colorées
     */
    public static function renderWithValueColor(MapCollection $data, string $valueColor = 'green', int $indent = 0): string
    {
        if ($data->isEmpty()) {
            return '<fg=yellow>⚠️  No data to display</fg=yellow>';
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
                ->concat('<fg='.$valueColor.'>')
                ->concat($valueString)
                ->concat('</fg>');

            $lines = $lines->add($line->getValue());
        }

        return $lines->reduce(
            fn ($carry, $line) => $carry === '' ? $line : $carry.PHP_EOL.$line,
            ''
        );
    }

    /**
     * Affiche les clés => valeurs avec un séparateur personnalisé
     */
    public static function renderWithSeparator(MapCollection $data, string $separator = ' → ', int $indent = 0): string
    {
        if ($data->isEmpty()) {
            return '<fg=yellow>⚠️  No data to display</fg=yellow>';
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
                ->concat('<fg=cyan>')
                ->concat($paddedKey)
                ->concat('</fg>')
                ->concat($separator)
                ->concat($valueString);

            $lines = $lines->add($line->getValue());
        }

        return $lines->reduce(
            fn ($carry, $line) => $carry === '' ? $line : $carry.PHP_EOL.$line,
            ''
        );
    }

    /**
     * Calcule la longueur maximale des clés en utilisant mb_strlen
     * pour gérer correctement les caractères Unicode (émojis, accents)
     */
    private static function calculateMaxKeyLength(MapCollection $data): FloatVO
    {
        $maxLength = FloatVO::from(0);

        foreach ($data->keys() as $key) {
            $keyString = self::toSafeString($key);
            // Utiliser mb_strlen pour les caractères multi-octets
            $length = FloatVO::from(mb_strlen($keyString->getValue()));
            $maxLength = $maxLength->max($length);
        }

        return $maxLength;
    }

    /**
     * Pad la clé à la largeur totale
     * Largeur totale = longueur max + extra spaces
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
     * Convertit n'importe quelle valeur en StringVO de manière sécurisée
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

    /**
     * Version avec espaces supplémentaires personnalisables
     */
    public static function renderWithExtraSpaces(MapCollection $data, int $extraSpaces = 3, int $indent = 0): string
    {
        if ($data->isEmpty()) {
            return '<fg=yellow>⚠️  No data to display</fg=yellow>';
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
                ->concat('<fg=cyan>')
                ->concat($paddedKey)
                ->concat('</fg>')
                ->concat(self::SEPARATOR)
                ->concat($valueString);

            $lines = $lines->add($line->getValue());
        }

        return $lines->reduce(
            fn ($carry, $line) => $carry === '' ? $line : $carry.PHP_EOL.$line,
            ''
        );
    }

    /**
     * Version de debug pour voir les longueurs
     */
    public static function debug(MapCollection $data, int $indent = 0): string
    {
        if ($data->isEmpty()) {
            return '<fg=yellow>⚠️  No data to display</fg=yellow>';
        }

        $padding = StringVO::from('')->concat(str_repeat(self::INDENT, $indent));
        $maxKeyLength = self::calculateMaxKeyLength($data);
        $totalKeyWidth = $maxKeyLength->add(FloatVO::from(self::EXTRA_SPACES));
        $lines = ListCollection::from([]);

        $lines = $lines->add('<fg=yellow>📊 Debug: maxLength='.$maxKeyLength->getValue().', totalWidth='.$totalKeyWidth->getValue().'</fg=yellow>');

        foreach ($data->keys() as $key) {
            $keyString = self::toSafeString($key);
            $length = mb_strlen($keyString->getValue());
            $lines = $lines->add('<fg=gray>  key: "'.$keyString->getValue().'" length='.$length.'</fg=gray>');
        }

        $lines = $lines->add('');

        foreach ($data as $key => $value) {
            $keyString = self::toSafeString($key);
            $valueString = self::toSafeString($value);
            $paddedKey = self::padKey($keyString, $totalKeyWidth);

            $line = $padding
                ->concat('<fg=cyan>')
                ->concat($paddedKey)
                ->concat('</fg>')
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
