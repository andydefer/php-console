<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Services;

use AndyDefer\ConsoleWriter\Contracts\Services\AnsiConverterInterface;
use AndyDefer\DomainStructures\Utils\ListCollection;
use AndyDefer\DomainStructures\Utils\MapCollection;

/**
 * Moteur de rendu terminal virtuel
 * Gère un ensemble de lignes identifiées par des clés, avec insertion, suppression et mise à jour
 * Fonctionne comme un DOM virtuel pour la console
 *
 * @example
 * $vt = new VirtualTerminalService($ansi);
 * $vt->add('line1', 'Hello');
 * $vt->add('line2', 'World');
 * $vt->render(); // Affiche "Hello\nWorld" avec couleurs
 *
 * $vt->update('line1', 'Bonjour');
 * $vt->render(); // Affiche "Bonjour\nWorld"
 *
 * $vt->remove('line2');
 * $vt->render(); // Affiche "Bonjour"
 */
final class VirtualTerminalService
{
    /**
     * @var MapCollection<string, array{content: string, position: int}>
     */
    private MapCollection $lines;

    private int $nextPosition = 0;

    private int $lastLineCount = 0;

    private AnsiConverterInterface $ansi;

    public function __construct(?AnsiConverterInterface $ansi = null)
    {
        $this->lines = MapCollection::from([]);
        $this->ansi = $ansi ?? new AnsiConverterService;
    }

    /**
     * Ajoute une ligne à la fin
     */
    public function add(string $key, string $content): self
    {
        return $this->addAt($key, $content, $this->nextPosition);
    }

    /**
     * Ajoute une ligne à une position spécifique
     * Les lignes suivantes sont décalées automatiquement
     */
    public function addAt(string $key, string $content, int $position): self
    {
        // Si la clé existe déjà, on la met à jour
        if ($this->lines->hasKey($key)) {
            return $this->update($key, $content, $position);
        }

        // Décaler les positions des lignes après la position d'insertion
        $updatedLines = MapCollection::from([]);
        foreach ($this->lines as $existingKey => $line) {
            $currentPos = $line['position'];
            if ($currentPos >= $position) {
                $updatedLines = $updatedLines->put($existingKey, [
                    'content' => $line['content'],
                    'position' => $currentPos + 1,
                ]);
            } else {
                $updatedLines = $updatedLines->put($existingKey, $line);
            }
        }

        // Ajouter la nouvelle ligne
        $updatedLines = $updatedLines->put($key, [
            'content' => $content,
            'position' => $position,
        ]);

        $this->lines = $updatedLines;
        $this->nextPosition = $this->lines->count();

        return $this;
    }

    /**
     * Met à jour le contenu d'une ligne existante
     */
    public function update(string $key, string $content, ?int $position = null): self
    {
        if (! $this->lines->hasKey($key)) {
            return $this;
        }

        $line = $this->lines->get($key);
        $currentPosition = $line['position'];

        // Si une nouvelle position est spécifiée, on déplace la ligne
        if ($position !== null && $position !== $currentPosition) {
            $this->remove($key);

            return $this->addAt($key, $content, $position);
        }

        // Sinon, on met juste à jour le contenu
        $this->lines = $this->lines->put($key, [
            'content' => $content,
            'position' => $currentPosition,
        ]);

        return $this;
    }

    /**
     * Supprime une ligne par sa clé
     * Les lignes suivantes sont remontées automatiquement
     */
    public function remove(string $key): self
    {
        if (! $this->lines->hasKey($key)) {
            return $this;
        }

        $removedPosition = $this->lines->get($key)['position'];

        // Supprimer la ligne
        $this->lines = $this->lines->remove($key);

        // Recalculer les positions
        $updatedLines = MapCollection::from([]);
        foreach ($this->lines as $existingKey => $line) {
            $currentPos = $line['position'];
            if ($currentPos > $removedPosition) {
                $updatedLines = $updatedLines->put($existingKey, [
                    'content' => $line['content'],
                    'position' => $currentPos - 1,
                ]);
            } else {
                $updatedLines = $updatedLines->put($existingKey, $line);
            }
        }

        $this->lines = $updatedLines;
        $this->nextPosition = $this->lines->count();

        return $this;
    }

