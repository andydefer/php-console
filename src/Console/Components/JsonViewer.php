<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components;

use AndyDefer\ConsoleWriter\Console\Enums\FgColor;
use AndyDefer\ConsoleWriter\Console\Services\AnsiConverterService;
use AndyDefer\ConsoleWriter\Contracts\Services\AnsiConverterInterface;

/**
 * Affiche du JSON formaté et coloré dans la console
 */
final class JsonViewer
{
    private const INDENT = '  ';

    private static ?AnsiConverterInterface $ansi = null;

    private static function getAnsi(): AnsiConverterInterface
    {
        if (self::$ansi === null) {
            self::$ansi = new AnsiConverterService;
        }

        return self::$ansi;
    }

    public static function render(array|string $data, int $indent = 0): string
    {
        $json = self::getJson($data);
        $decoded = json_decode($json, true);

        if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
            return '<fg=red>⚠️  Invalid JSON: '.json_last_error_msg().'</fg=red>';
        }

        if (empty($decoded)) {
            return '{}';
        }

        return self::formatJson($decoded, $indent);
    }

    public static function renderRaw(array|string $data, int $indent = 0): string
    {
        $json = self::getJson($data);
        $decoded = json_decode($json, true);

        if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
            return '⚠️  Invalid JSON: '.json_last_error_msg();
        }

        return json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public static function renderCompact(array|string $data): string
    {
        $json = self::getJson($data);
        $decoded = json_decode($json, true);

        if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
            return '<fg=red>⚠️  Invalid JSON: '.json_last_error_msg().'</fg=red>';
        }

        return json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    private static function formatJson(array|object $data, int $indent = 0): string
    {
        $ansi = self::getAnsi();
        $lines = [];
        $indentStr = str_repeat(self::INDENT, $indent);

        // ✅ Si c'est un tableau simple (liste)
        if (array_is_list($data)) {
            $lines = [];
            foreach ($data as $value) {
                if (is_array($value) || is_object($value)) {
                    $lines[] = $indentStr.'- '.self::formatJson($value, $indent + 1);
                } else {
                    $lines[] = $indentStr.'- '.self::formatValue($value);
                }
            }

            return implode("\n", $lines);
        }

        // ✅ Si c'est un objet / tableau associatif
        foreach ($data as $key => $value) {
            $keyFormatted = $ansi->colorEnum('"'.$key.'"', FgColor::CYAN);

            if (is_array($value) || is_object($value)) {
                if (empty((array) $value)) {
                    $lines[] = $indentStr.$keyFormatted.': {}';
                } else {
                    $lines[] = $indentStr.$keyFormatted.': {';
                    $lines[] = self::formatJson($value, $indent + 1);
                    $lines[] = $indentStr.'}';
                }
            } else {
                $valueFormatted = self::formatValue($value);
                $lines[] = $indentStr.$keyFormatted.': '.$valueFormatted;
            }
        }

        return implode("\n", $lines);
    }

    private static function formatValue(mixed $value): string
    {
        $ansi = self::getAnsi();

        if (is_string($value)) {
            return $ansi->colorEnum('"'.addslashes($value).'"', FgColor::GREEN);
        }

        if (is_int($value)) {
            return $ansi->colorEnum((string) $value, FgColor::YELLOW);
        }

        if (is_float($value)) {
            return $ansi->colorEnum((string) $value, FgColor::YELLOW);
        }

        if (is_bool($value)) {
            return $ansi->colorEnum($value ? 'true' : 'false', FgColor::MAGENTA);
        }

        if ($value === null) {
            return $ansi->colorEnum('null', FgColor::GRAY);
        }

        return (string) $value;
    }

    private static function getJson(array|string $data): string
    {
        if (is_array($data)) {
            return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        return $data;
    }

    public static function renderWithDepth(array|string $data, int $maxDepth = 3, int $currentDepth = 0): string
    {
        if ($currentDepth >= $maxDepth) {
            return '...';
        }

        $json = self::getJson($data);
        $decoded = json_decode($json, true);

        if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
            return '<fg=red>⚠️  Invalid JSON: '.json_last_error_msg().'</fg=red>';
        }

        return self::formatJsonWithDepth($decoded, $maxDepth, $currentDepth);
    }

    private static function formatJsonWithDepth(array|object $data, int $maxDepth, int $currentDepth = 0): string
    {
        if ($currentDepth >= $maxDepth) {
            return '...';
        }

        $ansi = self::getAnsi();
        $lines = [];
        $indentStr = str_repeat(self::INDENT, $currentDepth);

        // ✅ Si c'est un tableau simple (liste)
        if (array_is_list($data)) {
            $lines = [];
            foreach ($data as $value) {
                if (is_array($value) || is_object($value)) {
                    $lines[] = $indentStr.'- '.self::formatJsonWithDepth($value, $maxDepth, $currentDepth + 1);
                } else {
                    $lines[] = $indentStr.'- '.self::formatValue($value);
                }
            }

            return implode("\n", $lines);
        }

        foreach ($data as $key => $value) {
            $keyFormatted = $ansi->colorEnum('"'.$key.'"', FgColor::CYAN);

            if (is_array($value) || is_object($value)) {
                if (empty((array) $value)) {
                    $lines[] = $indentStr.$keyFormatted.': {}';
                } else {
                    $lines[] = $indentStr.$keyFormatted.': {';
                    $lines[] = self::formatJsonWithDepth($value, $maxDepth, $currentDepth + 1);
                    $lines[] = $indentStr.'}';
                }
            } else {
                $valueFormatted = self::formatValue($value);
                $lines[] = $indentStr.$keyFormatted.': '.$valueFormatted;
            }
        }

        return implode("\n", $lines);
    }
}
