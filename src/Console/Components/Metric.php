<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components;

use AndyDefer\ConsoleWriter\Console\Abstracts\Component;
use AndyDefer\ConsoleWriter\Console\Enums\Options;

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
final class Metric extends Component
{
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
}
