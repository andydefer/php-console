<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Components\Link;
use AndyDefer\ConsoleWriter\Console\Console;
use AndyDefer\ConsoleWriter\Console\Enums\ListStyle;

$console = new Console;

$console->title('🔗 Démonstration du composant Link');

// ========================================================================
// 1. LINK - Basique
// ========================================================================

$console->line();
$console->info('1. Lien basique');

$console->link('https://github.com/andydefer/php-console-writer');

// ========================================================================
// 2. LINK - Avec texte personnalisé
// ========================================================================

$console->line();
$console->info('2. Lien avec texte personnalisé');

$console->link('https://github.com', '📦 Voir le projet sur GitHub');

// ========================================================================
// 3. LINK - Avec icône
// ========================================================================

$console->line();
$console->info('3. Lien avec icône');

$console->raw(Link::renderWithIcon(
    'https://packagist.org/packages/andy-defer/php-console-writer',
    'Packagist',
    '📦'
));

// ========================================================================
// 4. LINK - Avec couleur personnalisée
// ========================================================================

$console->line();
$console->info('4. Lien avec couleur personnalisée (magenta)');

$console->raw(Link::renderWithColor(
    'https://github.com/andydefer/php-console-writer',
    'GitHub Repository',
    'magenta'
));

// ========================================================================
// 5. LINK - Rendu direct
// ========================================================================

$console->line();
$console->info('5. Rendu direct avec Link::render()');

echo Link::render('https://example.com')."\n";
echo Link::renderWithText('https://example.com', 'Visiter Example.com')."\n";

// ========================================================================
// 6. LINK - Dans une KeyValue
// ========================================================================

$console->line();
$console->info('6. Liens dans une KeyValue');

$console->keyValue([
    'Documentation' => Link::renderWithText('https://docs.example.com', '📖 Voir la doc'),
    'GitHub' => Link::renderWithText('https://github.com', '🐙 Voir le code'),
    'Packagist' => Link::renderWithText('https://packagist.org', '📦 Installer le package'),
]);

// ========================================================================
// 7. LINK - Combinaison avec d'autres composants
// ========================================================================

$console->line();
$console->info('7. Combinaison avec d\'autres composants');

$console
    ->title('📚 Ressources')
    ->line()
    ->info('Liens utiles :')
    ->list([
        Link::renderWithIcon('https://github.com/andydefer/php-console-writer', 'PHP Console Writer', '📦'),
        Link::renderWithIcon('https://packagist.org', 'Packagist', '📦'),
        Link::renderWithIcon('https://github.com', 'GitHub', '🐙'),
    ], ListStyle::BULLET)
    ->line()
    ->badgeInfo('🔗 Liens cliquables');

// ========================================================================
// 8. LINK - Dans une alerte
// ========================================================================

$console->line();
$console->info('8. Lien dans une alerte');

$console
    ->alertWithColor(
        '📌 Consultez la documentation : '.Link::renderWithText('https://docs.example.com', 'docs.example.com'),
        'cyan',
        4
    );

// ========================================================================
// FIN
// ========================================================================

$console->line();
$console->success('✅ Tous les liens ont été affichés avec succès !');
$console->render();
