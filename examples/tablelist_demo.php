<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Components\TableList;
use AndyDefer\ConsoleWriter\Console\Console;
use AndyDefer\DomainStructures\Utils\ListCollection;

$console = new Console;

$console->title('📋 Démonstration du composant TableList');

// ========================================================================
// 1. TABLELIST - 6 colonnes (automatique)
// ========================================================================

$console->line();
$console->info('1. 6 colonnes → TableList automatique');

$headers6 = ListCollection::from(['Service', 'Status', 'Port', 'Version', 'Uptime', 'Memory']);
$rows6 = ListCollection::from([
    ListCollection::from(['PHP-FPM', '✅ Running', '9000', '8.2.15', '72h', '128 MB']),
    ListCollection::from(['MySQL', '✅ Running', '3306', '8.0.35', '168h', '512 MB']),
    ListCollection::from(['Redis', '❌ Failed', '6379', '7.2.4', '0h', '256 MB']),
    ListCollection::from(['Nginx', '✅ Running', '80', '1.24.0', '720h', '64 MB']),
]);

$console->table($headers6, $rows6);

// ========================================================================
// 2. TABLELIST - 7 colonnes
// ========================================================================

$console->line();
$console->info('2. 7 colonnes → TableList');

$headers7 = ListCollection::from(['Service', 'Status', 'Port', 'Version', 'Uptime', 'Memory', 'CPU']);
$rows7 = ListCollection::from([
    ListCollection::from(['PHP-FPM', '✅ Running', '9000', '8.2.15', '72h', '128 MB', '5%']),
    ListCollection::from(['MySQL', '✅ Running', '3306', '8.0.35', '168h', '512 MB', '15%']),
    ListCollection::from(['Redis', '❌ Failed', '6379', '7.2.4', '0h', '256 MB', '0%']),
    ListCollection::from(['Nginx', '✅ Running', '80', '1.24.0', '720h', '64 MB', '2%']),
]);

$console->adaptiveTable($headers7, $rows7);

// ========================================================================
// 3. TABLELIST - Rendu direct avec TableList::render()
// ========================================================================

$console->line();
$console->info('3. Rendu direct avec TableList::render()');

$headers = ListCollection::from(['ID', 'Name', 'Description', 'Price', 'Stock', 'Category']);
$rows = ListCollection::from([
    ListCollection::from(['1', 'Laptop Pro', 'High-performance laptop with 16GB RAM', '1299.99', '15', 'Electronics']),
    ListCollection::from(['2', 'Wireless Mouse', 'Ergonomic wireless mouse with Bluetooth 5.0', '29.99', '42', 'Accessories']),
    ListCollection::from(['3', 'USB-C Hub', '7-in-1 USB-C hub with HDMI and USB 3.0', '49.99', '50', 'Accessories']),
]);

echo TableList::render($headers, $rows)."\n";

// ========================================================================
// 4. TABLELIST - Avec titre personnalisé
// ========================================================================

$console->line();
$console->info('4. TableList avec titre personnalisé');

$headersTitle = ListCollection::from(['ID', 'Nom', 'Email', 'Rôle', 'Statut', 'Dernière connexion']);
$rowsTitle = ListCollection::from([
    ListCollection::from(['1', 'Jean Dupont', 'jean@example.com', 'Admin', '✅ Actif', '2026-06-26 14:30:00']),
    ListCollection::from(['2', 'Marie Martin', 'marie@example.com', 'User', '❌ Inactif', '2026-06-20 10:15:00']),
    ListCollection::from(['3', 'Pierre Durand', 'pierre@example.com', 'Guest', '⏳ En attente', '2026-06-25 09:00:00']),
]);

echo TableList::renderWithTitle($headersTitle, $rowsTitle, '📊 Liste des utilisateurs')."\n";

// ========================================================================
// 5. TABLELIST - Avec données serveur
// ========================================================================

$console->line();
$console->info('5. TableList - Données serveur');

$headersServer = ListCollection::from(['Serveur', 'IP', 'CPU', 'RAM', 'DISQUE', 'STATUT', 'UPTIME']);
$rowsServer = ListCollection::from([
    ListCollection::from(['Web01', '192.168.1.10', '45%', '8.2 GB', '256 GB', '✅ OK', '72h']),
    ListCollection::from(['Web02', '192.168.1.11', '32%', '6.4 GB', '128 GB', '✅ OK', '48h']),
    ListCollection::from(['DB01', '192.168.1.20', '78%', '32 GB', '512 GB', '⚠️ Charge', '168h']),
    ListCollection::from(['Cache01', '192.168.1.30', '12%', '4 GB', '64 GB', '❌ HS', '0h']),
]);

$console->table($headersServer, $rowsServer);

// ========================================================================
// 6. TABLELIST - 6 colonnes avec émojis
// ========================================================================

$console->line();
$console->info('6. TableList 6 colonnes avec émojis');

$headersEmoji = ListCollection::from(['✅ Status', '📊 Data', '📈 Trend', '📉 Ratio', '⭐ Rating', '📌 Priority']);
$rowsEmoji = ListCollection::from([
    ListCollection::from(['✅ OK', '📈 100%', '↑ 12%', '42:1', '⭐ 5', '🔴 Élevée']),
    ListCollection::from(['❌ KO', '📉 50%', '↓ 8%', '15:3', '⭐ 2', '🟢 Basse']),
    ListCollection::from(['⚠️ WARN', '📊 75%', '→ 0%', '28:7', '⭐ 4', '🟡 Moyenne']),
]);

$console->adaptiveTable($headersEmoji, $rowsEmoji);

// ========================================================================
// 7. TABLELIST - Combinaison avec d'autres composants
// ========================================================================

$console->line();
$console->info('7. TableList combiné avec d\'autres composants');

$console
    ->title('📦 Liste des packages')
    ->line()
    ->table($headers6, $rows6)
    ->line()
    ->badgeSuccess('4 packages')
    ->space()
    ->badgeWarning('1 alerte')
    ->line();

// ========================================================================
// 8. TABLELIST - Rendu manuel pour comparaison
// ========================================================================

$console->line();
$console->info('8. Comparaison Table vs TableList');

$console->info('📋 Table (3 colonnes)');
$headers3 = ListCollection::from(['Service', 'Status', 'Port']);
$rows3 = ListCollection::from([
    ListCollection::from(['PHP-FPM', '✅ Running', '9000']),
    ListCollection::from(['MySQL', '✅ Running', '3306']),
    ListCollection::from(['Redis', '❌ Failed', '6379']),
]);

$console->table($headers3, $rows3);

$console->line();
$console->info('📋 TableList (6 colonnes)');
$console->table($headers6, $rows6);

// ========================================================================
// FIN
// ========================================================================

$console->line();
$console->success('✅ Tous les TableList ont été affichés avec succès !');
$console->render();
