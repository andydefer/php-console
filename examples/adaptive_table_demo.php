<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Console;
use AndyDefer\DomainStructures\Utils\ListCollection;

$console = new Console;

$console->title('📊 Démonstration du composant AdaptiveTable');

// ========================================================================
// 1. ADAPTIVE TABLE - 3 colonnes → Tableau
// ========================================================================

$console->line();
$console->info('1. 3 colonnes → affichage en tableau (≤ 5 colonnes)');

$headers3 = ListCollection::from(['Service', 'Status', 'Port']);
$rows3 = ListCollection::from([
    ListCollection::from(['PHP-FPM', '✅ Running', '9000']),
    ListCollection::from(['MySQL', '✅ Running', '3306']),
    ListCollection::from(['Redis', '❌ Failed', '6379']),
    ListCollection::from(['Nginx', '✅ Running', '80']),
]);

$console->adaptiveTable($headers3, $rows3);

// ========================================================================
// 2. ADAPTIVE TABLE - 4 colonnes → Tableau
// ========================================================================

$console->line();
$console->info('2. 4 colonnes → affichage en tableau (≤ 5 colonnes)');

$headers4 = ListCollection::from(['Service', 'Status', 'Port', 'Version']);
$rows4 = ListCollection::from([
    ListCollection::from(['PHP-FPM', '✅ Running', '9000', '8.2.15']),
    ListCollection::from(['MySQL', '✅ Running', '3306', '8.0.35']),
    ListCollection::from(['Redis', '❌ Failed', '6379', '7.2.4']),
    ListCollection::from(['Nginx', '✅ Running', '80', '1.24.0']),
]);

$console->adaptiveTable($headers4, $rows4);

// ========================================================================
// 3. ADAPTIVE TABLE - 5 colonnes → Tableau
// ========================================================================

$console->line();
$console->info('3. 5 colonnes → affichage en tableau (≤ 5 colonnes)');

$headers5 = ListCollection::from(['Service', 'Status', 'Port', 'Version', 'Uptime']);
$rows5 = ListCollection::from([
    ListCollection::from(['PHP-FPM', '✅ Running', '9000', '8.2.15', '72h']),
    ListCollection::from(['MySQL', '✅ Running', '3306', '8.0.35', '168h']),
    ListCollection::from(['Redis', '❌ Failed', '6379', '7.2.4', '0h']),
    ListCollection::from(['Nginx', '✅ Running', '80', '1.24.0', '720h']),
]);

$console->adaptiveTable($headers5, $rows5);

// ========================================================================
// 4. ADAPTIVE TABLE - 6 colonnes → Liste automatique
// ========================================================================

$console->line();
$console->info('4. 6 colonnes → affichage en liste (> 5 colonnes)');

$headers6 = ListCollection::from(['Service', 'Status', 'Port', 'Version', 'Uptime', 'Memory']);
$rows6 = ListCollection::from([
    ListCollection::from(['PHP-FPM', '✅ Running', '9000', '8.2.15', '72h', '128 MB']),
    ListCollection::from(['MySQL', '✅ Running', '3306', '8.0.35', '168h', '512 MB']),
    ListCollection::from(['Redis', '❌ Failed', '6379', '7.2.4', '0h', '256 MB']),
    ListCollection::from(['Nginx', '✅ Running', '80', '1.24.0', '720h', '64 MB']),
]);

$console->adaptiveTable($headers6, $rows6);

// ========================================================================
// 5. FORCÉ EN LISTE - 3 colonnes forcé en liste
// ========================================================================

$console->line();
$console->info('5. 3 colonnes forcé en liste (renderAsList)');

$console->adaptiveTable($headers3, $rows3);

// ========================================================================
// 6. FORCÉ EN TABLEAU - 6 colonnes forcé en tableau
// ========================================================================

$console->line();
$console->info('6. 6 colonnes forcé en tableau (renderAsTable)');

$console->adaptiveTable($headers6, $rows6);

