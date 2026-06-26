<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components\Input;

use AndyDefer\ConsoleWriter\Console\Contracts\InputReaderInterface;
use AndyDefer\ConsoleWriter\Console\Enums\FgColor;
use AndyDefer\ConsoleWriter\Console\Enums\Options;
use AndyDefer\ConsoleWriter\Console\Services\VirtualTerminalService;
use AndyDefer\ConsoleWriter\Contracts\Services\AnsiConverterInterface;

final class ConfirmWithTimeout
{
    public static function execute(
        AnsiConverterInterface $ansi,
        InputReaderInterface $reader,
        string $question,
        int $timeout = 5,
        bool $default = true,
        string $color = 'cyan',
        ?VirtualTerminalService $vt = null
    ): bool {
        $fg = self::getFgColor($color);
        $defaultText = $default ? '[Y/n]' : '[y/N]';

        $questionFormatted = $ansi->colorEnum(
            $ansi->option($question.' '.$defaultText, Options::BOLD),
            $fg
        );

        $useVt = $vt !== null;
        $vt = $vt ?? new VirtualTerminalService;
        $key = 'confirm_line';

        // Afficher la question initiale
        if ($useVt) {
            $vt->add($key, $questionFormatted.' ('.$timeout.'s restantes) : ');
            $vt->render();
        } else {
            echo $questionFormatted.' ('.$timeout.'s restantes) : ';
        }

        $userInput = '';
        $isTimeout = false;

        // Lire avec timeout
        $input = $reader->readLineWithTimeout($timeout, function ($remaining, $currentBuffer) use ($questionFormatted, $vt, $key, $useVt) {
            $line = $questionFormatted.' ('.$remaining.'s restantes) : '.$currentBuffer;

            if ($useVt) {
                $vt->update($key, $line)->render();
            } else {
                echo "\r\033[K".$line;
            }
        });

        $userInput = $input;
        $isTimeout = $input === '';

        // ✅ Déterminer le résultat
        $result = $isTimeout ? $default : self::parseInput($input);

        // ✅ Construire le message final expressif
        $finalMessage = self::buildFinalMessage($result, $isTimeout, $default, $question, $userInput);
        $finalColor = $result ? 'green' : 'red';
        $icon = $result ? '✅' : '❌';

        $finalFormatted = $ansi->colorEnum(
            $icon.' '.$finalMessage,
            self::getFgColor($finalColor)
        );

        if ($useVt) {
            $vt->update($key, $finalFormatted)->render();
            echo PHP_EOL;
        } else {
            echo "\r\033[K".$finalFormatted.PHP_EOL;
        }

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
