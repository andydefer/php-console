<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Contracts\Interfaces;

use AndyDefer\ConsoleWriter\Console\Enums\SoundType;

/**
 * Interface pour les méthodes système (notifications, sons, logger)
 */
interface SystemInterface
{
    // ========== NOTIFICATION ==========

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

    // ========== SOUND ==========

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

    // ========== LOGGER ==========

    /**
     * Log de niveau INFO (bleu)
     */
    public function logInfo(string $message): self;

    /**
     * Log de niveau SUCCESS (vert)
     */
    public function logSuccess(string $message): self;

    /**
     * Log de niveau ERROR (rouge)
     */
    public function logError(string $message): self;

    /**
     * Log de niveau WARNING (jaune)
     */
    public function logWarning(string $message): self;

    /**
     * Log de niveau DEBUG (gris)
     */
    public function logDebug(string $message): self;

    /**
     * Log de niveau NOTICE (cyan)
     */
    public function logNotice(string $message): self;

    /**
     * Log de niveau CRITICAL (magenta)
     */
    public function logCritical(string $message): self;

    /**
     * Log personnalisé
     */
    public function log(string $level, string $message, string $color = 'white'): self;
}
