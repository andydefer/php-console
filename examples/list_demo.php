<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Console;
use AndyDefer\ConsoleWriter\Console\Enums\ListStyle;
use AndyDefer\DomainStructures\Utils\SetCollection;

$console = new Console;

$console->title('📋 Démonstration du composant List');

// ========================================================================
// 1. LISTE - Styles de puces
// ========================================================================

$console->line();
$console->info('1. Styles de puces');

$items = ['Item 1', 'Item 2', 'Item 3'];

$console->info('• Puces (BULLET)');
$console->list($items, ListStyle::BULLET);
$console->line();

$console->info('→ Flèches (ARROW)');
$console->list($items, ListStyle::ARROW);
$console->line();

$console->info('— Tiret (DASH)');
$console->list($items, ListStyle::DASH);
$console->line();

// ========================================================================
// 2. LISTE - Numérotée
// ========================================================================

$console->info('2. Listes numérotées');

$console->info('1. Numérotée (NUMBER)');
$console->list(['First', 'Second', 'Third'], ListStyle::NUMBER);
$console->line();

$console->info('a. Alphabétique (ALPHA)');
$console->list(['Alpha', 'Beta', 'Gamma'], ListStyle::ALPHA);
$console->line();

$console->info('i. Romain (ROMAN)');
$console->list(['Un', 'Deux', 'Trois'], ListStyle::ROMAN);
$console->line();

// ========================================================================
// 3. LISTE - Symboles
// ========================================================================

$console->info('3. Symboles');

$console->info('✓ Check (CHECK)');
$console->list(['Tâche terminée', 'Tests passés'], ListStyle::CHECK);
$console->line();

$console->info('✗ Cross (CROSS)');
$console->list(['Échec du build', 'Erreur de compilation'], ListStyle::CROSS);
$console->line();

$console->info('★ Star (STAR)');
$console->list(['Important', 'Prioritaire'], ListStyle::STAR);
$console->line();

// ========================================================================
// 4. LISTE - Colorée
// ========================================================================

$console->info('4. Listes colorées');

$console->listColored(
    ['✅ Tâche terminée', '✅ Tests passés', '✅ Déploiement réussi'],
    ListStyle::CHECK,
    'green'
);
$console->line();

$console->listColored(
    ['❌ Échec du build', '❌ Erreur de compilation', '❌ Test échoué'],
    ListStyle::CROSS,
    'red'
);
$console->line();

// ========================================================================
// 5. LISTE - Avec indentation
// ========================================================================

$console->info('5. Liste avec indentation (2 niveaux)');

$console->list(['Item principal', 'Sous-item 1', 'Sous-item 2'], ListStyle::BULLET, 2);
$console->line();

// ========================================================================
// 6. LISTE - SetCollection
// ========================================================================

$console->info('6. Liste avec SetCollection');

$items = SetCollection::from(['Apple', 'Banana', 'Cherry']);
$console->list($items, ListStyle::ARROW);
$console->line();

// ========================================================================
// 7. LISTE - Tous les styles
// ========================================================================

$console->info('7. Tous les styles de listes');

$items = ['Item A', 'Item B', 'Item C'];

foreach (ListStyle::cases() as $style) {
    $console->line('  '.$style->name);
    $console->list($items, $style);
    $console->line();
}

// ========================================================================
// 8. LISTE - Combinaison avec d'autres composants
// ========================================================================

$console->line();
$console->info('8. Combinaison avec d\'autres composants');

$console
    ->title('📦 Liste des services')
    ->line()
    ->listColored(
        ['✅ PHP-FPM', '✅ MySQL', '❌ Redis', '✅ Nginx'],
        ListStyle::CHECK,
        'green'
    )
    ->listColored(
        ['Services en ligne : 3/4'],
        ListStyle::STAR,
        'cyan'
    )
    ->line()
    ->alertError('⚠️ Redis est hors ligne')
    ->line()
    ->badgeDanger('1 service critique');

// ========================================================================
// 9. LISTE - ToDo
// ========================================================================

$console->line();
$console->info('9. Liste de tâches (ToDo)');

$console
    ->title('📝 Liste des tâches')
    ->line()
    ->listColored(['Tâche 1 : Terminée'], ListStyle::CHECK, 'green')
    ->listColored(['Tâche 2 : En cours'], ListStyle::CHECK, 'yellow')
    ->listColored(['Tâche 3 : À faire'], ListStyle::CHECK, 'gray')
    ->listColored(['Tâche 4 : En attente'], ListStyle::CHECK, 'yellow')
    ->line()
    ->info('Progression : 1/4 terminée');

// ========================================================================
// 10. LISTE - Étapes
// ========================================================================

$console->line();
$console->info('10. Étapes de déploiement');

$console
    ->title('🚀 Déploiement')
    ->line()
    ->list(
        ['Téléchargement des sources', 'Compilation des assets', 'Migration DB', 'Redémarrage des services'],
        ListStyle::NUMBER
    )
    ->line()
    ->success('✅ Déploiement terminé !');

// ========================================================================
// FIN
// ========================================================================

$console->line();
$console->success('✅ Toutes les listes ont été affichées avec succès !');
$console->render();
