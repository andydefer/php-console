<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Contracts\Interfaces;

use AndyDefer\ConsoleWriter\Console\Components\Form;

/**
 * Interface pour les méthodes interactives (saisies utilisateur)
 */
interface InteractiveInterface
{
    /**
     * Demande une saisie simple
     */
    public function ask(string $question, ?string $default = null, string $color = 'cyan'): string;

    /**
     * Demande une saisie masquée (mot de passe)
     */
    public function secret(string $question, string $color = 'cyan'): string;

    /**
     * Demande une confirmation (Oui/Non)
     */
    public function confirm(string $question, bool $default = true, string $color = 'cyan'): bool;

    /**
     * Demande un choix unique
     */
    public function choice(string $question, array $choices, ?int $default = null, string $color = 'cyan'): string;

    /**
     * Demande une saisie avec autocomplétion
     */
    public function suggest(string $question, array $suggestions, string $color = 'cyan'): string;

    /**
     * Demande un nombre avec validation
     */
    public function number(string $question, ?int $min = null, ?int $max = null, ?int $default = null, string $color = 'cyan'): int;

    /**
     * Demande une confirmation avec timeout
     */
    public function confirmWithTimeout(string $question, int $timeout = 5, bool $default = true, string $color = 'cyan'): bool;

    /**
     * Demande une sélection multiple
     */
    public function multiChoice(string $question, array $options, array $selected = [], string $color = 'cyan'): array;

    /**
     * Crée un formulaire interactif
     */
    public function form(): Form;
}
