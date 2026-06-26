<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components\Input;

use AndyDefer\ConsoleWriter\Console\Abstracts\InteractiveComponent;
use AndyDefer\ConsoleWriter\Console\Enums\Options;

final class Confirm extends InteractiveComponent
{
    public static function execute(
        string $question,
        bool $default = true,
        string $color = 'cyan'
    ): bool {
        $fg = self::getFgColor($color);
        $defaultText = $default ? '[Y/n]' : '[y/N]';
        $questionFormatted = self::getAnsi()->colorEnum(self::getAnsi()->option($question.' '.$defaultText, Options::BOLD), $fg);

        echo $questionFormatted.' ';
        $input = strtolower(self::getReader()->readLine());

        if ($input === '') {
            return $default;
        }

        return in_array($input, ['y', 'yes', 'o', 'oui', 'true', '1']);
    }
}
