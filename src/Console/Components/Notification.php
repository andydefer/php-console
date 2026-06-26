<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components;

use AndyDefer\ConsoleWriter\Console\Enums\FgColor;
use AndyDefer\ConsoleWriter\Console\Enums\Options;
use AndyDefer\ConsoleWriter\Console\Services\AnsiConverterService;
use AndyDefer\ConsoleWriter\Contracts\Services\AnsiConverterInterface;

final class Notification
{
    private const DEFAULT_ICON = '🔔';

    private static ?AnsiConverterInterface $ansi = null;

    /**
     * Mapping des types vers leurs icônes
     */
    private const TYPE_ICONS = [
        'success' => '✅',
        'error' => '❌',
        'warning' => '⚠️',
        'info' => 'ℹ️',
    ];

    private static function getAnsi(): AnsiConverterInterface
    {
        if (self::$ansi === null) {
            self::$ansi = new AnsiConverterService;
        }

        return self::$ansi;
    }

    public static function render(string $message, string $type = 'default', ?string $icon = null): string
    {
        $ansi = self::getAnsi();
        $color = self::getColorForType($type);

        if ($icon === null) {
            $icon = self::TYPE_ICONS[$type] ?? self::DEFAULT_ICON;
        }

        $fg = self::getFgColor($color);
        $iconFormatted = $ansi->colorEnum($icon, $fg);
        $messageFormatted = $ansi->colorEnum($ansi->option($message, Options::BOLD), $fg);

        return $iconFormatted.' '.$messageFormatted;
    }

    /**
     * Notification de succès (✅)
     */
    public static function success(string $message): string
    {
        return self::render($message, 'success');
    }

    /**
     * Notification d'erreur (❌)
     */
    public static function error(string $message): string
    {
        return self::render($message, 'error');
    }

    /**
     * Notification d'avertissement (⚠️)
     */
    public static function warning(string $message): string
    {
        return self::render($message, 'warning');
    }

    /**
     * Notification d'information (ℹ️)
     */
    public static function info(string $message): string
    {
        return self::render($message, 'info');
    }

    /**
     * Notification avec icône personnalisée
     */
    public static function withIcon(string $message, string $icon, string $type = 'info'): string
    {
        return self::render($message, $type, $icon);
    }

    /**
     * Notification avec une couleur personnalisée
     */
    public static function withColor(string $message, string $color, ?string $icon = null): string
    {
        if ($icon === null) {
            $icon = self::DEFAULT_ICON;
        }

        $ansi = self::getAnsi();
        $fg = self::getFgColor($color);

        $iconFormatted = $ansi->colorEnum($icon, $fg);
        $messageFormatted = $ansi->colorEnum($ansi->option($message, Options::BOLD), $fg);

        return $iconFormatted.' '.$messageFormatted;
    }

    private static function getColorForType(string $type): string
    {
        return match ($type) {
            'success' => 'green',
            'error' => 'red',
            'warning' => 'yellow',
            'info' => 'blue',
            default => 'white',
        };
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
