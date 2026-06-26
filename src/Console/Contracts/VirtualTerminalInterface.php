<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Contracts;

use AndyDefer\DomainStructures\Utils\ListCollection;
use AndyDefer\DomainStructures\Utils\MapCollection;

/**
 * Interface pour le moteur de rendu terminal virtuel
 * Gère un ensemble de lignes identifiées par des clés, avec insertion, suppression et mise à jour
 * Fonctionne comme un DOM virtuel pour la console
 *
 * @example
 * $vt = new VirtualTerminalService();
 * $vt->add('line1', 'Hello');
 * $vt->add('line2', 'World');
 * $vt->render(); // Affiche "Hello\nWorld"
 *
 * $vt->update('line1', 'Bonjour');
 * $vt->render(); // Affiche "Bonjour\nWorld"
 *
 * $vt->remove('line2');
 * $vt->render(); // Affiche "Bonjour"
 */
interface VirtualTerminalInterface
{
    /**
     * Ajoute une ligne à la fin
     */
    public function add(string $key, string $content): self;

    /**
     * Ajoute une ligne à une position spécifique
     * Les lignes suivantes sont décalées automatiquement
     */
    public function addAt(string $key, string $content, int $position): self;

    /**
     * Met à jour le contenu d'une ligne existante
     */
    public function update(string $key, string $content, ?int $position = null): self;

    /**
     * Supprime une ligne par sa clé
     * Les lignes suivantes sont remontées automatiquement
     */
    public function remove(string $key): self;

    /**
     * Récupère le contenu d'une ligne
     */
    public function get(string $key): ?string;

    /**
     * Récupère la position d'une ligne
     */
    public function getPosition(string $key): ?int;

    /**
     * Vérifie si une ligne existe
     */
    public function has(string $key): bool;

    /**
     * Récupère toutes les lignes triées par position
     */
    public function getLines(): ListCollection;

    /**
     * Récupère toutes les lignes avec leurs clés et positions
     */
    public function getLinesWithKeys(): MapCollection;

    /**
     * Nombre total de lignes
     */
    public function count(): int;

    /**
     * Rend toutes les lignes dans le terminal (avec conversion ANSI)
     */
    public function render(): self;

    /**
     * Efface tout le contenu
     */
    public function clear(): self;

    /**
     * Efface l'affichage actuel
     */
    public function clearDisplay(): self;

    /**
     * Exporte toutes les lignes sous forme de MapCollection
     */
    public function export(): MapCollection;

    /**
     * Importe des lignes depuis une MapCollection
     */
    public function import(MapCollection $lines): self;

    /**
     * Vide complètement le terminal (efface tout l'écran)
     */
    public function clearScreen(): self;

    /**
     * Déplace le curseur à une position spécifique
     */
    public function moveCursor(int $row, int $col): self;

    /**
     * Sauvegarde la position du curseur
     */
    public function saveCursor(): self;

    /**
     * Restaure la position du curseur
     */
    public function restoreCursor(): self;
}
