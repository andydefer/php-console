<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components\Input;

use AndyDefer\ConsoleWriter\Console\Abstracts\InteractiveComponent;
use AndyDefer\ConsoleWriter\Console\Enums\Options;

final class Number extends InteractiveComponent
{
    public static function execute(
        string $question,
        ?int $min = null,
        ?int $max = null,
        ?int $default = null,
        string $color = 'cyan'
    ): int {
        $fg = self::getFgColor($color);

        $rangeText = '';
        if ($min !== null && $max !== null) {
            $rangeText = " ({$min}-{$max})";
        } elseif ($min !== null) {
            $rangeText = " (min: {$min})";
        } elseif ($max !== null) {
            $rangeText = " (max: {$max})";
        }

        $questionFormatted = self::getAnsi()->colorEnum(self::getAnsi()->option($question.$rangeText, Options::BOLD), $fg);

        while (true) {
            echo $questionFormatted.' ';
            $input = self::getReader()->readLine();

            if ($input === '' && $default !== null) {
                return $default;
            }

            if (! is_numeric($input)) {
                echo 'Veuillez entrer un nombre valide.'.PHP_EOL;

                continue;
            }

            $value = (int) $input;

            if ($min !== null && $value < $min) {
                echo "La valeur doit être supérieure ou égale à {$min}.".PHP_EOL;

                continue;
            }

            if ($max !== null && $value > $max) {
                echo "La valeur doit être inférieure ou égale à {$max}.".PHP_EOL;

                continue;
            }

            return $value;
        }
    }
}
