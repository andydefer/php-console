<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Contracts\Interfaces;

use AndyDefer\ConsoleWriter\Console\Enums\SoundType;
use AndyDefer\ConsoleWriter\Contracts\Services\AnsiConverterInterface;

/**
 * Interface pour les composants système (notification, sound, logger, ansi)
 */
interface SystemInterface
{
    /**
     * Affiche une notification
     */
    public function notify(string $message, string $type = 'info', string $icon = '🔔'): self;

    /**
     * Affiche une notification de succès
     */
    public function notifySuccess(string $message): self;

    /**
     * Affiche une notification d'erreur
     */
    public function notifyError(string $message): self;

    /**
     * Affiche une notification d'avertissement
     */
    public function notifyWarning(string $message): self;

    /**
     * Affiche une notification d'information
     */
    public function notifyInfo(string $message): self;

    /**
     * Joue un son de succès
     */
    public function soundSuccess(): self;

    /**
     * Joue un son d'erreur
     */
    public function soundError(): self;

    /**
     * Joue un son d'information
     */
    public function soundInfo(): self;

    /**
     * Joue un son personnalisé
     */
    public function sound(SoundType $type): self;

    /**
     * Joue un son de manière asynchrone
     */
    public function soundAsync(SoundType $type): self;

    /**
     * Affiche un log de niveau INFO
     */
    public function logInfo(string $message): self;

    /**
     * Affiche un log de niveau SUCCESS
     */
    public function logSuccess(string $message): self;

    /**
     * Affiche un log de niveau ERROR
     */
    public function logError(string $message): self;

    /**
     * Affiche un log de niveau WARNING
     */
    public function logWarning(string $message): self;

    /**
     * Affiche un log de niveau DEBUG
     */
    public function logDebug(string $message): self;

    /**
     * Affiche un log de niveau NOTICE
     */
    public function logNotice(string $message): self;

    /**
     * Affiche un log de niveau CRITICAL
     */
    public function logCritical(string $message): self;

    /**
     * Affiche un log personnalisé
     */
    public function log(string $level, string $message, string $color = 'white'): self;

    /**
     * Retourne le convertisseur ANSI
     */
    public function getAnsiConverter(): AnsiConverterInterface;

    /**
     * Affiche du texte avec conversion ANSI directe
     */
    public function ansi(string $text): self;
}
