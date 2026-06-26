<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components;

use AndyDefer\ConsoleWriter\Console\Contracts\ConsoleInterface;
use AndyDefer\ConsoleWriter\Console\Enums\ListStyle;
use AndyDefer\DomainStructures\Utils\ListCollection;
use AndyDefer\DomainStructures\Utils\MapCollection;
use AndyDefer\DomainStructures\Utils\SetCollection;

/**
 * Formulaire interactif pour la console
 *
 * @example
 * $form = new Form($console);
 * $answers = $form
 *     ->title('📝 Formulaire d\'inscription')
 *     ->line()
 *     ->ask('Nom complet :', 'name', 'yellow')
 *     ->ask('Email :', 'email', 'cyan')
 *     ->number('Âge :', 'age', 1, 120)
 *     ->secret('Mot de passe :', 'password')
 *     ->confirm('Newsletter ?', 'newsletter', true)
 *     ->choice('Langage :', 'lang', ['PHP', 'JavaScript', 'Python'])
 *     ->table(['Champ', 'Valeur'], $answers)
 *     ->submit();
 */
final class Form
{
    private ConsoleInterface $console;

    private MapCollection $answers;

    public function __construct(ConsoleInterface $console)
    {
        $this->console = $console;
        $this->answers = MapCollection::from([]);
    }

    // ========== MÉTHODES DE RENDU ==========

    /**
     * Affiche un titre
     */
    public function title(string $message): self
    {
        $this->console->title($message);

        return $this;
    }

    /**
     * Affiche un message d'information
     */
    public function info(string $message): self
    {
        $this->console->info($message);

        return $this;
    }

    /**
     * Affiche un message de succès
     */
    public function success(string $message): self
    {
        $this->console->success($message);

        return $this;
    }

    /**
     * Affiche un message d'erreur
     */
    public function error(string $message): self
    {
        $this->console->error($message);

        return $this;
    }

    /**
     * Affiche une alerte
     */
    public function alert(string $message): self
    {
        $this->console->alert($message);

        return $this;
    }

    /**
     * Affiche une ligne
     */
    public function line(string $message = ''): self
    {
        $this->console->line($message);

        return $this;
    }

    /**
     * Ajoute des sauts de ligne
     */
    public function newLine(int $count = 1): self
    {
        $this->console->newLine($count);

        return $this;
    }

    /**
     * Affiche un tableau
     */
    public function table(ListCollection|array $headers, ListCollection|array $rows): self
    {
        $this->console->table($headers, $rows);

        return $this;
    }

    /**
     * Affiche un tableau adaptatif
     */
    public function adaptiveTable(ListCollection|array $headers, ListCollection|array $rows): self
    {
        $this->console->adaptiveTable($headers, $rows);

        return $this;
    }

    /**
     * Affiche une liste
     */
    public function list(SetCollection|array $items, ListStyle $style = ListStyle::BULLET, int $indent = 0): self
    {
        $this->console->list($items, $style, $indent);

        return $this;
    }

    /**
     * Affiche une liste colorée
     */
    public function listColored(SetCollection|array $items, ListStyle $style = ListStyle::BULLET, string $color = 'green'): self
    {
        $this->console->listColored($items, $style, $color);

        return $this;
    }

    /**
     * Affiche des clés → valeurs
     */
    public function keyValue(MapCollection|array $data, int $indent = 0): self
    {
        $this->console->keyValue($data, $indent);

        return $this;
    }

    /**
     * Affiche des clés → valeurs avec couleurs
     */
    public function keyValueWithValueColor(MapCollection|array $data, string $color = 'green', int $indent = 0): self
    {
        $this->console->keyValueWithValueColor($data, $color, $indent);

        return $this;
    }

    /**
     * Affiche un badge
     */
    public function badge(string $text, string $style = 'default'): self
    {
        $this->console->badge($text, $style);

        return $this;
    }

    /**
     * Affiche un badge de succès
     */
    public function badgeSuccess(string $text = 'SUCCESS'): self
    {
        $this->console->badgeSuccess($text);

        return $this;
    }

    /**
     * Affiche un badge d'erreur
     */
    public function badgeDanger(string $text = 'FAILED'): self
    {
        $this->console->badgeDanger($text);

        return $this;
    }

    /**
     * Affiche un badge d'avertissement
     */
    public function badgeWarning(string $text = 'PENDING'): self
    {
        $this->console->badgeWarning($text);

        return $this;
    }

    /**
     * Affiche une métrique
     */
    public function metric(string $label, string $value, string $color = 'white'): self
    {
        $this->console->metric($label, $value, $color);

        return $this;
    }

    /**
     * Affiche une notification
     */
    public function notify(string $message, string $type = 'info', string $icon = '🔔'): self
    {
        $this->console->notify($message, $type, $icon);

        return $this;
    }

    /**
     * Affiche une notification de succès
     */
    public function notifySuccess(string $message): self
    {
        $this->console->notifySuccess($message);

        return $this;
    }

    /**
     * Affiche une notification d'erreur
     */
    public function notifyError(string $message): self
    {
        $this->console->notifyError($message);

        return $this;
    }

    /**
     * Affiche une notification d'avertissement
     */
    public function notifyWarning(string $message): self
    {
        $this->console->notifyWarning($message);

        return $this;
    }

    /**
     * Affiche un log
     */
    public function logInfo(string $message): self
    {
        $this->console->logInfo($message);

        return $this;
    }

