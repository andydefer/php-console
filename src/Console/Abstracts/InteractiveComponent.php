<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Abstracts;

use AndyDefer\ConsoleWriter\Console\Contracts\InputReaderInterface;
use AndyDefer\ConsoleWriter\Console\Services\StandardInputReaderService;

/**
 * Classe abstraite pour les composants interactifs
 * Gère la configuration et la restauration du terminal
 */
abstract class InteractiveComponent extends Component
{
    protected static ?string $oldStty = null;

    protected static ?InputReaderInterface $reader = null;

    /**
     * Configure le terminal pour la saisie interactive
     * - Désactive l'écho (masque la saisie)
     * - Mode canonique désactivé (lecture caractère par caractère)
     */
    protected static function setupTerminal(): void
    {
        self::$oldStty = shell_exec('stty -g');
        shell_exec('stty -icanon -echo min 1 time 0');
    }

    /**
     * Restaure la configuration originale du terminal
     */
    protected static function restoreTerminal(): void
    {
        if (self::$oldStty !== null) {
            shell_exec('stty '.self::$oldStty);
            self::$oldStty = null;
        }
    }

    /**
     * Vérifie si le terminal est configuré pour l'interaction
     */
    protected static function isTerminalSetup(): bool
    {
        return self::$oldStty !== null;
    }

    /**
     * Récupère le lecteur de saisie utilisateur
     * Crée une nouvelle instance si elle n'existe pas
     */
    protected static function getReader(): InputReaderInterface
    {
        if (self::$reader === null) {
            self::$reader = new StandardInputReaderService;
        }

        return self::$reader;
    }
}
