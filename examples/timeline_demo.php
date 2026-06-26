<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Console;
use AndyDefer\DomainStructures\Utils\ListCollection;

$console = new Console;

$console->title('⏱️ Démonstration du composant Timeline');

// ========================================================================
// 1. TIMELINE - Simple
// ========================================================================

$console->line();
$console->info('1. Timeline simple');

$events = ListCollection::from([
    ListCollection::from(['12:00', 'Application démarrée', 'Service web initialisé']),
    ListCollection::from(['12:01', 'Connexion DB', 'Connexion établie en 45ms']),
    ListCollection::from(['12:02', 'Serveur prêt', 'En attente des requêtes']),
]);

$console->timeline($events);

// ========================================================================
// 2. TIMELINE - Avec couleurs personnalisées
// ========================================================================

$console->line();
$console->info('2. Timeline avec couleurs personnalisées');

$colors = ['green', 'yellow', 'red'];

$console->timelineWithColors($events, $colors);

// ========================================================================
// 3. TIMELINE - Avec icônes personnalisées
// ========================================================================

$console->line();
$console->info('3. Timeline avec icônes personnalisées');

$console->timelineWithIcons($events, '★', 'yellow');

// ========================================================================
// 4. TIMELINE - Avec statuts
// ========================================================================

$console->line();
$console->info('4. Timeline avec statuts');

$statuses = ['success', 'warning', 'error'];

$eventsStatus = ListCollection::from([
    ListCollection::from(['12:00', 'Application démarrée', 'Service web initialisé']),
    ListCollection::from(['12:01', 'Connexion DB', 'Connexion établie en 45ms']),
    ListCollection::from(['12:02', 'Serveur prêt', 'En attente des requêtes']),
]);

$console->timelineWithStatus($eventsStatus, $statuses);

// ========================================================================
// 5. TIMELINE - Flux de déploiement
// ========================================================================

$console->line();
$console->info('5. Timeline - Flux de déploiement');

$deployEvents = ListCollection::from([
    ListCollection::from(['14:00:00', '🚀 Déploiement démarré', 'Version 2.5.0']),
    ListCollection::from(['14:00:15', '📦 Téléchargement', 'Sources téléchargées (2.4 MB)']),
    ListCollection::from(['14:00:30', '⚙️ Compilation', 'Assets compilés (12 fichiers)']),
    ListCollection::from(['14:00:45', '🗄️ Migration DB', 'Migrations exécutées (4 migrations)']),
    ListCollection::from(['14:01:00', '🔄 Redémarrage', 'Services redémarrés']),
    ListCollection::from(['14:01:15', '✅ Déploiement terminé', 'Service disponible']),
]);

$deployStatuses = ['info', 'info', 'warning', 'warning', 'success', 'success'];

$console->timelineWithStatus($deployEvents, $deployStatuses);

// ========================================================================
// 6. TIMELINE - Événements système
// ========================================================================

$console->line();
$console->info('6. Timeline - Événements système');

$systemEvents = ListCollection::from([
    ListCollection::from(['08:30:00', '🔄 Service démarré', 'PHP-FPM initialisé']),
    ListCollection::from(['08:30:05', '✅ Connexion DB', 'MySQL connecté']),
    ListCollection::from(['08:30:10', '✅ Cache prêt', 'Redis connecté']),
    ListCollection::from(['08:30:15', '⚠️ Services', 'Nginx en attente']),
    ListCollection::from(['08:30:20', '✅ Système prêt', 'Tous les services sont en ligne']),
]);

$systemStatuses = ['info', 'success', 'success', 'warning', 'success'];

$console->timelineWithStatus($systemEvents, $systemStatuses);

// ========================================================================
// 7. TIMELINE - Combinaison
// ========================================================================

$console->line();
$console->info('7. Timeline combinée avec d\'autres composants');

$console
    ->title('📊 État des services')
    ->line()
    ->timelineWithStatus($systemEvents, $systemStatuses)
    ->line()
    ->badgeSuccess('Système OK')
    ->space()
    ->badgeWarning('1 avertissement')
    ->line();

// ========================================================================
// 8. TIMELINE - Avec icônes custom
// ========================================================================

$console->line();
$console->info('8. Timeline avec icônes custom (⚡)');

$console->timelineWithIcons($events, '⚡', 'magenta');

// ========================================================================
// 9. TIMELINE - Sans descriptions
// ========================================================================

$console->line();
$console->info('9. Timeline sans descriptions');

$simpleEvents = ListCollection::from([
    ListCollection::from(['12:00', 'Application démarrée']),
    ListCollection::from(['12:01', 'Connexion DB']),
    ListCollection::from(['12:02', 'Serveur prêt']),
]);

$console->timeline($simpleEvents);

// ========================================================================
// 10. TIMELINE - Événements d'erreur
// ========================================================================

$console->line();
$console->info('10. Timeline - Événements d\'erreur');

$errorEvents = ListCollection::from([
    ListCollection::from(['10:00:00', '⚠️ Erreur détectée', 'Connexion Redis échouée']),
    ListCollection::from(['10:00:05', '🔄 Tentative 1', 'Reconnexion...']),
    ListCollection::from(['10:00:10', '🔄 Tentative 2', 'Reconnexion...']),
    ListCollection::from(['10:00:15', '❌ Échec critique', 'Redis hors ligne']),
]);

$errorStatuses = ['warning', 'info', 'info', 'error'];

$console->timelineWithStatus($errorEvents, $errorStatuses);

// ========================================================================
// FIN
// ========================================================================

$console->line();
$console->success('✅ Toutes les timelines ont été affichées avec succès !');
$console->render();
