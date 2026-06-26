<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components;

use AndyDefer\ConsoleWriter\Console\Abstracts\Component;
use AndyDefer\ConsoleWriter\Console\Enums\FgColor;
use AndyDefer\ConsoleWriter\Console\Enums\Options;

/**
 * Affiche des logs formatés dans la console
 *
 * @example
 * Logger::info('Build démarré');
 * Logger::error('Redis inaccessible');
 * Logger::debug('Variable $user = "John"');
 *
 * // Sortie:
 * // [14:32:10] INFO  - Build démarré
 * // [14:32:12] ERROR - Redis inaccessible
 */
final class Logger extends Component
{
    private static string $timeFormat = 'H:i:s';

    /**
     * Retourne le format de l'heure actuel
     */
    public static function getTimeFormat(): string
    {
        return self::$timeFormat;
    }

    /**
     * Définit le format de l'heure
     */
    public static function setTimeFormat(string $format): void
    {
        self::$timeFormat = $format;
    }

    /**
     * Log de niveau INFO (bleu)
     */
    public static function info(string $message): string
    {
        return self::render($message, 'INFO', 'blue');
    }

    /**
     * Log de niveau SUCCESS (vert)
     */
    public static function success(string $message): string
    {
        return self::render($message, 'SUCCESS', 'green');
    }

    /**
     * Log de niveau ERROR (rouge)
     */
    public static function error(string $message): string
    {
        return self::render($message, 'ERROR', 'red');
    }

    /**
     * Log de niveau WARNING (jaune)
     */
    public static function warning(string $message): string
    {
        return self::render($message, 'WARNING', 'yellow');
    }

    /**
     * Log de niveau DEBUG (gris)
     */
    public static function debug(string $message): string
    {
        return self::render($message, 'DEBUG', 'gray');
    }

    /**
     * Log de niveau NOTICE (cyan)
     */
    public static function notice(string $message): string
    {
        return self::render($message, 'NOTICE', 'cyan');
    }

    /**
     * Log de niveau CRITICAL (magenta)
     */
    public static function critical(string $message): string
    {
        return self::render($message, 'CRITICAL', 'magenta');
    }

    /**
     * Log personnalisé
     */
    public static function log(string $level, string $message, string $color = 'white'): string
    {
        return self::render($message, $level, $color);
    }

    /**
     * Rendu d'un log
     */
    private static function render(string $message, string $level, string $color): string
    {
        $ansi = self::getAnsi();
        $timestamp = date(self::$timeFormat);
        $fg = self::getFgColor($color);

        // ✅ Timestamp en gris
        $timestampFormatted = $ansi->colorEnum('['.$timestamp.']', FgColor::GRAY);

        // ✅ Niveau en couleur (bold)
        $levelFormatted = $ansi->colorEnum(
            $ansi->option(str_pad($level, 7, ' ', STR_PAD_RIGHT), Options::BOLD),
            $fg
        );

        // ✅ Message en blanc (pas gris)
        $messageFormatted = $ansi->colorEnum($message, FgColor::WHITE);

        return $timestampFormatted.' '.$levelFormatted.' - '.$messageFormatted;
    }
}
