<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components\Input;

use AndyDefer\ConsoleWriter\Console\Contracts\InputReaderInterface;
use AndyDefer\ConsoleWriter\Console\Enums\FgColor;
use AndyDefer\ConsoleWriter\Console\Enums\Options;
use AndyDefer\ConsoleWriter\Contracts\Services\AnsiConverterInterface;

final class MultiChoice
{
    private static ?string $oldStty = null;

    private static int $lineCount = 0;

    private static array $state = [];

    public static function execute(
        AnsiConverterInterface $ansi,
        InputReaderInterface $reader,
        string $question,
        array $options,
        array $selected = [],
        string $color = 'cyan'
    ): array {
        $fg = self::getFgColor($color);
        $currentIndex = 0;
        $selected = array_intersect($selected, $options);

        self::$state = [
            'question' => $question,
            'options' => $options,
            'selected' => $selected,
            'currentIndex' => $currentIndex,
            'color' => $color,
            'fg' => $fg,
            'ansi' => $ansi,
        ];

        self::setupTerminal();

        try {
            // ✅ Premier affichage initial
            self::render();

            while (true) {
                $fp = fopen('php://stdin', 'r');
                $read = [$fp];
                $write = null;
                $except = null;

                if (stream_select($read, $write, $except, 0, 100000) > 0) {
                    $char = fread($fp, 3);
                    $key = self::parseKey($char);

                    if ($key === 'UP') {
                        $currentIndex = max(0, $currentIndex - 1);
                        self::$state['currentIndex'] = $currentIndex;
                    } elseif ($key === 'DOWN') {
                        $currentIndex = min(count($options) - 1, $currentIndex + 1);
                        self::$state['currentIndex'] = $currentIndex;
                    } elseif ($key === 'SPACE') {
                        $currentOption = $options[$currentIndex];
                        if (in_array($currentOption, $selected, true)) {
                            $selected = array_filter($selected, fn ($item) => $item !== $currentOption);
                        } else {
                            $selected[] = $currentOption;
                        }
                        self::$state['selected'] = $selected;
                    } elseif ($key === 'ENTER') {
                        fclose($fp);
                        break;
                    } elseif ($key === 'ESC') {
                        fclose($fp);

                        return [];
                    }

                    // ✅ 1. Effacer l'affichage précédent
                    self::clearLines();

                    // ✅ 2. Réafficher le bloc mis à jour au même endroit
                    self::render();
                }
                fclose($fp);
            }
        } finally {
            self::restoreTerminal();
        }

        return array_values($selected);
    }

    private static function render(): void
    {
        $ansi = self::$state['ansi'];
        $fg = self::$state['fg'];
        $question = self::$state['question'];
        $options = self::$state['options'];
        $selected = self::$state['selected'];
        $currentIndex = self::$state['currentIndex'];

        $lines = [];

        $questionFormatted = $ansi->colorEnum(
            $ansi->option($question, Options::BOLD),
            $fg
        );
        $lines[] = $questionFormatted;

        $lines[] = '';

        foreach ($options as $index => $option) {
            $isSelected = in_array($option, $selected, true);
            $isCurrent = ($index === $currentIndex);

            $checkbox = $isSelected ? '[x]' : '[ ]';
            $prefix = $isCurrent ? '> ' : '  ';

            if ($isCurrent) {
                $line = $prefix.$ansi->colorEnum($checkbox, FgColor::YELLOW).' '.$option;
            } elseif ($isSelected) {
                $line = $prefix.$ansi->colorEnum($checkbox, FgColor::GREEN).' '.$option;
            } else {
                $line = $prefix.$checkbox.' '.$option;
            }

            $lines[] = $line;
        }

        $lines[] = '';
        $lines[] = '↑/↓ naviguer, Espace sélectionner, Entrée valider, Échap annuler';

        // ✅ Stocker le nombre exact de lignes du bloc
        self::$lineCount = count($lines);

        // ✅ Affichage propre suivi d'un retour à la ligne pour le curseur
        echo implode(PHP_EOL, $lines).PHP_EOL;
    }

    private static function clearLines(): void
    {
        if (self::$lineCount > 0) {
            // ✅ On remonte d'une ligne d'abord pour compenser le PHP_EOL final du render
            echo "\033[1A";

            // ✅ On remonte et efface chaque ligne du bas vers le haut
            // Répéter l'opération (Effacer la ligne + Monter d'une ligne)
            echo str_repeat("\033[2K\033[1A", self::$lineCount - 1);

            // ✅ On efface la toute première ligne (la question) et on se remet au début (\r)
            echo "\033[2K\r";
        }
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

    private static function parseKey(string $char): string
    {
        if ($char === false || $char === '') {
            return 'UNKNOWN';
        }

        if (strlen($char) === 3 && $char[0] === "\033" && $char[1] === '[') {
            return match ($char[2]) {
                'A' => 'UP',
                'B' => 'DOWN',
                'C' => 'RIGHT',
                'D' => 'LEFT',
                default => 'UNKNOWN',
            };
        }

        if ($char === "\033") {
            return 'ESC';
        }

        return match ($char) {
            ' ' => 'SPACE',
            "\r", "\n" => 'ENTER',
            default => 'UNKNOWN',
        };
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
