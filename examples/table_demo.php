<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Components\Table;
use AndyDefer\ConsoleWriter\Console\Components\TableList;
use AndyDefer\ConsoleWriter\Console\Console;
use AndyDefer\DomainStructures\Utils\ListCollection;

$console = new Console;

$headers = ListCollection::from(['ID', 'Name', 'Description', 'Price', 'Stock']);
$rows = ListCollection::from([
    ListCollection::from(['1', 'Laptop Pro', 'High-performance laptop with 16GB RAM', '1299.99', '15']),
    ListCollection::from(['2', 'Wireless Mouse', 'Ergonomic wireless mouse with Bluetooth 5.0', '29.99', '42']),
    ListCollection::from(['3', 'USB-C Hub', '7-in-1 USB-C hub with HDMI, Ethernet and USB 3.0', '49.99', '50']),
]);

TableList::render($headers, $rows);

$console->title('Démonstration des tableaux avec VT');

// ========================================================================
// 1. TABLEAU 2 COLONNES
// ========================================================================

$console->line();
$console->info('1. Tableau 2 colonnes');
$console->line();

$headers2 = ListCollection::from(['Service', 'Status']);
$rows2 = ListCollection::from([
    ListCollection::from(['PHP-FPM', 'Running']),
    ListCollection::from(['MySQL', 'Running']),
    ListCollection::from(['Redis', 'Failed']),
    ListCollection::from(['Nginx', 'Running']),
]);

Table::render($headers2, $rows2);

// ========================================================================
// 2. TABLEAU 3 COLONNES
// ========================================================================

$console->line();
$console->info('2. Tableau 3 colonnes');
$console->line();

$headers3 = ListCollection::from(['Service', 'Status', 'Port']);
$rows3 = ListCollection::from([
    ListCollection::from(['PHP-FPM', 'Running', '9000']),
    ListCollection::from(['MySQL', 'Running', '3306']),
    ListCollection::from(['Redis', 'Failed', '6379']),
    ListCollection::from(['Nginx', 'Running', '80']),
]);

Table::render($headers3, $rows3);

// ========================================================================
// 3. TABLEAU 4 COLONNES
// ========================================================================

$console->line();
$console->info('3. Tableau 4 colonnes');
$console->line();

$headers4 = ListCollection::from(['Service', 'Status', 'Port', 'Version']);
$rows4 = ListCollection::from([
    ListCollection::from(['PHP-FPM', 'Running', '9000', '8.2.15']),
    ListCollection::from(['MySQL', 'Running', '3306', '8.0.35']),
    ListCollection::from(['Redis', 'Failed', '6379', '7.2.4']),
    ListCollection::from(['Nginx', 'Running', '80', '1.24.0']),
]);

Table::render($headers4, $rows4);

// ========================================================================
// 4. TABLEAU 5 COLONNES
// ========================================================================

$console->line();
$console->info('4. Tableau 5 colonnes');
$console->line();

$headers5 = ListCollection::from(['Service', 'Status', 'Port', 'Version', 'Uptime']);
$rows5 = ListCollection::from([
    ListCollection::from(['PHP-FPM', 'Running', '9000', '8.2.15', '72h']),
    ListCollection::from(['MySQL', 'Running', '3306', '8.0.35', '168h']),
    ListCollection::from(['Redis', 'Failed', '6379', '7.2.4', '0h']),
    ListCollection::from(['Nginx', 'Running', '80', '1.24.0', '720h']),
]);

Table::render($headers5, $rows5);

// ========================================================================
// 5. TABLEAU 6 COLONNES
// ========================================================================

$console->line();
$console->info('5. Tableau 6 colonnes (→ TableList automatique)');
$console->line();

$headers6 = ListCollection::from(['Service', 'Status', 'Port', 'Version', 'Uptime', 'Memory']);
$rows6 = ListCollection::from([
    ListCollection::from(['PHP-FPM', 'Running', '9000', '8.2.15', '72h', '128 MB']),
    ListCollection::from(['MySQL', 'Running', '3306', '8.0.35', '168h', '512 MB']),
    ListCollection::from(['Redis', 'Failed', '6379', '7.2.4', '0h', '256 MB']),
    ListCollection::from(['Nginx', 'Running', '80', '1.24.0', '720h', '64 MB']),
]);

