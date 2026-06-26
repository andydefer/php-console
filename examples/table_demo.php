<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Console;
use AndyDefer\DomainStructures\Utils\ListCollection;

$console = new Console;

$console->title('📊 Démonstration du composant Table');

// ========================================================================
// 1. TABLEAU 2 COLONNES
// ========================================================================

$console->line();
$console->info('1. Tableau 2 colonnes');

$headers2 = ListCollection::from(['Service', 'Status']);
$rows2 = ListCollection::from([
    ListCollection::from(['PHP-FPM', '✅ Running']),
    ListCollection::from(['MySQL', '✅ Running']),
    ListCollection::from(['Redis', '❌ Failed']),
    ListCollection::from(['Nginx', '✅ Running']),
]);

$console->table($headers2, $rows2);

// ========================================================================
// 2. TABLEAU 3 COLONNES
// ========================================================================

$console->line();
$console->info('2. Tableau 3 colonnes');

$headers3 = ListCollection::from(['Service', 'Status', 'Port']);
$rows3 = ListCollection::from([
    ListCollection::from(['PHP-FPM', '✅ Running', '9000']),
    ListCollection::from(['MySQL', '✅ Running', '3306']),
    ListCollection::from(['Redis', '❌ Failed', '6379']),
    ListCollection::from(['Nginx', '✅ Running', '80']),
]);

$console->table($headers3, $rows3);

// ========================================================================
// 3. TABLEAU 4 COLONNES
// ========================================================================

$console->line();
$console->info('3. Tableau 4 colonnes');

$headers4 = ListCollection::from(['Service', 'Status', 'Port', 'Version']);
$rows4 = ListCollection::from([
    ListCollection::from(['PHP-FPM', '✅ Running', '9000', '8.2.15']),
    ListCollection::from(['MySQL', '✅ Running', '3306', '8.0.35']),
    ListCollection::from(['Redis', '❌ Failed', '6379', '7.2.4']),
    ListCollection::from(['Nginx', '✅ Running', '80', '1.24.0']),
]);

$console->table($headers4, $rows4);

// ========================================================================
// 4. TABLEAU 5 COLONNES
// ========================================================================

$console->line();
$console->info('4. Tableau 5 colonnes');

$headers5 = ListCollection::from(['Service', 'Status', 'Port', 'Version', 'Uptime']);
$rows5 = ListCollection::from([
    ListCollection::from(['PHP-FPM', '✅ Running', '9000', '8.2.15', '72h']),
    ListCollection::from(['MySQL', '✅ Running', '3306', '8.0.35', '168h']),
    ListCollection::from(['Redis', '❌ Failed', '6379', '7.2.4', '0h']),
    ListCollection::from(['Nginx', '✅ Running', '80', '1.24.0', '720h']),
]);

$console->table($headers5, $rows5);

// ========================================================================
// 5. TABLEAU AVEC ÉMOJIS
// ========================================================================

$console->line();
$console->info('5. Tableau avec émojis');

$headersEmoji = ListCollection::from(['✅ Status', '📊 Data', '📈 Trend']);
$rowsEmoji = ListCollection::from([
    ListCollection::from(['✅ OK', '📈 100%', '↑ 12%']),
    ListCollection::from(['❌ KO', '📉 50%', '↓ 8%']),
    ListCollection::from(['⚠️ WARN', '📊 75%', '→ 0%']),
]);

$console->table($headersEmoji, $rowsEmoji);

// ========================================================================
// 6. TABLEAU AVEC TYPES MIXTES
// ========================================================================

$console->line();
$console->info('6. Tableau avec types mixtes');

$headersMixed = ListCollection::from(['ID', 'Nom', 'Actif', 'Score']);
$rowsMixed = ListCollection::from([
    ListCollection::from([1, 'Alice', '✅ Oui', 98.5]),
    ListCollection::from([2, 'Bob', '❌ Non', 45.2]),
    ListCollection::from([3, 'Charlie', '✅ Oui', 76.8]),
    ListCollection::from([4, 'Diana', '✅ Oui', 92.3]),
]);

$console->table($headersMixed, $rowsMixed);

// ========================================================================
// 7. TABLEAU 3 COLONNES AVEC DONNÉES SYSTÈME
// ========================================================================

$console->line();
$console->info('7. Tableau 3 colonnes - Données système');

$headersSys = ListCollection::from(['Processus', 'PID', 'CPU']);
$rowsSys = ListCollection::from([
    ListCollection::from(['php-fpm', '1234', '5.2%']),
    ListCollection::from(['mysql', '5678', '15.8%']),
    ListCollection::from(['redis', '9012', '0.5%']),
    ListCollection::from(['nginx', '3456', '2.3%']),
]);

$console->table($headersSys, $rowsSys);

// ========================================================================
// 8. TABLEAU 3 COLONNES - SERVICES
// ========================================================================

$console->line();
$console->info('8. Tableau 3 colonnes - Services');

$headersServices = ListCollection::from(['Service', 'Statut', 'Port']);
$rowsServices = ListCollection::from([
    ListCollection::from(['PHP-FPM', '✅ En ligne', '9000']),
    ListCollection::from(['MySQL', '✅ En ligne', '3306']),
    ListCollection::from(['Redis', '❌ Hors ligne', '6379']),
    ListCollection::from(['Nginx', '✅ En ligne', '80']),
]);

$console->table($headersServices, $rowsServices);

// ========================================================================
// 9. TABLEAU AVEC LIST COLLECTION
// ========================================================================

$console->line();
$console->info('9. Tableau avec ListCollection');

$headersLC = ListCollection::from(['Produit', 'Prix', 'Stock']);
$rowsLC = ListCollection::from([
    ListCollection::from(['Laptop', '999.99', '15']),
    ListCollection::from(['Mouse', '29.99', '42']),
    ListCollection::from(['Keyboard', '79.99', '28']),
]);

$console->table($headersLC, $rowsLC);

// ========================================================================
// 10. TABLEAU - Combinaison
// ========================================================================

$console->line();
$console->info('10. Tableau combiné avec d\'autres composants');

$console
    ->title('📦 Dashboard Services')
    ->line()
    ->table($headers3, $rows3)
    ->line()
    ->badgeSuccess('4 services')
    ->space()
    ->badgeDanger('1 hors ligne')
    ->line();

// ========================================================================
// FIN
// ========================================================================

$console->line();
$console->success('✅ Tous les tableaux ont été affichés avec succès !');
$console->render();
