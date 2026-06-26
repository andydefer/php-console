<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components\Input;

use AndyDefer\ConsoleWriter\Console\Contracts\InputReaderInterface;
use AndyDefer\ConsoleWriter\Console\Enums\FgColor;
use AndyDefer\ConsoleWriter\Console\Enums\Options;
use AndyDefer\ConsoleWriter\Contracts\Services\AnsiConverterInterface;

final class Secret
{
    public static function execute(
        AnsiConverterInterface $ansi,
        InputReaderInterface $reader,
        string $question,
        string $color = 'cyan'
    ): string {
        $fg = self::getFgColor($color);
        $questionFormatted = $ansi->colorEnum($ansi->option($question, Options::BOLD), $fg);

        echo $questionFormatted.' ';

        return $reader->readSecretLine();
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
