<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Console;
use AndyDefer\DomainStructures\Utils\MapCollection;
use AndyDefer\DomainStructures\Utils\SetCollection;

$console = new Console;

$console->title('🌳 Démonstration du composant Tree');

// ========================================================================
// 1. TREE - Structure simple
// ========================================================================

$console->line();
$console->info('1. Structure arborescente simple');

$tree = MapCollection::from([
    'src' => MapCollection::from([
        'Console' => MapCollection::from([
            'Components' => MapCollection::from([
                'Table.php' => MapCollection::from([]),
                'Tree.php' => MapCollection::from([]),
            ]),
            'Services' => MapCollection::from([
                'AnsiConverterService.php' => MapCollection::from([]),
            ]),
        ]),
        'Contracts' => MapCollection::from([
            'Renderable.php' => MapCollection::from([]),
        ]),
    ]),
    'tests' => MapCollection::from([
        'Unit' => MapCollection::from([
            'Components' => MapCollection::from([
                'TreeTest.php' => MapCollection::from([]),
            ]),
        ]),
    ]),
]);

$console->tree($tree, '📦 php-console-writer');

// ========================================================================
// 2. TREE - Avec couleurs personnalisées
// ========================================================================

$console->line();
$console->info('2. Arbre avec couleurs personnalisées (nœuds en vert, feuilles en jaune)');

$console->treeWithColors($tree, '📦 php-console-writer', 'green', 'yellow');

// ========================================================================
// 3. TREE - Avec icônes
// ========================================================================

$console->line();
$console->info('3. Arbre avec icônes (📁 dossiers, 📄 fichiers)');

$console->treeWithIcons($tree, '📦 php-console-writer', '📂', '📝');

// ========================================================================
// 4. TREE - À partir de chemins
// ========================================================================

$console->line();
$console->info('4. Arbre à partir de chemins');

$paths = SetCollection::from([
    'src/Console/Components',
    'src/Console/Services',
    'src/Console/Enums',
    'tests/Unit/Components',
    'tests/Unit/Services',
    'README.md',
    'LICENSE',
    'composer.json',
]);

$console->treeFromPaths($paths, '📁 php-console-writer');

// ========================================================================
// 5. TREE - Structure de projet Web
// ========================================================================

$console->line();
$console->info('5. Structure de projet Web');

$webTree = MapCollection::from([
    'public' => MapCollection::from([
        'index.php' => MapCollection::from([]),
        'style.css' => MapCollection::from([]),
        'images' => MapCollection::from([
            'logo.png' => MapCollection::from([]),
            'banner.jpg' => MapCollection::from([]),
        ]),
    ]),
    'app' => MapCollection::from([
        'Controllers' => MapCollection::from([
            'HomeController.php' => MapCollection::from([]),
            'UserController.php' => MapCollection::from([]),
        ]),
        'Models' => MapCollection::from([
            'User.php' => MapCollection::from([]),
        ]),
        'Views' => MapCollection::from([
            'home.blade.php' => MapCollection::from([]),
            'user.blade.php' => MapCollection::from([]),
        ]),
    ]),
    'config' => MapCollection::from([
        'app.php' => MapCollection::from([]),
        'database.php' => MapCollection::from([]),
    ]),
]);

$console->tree($webTree, '📁 MonProjet');

// ========================================================================
// 6. TREE - Structure d'organisation
// ========================================================================

$console->line();
$console->info('6. Structure organisationnelle');

$orgTree = MapCollection::from([
    'Direction' => MapCollection::from([
        'CEO' => MapCollection::from([
            'Jean Dupont' => MapCollection::from([]),
        ]),
        'RH' => MapCollection::from([
            'Marie Martin' => MapCollection::from([]),
            'Paul Durand' => MapCollection::from([]),
        ]),
        'Finance' => MapCollection::from([
            'Sophie Petit' => MapCollection::from([]),
        ]),
    ]),
    'Technique' => MapCollection::from([
        'Dev' => MapCollection::from([
            'Backend' => MapCollection::from([
                'Alice Bernard' => MapCollection::from([]),
                'Thomas Robert' => MapCollection::from([]),
            ]),
            'Frontend' => MapCollection::from([
                'Laura Moreau' => MapCollection::from([]),
                'Nicolas Leroy' => MapCollection::from([]),
            ]),
        ]),
        'Produit' => MapCollection::from([
            'Product Owner' => MapCollection::from([
                'Julie Rousseau' => MapCollection::from([]),
            ]),
        ]),
    ]),
]);

$console->treeWithColors($orgTree, '🏢 Entreprise ABC', 'cyan', 'white');

// ========================================================================
// 7. TREE - Système de fichiers
// ========================================================================

$console->line();
$console->info('7. Système de fichiers');

$fsTree = MapCollection::from([
    'home' => MapCollection::from([
        'user' => MapCollection::from([
            'documents' => MapCollection::from([
                'rapport.pdf' => MapCollection::from([]),
                'notes.txt' => MapCollection::from([]),
            ]),
            'downloads' => MapCollection::from([
                'package.zip' => MapCollection::from([]),
            ]),
            '.bashrc' => MapCollection::from([]),
        ]),
    ]),
]);

$console->treeWithIcons($fsTree, '📁 /home', '📂', '📄');

// ========================================================================
// 8. TREE - Combinaison avec d'autres composants
// ========================================================================

$console->line();
$console->info('8. Combinaison avec d\'autres composants');

$console
    ->title('📊 Structure du projet')
    ->line()
    ->treeWithIcons($tree, '📦 php-console-writer', '📂', '📝')
    ->line()
    ->badgeSuccess('✅ Structure OK')
    ->space()
    ->badgeInfo('12 fichiers');

// ========================================================================
// 9. TREE - Avec rendu direct
// ========================================================================

$console->line();
$console->info('9. Rendu direct avec Tree::render()');

use AndyDefer\ConsoleWriter\Console\Components\Tree;

$simpleTree = MapCollection::from([
    'README.md' => MapCollection::from([]),
    'src' => MapCollection::from([
        'Console.php' => MapCollection::from([]),
        'Components' => MapCollection::from([
            'Alert.php' => MapCollection::from([]),
            'Table.php' => MapCollection::from([]),
        ]),
    ]),
]);

echo Tree::render($simpleTree, '📦 package')."\n";

// ========================================================================
// 10. TREE - Tous les styles
// ========================================================================

$console->line();
$console->info('10. Tous les styles');

$demoTree = MapCollection::from([
    'dossier1' => MapCollection::from([
        'fichier1.txt' => MapCollection::from([]),
        'fichier2.txt' => MapCollection::from([]),
    ]),
    'dossier2' => MapCollection::from([
        'sous_dossier' => MapCollection::from([
            'fichier3.txt' => MapCollection::from([]),
        ]),
    ]),
    'fichier_racine.txt' => MapCollection::from([]),
]);

$console
    ->line()
    ->info('▶ Tree simple')
    ->tree($demoTree, '📁 Racine')
    ->line()
    ->info('▶ Tree avec couleurs')
    ->treeWithColors($demoTree, '📁 Racine', 'green', 'yellow')
    ->line()
    ->info('▶ Tree avec icônes')
    ->treeWithIcons($demoTree, '📁 Racine', '📂', '📄')
    ->line();

// ========================================================================
// FIN
// ========================================================================

$console->line();
$console->success('✅ Tous les arbres ont été affichés avec succès !');
$console->render();
