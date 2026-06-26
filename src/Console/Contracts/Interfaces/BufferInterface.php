<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Contracts\Interfaces;

/**
 * Interface pour la gestion du buffer de sortie
 */
interface BufferInterface
{
    /**
     * Démarre le buffer de sortie
     */
    public function startBuffer(): self;

    /**
     * Affiche le contenu du buffer
     */
    public function render(): self;

    /**
     * Vide le buffer sans afficher
     */
    public function clear(): self;

    /**
     * Retourne les lignes du buffer
     */
    public function getLines(): array;

    /**
     * Vérifie si le buffer est actif
     */
    public function isBuffered(): bool;
}
