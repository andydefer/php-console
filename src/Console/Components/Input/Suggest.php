<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components\Input;

use AndyDefer\ConsoleWriter\Console\Contracts\InputReaderInterface;
use AndyDefer\ConsoleWriter\Console\Enums\FgColor;
use AndyDefer\ConsoleWriter\Console\Enums\Options;
use AndyDefer\ConsoleWriter\Contracts\Services\AnsiConverterInterface;

final class Suggest
{
    private static ?string $oldStty = null;

    public static function execute(
        AnsiConverterInterface $ansi,
        InputReaderInterface $reader,
        string $question,
        array $suggestions,
        string $color = 'cyan'
    ): string {
        $fg = self::getFgColor($color);

        // Formate uniquement la question sans la liste entre crochets
        $questionFormatted = $ansi->colorEnum($ansi->option($question, Options::BOLD), $fg);

        $buffer = '';
        self::setupTerminal();

        try {
            while (true) {
                $currentSuggestion = '';
                if ($buffer !== '') {
                    foreach ($suggestions as $suggestion) {
                        if (strpos(strtolower($suggestion), strtolower($buffer)) === 0) {
                            $currentSuggestion = substr($suggestion, strlen($buffer));
                            break;
                        }
                    }
                }

                // Réécrit la question et la saisie sur la même ligne à chaque rafraîchissement
                echo "\r\033[K".$questionFormatted.' '.$buffer.$ansi->colorEnum($currentSuggestion, FgColor::GRAY);

                $key = $reader->readChar();

                if ($key === 'ENTER') {
                    if ($currentSuggestion !== '') {
                        $buffer .= $currentSuggestion;
                    }
                    echo PHP_EOL;
                    break;
                }

                if ($key === 'BACKSPACE') {
                    if ($buffer !== '') {
                        $buffer = mb_substr($buffer, 0, -1);
                    }

                    continue;
                }

                if ($key === 'ESC') {
                    $buffer = '';
                    echo PHP_EOL;
                    break;
                }

                if ($key === 'SPACE') {
                    if ($currentSuggestion !== '') {
                        $buffer .= $currentSuggestion;
                    } else {
                        $buffer .= ' ';
                    }

                    continue;
                }

                // ✅ GESTION DE LA FLÈCHE DROITE : Complète le texte sans valider la ligne
                if ($key === 'RIGHT') {
                    if ($currentSuggestion !== '') {
                        $buffer .= $currentSuggestion;
                    }

                    continue;
                }

                if (in_array($key, ['UP', 'DOWN', 'LEFT', 'UNKNOWN'], true)) {
                    continue;
                }

                $buffer .= $key;
            }
        } finally {
            self::restoreTerminal();
        }

        foreach ($suggestions as $suggestion) {
            if (strtolower($buffer) === strtolower($suggestion)) {
                return $suggestion;
            }
        }

        foreach ($suggestions as $suggestion) {
            if (strpos(strtolower($suggestion), strtolower($buffer)) === 0) {
                return $suggestion;
            }
        }

        return $buffer;
    }

    private static function setupTerminal(): void
    {
        self::$oldStty = shell_exec('stty -g');
        shell_exec('stty -icanon -echo min 1 time 0');
    }

    private static function restoreTerminal(): void
    {
        if (self::$oldStty !== null) {
            shell_exec('stty '.self::$oldStty);
            self::$oldStty = null;
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
