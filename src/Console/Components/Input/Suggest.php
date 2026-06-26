<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components\Input;

use AndyDefer\ConsoleWriter\Console\Abstracts\InteractiveComponent;
use AndyDefer\ConsoleWriter\Console\Enums\FgColor;
use AndyDefer\ConsoleWriter\Console\Enums\Options;

final class Suggest extends InteractiveComponent
{
    public static function execute(
        string $question,
        array $suggestions,
        string $color = 'cyan'
    ): string {
        $fg = self::getFgColor($color);

        // Formate uniquement la question sans la liste entre crochets
        $questionFormatted = self::getAnsi()->colorEnum(self::getAnsi()->option($question, Options::BOLD), $fg);

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
                echo "\r\033[K".$questionFormatted.' '.$buffer.self::getAnsi()->colorEnum($currentSuggestion, FgColor::GRAY);

                $key = self::getReader()->readChar();

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
}
