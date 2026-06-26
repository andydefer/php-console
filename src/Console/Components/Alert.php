<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components;

use AndyDefer\ConsoleWriter\Console\Abstracts\Component;
use AndyDefer\PhpVo\ValueObjects\Types\FloatVO;
use AndyDefer\PhpVo\ValueObjects\Types\StringVO;

final class Alert extends Component
{
    private const DEFAULT_PADDING = 4;

    private const DEFAULT_ICON = '⚠️';

    private const DEFAULT_COLOR = 'yellow';

    private const DEFAULT_BORDER = '─';

    /**
     * Affiche une alerte avec les paramètres par défaut
     */
    public static function render(string $message): string
    {
        return self::buildAlert(
            message: $message,
            icon: self::DEFAULT_ICON,
            padding: self::DEFAULT_PADDING,
            color: self::DEFAULT_COLOR,
            borderChar: self::DEFAULT_BORDER
        );
    }

    /**
     * Version avec padding personnalisé
     */
    public static function renderWithPadding(string $message, int $padding): string
    {
        return self::buildAlert(
            message: $message,
            icon: self::DEFAULT_ICON,
            padding: $padding,
            color: self::DEFAULT_COLOR,
            borderChar: self::DEFAULT_BORDER
        );
    }

    /**
     * Version avec icône personnalisée
     */
    public static function renderWithIcon(string $message, string $icon = '⚠️', int $padding = 4): string
    {
        return self::buildAlert(
            message: $message,
            icon: $icon,
            padding: $padding,
            color: self::DEFAULT_COLOR,
            borderChar: self::DEFAULT_BORDER
        );
    }

    /**
     * Version avec couleur personnalisée
     */
    public static function renderWithColor(string $message, string $color = 'yellow', int $padding = 4): string
    {
        return self::buildAlert(
            message: $message,
            icon: self::DEFAULT_ICON,
            padding: $padding,
            color: $color,
            borderChar: self::DEFAULT_BORDER
        );
    }

    /**
     * Version avec bordure personnalisée
     */
    public static function renderWithBorder(string $message, string $borderChar = '─', string $color = 'yellow', int $padding = 4): string
    {
        return self::buildAlert(
            message: $message,
            icon: self::DEFAULT_ICON,
            padding: $padding,
            color: $color,
            borderChar: $borderChar
        );
    }

    /**
     * ✅ Méthode principale de construction de l'alerte
     */
    private static function buildAlert(
        string $message,
        string $icon,
        int $padding,
        string $color,
        string $borderChar
    ): string {
        $text = StringVO::from($message);
        $iconVO = StringVO::from($icon);
        $paddingVO = FloatVO::from($padding);

        // Calcul des longueurs
        $iconLength = FloatVO::from(mb_strlen($iconVO->getValue()));
        $textLength = FloatVO::from(mb_strlen($text->getValue()));

        // Largeur totale
        $totalWidth = $iconLength
            ->add(FloatVO::from(1)) // espace après l'icône
            ->add($textLength)
            ->add($paddingVO->multiply(FloatVO::from(2)));

        $totalWidthInt = $totalWidth->toInt();
        $paddingInt = $paddingVO->toInt();

        // Bordure
        $border = StringVO::from('')
            ->concat(str_repeat($borderChar, $totalWidthInt));

        // Contenu
        $content = StringVO::from('')
            ->concat(str_repeat(' ', $paddingInt))
            ->concat($iconVO)
            ->concat(' ')
            ->concat($text)
            ->concat(str_repeat(' ', $paddingInt));

        // ✅ Utilisation des méthodes de Component
        return self::fg('', $color)
            .'┌'.$border->getValue().'┐'."\n"
            .$content->getValue()."\n"
            .'└'.$border->getValue().'┘'
            .self::getAnsi()->reset();
    }

    /**
     * ✅ Version avec icône et couleur ensemble
     */
    public static function renderWithIconAndColor(
        string $message,
        string $icon = '⚠️',
        string $color = 'yellow',
        int $padding = 4
    ): string {
        return self::buildAlert(
            message: $message,
            icon: $icon,
            padding: $padding,
            color: $color,
            borderChar: self::DEFAULT_BORDER
        );
    }

    /**
     * ✅ Version complète avec tous les paramètres
     */
    public static function renderFull(
        string $message,
        string $icon = '⚠️',
        string $color = 'yellow',
        string $borderChar = '─',
        int $padding = 4
    ): string {
        return self::buildAlert(
            message: $message,
            icon: $icon,
            padding: $padding,
            color: $color,
            borderChar: $borderChar
        );
    }

    /**
     * ✅ Version avec style prédéfini
     */
    public static function renderSuccess(string $message): string
    {
        return self::buildAlert(
            message: $message,
            icon: '✅',
            padding: self::DEFAULT_PADDING,
            color: 'green',
            borderChar: self::DEFAULT_BORDER
        );
    }

    public static function renderError(string $message): string
    {
        return self::buildAlert(
            message: $message,
            icon: '❌',
            padding: self::DEFAULT_PADDING,
            color: 'red',
            borderChar: self::DEFAULT_BORDER
        );
    }

    public static function renderWarning(string $message): string
    {
        return self::buildAlert(
            message: $message,
            icon: '⚠️',
            padding: self::DEFAULT_PADDING,
            color: 'yellow',
            borderChar: self::DEFAULT_BORDER
        );
    }

    public static function renderInfo(string $message): string
    {
        return self::buildAlert(
            message: $message,
            icon: 'ℹ️',
            padding: self::DEFAULT_PADDING,
            color: 'blue',
            borderChar: self::DEFAULT_BORDER
        );
    }
}
