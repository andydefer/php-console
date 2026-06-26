<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Contracts\Interfaces;

/**
 * Interface pour les composants de progression (barre de progression, spinner)
 */
interface ProgressInterface
{
    // ========== PROGRESS BAR ==========

    /**
     * Crée une barre de progression
     */
    public function progressBar(int $total, int $width = 50, string $prefix = '', string $suffix = ''): self;

    /**
     * Crée une barre de progression avec style prédéfini
     */
    public function progressBarStyled(int $total, string $style = 'default', int $width = 50): self;

    /**
     * Avance la barre de progression
     */
    public function advance(int $steps = 1): self;

    /**
     * Définit la progression à une valeur spécifique
     */
    public function setProgress(int $current): self;

    /**
     * Change le préfixe de la barre de progression
     */
    public function setPrefix(string $prefix): self;

    /**
     * Change le suffixe de la barre de progression
     */
    public function setSuffix(string $suffix): self;

    /**
     * Termine la barre de progression
     */
    public function finish(): self;

    /**
     * Vérifie si une barre de progression est active
     */
    public function hasProgressBar(): bool;

    /**
     * Récupère la barre de progression active
     */
    public function getProgressBar(): ?object;

    // ========== SPINNER ==========

    /**
     * Crée un spinner et exécute une tâche
     */
    public function spinner(string $message, callable $task, string $prefix = '', string $suffix = ''): self;

    /**
     * Crée un spinner qui attend une condition
     */
    public function spinnerWait(string $message, callable $isComplete, string $prefix = '', string $suffix = ''): self;
}
