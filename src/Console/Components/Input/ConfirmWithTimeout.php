<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components\Input;

use AndyDefer\ConsoleWriter\Console\Abstracts\InteractiveComponent;
use AndyDefer\ConsoleWriter\Console\Enums\Options;

final class ConfirmWithTimeout extends InteractiveComponent
{
    public static function execute(
        string $question,
        int $timeout = 5,
        bool $default = true,
        string $color = 'cyan'
    ): bool {
        $fg = self::getFgColor($color);
        $defaultText = $default ? '[Y/n]' : '[y/N]';

        $questionFormatted = self::getAnsi()->colorEnum(
            self::getAnsi()->option($question.' '.$defaultText, Options::BOLD),
            $fg
        );

        $vt = self::getVT();
        $vt->clear();
        $key = 'confirm_line';

        // Afficher la question initiale
        $vt->add($key, $questionFormatted.' ('.$timeout.'s restantes) : ');
        $vt->render();

        $userInput = '';
        $isTimeout = false;

        // Lire avec timeout
        $input = self::getReader()->readLineWithTimeout($timeout, function ($remaining, $currentBuffer) use ($questionFormatted, $vt, $key) {
            $line = $questionFormatted.' ('.$remaining.'s restantes) : '.$currentBuffer;
            $vt->update($key, $line)->render();
        });

        $userInput = $input;
        $isTimeout = $input === '';

        // Déterminer le résultat
        $result = $isTimeout ? $default : self::parseInput($input);

        // Construire le message final
        $finalMessage = self::buildFinalMessage($result, $isTimeout, $default, $question, $userInput);
        $finalColor = $result ? 'green' : 'red';
        $icon = $result ? '✅' : '❌';

        $finalFormatted = self::getAnsi()->colorEnum(
            $icon.' '.$finalMessage,
            self::getFgColor($finalColor)
        );

        $vt->update($key, $finalFormatted)->render();
        echo PHP_EOL;

        return $result;
    }

    private static function parseInput(string $input): bool
    {
        $input = strtolower(trim($input));

        return in_array($input, ['y', 'yes', 'o', 'oui', 'true', '1'], true);
    }

    private static function buildFinalMessage(bool $result, bool $isTimeout, bool $default, string $question, string $userInput): string
    {
        if ($isTimeout) {
            return '⏰ Délai expiré pour la question : "'.$question.'" → Choix par défaut : '.($default ? 'Oui ✅' : 'Non ❌');
        }

        $userResponse = $result ? 'Oui' : 'Non';
        $responseDisplay = $result ? '✅ Oui' : '❌ Non';

        return 'Vous avez répondu "'.$userResponse.'" à la question : "'.$question.'" → '.$responseDisplay;
    }
}
