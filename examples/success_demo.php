<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Components\Success;
use AndyDefer\ConsoleWriter\Console\Console;
use AndyDefer\ConsoleWriter\Console\Enums\ListStyle;

$console = new Console;

$console->title('✅ Démonstration du composant Success');

// ========================================================================
// 1. SUCCESS - Messages simples
// ========================================================================

$console->line();
$console->info('1. Messages de succès simples');

$console->success('Opération terminée avec succès !');
$console->success('✅ Téléchargement terminé');
$console->success('✅ Installation réussie');
$console->success('✅ Tests passés avec succès');

// ========================================================================
// 2. SUCCESS - Rendu direct
// ========================================================================

$console->line();
$console->info('2. Rendu direct avec Success::render()');

echo Success::render('Direct render avec la classe statique')."\n";
echo Success::render('Autre message direct')."\n";

// ========================================================================
// 3. SUCCESS - Dans une liste
// ========================================================================

$console->line();
$console->info('3. Messages de succès dans une liste');

$console->list(
    [
        Success::render('✅ Tâche 1 terminée'),
        Success::render('✅ Tâche 2 terminée'),
        Success::render('✅ Tâche 3 terminée'),
    ],
    ListStyle::CHECK
);

// ========================================================================
// 4. SUCCESS - Dans une KeyValue
// ========================================================================

$console->line();
$console->info('4. Messages de succès dans une KeyValue');

$console->keyValue([
    'Statut' => Success::render('✅ Opération réussie'),
    'Cache' => Success::render('✅ OK'),
    'Redis' => Success::render('✅ Connecté'),
]);

// ========================================================================
// 5. SUCCESS - Dans un tableau
// ========================================================================

$console->line();
$console->info('5. Messages de succès dans un tableau');

$console->table(
    ['Service', 'Status'],
    [
        ['PHP-FPM', Success::render('✅ OK')],
        ['MySQL', Success::render('✅ OK')],
        ['Redis', Success::render('✅ OK')],
        ['Nginx', Success::render('✅ OK')],
    ]
);

// ========================================================================
// 6. SUCCESS - Combinaison avec d'autres composants
// ========================================================================

$console->line();
$console->info('6. Combinaison avec d\'autres composants');

$console
    ->title('📦 Installation du package')
    ->line()
    ->success('✅ Téléchargement terminé')
    ->success('✅ Extraction terminée')
    ->success('✅ Dépendances installées')
    ->success('✅ Configuration terminée')
    ->line()
    ->badgeSuccess('Package installé')
    ->space()
    ->badgeInfo('v1.0.0')
    ->line()
    ->alertSuccess('🎉 Installation réussie !');

// ========================================================================
// 7. SUCCESS - Flux de travail
// ========================================================================

$console->line();
$console->info('7. Flux de travail avec succès');

$console
    ->info('📦 Téléchargement des sources...')
    ->success('✅ Sources téléchargées (2.4 MB)')
    ->info('⚙️  Compilation...')
    ->success('✅ Compilation réussie')
    ->info('🚀 Déploiement...')
    ->success('✅ Déploiement terminé')
    ->info('🧪 Tests...')
    ->success('✅ Tous les tests passent (42 tests)');

// ========================================================================
// FIN
// ========================================================================

$console->line();
$console->success('✅ Tous les messages de succès ont été affichés avec succès !');
$console->render();
