<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components\Input;

use AndyDefer\ConsoleWriter\Console\Abstracts\InteractiveComponent;
use AndyDefer\ConsoleWriter\Console\Enums\Options;

final class Ask extends InteractiveComponent
{
    public static function execute(

        string $question,
        ?string $default = null,
        string $color = 'cyan'
    ): string {
        $fg = self::getFgColor($color);
        $questionFormatted = self::getAnsi()->colorEnum(self::getAnsi()->option($question, Options::BOLD), $fg);

        echo $questionFormatted.' ';
        $input = self::getReader()->readLine();

        if ($input === '' && $default !== null) {
            return $default;
        }

        return $input;
    }
}
