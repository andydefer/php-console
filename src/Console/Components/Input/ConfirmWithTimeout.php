<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components\Input;

use AndyDefer\ConsoleWriter\Console\Contracts\InputReaderInterface;
use AndyDefer\ConsoleWriter\Console\Enums\FgColor;
use AndyDefer\ConsoleWriter\Console\Enums\Options;
use AndyDefer\ConsoleWriter\Contracts\Services\AnsiConverterInterface;

final class ConfirmWithTimeout
{
    public static function execute(
        AnsiConverterInterface $ansi,
        InputReaderInterface $reader,
        string $question,
        int $timeout = 5,
        bool $default = true,
        string $color = 'cyan'
    ): bool {
        $fg = self::getFgColor($color);
        $defaultText = $default ? '[Y/n]' : '[y/N]';

        $questionFormatted = $ansi->colorEnum(
            $ansi->option($question.' '.$defaultText, Options::BOLD),
            $fg
        );

        // Premier affichage initial
        echo $questionFormatted.' ('.$timeout.'s restantes) : ';

        // On passe la question formatée au reader
        $input = $reader->readLineWithTimeout($timeout, function ($remaining, $currentBuffer) use ($questionFormatted) {
            // Réaffichage synchronisé contenant la question, le temps mis à jour et ce que l'utilisateur a écrit
            echo "\r\033[K".$questionFormatted.' ('.$remaining.'s restantes) : '.$currentBuffer;
        });

        // Force le retour à la ligne propre après validation ou expiration
        echo PHP_EOL;

        if ($input === '') {
            return $default;
        }

        $input = strtolower($input);

        return in_array($input, ['y', 'yes', 'o', 'oui', 'true', '1'], true);
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
