<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Contracts\Interfaces;

use AndyDefer\ConsoleWriter\Console\Components\ProgressBar;
use AndyDefer\ConsoleWriter\Console\Components\Spinner;

/**
 * Interface pour les composants de progression (ProgressBar, Spinner)
 */
interface ProgressInterface
{
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
     * Définit la progression
     */
    public function setProgress(int $current): self;

    /**
     * Définit le préfixe
     */
    public function setPrefix(string $prefix): self;

    /**
     * Définit le suffixe
     */
    public function setSuffix(string $suffix): self;

    /**
     * Termine la barre de progression
     */
    public function finish(): self;

    /**
     * Vérifie si une barre de progression existe
     */
    public function hasProgressBar(): bool;

    /**
     * Récupère la barre de progression
     */
    public function getProgressBar(): ?ProgressBar;

    /**
     * Affiche un spinner avec une tâche
     */
    public function spinner(string $message, callable $task, string $prefix = '', string $suffix = ''): self;

    /**
     * Affiche un spinner en attente
     */
    public function spinnerWait(string $message, callable $isComplete, string $prefix = '', string $suffix = ''): self;
}
