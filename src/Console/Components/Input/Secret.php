<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components\Input;

use AndyDefer\ConsoleWriter\Console\Abstracts\InteractiveComponent;
use AndyDefer\ConsoleWriter\Console\Enums\Options;

final class Secret extends InteractiveComponent
{
    public static function execute(
        string $question,
        string $color = 'cyan'
    ): string {
        $fg = self::getFgColor($color);
        $questionFormatted = self::getAnsi()->colorEnum(self::getAnsi()->option($question, Options::BOLD), $fg);

        echo $questionFormatted.' ';

        return self::getReader()->readSecretLine();
    }
}
