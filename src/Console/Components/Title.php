<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components;

use AndyDefer\ConsoleWriter\Console\Abstracts\Component;
use AndyDefer\PhpVo\ValueObjects\Types\FloatVO;
use AndyDefer\PhpVo\ValueObjects\Types\StringVO;

final class Title extends Component
{
    private const MIN_PADDING = 3;

    private const MAX_PADDING = 10;

    public static function render(string $message): string
    {
        $text = StringVO::from($message);
        $textLength = FloatVO::from(mb_strlen($text->getValue()));

        $padding = self::calculatePadding($textLength);

        $totalWidth = $textLength->add($padding->multiply(FloatVO::from(2)));
        $totalWidthInt = $totalWidth->toInt();
        $paddingInt = $padding->toInt();

        $border = StringVO::from('')
            ->concat(str_repeat('═', $totalWidthInt));

        $content = StringVO::from('')
            ->concat(str_repeat(' ', $paddingInt))
            ->concat($text)
            ->concat(str_repeat(' ', $paddingInt));

        return self::fg(self::bold(
            '╔'.$border->getValue().'╗'."\n".
            $content->getValue()."\n".
            '╚'.$border->getValue().'╝'
        ), 'cyan');
    }

    private static function calculatePadding(FloatVO $textLength): FloatVO
    {
        $padding = $textLength->divide(FloatVO::from(4))->ceil();

        if ($padding->lessThan(FloatVO::from(self::MIN_PADDING))->getValue()) {
            return FloatVO::from(self::MIN_PADDING);
        }
        if ($padding->greaterThan(FloatVO::from(self::MAX_PADDING))->getValue()) {
            return FloatVO::from(self::MAX_PADDING);
        }

        return $padding;
    }

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

        return self::fg(self::bold(
            '╔'.$border->getValue().'╗'."\n".
            $content->getValue()."\n".
            '╚'.$border->getValue().'╝'
        ), 'cyan');
    }

    public static function renderWithWidth(string $message, int $width): string
    {
        $text = StringVO::from($message);
        $textLength = FloatVO::from(mb_strlen($text->getValue()));
        $totalWidth = FloatVO::from($width);

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

        return self::fg(self::bold(
            '╔'.$border->getValue().'╗'."\n".
            $content->getValue()."\n".
            '╚'.$border->getValue().'╝'
        ), 'cyan');
    }

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

        return self::fg(self::bold(
            '╔'.$border->getValue().'╗'."\n".
            $content->getValue()."\n".
            '╚'.$border->getValue().'╝'
        ), 'cyan');
    }
}