// ========================================================================
// 7. ADAPTIVE TABLE AVEC ÉMOJIS
// ========================================================================

$console->line();
$console->info('7. Adaptive Table avec émojis (3 colonnes)');

$headersEmoji = ListCollection::from(['✅ Status', '📊 Data', '📈 Trend']);
$rowsEmoji = ListCollection::from([
    ListCollection::from(['✅ OK', '📈 100%', '↑ 12%']),
    ListCollection::from(['❌ KO', '📉 50%', '↓ 8%']),
    ListCollection::from(['⚠️ WARN', '📊 75%', '→ 0%']),
]);

$console->adaptiveTable($headersEmoji, $rowsEmoji);

// ========================================================================
// 8. ADAPTIVE TABLE AVEC TYPES MIXTES
// ========================================================================

$console->line();
$console->info('8. Adaptive Table avec types mixtes');

$headersMixed = ListCollection::from(['ID', 'Nom', 'Actif', 'Score', 'Date']);
$rowsMixed = ListCollection::from([
    ListCollection::from([1, 'Alice', '✅ Oui', 98.5, '2026-01-15']),
    ListCollection::from([2, 'Bob', '❌ Non', 45.2, '2026-02-20']),
    ListCollection::from([3, 'Charlie', '✅ Oui', 76.8, '2026-03-10']),
    ListCollection::from([4, 'Diana', '✅ Oui', 92.3, '2026-04-05']),
]);

$console->adaptiveTable($headersMixed, $rowsMixed);

// ========================================================================
// 9. ADAPTIVE TABLE AVEC DONNÉES SYSTÈME (6 colonnes → liste)
// ========================================================================

$console->line();
$console->info('9. Adaptive Table - Données système (6 colonnes → liste)');

$headersSys = ListCollection::from(['Processus', 'PID', 'CPU', 'Mémoire', 'Statut', 'Uptime']);
$rowsSys = ListCollection::from([
    ListCollection::from(['php-fpm', '1234', '5.2%', '128 MB', '✅ Actif', '72h']),
    ListCollection::from(['mysql', '5678', '15.8%', '512 MB', '✅ Actif', '168h']),
    ListCollection::from(['redis', '9012', '0.5%', '256 MB', '❌ Arrêté', '0h']),
    ListCollection::from(['nginx', '3456', '2.3%', '64 MB', '✅ Actif', '720h']),
]);

$console->adaptiveTable($headersSys, $rowsSys);

// ========================================================================
// 10. ADAPTIVE TABLE AVEC DONNÉES SERVEUR (7 colonnes → liste)
// ========================================================================

$console->line();
$console->info('10. Adaptive Table - Données serveur (7 colonnes → liste)');

$headersServer = ListCollection::from(['Serveur', 'IP', 'CPU', 'RAM', 'DISQUE', 'STATUT', 'UPTIME']);
$rowsServer = ListCollection::from([
    ListCollection::from(['Web01', '192.168.1.10', '45%', '8.2 GB', '256 GB', '✅ OK', '72h']),
    ListCollection::from(['Web02', '192.168.1.11', '32%', '6.4 GB', '128 GB', '✅ OK', '48h']),
    ListCollection::from(['DB01', '192.168.1.20', '78%', '32 GB', '512 GB', '⚠️ Charge', '168h']),
    ListCollection::from(['Cache01', '192.168.1.30', '12%', '4 GB', '64 GB', '❌ HS', '0h']),
]);

$console->adaptiveTable($headersServer, $rowsServer);

// ========================================================================
// 11. FORCÉ EN LISTE - 4 colonnes forcé en liste
// ========================================================================

$console->line();
$console->info('11. 4 colonnes forcé en liste (renderAsList)');

$console->adaptiveTable($headers4, $rows4);

// ========================================================================
// FIN
// ========================================================================

$console->line();
$console->success('✅ Tous les tableaux adaptatifs ont été affichés avec succès !');
$console->render();
