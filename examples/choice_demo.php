<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Components\KeyValue;
use AndyDefer\ConsoleWriter\Console\Console;
use AndyDefer\DomainStructures\Utils\MapCollection;

$console = new Console;

$console->title('🎯 Démonstration du composant Choice');

// ========================================================================
// 1. CHOIX SIMPLE
// ========================================================================

$console->line();
$console->info('1. Choix simple avec flèches');

$languages = ['PHP', 'JavaScript', 'Python', 'Go', 'Rust', 'Ruby'];
$lang = $console->choice('Choisissez votre langage préféré :', $languages, 0);

$console->success('✅ Vous avez choisi : '.$lang);
$console->line();

// ========================================================================
// 2. CHOIX AVEC SAISIE NUMÉRIQUE
// ========================================================================

$console->info('2. Choix avec saisie numérique (tapez 1, 2, 3...)');

$frameworks = ['Laravel', 'Symfony', 'React', 'Vue.js', 'Angular'];
$framework = $console->choice('Choisissez votre framework :', $frameworks, null, 'yellow');

$console->success('✅ Framework choisi : '.$framework);
$console->line();

// ========================================================================
// 3. MENU INTERACTIF
// ========================================================================

$console->info('3. Menu interactif avec navigation');

$menu = ['Pizza Margherita', 'Pâtes Carbonara', 'Salade César', 'Steak Frites', 'Quitter'];

while (true) {
    $choice = $console->choice('Choisissez un plat :', $menu, null, 'yellow');

    if ($choice === 'Quitter') {
        $console->success('👋 Au revoir !');
        break;
    }

    $console->success('✅ Vous avez commandé : '.$choice);
    $confirm = $console->confirm('Confirmer la commande ?', true);

    if ($confirm) {
        $console->info('📦 Commande en préparation...');
        $console->line();
    } else {
        $console->error('⚠️ Commande annulée, choisissez un autre plat');
        $console->line();
    }
}

// ========================================================================
// 4. CHOIX AVEC COULEUR PERSONNALISÉE
// ========================================================================

$console->line();
$console->info('4. Choix avec couleur personnalisée (magenta)');

$colors = ['Rouge', 'Vert', 'Bleu', 'Jaune', 'Noir', 'Blanc'];
$color = $console->choice('Choisissez votre couleur préférée :', $colors, null, 'magenta');

$console->success('✅ Couleur choisie : '.$color);
$console->line();

// ========================================================================
// 5. CHOIX AVEC VALEUR PAR DÉFAUT
// ========================================================================

$console->info('5. Choix avec valeur par défaut (PHP)');

$languages = ['PHP', 'JavaScript', 'Python', 'Go', 'Rust'];
$lang = $console->choice('Choisissez un langage :', $languages, 0, 'cyan');

$console->success('✅ Langage choisi : '.$lang);
$console->line();

// ========================================================================
// 6. FORMULAIRE COMPLET AVEC CHOIX
// ========================================================================

$console->line();
$console->info('6. Formulaire complet avec choix');

$name = $console->ask('Nom complet :', null, 'yellow');
$email = $console->ask('Email :', null, 'cyan');
$age = $console->number('Âge :', 1, 120);
$lang = $console->choice('Langage préféré :', ['PHP', 'JavaScript', 'Python', 'Go'], null, 'green');
$framework = $console->choice('Framework préféré :', ['Laravel', 'Symfony', 'React', 'Vue.js'], null, 'magenta');

$console->line();
$console->title('📊 RÉCAPITULATIF');
$console->line();

KeyValue::renderWithValueColor(
    MapCollection::from([
        'Nom' => $name,
        'Email' => $email,
        'Âge' => $age,
        'Langage' => $lang,
        'Framework' => $framework,
    ]),
    'green',
    0
);

$console->line();
$console->success('✅ Formulaire complété avec succès !');
$console->render();
