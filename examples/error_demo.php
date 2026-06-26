<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Components\Error;
use AndyDefer\ConsoleWriter\Console\Console;
use AndyDefer\ConsoleWriter\Console\Enums\ListStyle;

$console = new Console;

$console->title('❌ Démonstration du composant Error');

// ========================================================================
// 1. ERROR - Message simple
// ========================================================================

$console->line();
$console->info('1. Message d\'erreur simple');

$console->error('Impossible de se connecter à la base de données');

// ========================================================================
// 2. ERROR - Messages avec différents contextes
// ========================================================================

$console->line();
$console->info('2. Messages d\'erreur avec différents contextes');

$console->error('Fichier config.php introuvable');
$console->error('La commande n\'existe pas');
$console->error('Permission refusée pour écrire dans le répertoire');

// ========================================================================
// 3. ERROR - Avec rendu direct
// ========================================================================

$console->line();
$console->info('3. Rendu direct avec Error::render()');

echo Error::render('Direct render avec la classe statique').PHP_EOL;

// ========================================================================
// 4. ERROR - Dans un tableau
// ========================================================================

$console->line();
$console->info('4. Messages d\'erreur dans un tableau');

$console->table(
    ['Service', 'Status', 'Message'],
    [
        ['PHP-FPM', '✅ OK', ''],
        ['MySQL', '✅ OK', ''],
        ['Redis', '❌ KO', Error::render('Connexion refusée')],
        ['Nginx', '✅ OK', ''],
    ]
);

// ========================================================================
// 5. ERROR - Dans une KeyValue
// ========================================================================

$console->line();
$console->info('5. Messages d\'erreur dans une KeyValue');

$console->keyValue([
    'Statut général' => '⚠️ 1 service en erreur',
    'Redis' => Error::render('Connexion refusée'),
    'Action recommandée' => 'Vérifier la configuration Redis',
    'Documentation' => 'https://redis.io/docs/latest/',
]);

// ========================================================================
// 6. ERROR - Dans une alerte
// ========================================================================

$console->line();
$console->info('6. Message d\'erreur dans une alerte');

$console
    ->alertWithColor(Error::render('Erreur critique : service Redis indisponible'), 'red', 4)
    ->line();

// ========================================================================
// 7. ERROR - Dans une liste
// ========================================================================

$console->line();
$console->info('7. Liste des erreurs');

$console->list(
    [
        Error::render('Erreur de validation du formulaire'),
        Error::render('Token JWT expiré'),
        Error::render('Requête API en échec (code 500)'),
        Error::render('Timeout de connexion'),
    ],
    ListStyle::CROSS
);

// ========================================================================
// 8. ERROR - Avec succès pour contraste
// ========================================================================

$console->line();
$console->info('8. Contraste entre erreur et succès');

$console
    ->error('❌ Échec de la compilation')
    ->success('✅ Compilation réussie')
    ->error('❌ Échec des tests unitaires')
    ->success('✅ Tous les tests passent');

// ========================================================================
// 9. ERROR - Simulation de logs
// ========================================================================

$console->line();
$console->info('9. Simulation de logs d\'erreur');

$logs = [
    ['14:30:00', 'INFO', 'Démarrage du service'],
    ['14:30:05', 'ERROR', Error::render('Connexion Redis échouée')],
    ['14:30:10', 'WARN', 'Tentative de reconnexion...'],
    ['14:30:15', 'ERROR', Error::render('Redis : échec après 3 tentatives')],
    ['14:30:20', 'INFO', 'Service démarré en mode dégradé'],
];

$console->table(
    ['Heure', 'Niveau', 'Message'],
    $logs
);

// ========================================================================
// 10. ERROR - Formulaire avec erreur
// ========================================================================

$console->line();
$console->info('10. Formulaire avec erreur');

$console
    ->title('📝 Formulaire d\'inscription')
    ->line()
    ->info('Veuillez remplir tous les champs')
    ->line()
    ->info('✓ Nom : Jean Dupont')
    ->info('✓ Email : jean@example.com')
    ->error('❌ Le mot de passe doit contenir au moins 8 caractères')
    ->info('   ⚠️ Le mot de passe actuel contient 6 caractères')
    ->line()
    ->info('Veuillez corriger les erreurs ci-dessus')
    ->line();

// ========================================================================
// 11. ERROR - Combinaison avec d'autres composants
// ========================================================================

$console->line();
$console->info('11. Combinaison avec d\'autres composants');

$console
    ->title('🚀 Script de déploiement')
    ->line()
    ->info('Vérification des prérequis...')
    ->success('✅ PHP 8.2.15 disponible')
    ->success('✅ MySQL 8.0.35 disponible')
    ->error('❌ Redis 7.2.4 non installé')
    ->info('Installation automatique de Redis...')
    ->error('❌ Échec de l\'installation : permission root requise')
    ->alert('⚠️  Action manuelle requise')
    ->line()
    ->badgeDanger('Échec du déploiement')
    ->line()
    ->badgeWarning('En attente d\'action')
    ->line()
    ->error('Veuillez contacter l\'administrateur système')
    ->line();

// ========================================================================
// 12. ERROR - Messages d'erreur avec détails
// ========================================================================

$console->line();
$console->info('12. Messages d\'erreur avec détails');

$console->error('Erreur SQL : table "users" n\'existe pas');
$console->error('Erreur HTTP 404 - Page non trouvée : /api/posts/999');
$console->error('Erreur de validation : le champ "email" est requis');
$console->error('Erreur de cache : impossible d\'écrire dans /tmp/cache');
$console->error('Erreur réseau : connexion refusée sur le port 6379');

// ========================================================================
// FIN
// ========================================================================

$console->line();
$console->success('✅ Tous les messages d\'erreur ont été affichés avec succès !');
$console->render();
