<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components;

use AndyDefer\ConsoleWriter\Contracts\Renderable;
use AndyDefer\PhpVo\ValueObjects\Types\FloatVO;
use AndyDefer\PhpVo\ValueObjects\Types\StringVO;

final class Alert implements Renderable
{
    private const PADDING = 4;

    private const ICON = '⚠️';

    public static function render(string $message): string
    {
        $text = StringVO::from($message);

        // Longueur du message + icône + espaces
        $iconLength = FloatVO::from(mb_strlen(self::ICON));
        $textLength = FloatVO::from(mb_strlen($text->getValue()));

        // Largeur totale = icône + espace + message + padding gauche + padding droit
        $totalWidth = $iconLength
            ->add(1)  // espace après l'icône
            ->add($textLength)
            ->add(self::PADDING * 2);

        // Bordure supérieure et inférieure
        $border = StringVO::from('')
            ->concat(str_repeat('─', $totalWidth->toInt()));

        // Contenu centré sans colonnes
        $content = StringVO::from('')
            ->concat(str_repeat(' ', self::PADDING))
            ->concat(self::ICON)
            ->concat(' ')
            ->concat($text)
            ->concat(str_repeat(' ', self::PADDING));

        return '<fg=yellow>'
            .'┌'.$border->getValue().'┐'."\n"
            .$content->getValue()."\n"
            .'└'.$border->getValue().'┘'
            .'</fg=yellow>';
    }

    /**
     * Version avec padding personnalisé
     */
    public static function renderWithPadding(string $message, int $padding): string
    {
        $text = StringVO::from($message);
        $paddingVO = FloatVO::from($padding);

        $iconLength = FloatVO::from(mb_strlen(self::ICON));
        $textLength = FloatVO::from(mb_strlen($text->getValue()));

        $totalWidth = $iconLength
            ->add(FloatVO::from(1))
            ->add($textLength)
            ->add($paddingVO->multiply(FloatVO::from(2)));

        $totalWidthInt = $totalWidth->toInt();
        $paddingInt = $paddingVO->toInt();

        $border = StringVO::from('')
            ->concat(str_repeat('─', $totalWidthInt));

        $content = StringVO::from('')
            ->concat(str_repeat(' ', $paddingInt))
            ->concat(self::ICON)
            ->concat(' ')
            ->concat($text)
            ->concat(str_repeat(' ', $paddingInt));

        return '<fg=yellow>'
            .'┌'.$border->getValue().'┐'."\n"
            .$content->getValue()."\n"
            .'└'.$border->getValue().'┘'
            .'</fg=yellow>';
    }

    /**
     * Version avec icône personnalisée
     */
    public static function renderWithIcon(string $message, string $icon = '⚠️', int $padding = 4): string
    {
        $text = StringVO::from($message);
        $iconVO = StringVO::from($icon);
        $paddingVO = FloatVO::from($padding);

        $iconLength = FloatVO::from(mb_strlen($iconVO->getValue()));
        $textLength = FloatVO::from(mb_strlen($text->getValue()));

        $totalWidth = $iconLength
            ->add(FloatVO::from(1))
            ->add($textLength)
            ->add($paddingVO->multiply(FloatVO::from(2)));

        $totalWidthInt = $totalWidth->toInt();
        $paddingInt = $paddingVO->toInt();

        $border = StringVO::from('')
            ->concat(str_repeat('─', $totalWidthInt));

        $content = StringVO::from('')
            ->concat(str_repeat(' ', $paddingInt))
            ->concat($iconVO)
            ->concat(' ')
            ->concat($text)
            ->concat(str_repeat(' ', $paddingInt));

        return '<fg=yellow>'
            .'┌'.$border->getValue().'┐'."\n"
            .$content->getValue()."\n"
            .'└'.$border->getValue().'┘'
            .'</fg=yellow>';
    }

    /**
     * Version avec couleur personnalisée
     */
    public static function renderWithColor(string $message, string $color = 'yellow', int $padding = 4): string
    {
        $text = StringVO::from($message);
        $paddingVO = FloatVO::from($padding);

        $iconLength = FloatVO::from(mb_strlen(self::ICON));
        $textLength = FloatVO::from(mb_strlen($text->getValue()));

        $totalWidth = $iconLength
            ->add(FloatVO::from(1))
            ->add($textLength)
            ->add($paddingVO->multiply(FloatVO::from(2)));

        $totalWidthInt = $totalWidth->toInt();
        $paddingInt = $paddingVO->toInt();

        $border = StringVO::from('')
            ->concat(str_repeat('─', $totalWidthInt));

        $content = StringVO::from('')
            ->concat(str_repeat(' ', $paddingInt))
            ->concat(self::ICON)
            ->concat(' ')
            ->concat($text)
            ->concat(str_repeat(' ', $paddingInt));

        return '<fg='.$color.'>'
            .'┌'.$border->getValue().'┐'."\n"
            .$content->getValue()."\n"
            .'└'.$border->getValue().'┘'
            .'</fg='.$color.'>';
    }

    /**
     * Version avec bordure personnalisée
     */
    public static function renderWithBorder(string $message, string $borderChar = '─', string $color = 'yellow', int $padding = 4): string
    {
        $text = StringVO::from($message);
        $paddingVO = FloatVO::from($padding);

        $iconLength = FloatVO::from(mb_strlen(self::ICON));
        $textLength = FloatVO::from(mb_strlen($text->getValue()));

        $totalWidth = $iconLength
            ->add(FloatVO::from(1))
            ->add($textLength)
            ->add($paddingVO->multiply(FloatVO::from(2)));

        $totalWidthInt = $totalWidth->toInt();
        $paddingInt = $paddingVO->toInt();

        $border = StringVO::from('')
            ->concat(str_repeat($borderChar, $totalWidthInt));

        $content = StringVO::from('')
            ->concat(str_repeat(' ', $paddingInt))
            ->concat(self::ICON)
            ->concat(' ')
            ->concat($text)
            ->concat(str_repeat(' ', $paddingInt));

        return '<fg='.$color.'>'
            .'┌'.$border->getValue().'┐'."\n"
            .$content->getValue()."\n"
            .'└'.$border->getValue().'┘'
            .'</fg='.$color.'>';
    }
}
