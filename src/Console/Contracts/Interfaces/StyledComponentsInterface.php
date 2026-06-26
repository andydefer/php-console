<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Contracts\Interfaces;

use AndyDefer\ConsoleWriter\Console\Enums\ListStyle;
use AndyDefer\DomainStructures\Utils\ListCollection;
use AndyDefer\DomainStructures\Utils\MapCollection;
use AndyDefer\DomainStructures\Utils\SetCollection;

/**
 * Interface pour les composants stylisés (badge, metric, columns, timeline, tree, json, link, list, keyvalue, table)
 */
interface StyledComponentsInterface
{
    // ========== ALERT ==========

    /**
     * Affiche une alerte avec icône et couleur personnalisée
     */
    public function alertWithIconAndColor(string $message, string $icon, string $color, int $padding = 4): self;

    /**
     * Affiche une alerte complète avec tous les paramètres
     */
    public function alertFull(string $message, string $icon, string $color, string $borderChar, int $padding): self;

    // ========== LINK ==========

    /**
     * Affiche un lien cliquable
     */
    public function link(string $url, ?string $text = null): self;

    // ========== LIST ==========

    /**
     * Affiche une liste avec différents styles de puces
     */
    public function list(SetCollection|array $items, ListStyle $style = ListStyle::BULLET, int $indent = 0): self;

    /**
     * Affiche une liste avec des puces colorées
     */
    public function listColored(SetCollection|array $items, ListStyle $style = ListStyle::BULLET, string $color = 'green'): self;

    // ========== KEY VALUE ==========

    /**
     * Affiche des paires clé → valeur
     */
    public function keyValue(MapCollection|array $data, int $indent = 0): self;

    /**
     * Affiche des paires clé → valeur avec clés colorées
     */
    public function keyValueWithColor(MapCollection|array $data, string $keyColor = 'cyan', int $indent = 0): self;

    /**
     * Affiche des paires clé → valeur avec valeurs colorées
     */
    public function keyValueWithValueColor(MapCollection|array $data, string $valueColor = 'green', int $indent = 0): self;

    /**
     * Affiche des paires clé → valeur avec séparateur personnalisé
     */
    public function keyValueWithSeparator(MapCollection|array $data, string $separator = ' → ', int $indent = 0): self;

    // ========== TABLE ==========

    /**
     * Affiche un tableau formaté
     */
    public function table(ListCollection|array $headers, ListCollection|array $rows): self;

    /**
     * Affiche un tableau adaptatif (≤5 colonnes → tableau, >5 → liste)
     */
    public function adaptiveTable(ListCollection|array $headers, ListCollection|array $rows): self;

    // ========== TREE ==========

    /**
     * Affiche une structure arborescente
     */
    public function tree(MapCollection $tree, string $rootLabel = ''): self;

    /**
     * Affiche une structure arborescente avec couleurs personnalisées
     */
    public function treeWithColors(
        MapCollection $tree,
        string $rootLabel = '',
        string $nodeColor = 'cyan',
        string $leafColor = 'white'
    ): self;

    /**
     * Affiche une structure arborescente à partir de chemins
     */
    public function treeFromPaths(SetCollection $paths, string $rootLabel = '📁 Project'): self;

    /**
     * Affiche une structure arborescente avec icônes
     */
    public function treeWithIcons(
        MapCollection $tree,
        string $rootLabel = '',
        string $folderIcon = '📁',
        string $fileIcon = '📄'
    ): self;

    // ========== BADGE ==========

    /**
     * Affiche un badge
     */
    public function badge(string $text, string $style = 'default'): self;

    /**
     * Affiche un badge avec icône
     */
    public function badgeWithIcon(string $text, string $icon, string $style = 'default'): self;

    /**
     * Affiche un badge de succès
     */
    public function badgeSuccess(string $text = 'SUCCESS'): self;

    /**
     * Affiche un badge d'erreur
     */
    public function badgeDanger(string $text = 'FAILED'): self;

    /**
     * Affiche un badge d'avertissement
     */
    public function badgeWarning(string $text = 'PENDING'): self;

    /**
     * Affiche un badge d'information
     */
    public function badgeInfo(string $text = 'INFO'): self;

    /**
     * Affiche un badge primary
     */
    public function badgePrimary(string $text = 'PRIMARY'): self;

    /**
     * Affiche un badge dark
     */
    public function badgeDark(string $text = 'DARK'): self;

    /**
     * Affiche un badge light
     */
    public function badgeLight(string $text = 'LIGHT'): self;

    // ========== METRIC ==========

    /**
     * Affiche une métrique
     */
    public function metric(string $label, string $value, string $color = 'white'): self;

    /**
     * Affiche une métrique avec icône
     */
    public function metricWithIcon(string $label, string $value, string $icon, string $color = 'white'): self;

    /**
     * Affiche une métrique avec tendance
     */
    public function metricWithTrend(
        string $label,
        string $value,
        string $trend,
        string $trendColor = 'green',
        string $valueColor = 'white'
    ): self;

    /**
     * Affiche une métrique en ligne
     */
    public function metricInline(string $label, string $value, string $color = 'white'): self;

    // ========== COLUMNS ==========

    /**
     * Affiche des colonnes
     */
    public function columns(array $columns, int $width = 10, string $separator = '   '): self;

    /**
     * Affiche des colonnes avec icônes
     */
    public function columnsWithIcons(array $columns, int $width = 10, string $separator = '   '): self;

    /**
     * Affiche des colonnes avec couleurs personnalisées
     */
    public function columnsWithColors(array $columns, array $colors = [], int $width = 10, string $separator = '   '): self;

    /**
     * Affiche des colonnes avec en-têtes séparées
     */
    public function columnsWithHeaders(array $columns, int $width = 20, string $separator = '   '): self;

    /**
     * Affiche des colonnes en format compact
     */
    public function columnsCompact(array $columns, string $separator = '   '): self;

    // ========== TIMELINE ==========

    /**
     * Affiche une timeline
     */
    public function timeline(ListCollection|array $events, string $color = 'cyan'): self;

    /**
     * Affiche une timeline avec couleurs personnalisées
     */
    public function timelineWithColors(ListCollection|array $events, array $colors = []): self;

    /**
     * Affiche une timeline avec icônes personnalisées
     */
    public function timelineWithIcons(ListCollection|array $events, string $icon = '●', string $color = 'cyan'): self;

    /**
     * Affiche une timeline avec statuts
     */
    public function timelineWithStatus(ListCollection|array $events, array $statuses = []): self;

    // ========== JSON VIEWER ==========

    /**
     * Affiche du JSON formaté et coloré
     */
    public function json(array|string $data): self;

    /**
     * Affiche du JSON brut (sans couleurs)
     */
    public function jsonRaw(array|string $data): self;

    /**
     * Affiche du JSON compact (une seule ligne)
     */
    public function jsonCompact(array|string $data): self;

    /**
     * Affiche du JSON avec profondeur limitée
     */
    public function jsonWithDepth(array|string $data, int $maxDepth = 3): self;

    /**
     * Ajoute des espaces
     */
    public function space(int $count = 1): self;
}
