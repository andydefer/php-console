<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components;

use AndyDefer\ConsoleWriter\Contracts\Renderable;
use AndyDefer\PhpVo\ValueObjects\Types\StringVO;

final class Link implements Renderable
{
    public static function render(string $message): string
    {
        $url = StringVO::from($message);

        return '<href='.$url->getValue().'>'.$url->getValue().'</href>';
    }

    public static function renderWithText(string $url, string $text): string
    {
        $urlString = StringVO::from($url);
        $textString = StringVO::from($text);

        return '<href='.$urlString->getValue().'>'.$textString->getValue().'</href>';
    }
}