    /**
     * Récupère le contenu d'une ligne
     */
    public function get(string $key): ?string
    {
        if (! $this->lines->hasKey($key)) {
            return null;
        }

        return $this->lines->get($key)['content'];
    }

    /**
     * Récupère la position d'une ligne
     */
    public function getPosition(string $key): ?int
    {
        if (! $this->lines->hasKey($key)) {
            return null;
        }

        return $this->lines->get($key)['position'];
    }

    /**
     * Vérifie si une ligne existe
     */
    public function has(string $key): bool
    {
        return $this->lines->hasKey($key);
    }

    /**
     * Récupère toutes les lignes triées par position
     */
    public function getLines(): ListCollection
    {
        $sorted = $this->lines->toArray();
        usort($sorted, fn ($a, $b) => $a['position'] <=> $b['position']);

        $contents = array_map(fn ($line) => $line['content'], $sorted);

        return ListCollection::from($contents);
    }

    /**
     * Récupère toutes les lignes avec leurs clés et positions
     */
    public function getLinesWithKeys(): MapCollection
    {
        $sorted = $this->lines->toArray();
        usort($sorted, fn ($a, $b) => $a['position'] <=> $b['position']);

        $result = [];
        foreach ($sorted as $key => $line) {
            $result[] = [
                'key' => $key,
                'content' => $line['content'],
                'position' => $line['position'],
            ];
        }

        return MapCollection::from($result);
    }

    /**
     * Nombre total de lignes
     */
    public function count(): int
    {
        return $this->lines->count();
    }

    /**
     * Rend toutes les lignes dans le terminal (avec conversion ANSI)
     */
    public function render(): self
    {
        $lines = $this->getLines()->toArray();
        $lineCount = count($lines);

        if ($this->lastLineCount > 0) {
            // Effacer les lignes précédentes
            echo "\033[1A";
            echo str_repeat("\033[2K\033[1A", $this->lastLineCount - 1);
            echo "\033[2K\r";
        }

        // ✅ Convertir chaque ligne avec AnsiConverterService
        if ($lineCount > 0) {
            $convertedLines = array_map(
                fn ($line) => $this->ansi->convert($line),
                $lines
            );
            echo implode(PHP_EOL, $convertedLines).PHP_EOL;
        }

        $this->lastLineCount = $lineCount;

        return $this;
    }

    /**
     * Efface tout le contenu
     */
    public function clear(): self
    {
        $this->lines = MapCollection::from([]);
        $this->nextPosition = 0;
        $this->lastLineCount = 0;

        return $this;
    }

    /**
     * Efface l'affichage actuel
     */
    public function clearDisplay(): self
    {
        if ($this->lastLineCount > 0) {
            echo "\033[1A";
            echo str_repeat("\033[2K\033[1A", $this->lastLineCount - 1);
            echo "\033[2K\r";
            $this->lastLineCount = 0;
        }

        return $this;
    }

    /**
     * Exporte toutes les lignes sous forme de MapCollection
     */
    public function export(): MapCollection
    {
        return $this->lines;
    }

    /**
     * Importe des lignes depuis une MapCollection
     */
    public function import(MapCollection $lines): self
    {
        $this->lines = $lines;
        $this->nextPosition = $this->lines->count();

        return $this;
    }

    /**
     * Vide complètement le terminal (efface tout l'écran)
     */
    public function clearScreen(): self
    {
        echo "\033[2J\033[H";
        $this->lastLineCount = 0;

        return $this;
    }

    /**
     * Déplace le curseur à une position spécifique
     */
    public function moveCursor(int $row, int $col): self
    {
        echo "\033[".$row.';'.$col.'H';

        return $this;
    }

    /**
     * Sauvegarde la position du curseur
     */
    public function saveCursor(): self
    {
        echo "\033[s";

        return $this;
    }

    /**
     * Restaure la position du curseur
     */
    public function restoreCursor(): self
    {
        echo "\033[u";

        return $this;
    }
}