Table::render($headers6, $rows6);

// ========================================================================
// 6. TABLEAU 2 COLONNES AVEC ICÔNES
// ========================================================================

$console->line();
$console->info('6. Tableau 2 colonnes avec émojis');
$console->line();

$headersIcon = ListCollection::from(['Métrique', 'Valeur']);
$rowsIcon = ListCollection::from([
    ListCollection::from(['CPU', '45%']),
    ListCollection::from(['RAM', '8.2 GB']),
    ListCollection::from(['DISQUE', '256 GB']),
    ListCollection::from(['RÉSEAU', '1.2 Gbps']),
]);

Table::render($headersIcon, $rowsIcon);

// ========================================================================
// 7. TABLEAU 3 COLONNES AVEC ÉMOJIS
// ========================================================================

$console->line();
$console->info('7. Tableau 3 colonnes avec émojis');
$console->line();

$headersEmoji = ListCollection::from(['Status', 'Data', 'Trend']);
$rowsEmoji = ListCollection::from([
    ListCollection::from(['OK', '100%', '↑ 12%']),
    ListCollection::from(['KO', '50%', '↓ 8%']),
    ListCollection::from(['WARN', '75%', '→ 0%']),
]);

Table::render($headersEmoji, $rowsEmoji);

// ========================================================================
// 8. TABLEAU 4 COLONNES AVEC TYPES MIXTES
// ========================================================================

$console->line();
$console->info('8. Tableau 4 colonnes avec types mixtes');
$console->line();

$headersMixed = ListCollection::from(['ID', 'Nom', 'Actif', 'Score']);
$rowsMixed = ListCollection::from([
    ListCollection::from([1, 'Alice', 'Oui', 98.5]),
    ListCollection::from([2, 'Bob', 'Non', 45.2]),
    ListCollection::from([3, 'Charlie', 'Oui', 76.8]),
    ListCollection::from([4, 'Diana', 'Oui', 92.3]),
]);

Table::render($headersMixed, $rowsMixed);

// ========================================================================
// 9. TABLEAU 5 COLONNES AVEC DONNÉES SYSTÈME
// ========================================================================

$console->line();
$console->info('9. Tableau 5 colonnes - Données système');
$console->line();

$headersSys = ListCollection::from(['Processus', 'PID', 'CPU', 'Mémoire', 'Statut']);
$rowsSys = ListCollection::from([
    ListCollection::from(['php-fpm', '1234', '5.2%', '128 MB', 'Actif']),
    ListCollection::from(['mysql', '5678', '15.8%', '512 MB', 'Actif']),
    ListCollection::from(['redis', '9012', '0.5%', '256 MB', 'Arrêté']),
    ListCollection::from(['nginx', '3456', '2.3%', '64 MB', 'Actif']),
]);

Table::render($headersSys, $rowsSys);

// ========================================================================
// 10. TABLEAU 6 COLONNES AVEC DONNÉES SERVEUR
// ========================================================================

$console->line();
$console->info('10. Tableau 6 colonnes - Données serveur (→ TableList)');
$console->line();

$headersServer = ListCollection::from(['Serveur', 'IP', 'CPU', 'RAM', 'DISQUE', 'STATUT']);
$rowsServer = ListCollection::from([
    ListCollection::from(['Web01', '192.168.1.10', '45%', '8.2 GB', '256 GB', 'OK']),
    ListCollection::from(['Web02', '192.168.1.11', '32%', '6.4 GB', '128 GB', 'OK']),
    ListCollection::from(['DB01', '192.168.1.20', '78%', '32 GB', '512 GB', 'Charge']),
    ListCollection::from(['Cache01', '192.168.1.30', '12%', '4 GB', '64 GB', 'HS']),
]);

Table::render($headersServer, $rowsServer);

// ========================================================================
// FIN
// ========================================================================

$console->line();
$console->success('Tous les tableaux ont été affichés avec succès !');
$console->render();
