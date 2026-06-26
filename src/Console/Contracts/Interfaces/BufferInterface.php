<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Contracts\Interfaces;

/**
 * Interface pour la gestion du buffer
 */
interface BufferInterface
{
    /**
     * Démarre le buffer de sortie
     */
    public function startBuffer(): self;

    /**
     * Affiche et vide le buffer
     */
    public function render(): self;

    /**
     * Vide le buffer sans afficher
     */
    public function clear(): self;

    /**
     * Récupère les lignes du buffer
     */
    public function getLines(): array;

    /**
     * Vérifie si le buffer est actif
     */
    public function isBuffered(): bool;
}
