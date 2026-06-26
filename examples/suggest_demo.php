<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Console;

$console = new Console;

$console->title('💡 Démonstration du composant Suggest');

// ========================================================================
// 1. SUGGEST - Autocomplétion simple
// ========================================================================

$console->line();
$console->info('1. Autocomplétion simple (flèche droite pour compléter)');

$colors = ['red', 'green', 'blue', 'yellow', 'cyan', 'magenta', 'white', 'black', 'orange', 'purple'];
$color = $console->suggest('Choisissez une couleur :', $colors);

$console->success('✅ Couleur choisie : '.$color);
$console->line();

// ========================================================================
// 2. SUGGEST - Pays
// ========================================================================

$console->info('2. Autocomplétion de pays');

$countries = ['France', 'Belgique', 'Suisse', 'Canada', 'Allemagne', 'Espagne', 'Italie', 'Portugal', 'Royaume-Uni', 'États-Unis'];
$country = $console->suggest('Choisissez un pays :', $countries);

$console->success('✅ Pays choisi : '.$country);
$console->line();

// ========================================================================
// 3. SUGGEST - Frameworks
// ========================================================================

$console->info('3. Autocomplétion de frameworks');

$frameworks = ['Laravel', 'Symfony', 'React', 'Vue.js', 'Angular', 'Svelte', 'Django', 'Rails', 'Spring', 'Express'];
$framework = $console->suggest('Choisissez un framework :', $frameworks);

$console->success('✅ Framework choisi : '.$framework);
$console->line();

// ========================================================================
// 4. SUGGEST - Avec couleur personnalisée
// ========================================================================

$console->info('4. Autocomplétion avec couleur personnalisée (jaune)');

$cities = ['Paris', 'Lyon', 'Marseille', 'Toulouse', 'Nice', 'Nantes', 'Strasbourg', 'Montpellier', 'Bordeaux', 'Lille'];
$city = $console->suggest('Choisissez une ville :', $cities, 'yellow');

$console->success('✅ Ville choisie : '.$city);
$console->line();

// ========================================================================
// 5. SUGGEST - Avec couleur magenta
// ========================================================================

$console->info('5. Autocomplétion avec couleur personnalisée (magenta)');

$languages = ['PHP', 'JavaScript', 'Python', 'Go', 'Rust', 'Ruby', 'TypeScript', 'Java', 'C#', 'Kotlin'];
$language = $console->suggest('Choisissez un langage :', $languages, 'magenta');

$console->success('✅ Langage choisi : '.$language);
$console->line();

// ========================================================================
// 6. FORMULAIRE COMPLET AVEC SUGGEST
// ========================================================================

$console->info('6. Formulaire complet avec Suggest');

$name = $console->ask('Nom complet :', null, 'yellow');
$email = $console->ask('Email :', null, 'cyan');

$colors = ['Rouge', 'Bleu', 'Vert', 'Jaune', 'Noir', 'Blanc', 'Gris', 'Orange', 'Violet', 'Rose'];
$favoriteColor = $console->suggest('Couleur favorite :', $colors);

$cities = ['Paris', 'Lyon', 'Marseille', 'Toulouse', 'Nice', 'Nantes', 'Strasbourg', 'Montpellier'];
$city = $console->suggest('Ville de résidence :', $cities);

$frameworks = ['Laravel', 'Symfony', 'React', 'Vue.js', 'Angular', 'Svelte'];
$framework = $console->suggest('Framework préféré :', $frameworks);

$console->line();
$console->title('📊 RÉCAPITULATIF');
$console->line();

$console->keyValueWithValueColor([
    'Nom' => $name,
    'Email' => $email,
    'Couleur favorite' => $favoriteColor,
    'Ville' => $city,
    'Framework' => $framework,
], 'green');

$console->line();
$console->success('✅ Formulaire complété avec succès !');
$console->render();
