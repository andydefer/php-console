<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Console;

$console = new Console;

$console->title('🔄 Démonstration du composant Spinner');

// ========================================================================
// 1. SPINNER - Tâche simple
// ========================================================================

$console->line();
$console->info('1. Spinner avec tâche simple');

$console->spinner('Chargement en cours...', function ($spinner) {
    sleep(3);
    $spinner->success('Chargement terminé !');
});

// ========================================================================
// 2. SPINNER - Avec préfixe et suffixe
// ========================================================================

$console->line();
$console->info('2. Spinner avec préfixe et suffixe');

$console->spinner(
    'Téléchargement...',
    function ($spinner) {
        sleep(3);
        $spinner->success('Téléchargement terminé');
    },
    '⬇️',
    '📦 package.zip'
);

// ========================================================================
// 3. SPINNER - Avec erreur
// ========================================================================

$console->line();
$console->info('3. Spinner avec erreur');

$console->spinner('Connexion à Redis...', function ($spinner) {
    sleep(2);
    $spinner->error('Connexion échouée');
});

// ========================================================================
// 4. SPINNER - Avec info
// ========================================================================

$console->line();
$console->info('4. Spinner avec info');

$console->spinner('Vérification des mises à jour...', function ($spinner) {
    sleep(2);
    $spinner->info('Aucune mise à jour disponible');
});

// ========================================================================
// 5. SPINNER - Avec warning
// ========================================================================

$console->line();
$console->info('5. Spinner avec warning');

$console->spinner('Analyse de la sécurité...', function ($spinner) {
    sleep(2);
    $spinner->warning('Vulnérabilité détectée');
});

// ========================================================================
// 6. SPINNER - Avec icône personnalisée
// ========================================================================

$console->line();
$console->info('6. Spinner avec icône personnalisée');

$console->spinner('Déploiement en cours...', function ($spinner) {
    sleep(3);
    $spinner->stop('🚀', 'Déploiement terminé !');
});

// ========================================================================
// 7. SPINNER - Avec changement de message
// ========================================================================

$console->line();
$console->info('7. Spinner avec changement de message');

$console->spinner('Étape 1 : Analyse...', function ($spinner) {
    sleep(1);
    $spinner->setMessage('Étape 2 : Téléchargement...');
    sleep(1);
    $spinner->setMessage('Étape 3 : Installation...');
    sleep(1);
    $spinner->success('Installation terminée !');
});

// ========================================================================
// 8. SPINNER - Attente conditionnelle
// ========================================================================

$console->line();
$console->info('8. Spinner avec attente conditionnelle');

$counter = 0;
$console->spinnerWait('En attente du service...', function () use (&$counter) {
    $counter++;

    return $counter >= 5;
});

$console->success('✅ Service prêt !');

// ========================================================================
// 9. SPINNER - Simulation de processus long
// ========================================================================

$console->line();
$console->info('9. Simulation de processus long');

$console->spinner('Génération du rapport...', function ($spinner) {
    for ($i = 0; $i < 10; $i++) {
        sleep(1);
        $spinner->setMessage('Génération du rapport... '.($i + 1).'/10');
    }
    $spinner->success('Rapport généré avec succès !');
});

// ========================================================================
// 10. SPINNER - Combinaison avec d'autres composants
// ========================================================================

$console->line();
$console->info('10. Combinaison avec d\'autres composants');

$console
    ->title('📦 Installation du package')
    ->line()
    ->spinner('Téléchargement du package...', function ($spinner) {
        sleep(2);
        $spinner->success('✅ Téléchargement terminé');
    })
    ->spinner('Extraction des fichiers...', function ($spinner) {
        sleep(2);
        $spinner->success('✅ Extraction terminée');
    })
    ->spinner('Installation des dépendances...', function ($spinner) {
        sleep(2);
        $spinner->success('✅ Dépendances installées');
    })
    ->spinner('Configuration du projet...', function ($spinner) {
        sleep(2);
        $spinner->success('✅ Configuration terminée');
    })
    ->success('🎉 Installation terminée !');

// ========================================================================
// FIN
// ========================================================================

$console->line();
$console->success('✅ Tous les spinners ont été affichés avec succès !');
$console->render();
