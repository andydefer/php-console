<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components;

use AndyDefer\ConsoleWriter\Console\Enums\FgColor;
use AndyDefer\ConsoleWriter\Console\Enums\Options;
use AndyDefer\ConsoleWriter\Console\Services\AnsiConverterService;
use AndyDefer\ConsoleWriter\Contracts\Services\AnsiConverterInterface;
use AndyDefer\DomainStructures\Utils\MapCollection;

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
final class Badge
{
    private static ?AnsiConverterInterface $ansi = null;

    private static ?MapCollection $styles = null;

    /**
     * Initialise le service ANSI
     */
    private static function getAnsi(): AnsiConverterInterface
    {
        if (self::$ansi === null) {
            self::$ansi = new AnsiConverterService;
        }

        return self::$ansi;
    }

    /**
     * Styles prédéfinis pour les badges
     */
    private static function getStyles(): MapCollection
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
                // Versions sombres (plus visibles)
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
