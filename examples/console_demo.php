<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Console;
use AndyDefer\ConsoleWriter\Console\Enums\ListStyle;
use AndyDefer\ConsoleWriter\Console\Enums\SoundType;
use AndyDefer\DomainStructures\Utils\ListCollection;
use AndyDefer\DomainStructures\Utils\MapCollection;

$console = new Console;

$console->title('🎨 Démonstration complète du Console');

// ========================================================================
// 1. MESSAGES DE BASE
// ========================================================================

$console->line();
$console->info('1. Messages de base');

$console
    ->info('ℹ️  Message d\'information')
    ->success('✅ Message de succès')
    ->error('❌ Message d\'erreur')
    ->alert('⚠️  Message d\'alerte')
    ->title('📊 Titre encadré');

// ========================================================================
// 2. ALERTES STYLISÉES
// ========================================================================

$console->line();
$console->info('2. Alertes stylisées');

$console
    ->alertSuccess('✅ Succès !')
    ->alertError('❌ Erreur !')
    ->alertWarning('⚠️  Attention !')
    ->alertInfo('ℹ️  Information !')
    ->alertWithIcon('Message avec icône', '📬')
    ->alertWithColor('Message en rouge', 'red', 6)
    ->alertWithBorder('Message avec bordure', '=', 'magenta', 8)
    ->alertWithIconAndColor('🎉 Félicitations !', '🎉', 'green', 6)
    ->alertFull('Message complet', '🚀', 'cyan', '═', 6);

// ========================================================================
// 3. TABLEAUX
// ========================================================================

$console->line();
$console->info('3. Tableaux');

$headers = ListCollection::from(['Service', 'Status', 'Port', 'Version']);
$rows = ListCollection::from([
    ListCollection::from(['PHP-FPM', '✅ Running', '9000', '8.2.15']),
    ListCollection::from(['MySQL', '✅ Running', '3306', '8.0.35']),
    ListCollection::from(['Redis', '❌ Failed', '6379', '7.2.4']),
    ListCollection::from(['Nginx', '✅ Running', '80', '1.24.0']),
]);

$console->table($headers, $rows);

// ========================================================================
// 4. TABLEAU ADAPTATIF (> 5 colonnes → liste)
// ========================================================================

$console->line();
$console->info('4. Tableau adaptatif (6 colonnes → liste)');

$headers6 = ListCollection::from(['Service', 'Status', 'Port', 'Version', 'Uptime', 'Memory']);
$rows6 = ListCollection::from([
    ListCollection::from(['PHP-FPM', '✅ Running', '9000', '8.2.15', '72h', '128 MB']),
    ListCollection::from(['MySQL', '✅ Running', '3306', '8.0.35', '168h', '512 MB']),
    ListCollection::from(['Redis', '❌ Failed', '6379', '7.2.4', '0h', '256 MB']),
    ListCollection::from(['Nginx', '✅ Running', '80', '1.24.0', '720h', '64 MB']),
]);

$console->adaptiveTable($headers6, $rows6);

// ========================================================================
// 5. LISTES
// ========================================================================

$console->line();
$console->info('5. Listes');

$items = ['Item 1', 'Item 2', 'Item 3'];

$console
    ->list($items, ListStyle::BULLET)
    ->list($items, ListStyle::ARROW)
    ->list($items, ListStyle::NUMBER)
    ->listColored(['✅ Tâche terminée', '✅ Tests passés'], ListStyle::CHECK, 'green')
    ->listColored(['❌ Échec du build'], ListStyle::CROSS, 'red');

// ========================================================================
// 6. KEY VALUE
// ========================================================================

$console->line();
$console->info('6. Key Value');

$console
    ->keyValue([
        'Nom' => 'Jean Dupont',
        'Âge' => 42,
        'Ville' => 'Paris',
    ])
    ->keyValueWithValueColor([
        'CPU' => '45%',
        'RAM' => '8.2 GB',
        'DISQUE' => '256 GB',
    ], 'green')
    ->keyValueWithSeparator([
        'Utilisateur' => 'admin',
        'Rôle' => 'Administrateur',
    ], ' → ');

// ========================================================================
// 7. LIENS
// ========================================================================

$console->line();
$console->info('7. Liens');

$console
    ->link('https://github.com/andydefer/php-console-writer')
    ->link('https://github.com', '📦 Voir le projet');

// ========================================================================
// 8. ARBRES (TREE)
// ========================================================================

$console->line();
$console->info('8. Arbres');

$tree = MapCollection::from([
    'src' => MapCollection::from([
        'Console' => MapCollection::from([
            'Components' => MapCollection::from([
                'Table.php' => MapCollection::from([]),
                'Tree.php' => MapCollection::from([]),
            ]),
        ]),
    ]),
]);

