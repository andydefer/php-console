<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components;

use AndyDefer\ConsoleWriter\Console\Enums\FgColor;
use AndyDefer\ConsoleWriter\Console\Enums\Options;
use AndyDefer\ConsoleWriter\Console\Services\AnsiConverterService;
use AndyDefer\ConsoleWriter\Contracts\Services\AnsiConverterInterface;

/**
 * Affiche une métrique (KPI) dans la console
 *
 * @example
 * Metric::render('CPU', '45%');
 * Metric::render('RAM', '8.2 GB', 'green');
 * Metric::render('Requests', '1,234/s', 'yellow');
 *
 * // Sortie:
 * // CPU
 * // 45%
 */
final class Metric
{
    private static ?AnsiConverterInterface $ansi = null;

    private static function getAnsi(): AnsiConverterInterface
    {
        if (self::$ansi === null) {
            self::$ansi = new AnsiConverterService;
        }

        return self::$ansi;
    }

    /**
     * Affiche une métrique simple
     */
    public static function render(string $label, string $value, string $color = 'white'): string
    {
        $ansi = self::getAnsi();
        $fg = self::getFgColor($color);

        $labelFormatted = $ansi->option($label, Options::BOLD);
        $valueFormatted = $ansi->colorEnum($value, $fg);

        return $labelFormatted."\n".$valueFormatted;
    }

    /**
     * Affiche une métrique avec icône
     */
    public static function renderWithIcon(string $label, string $value, string $icon, string $color = 'white'): string
    {
        $ansi = self::getAnsi();
        $fg = self::getFgColor($color);

        $labelFormatted = $icon.' '.$ansi->option($label, Options::BOLD);
        $valueFormatted = $ansi->colorEnum($value, $fg);

        return $labelFormatted."\n".$valueFormatted;
    }

    /**
     * Affiche une métrique avec tendance
     */
    public static function renderWithTrend(
        string $label,
        string $value,
        string $trend,
        string $trendColor = 'green',
        string $valueColor = 'white'
    ): string {
        $ansi = self::getAnsi();
        $fg = self::getFgColor($valueColor);
        $trendFg = self::getFgColor($trendColor);

        $labelFormatted = $ansi->option($label, Options::BOLD);
        $valueFormatted = $ansi->colorEnum($value, $fg);
        $trendFormatted = $ansi->colorEnum(' '.$trend, $trendFg);

        return $labelFormatted."\n".$valueFormatted.$trendFormatted;
    }

    /**
     * Affiche une métrique en ligne (sur une seule ligne)
     */
    public static function renderInline(string $label, string $value, string $color = 'white'): string
    {
        $ansi = self::getAnsi();
        $fg = self::getFgColor($color);

        $labelFormatted = $ansi->option($label.':', Options::BOLD);
        $valueFormatted = $ansi->colorEnum($value, $fg);

        return $labelFormatted.' '.$valueFormatted;
    }

    /**
     * Convertit un nom de couleur en FgColor enum
     */
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
