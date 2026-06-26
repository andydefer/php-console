<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Services;

use AndyDefer\ConsoleWriter\Console\Enums\SoundType;

/**
 * Service de lecture audio pour les notifications
 * Limite la durée à 5 secondes maximum
 */
final class SoundPlayerService
{
    private const MAX_DURATION = 2; // secondes

    private const SUPPORTED_FORMATS = ['mp3', 'wav', 'ogg'];

    /**
     * Joue un son
     */
    public function play(SoundType $sound): bool
    {
        $filePath = $sound->getFilePath();

        if (! $sound->fileExists()) {
            return false;
        }

        // Limiter la durée à 5 secondes
        return $this->playWithTimeout($filePath, self::MAX_DURATION);
    }

    /**
     * Joue un son avec un timeout
     */
    private function playWithTimeout(string $filePath, int $maxDuration): bool
    {
        // Vérifier le système d'exploitation
        if (PHP_OS_FAMILY === 'Windows') {
            return $this->playOnWindows($filePath);
        }

        return $this->playOnUnix($filePath, $maxDuration);
    }

    /**
     * Lecture sur Windows
     */
    private function playOnWindows(string $filePath): bool
    {
        // Windows peut utiliser PowerShell pour jouer le son
        $psCommand = sprintf(
            '(New-Object Media.SoundPlayer "%s").PlaySync();',
            $filePath
        );

        $command = 'powershell -Command "'.$psCommand.'" 2>nul';
        exec($command, $output, $returnCode);

        return $returnCode === 0;
    }

    /**
     * Lecture sur Unix/Linux/Mac
     */
    private function playOnUnix(string $filePath, int $maxDuration): bool
    {
        // Essayer différents lecteurs
        $players = $this->getAvailablePlayers();

        foreach ($players as $player) {
            if ($this->playWithPlayer($player, $filePath, $maxDuration)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Joue avec un lecteur spécifique
     */
    private function playWithPlayer(string $player, string $filePath, int $maxDuration): bool
    {
        $command = match ($player) {
            'afplay' => sprintf('afplay -t %d "%s" 2>/dev/null', $maxDuration, $filePath),
            'ffplay' => sprintf('ffplay -nodisp -autoexit -t %d "%s" 2>/dev/null', $maxDuration, $filePath),
            'mpg123' => sprintf('mpg123 -t %d "%s" 2>/dev/null', $maxDuration, $filePath),
            'play' => sprintf('play -t %d "%s" 2>/dev/null', $maxDuration, $filePath),
            'aplay' => sprintf('aplay -t %d "%s" 2>/dev/null', $maxDuration, $filePath),
            default => null,
        };

        if ($command === null) {
            return false;
        }

        // Lancer en arrière-plan et tuer après maxDuration
        $pid = shell_exec(sprintf('%s > /dev/null 2>&1 & echo $!', $command));
        $pid = trim($pid);

        if (empty($pid)) {
            return false;
        }

        // Attendre maxDuration secondes puis tuer le processus
        sleep($maxDuration);
        shell_exec('kill '.$pid.' 2>/dev/null');

        return true;
    }

    /**
     * Récupère les lecteurs disponibles
     */
    private function getAvailablePlayers(): array
    {
        $players = [];

        // MacOS
        if ($this->commandExists('afplay')) {
            $players[] = 'afplay';
        }

        // Linux avec ffmpeg
        if ($this->commandExists('ffplay')) {
            $players[] = 'ffplay';
        }

        // Linux avec mpg123
        if ($this->commandExists('mpg123')) {
            $players[] = 'mpg123';
        }

        // Linux avec sox
        if ($this->commandExists('play')) {
            $players[] = 'play';
        }

        // Linux avec alsa
        if ($this->commandExists('aplay')) {
            $players[] = 'aplay';
        }

        return $players;
    }

    /**
     * Vérifie si une commande existe
     */
    private function commandExists(string $command): bool
    {
        $which = shell_exec(sprintf('which %s 2>/dev/null', $command));

        return $which !== null && trim($which) !== '';
    }

    /**
     * Joue un son de manière asynchrone (ne bloque pas)
     */
    public function playAsync(SoundType $sound): bool
    {
        if (! $sound->fileExists()) {
            return false;
        }

        // Lancer en arrière-plan
        $filePath = $sound->getFilePath();

        if (PHP_OS_FAMILY === 'Windows') {
            return $this->playOnWindows($filePath);
        }

        $players = $this->getAvailablePlayers();
        foreach ($players as $player) {
            $command = match ($player) {
                'afplay' => sprintf('afplay -t %d "%s" > /dev/null 2>&1 &', self::MAX_DURATION, $filePath),
                'ffplay' => sprintf('ffplay -nodisp -autoexit -t %d "%s" > /dev/null 2>&1 &', self::MAX_DURATION, $filePath),
                'mpg123' => sprintf('mpg123 -t %d "%s" > /dev/null 2>&1 &', self::MAX_DURATION, $filePath),
                'play' => sprintf('play -t %d "%s" > /dev/null 2>&1 &', self::MAX_DURATION, $filePath),
                'aplay' => sprintf('aplay -t %d "%s" > /dev/null 2>&1 &', self::MAX_DURATION, $filePath),
                default => null,
            };

            if ($command !== null) {
                exec($command);

                return true;
            }
        }

        return false;
    }
}