$console
    ->tree($tree, '📦 Projet')
    ->treeWithIcons($tree, '📦 Projet', '📂', '📄');

// ========================================================================
// 9. BADGES
// ========================================================================

$console->line();
$console->info('9. Badges');

$console
    ->badgeSuccess('OK')
    ->badgeDanger('KO')
    ->badgeWarning('WARN')
    ->badgeInfo('INFO')
    ->badgePrimary('PRIMARY');

// ========================================================================
// 10. MÉTRIQUES
// ========================================================================

$console->line();
$console->info('10. Métriques');

$console
    ->metric('CPU', '45%', 'yellow')
    ->metricWithIcon('RAM', '8.2 GB', '💾', 'green')
    ->metricWithTrend('REQUÊTES', '1 234', '↑ 5%', 'green')
    ->metricInline('UPTIME', '72h', 'cyan');

// ========================================================================
// 11. COLONNES
// ========================================================================

$console->line();
$console->info('11. Colonnes');

$columns = [
    ['Users', '123', 'Actif'],
    ['Servers', '5', 'En ligne'],
    ['Logs', '42', 'OK'],
];

$console
    ->columns($columns)
    ->columnsWithColors($columns, ['cyan', 'green', 'yellow']);

// ========================================================================
// 12. TIMELINE
// ========================================================================

$console->line();
$console->info('12. Timeline');

$events = ListCollection::from([
    ListCollection::from(['12:00', 'Application démarrée', 'Service web initialisé']),
    ListCollection::from(['12:01', 'Connexion DB', 'Connexion établie']),
    ListCollection::from(['12:02', 'Serveur prêt', 'En attente des requêtes']),
]);

$console
    ->timeline($events)
    ->timelineWithStatus($events, ['success', 'warning', 'error']);

// ========================================================================
// 13. JSON VIEWER
// ========================================================================

$console->line();
$console->info('13. JSON Viewer');

$data = [
    'user' => [
        'id' => 1,
        'name' => 'Andy',
        'active' => true,
    ],
];

$console
    ->json($data)
    ->jsonCompact($data);

// ========================================================================
// 14. NOTIFICATIONS
// ========================================================================

$console->line();
$console->info('14. Notifications');

$console
    ->notifySuccess('✅ Succès !')
    ->notifyError('❌ Erreur !')
    ->notifyWarning('⚠️  Attention !')
    ->notifyInfo('ℹ️  Information !');

// ========================================================================
// 15. LOGS
// ========================================================================

$console->line();
$console->info('15. Logs');

$console
    ->logInfo('Chargement...')
    ->logSuccess('✅ Terminé')
    ->logError('❌ Erreur')
    ->logWarning('⚠️  Attention')
    ->logDebug('Debug info');

// ========================================================================
// 16. SONS
// ========================================================================

$console->line();
$console->info('16. Sons');

$console
    ->soundSuccess()
    ->soundError()
    ->soundInfo()
    ->sound(SoundType::SUCCESS);

// ========================================================================
// 17. PROGRESS BAR
// ========================================================================

$console->line();
$console->info('17. Barre de progression');

$console
    ->progressBar(50, 40, '📦 Téléchargement');

for ($i = 0; $i < 50; $i++) {
    usleep(30000);
    $console->advance();
}

$console->finish();

// ========================================================================
// 18. SPINNER
// ========================================================================

$console->line();
$console->info('18. Spinner');

$console->spinner('Chargement en cours...', function ($spinner) {
    sleep(2);
    $spinner->success('Terminé !');
});

// ========================================================================
// 19. FORMULAIRE
// ========================================================================

$console->line();
$console->info('19. Formulaire');

$answers = $console->form()
    ->title('📝 Formulaire')
    ->line()
    ->ask('Nom :', 'name', null, 'yellow')
    ->ask('Email :', 'email', null, 'cyan')
    ->number('Âge :', 'age', 1, 120)
    ->confirm('Newsletter ?', 'newsletter', true)
    ->choice('Langage :', 'lang', ['PHP', 'JavaScript', 'Python'])
    ->submit();

$console->line();
$console->title('📊 Réponses');
$console->line();

$console->keyValueWithValueColor([
    'Nom' => $answers->get('name'),
    'Email' => $answers->get('email'),
    'Âge' => $answers->get('age'),
    'Newsletter' => $answers->get('newsletter') ? '✅ Oui' : '❌ Non',
    'Langage' => $answers->get('lang'),
], 'green');

// ========================================================================
// FIN
// ========================================================================

$console->line();
$console->success('✅ Toutes les démonstrations sont terminées !');
$console->render();
