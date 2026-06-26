<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Components\Logger;
use AndyDefer\ConsoleWriter\Console\Console;
use AndyDefer\ConsoleWriter\Console\Enums\ListStyle;

$console = new Console;

$console->title('📋 Démonstration du composant Logger');

// ========================================================================
// 1. LOG - Niveaux de base
// ========================================================================

$console->line();
$console->info('1. Logs avec niveaux de base');

echo Logger::info('Démarrage du service')."\n";
echo Logger::success('Service démarré avec succès')."\n";
echo Logger::warning('Espace disque faible (85%)')."\n";
echo Logger::error('Erreur de connexion à Redis')."\n";
echo Logger::debug('Variable $user = "John"')."\n";
echo Logger::notice('Maintenance programmée à 02:00')."\n";
echo Logger::critical('Service Redis hors ligne !')."\n";

// ========================================================================
// 2. LOG - Dans un tableau
// ========================================================================

$console->line();
$console->info('2. Logs dans un tableau');

$console->table(
    ['Heure', 'Niveau', 'Message'],
    [
        [date('H:i:s'), 'INFO', 'Démarrage du service'],
        [date('H:i:s'), 'SUCCESS', 'Service démarré'],
        [date('H:i:s'), 'WARNING', 'Espace disque faible'],
        [date('H:i:s'), 'ERROR', 'Erreur Redis'],
    ]
);

// ========================================================================
// 3. LOG - Dans une liste
// ========================================================================

$console->line();
$console->info('3. Logs dans une liste');

$console->list(
    [
        Logger::info('Démarrage...'),
        Logger::success('✅ OK'),
        Logger::warning('⚠️ Check mémoire'),
        Logger::error('❌ Erreur critique'),
    ],
    ListStyle::BULLET
);

// ========================================================================
// 4. LOG - Avec format personnalisé
// ========================================================================

$console->line();
$console->info('4. Logs avec format personnalisé (Y-m-d H:i:s)');

Logger::setTimeFormat('Y-m-d H:i:s');

echo Logger::info('Format Y-m-d H:i:s')."\n";
echo Logger::success('Log avec date complète')."\n";

// Restaurer le format par défaut
Logger::setTimeFormat('H:i:s');

// ========================================================================
// 5. LOG - Personnalisé
// ========================================================================

$console->line();
$console->info('5. Logs personnalisés');

echo Logger::log('CUSTOM', 'Message personnalisé en cyan', 'cyan')."\n";
echo Logger::log('AUDIT', 'Action utilisateur #123', 'magenta')."\n";
echo Logger::log('SECURITY', 'Connexion détectée', 'yellow')."\n";

// ========================================================================
// 6. LOG - Simulation de flux
// ========================================================================

$console->line();
$console->info('6. Simulation de flux de logs');

echo Logger::info('📦 Téléchargement des sources...')."\n";
sleep(1);
echo Logger::success('✅ Sources téléchargées (2.4 MB)')."\n";
sleep(1);
echo Logger::info('⚙️  Compilation...')."\n";
sleep(1);
echo Logger::warning('⚠️  Dépendance obsolète détectée')."\n";
sleep(1);
echo Logger::success('✅ Compilation réussie')."\n";
sleep(1);
echo Logger::info('🚀 Déploiement...')."\n";
sleep(1);
echo Logger::success('🎉 Déploiement terminé !')."\n";

// ========================================================================
// 7. LOG - Tous les niveaux
// ========================================================================

$console->line();
$console->info('7. Tous les niveaux de logs');

$console->list(
    [
        Logger::info('INFO - Information'),
        Logger::success('SUCCESS - Succès'),
        Logger::notice('NOTICE - Notice'),
        Logger::warning('WARNING - Avertissement'),
        Logger::error('ERROR - Erreur'),
        Logger::critical('CRITICAL - Critique'),
        Logger::debug('DEBUG - Debug'),
        Logger::log('CUSTOM', 'CUSTOM - Personnalisé', 'magenta'),
    ],
    ListStyle::NUMBER
);

// ========================================================================
// 8. LOG - Dans une KeyValue
// ========================================================================

$console->line();
$console->info('8. Logs dans une KeyValue');

$console->keyValue([
    'Statut' => Logger::success('✅ OK'),
    'Cache' => Logger::warning('⚠️ 85%'),
    'Redis' => Logger::error('❌ KO'),
    'Debug' => Logger::debug('Mode dev actif'),
]);

// ========================================================================
// 9. LOG - Combinaison avec d'autres composants
// ========================================================================

$console->line();
$console->info('9. Combinaison avec d\'autres composants');

$console
    ->title('📊 Dashboard Logs')
    ->line()
    ->raw(Logger::info('Chargement du dashboard...'))
    ->raw(Logger::success('Dashboard chargé'))
    ->line()
    ->badgeSuccess('Système OK')
    ->space()
    ->badgeWarning('1 avertissement')
    ->line();

// ========================================================================
// FIN
// ========================================================================

$console->line();
$console->success('✅ Tous les logs ont été affichés avec succès !');
$console->render();
