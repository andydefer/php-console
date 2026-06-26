<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Contracts\Interfaces;

/**
 * Interface pour les composants d'affichage (info, success, error, title, alert)
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
     * Affiche un titre encadré (cyan gras)
     */
    public function title(string $message): self;

    /**
     * Affiche une alerte encadrée (jaune)
     */
    public function alert(string $message): self;

    /**
     * Affiche une alerte avec icône personnalisée
     */
    public function alertWithIcon(string $message, string $icon, int $padding = 4): self;

    /**
     * Affiche une alerte avec couleur personnalisée
     */
    public function alertWithColor(string $message, string $color, int $padding = 4): self;

    /**
     * Affiche une alerte avec bordure personnalisée
     */
    public function alertWithBorder(string $message, string $borderChar, string $color = 'yellow', int $padding = 4): self;

    /**
     * Affiche une alerte de succès (✅ vert)
     */
    public function alertSuccess(string $message): self;

    /**
     * Affiche une alerte d'erreur (❌ rouge)
     */
    public function alertError(string $message): self;

    /**
     * Affiche une alerte d'avertissement (⚠️ jaune)
     */
    public function alertWarning(string $message): self;

    /**
     * Affiche une alerte d'information (ℹ️ bleu)
     */
    public function alertInfo(string $message): self;

    /**
     * Ajoute une ligne brute (déjà formatée)
     */
    public function raw(string $line): self;

    /**
     * Ajoute une ligne de texte simple
     */
    public function line(string $message = ''): self;

    /**
     * Ajoute des sauts de ligne
     */
    public function newLine(int $count = 1): self;
}
