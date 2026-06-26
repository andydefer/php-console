<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Contracts\Interfaces;

use AndyDefer\ConsoleWriter\Contracts\Services\AnsiConverterInterface;

/**
 * Interface pour les méthodes de rendu de base
 */
interface RenderableInterface
{
    /**
     * Affiche un message d'information (bleu)
     */
    public function info(string $message): self;

    /**
     * Affiche un message de succès (vert)
     */
    public function success(string $message): self;

    /**
     * Affiche un message d'erreur (rouge avec fond)
     */
    public function error(string $message): self;

    /**
     * Affiche une alerte encadrée (jaune)
     */
    public function alert(string $message): self;

    /**
     * Affiche un titre encadré (cyan gras)
     */
    public function title(string $message): self;

    /**
     * Affiche une ligne simple
     */
    public function line(string $message = ''): self;

    /**
     * Ajoute des sauts de ligne
     */
    public function newLine(int $count = 1): self;

    /**
     * Affiche du texte avec conversion ANSI directe
     */
    public function ansi(string $text): self;

    /**
     * Retourne le service de conversion ANSI
     */
    public function getAnsiConverter(): AnsiConverterInterface;
}
