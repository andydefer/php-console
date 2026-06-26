<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Abstracts;

use AndyDefer\ConsoleWriter\Console\Enums\BgColor;
use AndyDefer\ConsoleWriter\Console\Enums\FgColor;
use AndyDefer\ConsoleWriter\Console\Enums\Options;
use AndyDefer\ConsoleWriter\Console\Services\AnsiConverterService;
use AndyDefer\ConsoleWriter\Console\Services\VirtualTerminalService;
use AndyDefer\ConsoleWriter\Contracts\Services\AnsiConverterInterface;
use AndyDefer\DomainStructures\Utils\MapCollection;

abstract class Component
{
    protected static ?AnsiConverterInterface $ansi = null;

    protected static ?VirtualTerminalService $vt = null;

    protected static ?MapCollection $styles = null;

    protected static function getAnsi(): AnsiConverterInterface
    {
        if (self::$ansi === null) {
            self::$ansi = new AnsiConverterService;
        }

        return self::$ansi;
    }

    protected static function getVT(): VirtualTerminalService
    {
        if (self::$vt === null) {
            self::$vt = new VirtualTerminalService(self::getAnsi());
        }

        return self::$vt;
    }

    // ========== STYLES DE TEXTE ==========

    protected static function bold(string $text): string
    {
        return self::getAnsi()->option($text, Options::BOLD);
    }

    protected static function underline(string $text): string
    {
        return self::getAnsi()->option($text, Options::UNDERLINE);
    }

    protected static function italic(string $text): string
    {
        return self::getAnsi()->option($text, Options::ITALIC);
    }

    protected static function dim(string $text): string
    {
        return self::getAnsi()->option($text, Options::DIM);
    }

    protected static function reverse(string $text): string
    {
        return self::getAnsi()->option($text, Options::REVERSE);
    }

    // ========== COULEURS ==========

    protected static function fg(string $text, string|FgColor $color): string
    {
        $fg = $color instanceof FgColor ? $color : self::getFgColor($color);

        return self::getAnsi()->colorEnum($text, $fg);
    }

    protected static function bg(string $text, string|BgColor $color): string
    {
        $bg = $color instanceof BgColor ? $color : self::getBgColor($color);

        return self::getAnsi()->bgColorEnum($text, $bg);
    }

    // ========== CONVERSION DE COULEURS ==========

    protected static function getFgColor(string $color): FgColor
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

    protected static function getBgColor(string $color): BgColor
    {
        return match ($color) {
            'black' => BgColor::BLACK,
            'red' => BgColor::RED,
            'green' => BgColor::GREEN,
            'yellow' => BgColor::YELLOW,
            'blue' => BgColor::BLUE,
            'magenta' => BgColor::MAGENTA,
            'cyan' => BgColor::CYAN,
            'white' => BgColor::WHITE,
            default => BgColor::WHITE,
        };
    }

    // ========== STYLES PRÉDÉFINIS ==========

    protected static function getStyles(): MapCollection
    {
        if (self::$styles === null) {
            self::$styles = MapCollection::from([
                'default' => [
                    'fg' => 'white',
                    'icon' => '',
                    'tag' => '',
                ],
                'success' => [
                    'fg' => 'green',
                    'icon' => '🟢',
                    'tag' => 'SUCCESS',
                ],
                'danger' => [
                    'fg' => 'red',
                    'icon' => '🔴',
                    'tag' => 'FAILED',
                ],
                'warning' => [
                    'fg' => 'yellow',
                    'icon' => '🟡',
                    'tag' => 'PENDING',
                ],
                'info' => [
                    'fg' => 'blue',
                    'icon' => '🔵',
                    'tag' => 'INFO',
                ],
                'primary' => [
                    'fg' => 'cyan',
                    'icon' => '🟣',
                    'tag' => 'PRIMARY',
                ],
                'dark' => [
                    'fg' => 'gray',
                    'icon' => '⚫',
                    'tag' => 'DARK',
                ],
                'light' => [
                    'fg' => 'white',
                    'icon' => '⚪',
                    'tag' => 'LIGHT',
                ],
                'success-dark' => [
                    'fg' => 'green',
                    'icon' => '🟢',
                    'tag' => 'SUCCESS',
                ],
                'danger-dark' => [
                    'fg' => 'red',
                    'icon' => '🔴',
                    'tag' => 'FAILED',
                ],
                'warning-dark' => [
                    'fg' => 'yellow',
                    'icon' => '🟡',
                    'tag' => 'PENDING',
                ],
                'info-dark' => [
                    'fg' => 'blue',
                    'icon' => '🔵',
                    'tag' => 'INFO',
                ],
                'primary-dark' => [
                    'fg' => 'cyan',
                    'icon' => '🟣',
                    'tag' => 'PRIMARY',
                ],
            ]);
        }

        return self::$styles;
    }
}
