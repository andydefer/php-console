<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components\Input;

use AndyDefer\ConsoleWriter\Console\Contracts\InputReaderInterface;
use AndyDefer\ConsoleWriter\Console\Enums\FgColor;
use AndyDefer\ConsoleWriter\Console\Enums\Options;
use AndyDefer\ConsoleWriter\Console\Services\VirtualTerminalService;
use AndyDefer\ConsoleWriter\Contracts\Services\AnsiConverterInterface;

final class MultiChoice
{
    private static ?string $oldStty = null;

    private static ?VirtualTerminalService $vt = null;

    private static array $state = [];

    /**
     * ✅ Signature originale conservée
     */
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
            'reader' => $reader,
        ];

        // ✅ Initialiser le VirtualTerminalService
        self::$vt = new VirtualTerminalService;

        self::setupTerminal();

        try {
            // ✅ Premier affichage
            self::render();

            while (true) {
                $key = self::readKey();

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
                    break;
                } elseif ($key === 'ESC') {
                    return [];
                } else {
                    continue;
                }

                // ✅ Rafraîchir l'affichage
                self::render();
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

        // Question
        $questionFormatted = $ansi->colorEnum(
            $ansi->option($question, Options::BOLD),
            $fg
        );
        $lines[] = $questionFormatted;
        $lines[] = '';

        // Options
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

        // ✅ Effacer l'affichage précédent avec VT
        self::$vt->clearDisplay();
        self::$vt->clear();

        // ✅ Ajouter chaque ligne avec une clé
        foreach ($lines as $index => $line) {
            self::$vt->add('line_'.$index, $line);
        }

        // ✅ Rendre le nouveau contenu
        self::$vt->render();
    }

    private static function readKey(): string
    {
        $reader = self::$state['reader'];

        return $reader->readKey();
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