    public function logSuccess(string $message): self
    {
        $this->console->logSuccess($message);

        return $this;
    }

    public function logError(string $message): self
    {
        $this->console->logError($message);

        return $this;
    }

    public function logWarning(string $message): self
    {
        $this->console->logWarning($message);

        return $this;
    }

    public function logDebug(string $message): self
    {
        $this->console->logDebug($message);

        return $this;
    }

    // ========== MÉTHODES INTERACTIVES ==========

    /**
     * Ajoute une question de type Ask
     */
    public function ask(string $question, string $key, ?string $default = null, string $color = 'cyan'): self
    {
        $value = $this->console->ask($question, $default, $color);
        $this->answers = $this->answers->put($key, $value);

        return $this;
    }

    /**
     * Ajoute une question de type Secret
     */
    public function secret(string $question, string $key, string $color = 'cyan'): self
    {
        $value = $this->console->secret($question, $color);
        $this->answers = $this->answers->put($key, $value);

        return $this;
    }

    /**
     * Ajoute une question de type Confirm
     */
    public function confirm(string $question, string $key, bool $default = true, string $color = 'cyan'): self
    {
        $value = $this->console->confirm($question, $default, $color);
        $this->answers = $this->answers->put($key, $value);

        return $this;
    }

    /**
     * Ajoute une question de type Choice
     */
    public function choice(string $question, string $key, array $choices, ?int $default = null, string $color = 'cyan'): self
    {
        $value = $this->console->choice($question, $choices, $default, $color);
        $this->answers = $this->answers->put($key, $value);

        return $this;
    }

    /**
     * Ajoute une question de type Suggest
     */
    public function suggest(string $question, string $key, array $suggestions, string $color = 'cyan'): self
    {
        $value = $this->console->suggest($question, $suggestions, $color);
        $this->answers = $this->answers->put($key, $value);

        return $this;
    }

    /**
     * Ajoute une question de type Number
     */
    public function number(string $question, string $key, ?int $min = null, ?int $max = null, ?int $default = null, string $color = 'cyan'): self
    {
        $value = $this->console->number($question, $min, $max, $default, $color);
        $this->answers = $this->answers->put($key, $value);

        return $this;
    }

    /**
     * Ajoute une question de type ConfirmWithTimeout
     */
    public function confirmWithTimeout(string $question, string $key, int $timeout = 5, bool $default = true, string $color = 'cyan'): self
    {
        $value = $this->console->confirmWithTimeout($question, $timeout, $default, $color);
        $this->answers = $this->answers->put($key, $value);

        return $this;
    }

    /**
     * Ajoute une question de type MultiChoice
     */
    public function multiChoice(string $question, string $key, array $options, array $selected = [], string $color = 'cyan'): self
    {
        $value = $this->console->multiChoice($question, $options, $selected, $color);
        $this->answers = $this->answers->put($key, $value);

        return $this;
    }

    /**
     * Ajoute une question avec une valeur fixe
     */
    public function value(string $key, mixed $value): self
    {
        $this->answers = $this->answers->put($key, $value);

        return $this;
    }

    /**
     * Ajoute une question avec une fonction personnalisée
     */
    public function custom(string $key, callable $callback): self
    {
        $value = $callback($this->console);
        $this->answers = $this->answers->put($key, $value);

        return $this;
    }

    // ========== MÉTHODES DE SOUmission ==========

    /**
     * Soumet le formulaire et retourne les réponses
     */
    public function submit(): MapCollection
    {
        return $this->answers;
    }

    /**
     * Soumet le formulaire et retourne les réponses sous forme de tableau
     */
    public function toArray(): array
    {
        return $this->answers->toArray();
    }

    /**
     * Récupère une réponse spécifique
     */
    public function get(string $key): mixed
    {
        return $this->answers->get($key);
    }

    /**
     * Vérifie si une réponse existe
     */
    public function has(string $key): bool
    {
        return $this->answers->hasKey($key);
    }

    /**
     * Affiche un récapitulatif du formulaire
     */
    public function summary(string $color = 'green'): self
    {
        $this->console->line();
        $this->console->title('📊 Récapitulatif du formulaire');
        $this->console->line();

        foreach ($this->answers as $key => $value) {
            if (is_bool($value)) {
                $value = $value ? '✅ Oui' : '❌ Non';
            } elseif (is_array($value)) {
                $value = implode(', ', $value);
            } elseif ($key === 'password' || $key === 'mot_de_passe' || str_contains($key, 'password')) {
                $value = '••••••••';
            }
            $this->console->keyValueWithValueColor([$key => $value], $color);
        }

        $this->console->line();

        return $this;
    }

    /**
     * Affiche un récapitulatif sous forme de tableau
     */
    public function summaryTable(string $title = '📊 Récapitulatif'): self
    {
        $headers = ['Champ', 'Valeur'];
        $rows = [];

        foreach ($this->answers as $key => $value) {
            if (is_bool($value)) {
                $value = $value ? '✅ Oui' : '❌ Non';
            } elseif (is_array($value)) {
                $value = implode(', ', $value);
            } elseif ($key === 'password' || $key === 'mot_de_passe' || str_contains($key, 'password')) {
                $value = '••••••••';
            }
            $rows[] = [$key, (string) $value];
        }

        $this->console->line();
        $this->console->title($title);
        $this->console->line();
        $this->console->table($headers, $rows);
        $this->console->line();

        return $this;
    }
}
