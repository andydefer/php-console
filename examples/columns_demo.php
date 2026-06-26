<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Console;
use AndyDefer\DomainStructures\Utils\ListCollection;

$console = new Console;

$console->title('📊 Démonstration du composant Columns');

// ========================================================================
// 1. COLONNES SIMPLES
// ========================================================================

$console->line();
$console->info('1. Colonnes simples');

$columns = ListCollection::from([
    ListCollection::from(['Users', '123', 'Active']),
    ListCollection::from(['Servers', '5', 'Online']),
    ListCollection::from(['Logs', '42', 'Warning']),
]);

$console->columns($columns);

// ========================================================================
// 2. COLONNES AVEC COULEURS
// ========================================================================

$console->line();
$console->info('2. Colonnes avec couleurs');

$columnsWithColors = ListCollection::from([
    ListCollection::from(['Service', 'PHP-FPM', 'MySQL', 'Redis', 'Nginx']),
    ListCollection::from(['Status', '✅ OK', '✅ OK', '❌ KO', '✅ OK']),
    ListCollection::from(['Port', '9000', '3306', '6379', '80']),
]);

$console->columnsWithColors($columnsWithColors, ['cyan', 'green', 'yellow']);

// ========================================================================
// 3. COLONNES AVEC EN-TÊTES
// ========================================================================

$console->line();
$console->info('3. Colonnes avec en-têtes séparées');

$columnsWithHeaders = ListCollection::from([
    ListCollection::from(['Produit', 'Laptop', 'Mouse', 'Keyboard']),
    ListCollection::from(['Prix', '1299.99', '29.99', '79.99']),
    ListCollection::from(['Stock', '15', '42', '28']),
]);

$console->columnsWithHeaders($columnsWithHeaders);

// ========================================================================
// 4. COLONNES COMPACTES
// ========================================================================

$console->line();
$console->info('4. Colonnes compactes (sans largeur fixe)');

$columnsCompact = ListCollection::from([
    ListCollection::from(['Nom', 'Jean', 'Marie', 'Pierre']),
    ListCollection::from(['Âge', '42', '35', '28']),
    ListCollection::from(['Ville', 'Paris', 'Lyon', 'Marseille']),
]);

$console->columnsCompact($columnsCompact);

// ========================================================================
// 5. COLONNES AVEC DONNÉES MIXTES
// ========================================================================

$console->line();
$console->info('5. Colonnes avec données mixtes');

$columnsMixed = ListCollection::from([
    ListCollection::from(['Métrique', 'CPU', 'RAM', 'DISQUE', 'UPTIME']),
    ListCollection::from(['Valeur', '45%', '8.2 GB', '256 GB', '72h']),
    ListCollection::from(['Statut', '✅ OK', '✅ OK', '⚠️ 80%', '✅ OK']),
]);

$console->columnsWithColors($columnsMixed, ['cyan', 'green', 'yellow']);

// ========================================================================
// 6. COLONNES AVEC LARGEUR PERSONNALISÉE
// ========================================================================

$console->line();
$console->info('6. Colonnes avec largeur personnalisée (30 caractères)');

$columnsWidth = ListCollection::from([
    ListCollection::from(['📦 Package', 'php-console-writer', 'domain-structures']),
    ListCollection::from(['📌 Version', '1.0.0', '1.21.0']),
    ListCollection::from(['📂 Downloads', '125', '423']),
]);

$console->columnsWithHeaders($columnsWidth, 30);

// ========================================================================
// 7. RENDU MANUEL AVEC LA CLASSE STATIQUE
// ========================================================================

$console->line();
$console->info('7. Rendu manuel avec Columns::render()');

use AndyDefer\ConsoleWriter\Console\Components\Columns;

$manualColumns = ListCollection::from([
    ListCollection::from(['🔹 Feature', 'JWT Auth', 'Cache', 'Logging']),
    ListCollection::from(['📌 Status', '✅ Done', '✅ Done', '⏳ Pending']),
]);

$result = Columns::render($manualColumns, 25);
echo $result."\n";

// ========================================================================
// 8. COLONNES AVEC ICÔNES
// ========================================================================

$console->line();
$console->info('8. Colonnes avec icônes');

$columnsWithIcons = ListCollection::from([
    ListCollection::from(['📊 CPU', '45%']),
    ListCollection::from(['💾 RAM', '8.2 GB']),
    ListCollection::from(['📀 DISQUE', '256 GB']),
    ListCollection::from(['🌐 RÉSEAU', '1.2 Gbps']),
]);

$console->columnsWithColors($columnsWithIcons, ['cyan', 'green', 'yellow', 'magenta']);

// ========================================================================
// 9. COLONNES AVEC STATUTS
// ========================================================================

$console->line();
$console->info('9. Colonnes avec statuts');

$columnsStatus = ListCollection::from([
    ListCollection::from(['✅ OK', '❌ KO', '⚠️ WARN', '✅ OK']),
    ListCollection::from(['PHP-FPM', 'Redis', 'Nginx', 'MySQL']),
    ListCollection::from(['9000', '6379', '80', '3306']),
]);

$console->columnsWithColors($columnsStatus, ['green', 'red', 'yellow', 'cyan']);

// ========================================================================
// 10. COLONNES AVEC TEXTE LONG
// ========================================================================

$console->line();
$console->info('10. Colonnes avec texte long');

$columnsLongText = ListCollection::from([
    ListCollection::from(['ID', '1', '2', '3']),
    ListCollection::from(['Description', 'High-performance laptop with 16GB RAM and 512GB SSD', 'Ergonomic wireless mouse with Bluetooth 5.0', '7-in-1 USB-C hub with HDMI']),
    ListCollection::from(['Prix', '1299.99', '29.99', '49.99']),
]);

$console->columnsWithHeaders($columnsLongText, 35);

// ========================================================================
// FIN
// ========================================================================

$console->line();
$console->success('✅ Toutes les démonstrations de Columns sont terminées !');
$console->render();
