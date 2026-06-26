<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components\Input;

use AndyDefer\ConsoleWriter\Console\Contracts\InputReaderInterface;
use AndyDefer\ConsoleWriter\Console\Enums\FgColor;
use AndyDefer\ConsoleWriter\Console\Enums\Options;
use AndyDefer\ConsoleWriter\Contracts\Services\AnsiConverterInterface;

final class Confirm
{
    public static function execute(
        AnsiConverterInterface $ansi,
        InputReaderInterface $reader,
        string $question,
        bool $default = true,
        string $color = 'cyan'
    ): bool {
        $fg = self::getFgColor($color);
        $defaultText = $default ? '[Y/n]' : '[y/N]';
        $questionFormatted = $ansi->colorEnum($ansi->option($question.' '.$defaultText, Options::BOLD), $fg);

        echo $questionFormatted.' ';
        $input = strtolower($reader->readLine());

        if ($input === '') {
            return $default;
        }

        return in_array($input, ['y', 'yes', 'o', 'oui', 'true', '1']);
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
