<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components\Input;

use AndyDefer\ConsoleWriter\Console\Contracts\InputReaderInterface;
use AndyDefer\ConsoleWriter\Console\Enums\FgColor;
use AndyDefer\ConsoleWriter\Console\Enums\Options;
use AndyDefer\ConsoleWriter\Contracts\Services\AnsiConverterInterface;

final class Number
{
    public static function execute(
        AnsiConverterInterface $ansi,
        InputReaderInterface $reader,
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

        $questionFormatted = $ansi->colorEnum($ansi->option($question.$rangeText, Options::BOLD), $fg);

        while (true) {
            echo $questionFormatted.' ';
            $input = $reader->readLine();

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

    private static function getFgColor(string $color): FgColor
    {
        return match ($color) {
            'black' => FgColor::BLACK,
            'red' => FgColor::RED,
            'green' => FgColor::GREEN,
            'yellow' => FgColor::YELLOW,
            'blue' => FgColor::BLUE,
            'magenta' => FgColor::MAGENTA,
            'cyan' => FgColor::CYAN,
            'white' => FgColor::WHITE,
            'gray' => FgColor::GRAY,
            default => FgColor::CYAN,
        };
    }
}
