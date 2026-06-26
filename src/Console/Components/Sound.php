<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components;

use AndyDefer\ConsoleWriter\Console\Enums\SoundType;
use AndyDefer\ConsoleWriter\Console\Services\SoundPlayerService;

/**
 * Composant de notification sonore
 *
 * @example
 * Sound::success();
 * Sound::error();
 * Sound::info();
 * Sound::play(SoundType::SUCCESS);
 */
final class Sound
{
    private static ?SoundPlayerService $player = null;

    private static function getPlayer(): SoundPlayerService
    {
        if (self::$player === null) {
            self::$player = new SoundPlayerService;
        }

        return self::$player;
    }

    /**
     * Joue un son de succès
     */
    public static function success(): bool
    {
        return self::getPlayer()->play(SoundType::SUCCESS);
    }

    /**
     * Joue un son d'erreur
     */
    public static function error(): bool
    {
        return self::getPlayer()->play(SoundType::ERROR);
    }

    /**
     * Joue un son d'information
     */
    public static function info(): bool
    {
        return self::getPlayer()->play(SoundType::INFO);
    }

    /**
     * Joue un son personnalisé
     */
    public static function play(SoundType $type): bool
    {
        return self::getPlayer()->play($type);
    }

    /**
     * Joue un son de manière asynchrone
     */
    public static function playAsync(SoundType $type): bool
    {
        return self::getPlayer()->playAsync($type);
    }

    /**
     * Vérifie si le son est disponible
     */
    public static function isAvailable(SoundType $type): bool
    {
        return $type->fileExists();
    }
}
