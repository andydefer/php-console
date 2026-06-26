<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components;

use AndyDefer\ConsoleWriter\Console\Abstracts\Component;
use AndyDefer\ConsoleWriter\Console\Enums\Options;

/**
 * Affiche un badge coloré dans la console
 *
 * @example
 * Badge::render('SUCCESS', 'success');
 * Badge::render('FAILED', 'danger');
 * Badge::render('PENDING', 'warning');
 *
 * // Sortie:
 * // [SUCCESS]  (vert)
 * // [FAILED]   (rouge)
 * // [PENDING]  (jaune)
 */
final class Badge extends Component
{
    /**
     * Affiche un badge simple (outline - sans fond)
     */
    public static function render(string $text, string $style = 'default'): string
    {
        $styles = self::getStyles();

        if (! $styles->hasKey($style)) {
            $style = 'default';
        }

        $config = $styles->get($style);
        $fgColor = $config['fg'] ?? 'white';

        $ansi = self::getAnsi();
        $fg = self::getFgColor($fgColor);

        // ✅ Badge avec crochets
        $badge = '['.$text.']';

        return $ansi->colorEnum($ansi->option($badge, Options::BOLD), $fg);
    }

    /**
     * Affiche un badge avec icône (outline - sans fond)
     */
    public static function renderWithIcon(string $text, string $icon, string $style = 'default'): string
    {
        $styles = self::getStyles();

        if (! $styles->hasKey($style)) {
            $style = 'default';
        }

        $config = $styles->get($style);
        $fgColor = $config['fg'] ?? 'white';

        $ansi = self::getAnsi();
        $fg = self::getFgColor($fgColor);

        // ✅ Badge avec crochets
        $badge = $icon.' ['.$text.']';

        return $ansi->colorEnum($ansi->option($badge, Options::BOLD), $fg);
    }

    /**
     * Affiche un badge avec icône uniquement (sans fond)
     */
    public static function renderIconOnly(string $icon, string $text = ''): string
    {
        $ansi = self::getAnsi();

        if ($text !== '') {
            return $icon.' ['.$ansi->option($text, Options::BOLD).']';
        }

        return $icon;
    }

    /**
     * Affiche un badge avec style prédéfini (success) - version outline
     */
    public static function success(string $text = 'SUCCESS'): string
    {
        return self::renderWithIcon($text, '🟢', 'success-dark');
    }

    /**
     * Affiche un badge avec style prédéfini (danger) - version outline
     */
    public static function danger(string $text = 'FAILED'): string
    {
        return self::renderWithIcon($text, '🔴', 'danger-dark');
    }

    /**
     * Affiche un badge avec style prédéfini (warning) - version outline
     */
    public static function warning(string $text = 'PENDING'): string
    {
        return self::renderWithIcon($text, '🟡', 'warning-dark');
    }

    /**
     * Affiche un badge avec style prédéfini (info) - version outline
     */
    public static function info(string $text = 'INFO'): string
    {
        return self::renderWithIcon($text, '🔵', 'info-dark');
    }

    /**
     * Affiche un badge avec style prédéfini (primary) - version outline
     */
    public static function primary(string $text = 'PRIMARY'): string
    {
        return self::renderWithIcon($text, '🟣', 'primary-dark');
    }

    /**
     * Affiche un badge avec style prédéfini (dark) - version outline
     */
    public static function dark(string $text = 'DARK'): string
    {
        return self::renderWithIcon($text, '⚫', 'dark');
    }

    /**
     * Affiche un badge avec style prédéfini (light) - version outline
     */
    public static function light(string $text = 'LIGHT'): string
    {
        return self::renderWithIcon($text, '⚪', 'light');
    }

    /**
     * Ajoute un style personnalisé
     */
    public static function addStyle(string $name, string $fg, string $icon = '', string $tag = ''): void
    {
        $styles = self::getStyles();
        self::$styles = $styles->put($name, [
            'fg' => $fg,
            'icon' => $icon,
            'tag' => $tag,
        ]);
    }
}
