<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components\Input;

use AndyDefer\ConsoleWriter\Console\Abstracts\InteractiveComponent;
use AndyDefer\ConsoleWriter\Console\Enums\FgColor;
use AndyDefer\ConsoleWriter\Console\Enums\Options;

final class Choice extends InteractiveComponent
{
    private static int $totalLines = 0;

    public static function execute(
        string $question,
        array $choices,
        ?int $default = null,
        string $color = 'cyan'
    ): string {
        $fg = self::getFgColor($color);
        $currentIndex = $default ?? 0;
        $choices = array_values($choices);
        $totalChoices = count($choices);

        $inputBuffer = '';

        self::setupTerminal();

        try {
            while (true) {
                $lines = [];

                $questionFormatted = self::getAnsi()->colorEnum(
                    self::getAnsi()->option($question, Options::BOLD),
                    $fg
                );

                $currentDisplay = $inputBuffer !== '' ? $inputBuffer : $choices[$currentIndex];
                $lines[] = $questionFormatted.' : '.self::getAnsi()->colorEnum($currentDisplay, FgColor::GRAY);
                $lines[] = '';

                foreach ($choices as $index => $choice) {
                    $prefix = ($index === $currentIndex) ? '> ' : '  ';
                    $number = $index + 1;

                    if ($index === $currentIndex) {
                        $lines[] = $prefix.$number.'. '.self::getAnsi()->colorEnum($choice, FgColor::YELLOW);
                    } else {
                        $lines[] = $prefix.$number.'. '.$choice;
                    }
                }

                self::$totalLines = count($lines);
                echo implode(PHP_EOL, $lines).PHP_EOL;

                $key = self::getReader()->readChar();

                if ($key === 'UP') {
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
                    if ($inputBuffer !== '') {
                        $inputBuffer = mb_substr($inputBuffer, 0, -1);

                        if ($inputBuffer !== '') {
                            $targetIndex = (int) $inputBuffer - 1;
                            if ($targetIndex >= 0 && $targetIndex < $totalChoices) {
                                $currentIndex = $targetIndex;
                            }
                        } else {
                            $currentIndex = $default ?? 0;
                        }
                    }
                } elseif (is_numeric($key)) {
                    $newBuffer = $inputBuffer.$key;
                    $targetIndex = (int) $newBuffer - 1;

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
}
