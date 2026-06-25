<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components;

use AndyDefer\ConsoleWriter\Contracts\Renderable;
use AndyDefer\PhpVo\ValueObjects\Types\FloatVO;
use AndyDefer\PhpVo\ValueObjects\Types\StringVO;

final class Title implements Renderable
{
    private const MIN_PADDING = 3;

    private const MAX_PADDING = 10;

    public static function render(string $message): string
    {
        $text = StringVO::from($message);
        $textLength = FloatVO::from(mb_strlen($text->getValue()));

        // Calculer le padding
        $padding = self::calculatePadding($textLength);

        // Largeur totale = texte + padding gauche + padding droit
        $totalWidth = $textLength->add($padding->multiply(FloatVO::from(2)));
        $totalWidthInt = $totalWidth->toInt();
        $paddingInt = $padding->toInt();

        // Bordure supérieure et inférieure
        $border = StringVO::from('')
            ->concat(str_repeat('═', $totalWidthInt));

        // Contenu centré sans les colonnes
        $content = StringVO::from('')
            ->concat(str_repeat(' ', $paddingInt))
            ->concat($text)
            ->concat(str_repeat(' ', $paddingInt));

        return '<fg=cyan><options=bold>'
            .'╔'.$border->getValue().'╗'."\n"
            .$content->getValue()."\n"
            .'╚'.$border->getValue().'╝'
            .'</options=bold></fg=cyan>';
    }

    private static function calculatePadding(FloatVO $textLength): FloatVO
    {
        // Padding = longueur / 4, arrondi au supérieur
        $padding = $textLength->divide(FloatVO::from(4))->ceil();

        // Limiter entre MIN et MAX
        if ($padding->lessThan(FloatVO::from(self::MIN_PADDING))->getValue()) {
            return FloatVO::from(self::MIN_PADDING);
        }
        if ($padding->greaterThan(FloatVO::from(self::MAX_PADDING))->getValue()) {
            return FloatVO::from(self::MAX_PADDING);
        }

        return $padding;
    }

    /**
     * Version avec padding personnalisé
     */
    public static function renderWithPadding(string $message, int $padding): string
    {
        $text = StringVO::from($message);
        $paddingVO = FloatVO::from($padding);
        $textLength = FloatVO::from(mb_strlen($text->getValue()));

        $totalWidth = $textLength->add($paddingVO->multiply(FloatVO::from(2)));
        $totalWidthInt = $totalWidth->toInt();
        $paddingInt = $paddingVO->toInt();

        $border = StringVO::from('')
            ->concat(str_repeat('═', $totalWidthInt));

        $content = StringVO::from('')
            ->concat(str_repeat(' ', $paddingInt))
            ->concat($text)
            ->concat(str_repeat(' ', $paddingInt));

        return '<fg=cyan><options=bold>'
            .'╔'.$border->getValue().'╗'."\n"
            .$content->getValue()."\n"
            .'╚'.$border->getValue().'╝'
            .'</options=bold></fg=cyan>';
    }

    /**
     * Version avec largeur personnalisée
     */
    public static function renderWithWidth(string $message, int $width): string
    {
        $text = StringVO::from($message);
        $textLength = FloatVO::from(mb_strlen($text->getValue()));
        $totalWidth = FloatVO::from($width);

        // Calculer les espaces pour centrer
        $totalPadding = $totalWidth->subtract($textLength);
        $leftPadding = $totalPadding->divide(FloatVO::from(2))->floor();
        $rightPadding = $totalPadding->subtract($leftPadding);

        $leftPaddingInt = $leftPadding->toInt();
        $rightPaddingInt = $rightPadding->toInt();

        $border = StringVO::from('')
            ->concat(str_repeat('═', $totalWidth->toInt()));

        $content = StringVO::from('')
            ->concat(str_repeat(' ', $leftPaddingInt))
            ->concat($text)
            ->concat(str_repeat(' ', $rightPaddingInt));

        return '<fg=cyan><options=bold>'
            .'╔'.$border->getValue().'╗'."\n"
            .$content->getValue()."\n"
            .'╚'.$border->getValue().'╝'
            .'</options=bold></fg=cyan>';
    }

    /**
     * Version avec bordure personnalisée
     */
    public static function renderWithBorder(string $message, string $borderChar = '═', int $padding = 5): string
    {
        $text = StringVO::from($message);
        $paddingVO = FloatVO::from($padding);
        $textLength = FloatVO::from(mb_strlen($text->getValue()));

        $totalWidth = $textLength->add($paddingVO->multiply(FloatVO::from(2)));
        $totalWidthInt = $totalWidth->toInt();
        $paddingInt = $paddingVO->toInt();

        $border = StringVO::from('')
            ->concat(str_repeat($borderChar, $totalWidthInt));

        $content = StringVO::from('')
            ->concat(str_repeat(' ', $paddingInt))
            ->concat($text)
            ->concat(str_repeat(' ', $paddingInt));

        return '<fg=cyan><options=bold>'
            .'╔'.$border->getValue().'╗'."\n"
            .$content->getValue()."\n"
            .'╚'.$border->getValue().'╝'
            .'</options=bold></fg=cyan>';
    }
}
