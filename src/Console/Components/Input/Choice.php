<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components\Input;

use AndyDefer\ConsoleWriter\Console\Contracts\InputReaderInterface;
use AndyDefer\ConsoleWriter\Console\Enums\FgColor;
use AndyDefer\ConsoleWriter\Console\Enums\Options;
use AndyDefer\ConsoleWriter\Contracts\Services\AnsiConverterInterface;

final class Choice
{
    private static ?string $oldStty = null;

    private static int $totalLines = 0;

    public static function execute(
        AnsiConverterInterface $ansi,
        InputReaderInterface $reader,
        string $question,
        array $choices,
        ?int $default = null,
        string $color = 'cyan'
    ): string {
        $fg = self::getFgColor($color);
        $currentIndex = $default ?? 0;
        $choices = array_values($choices);
        $totalChoices = count($choices);

        // Tampon pour stocker la saisie de l'utilisateur (ex: "1", "12")
        $inputBuffer = '';

        self::setupTerminal();

        try {
            while (true) {
                $lines = [];

                // ✅ 1. QUESTION AVEC LA SÉLECTION ACTUELLE À CÔTÉ EN GRIS
                $questionFormatted = $ansi->colorEnum(
                    $ansi->option($question, Options::BOLD),
                    $fg
                );

                // Si l'utilisateur est en train de taper un nombre, on l'affiche, sinon on affiche le libellé de l'option active
                $currentDisplay = $inputBuffer !== '' ? $inputBuffer : $choices[$currentIndex];
                $lines[] = $questionFormatted.' : '.$ansi->colorEnum($currentDisplay, FgColor::GRAY);
                $lines[] = '';

                // ✅ 2. LISTE DES CHOIX EN DESSOUS
                foreach ($choices as $index => $choice) {
                    $prefix = ($index === $currentIndex) ? '> ' : '  ';
                    $number = $index + 1;

                    if ($index === $currentIndex) {
                        $lines[] = $prefix.$number.'. '.$ansi->colorEnum($choice, FgColor::YELLOW);
                    } else {
                        $lines[] = $prefix.$number.'. '.$choice;
                    }
                }

                self::$totalLines = count($lines);
                echo implode(PHP_EOL, $lines).PHP_EOL;

                $key = $reader->readChar();

                if ($key === 'UP') {
                    // Les flèches réinitialisent le tampon numérique pour reprendre la main manuellement
                    $inputBuffer = '';
                    $currentIndex = ($currentIndex - 1 + $totalChoices) % $totalChoices;
                } elseif ($key === 'DOWN') {
                    $inputBuffer = '';
                    $currentIndex = ($currentIndex + 1) % $totalChoices;
                } elseif ($key === 'ENTER' || $key === 'SPACE') {
                    echo PHP_EOL;

                    return $choices[$currentIndex];
                } elseif ($key === 'ESC') {
                    echo PHP_EOL;

                    return $choices[$default ?? 0];
                } elseif ($key === 'BACKSPACE') {
                    // ✅ EFFACEMENT DU DERNIER CHIFFRE SAISI
                    if ($inputBuffer !== '') {
                        $inputBuffer = mb_substr($inputBuffer, 0, -1);

                        if ($inputBuffer !== '') {
                            $targetIndex = (int) $inputBuffer - 1;
                            if ($targetIndex >= 0 && $targetIndex < $totalChoices) {
                                $currentIndex = $targetIndex;
                            }
                        } else {
                            // Si le tampon devient vide, on se remet sur la valeur par défaut ou la première option
                            $currentIndex = $default ?? 0;
                        }
                    }
                } elseif (is_numeric($key)) {
                    // ✅ ACCUMULATION DES CHIFFRES DANS LE BUFFER
                    $newBuffer = $inputBuffer.$key;
                    $targetIndex = (int) $newBuffer - 1;

                    // On applique le changement de curseur uniquement si le nombre saisi pointe vers une option existante
                    if ($targetIndex >= 0 && $targetIndex < $totalChoices) {
                        $inputBuffer = $newBuffer;
                        $currentIndex = $targetIndex;
                    }
                }

                self::clearLines();
            }
        } finally {
            self::restoreTerminal();
        }
    }

    private static function clearLines(): void
    {
        if (self::$totalLines > 0) {
            echo "\033[1A";
            echo str_repeat("\033[2K\033[1A", self::$totalLines - 1);
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
