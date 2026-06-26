<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Components\Notification;
use AndyDefer\ConsoleWriter\Console\Console;
use AndyDefer\ConsoleWriter\Console\Enums\ListStyle;

$console = new Console;

$console->title('🔔 Démonstration du composant Notification');

// ========================================================================
// 1. NOTIFICATION - Types prédéfinis
// ========================================================================

$console->line();
$console->info('1. Notifications avec types prédéfinis');

echo Notification::success('Déploiement réussi !')."\n";
echo Notification::error('Erreur de connexion à la base de données')."\n";
echo Notification::warning('Espace disque presque plein (85%)')."\n";
echo Notification::info('Nouvelle mise à jour disponible v2.5.0')."\n";

// ========================================================================
// 2. NOTIFICATION - Avec icônes personnalisées
// ========================================================================

$console->line();
$console->info('2. Notifications avec icônes personnalisées');

echo Notification::withIcon('Téléchargement terminé !', '📥', 'success')."\n";
echo Notification::withIcon('Fichier corrompu', '💥', 'error')."\n";
echo Notification::withIcon('Nettoyage du cache...', '🧹', 'info')."\n";
echo Notification::withIcon('Nouveau message reçu', '📬', 'info')."\n";

// ========================================================================
// 3. NOTIFICATION - Avec couleurs personnalisées
// ========================================================================

$console->line();
$console->info('3. Notifications avec couleurs personnalisées');

echo Notification::withColor('Message important', 'magenta', '📌')."\n";
echo Notification::withColor('Action requise', 'cyan', '⚡')."\n";
echo Notification::withColor('Traitement terminé', 'green', '🎉')."\n";

// ========================================================================
// 4. NOTIFICATION - Tous les types
// ========================================================================

$console->line();
$console->info('4. Tous les types de notifications');

echo Notification::render('Message par défaut', 'default')."\n";
echo Notification::render('Message de succès', 'success')."\n";
echo Notification::render('Message d\'erreur', 'error')."\n";
echo Notification::render('Message d\'avertissement', 'warning')."\n";
echo Notification::render('Message d\'information', 'info')."\n";

// ========================================================================
// 5. NOTIFICATION - Dans une liste
// ========================================================================

$console->line();
$console->info('5. Notifications dans une liste');

$console->list(
    [
        Notification::success('✅ Tâche 1 terminée'),
        Notification::warning('⚠️ Tâche 2 en attente'),
        Notification::error('❌ Tâche 3 échouée'),
        Notification::info('ℹ️ Tâche 4 démarrée'),
    ],
    ListStyle::BULLET
);

// ========================================================================
// 6. NOTIFICATION - Combinaison
// ========================================================================

$console->line();
$console->info('6. Combinaison avec d\'autres composants');

$console
    ->title('📊 Notifications système')
    ->line()
    ->raw(Notification::info('Chargement du dashboard...'))
    ->raw(Notification::success('Dashboard chargé'))
    ->line()
    ->badgeSuccess('Système OK')
    ->space()
    ->badgeWarning('1 avertissement')
    ->line();

// ========================================================================
// 7. NOTIFICATION - Simulation d'événements
// ========================================================================

$console->line();
$console->info('7. Simulation d\'événements système');

echo Notification::info('🔄 Événement : utilisateur connecté')."\n";
echo Notification::success('✅ Événement : sauvegarde effectuée')."\n";
echo Notification::warning('⚠️ Événement : tentative de connexion échouée')."\n";
echo Notification::error('❌ Événement : erreur critique détectée')."\n";

// ========================================================================
// 8. NOTIFICATION - Messages personnalisés
// ========================================================================

$console->line();
$console->info('8. Messages personnalisés');

echo Notification::withIcon('🚀 Déploiement en cours...', '🚀', 'info')."\n";
echo Notification::withIcon('📊 Génération du rapport...', '📊', 'info')."\n";
echo Notification::withIcon('✅ Rapport généré avec succès !', '✅', 'success')."\n";
echo Notification::withIcon('📧 Email envoyé à 42 destinataires', '📧', 'success')."\n";
echo Notification::withIcon('⏳ Attente de la réponse du serveur...', '⏳', 'warning')."\n";

// ========================================================================
// FIN
// ========================================================================

$console->line();
$console->success('✅ Toutes les notifications ont été affichées avec succès !');
$console->render();
