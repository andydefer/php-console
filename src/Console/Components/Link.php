<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components;

use AndyDefer\ConsoleWriter\Console\Abstracts\Component;
use AndyDefer\PhpVo\ValueObjects\Types\StringVO;

final class Link extends Component
{
    /**
     * Affiche un lien avec la séquence OSC 8 (standard)
     * Supporté par : iTerm2, Kitty, WezTerm, Windows Terminal, etc.
     */
    public static function render(string $message): string
    {
        $url = StringVO::from($message);

        return "\033]8;;".$url->getValue()."\033\\"
            .self::fg($url->getValue(), 'cyan')
            ."\033]8;;\033\\";
    }

    public static function renderWithText(string $url, string $text): string
    {
        $urlString = StringVO::from($url);
        $textString = StringVO::from($text);

        return "\033]8;;".$urlString->getValue()."\033\\"
            .self::fg($textString->getValue(), 'cyan')
            ."\033]8;;\033\\";
    }

    public static function renderWithIcon(string $url, string $text, string $icon = '🔗'): string
    {
        $urlString = StringVO::from($url);
        $textString = StringVO::from($text);
        $iconVO = StringVO::from($icon);

        return $iconVO->getValue().' '
            ."\033]8;;".$urlString->getValue()."\033\\"
            .self::fg($textString->getValue(), 'cyan')
            ."\033]8;;\033\\";
    }

    public static function renderWithColor(string $url, string $text, string $color = 'cyan'): string
    {
        $urlString = StringVO::from($url);
        $textString = StringVO::from($text);

        return "\033]8;;".$urlString->getValue()."\033\\"
            .self::fg($textString->getValue(), $color)
            ."\033]8;;\033\\";
    }

    public static function renderWithUnderline(string $url, string $text, string $color = 'cyan'): string
    {
        $urlString = StringVO::from($url);
        $textString = StringVO::from($text);

        return "\033]8;;".$urlString->getValue()."\033\\"
            .self::fg(self::underline($textString->getValue()), $color)
            ."\033]8;;\033\\";
    }
}
