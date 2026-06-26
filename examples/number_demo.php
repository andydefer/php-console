<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Console;

$console = new Console;

$console->title('🔢 Démonstration du composant Number');

// ========================================================================
// 1. NUMBER - Saisie simple sans validation
// ========================================================================

$console->line();
$console->info('1. Saisie simple sans validation');

$age = $console->number('Quel est votre âge ?');
$console->success('✅ Vous avez '.$age.' ans');
$console->line();

// ========================================================================
// 2. NUMBER - Avec valeur par défaut
// ========================================================================

$console->info('2. Saisie avec valeur par défaut');

$score = $console->number('Entrez votre score :', null, null, 0);
$console->success('✅ Score : '.$score);
$console->line();

// ========================================================================
// 3. NUMBER - Avec min et max
// ========================================================================

$console->info('3. Saisie avec min et max (0-100)');

$score = $console->number('Entrez votre score (0-100) :', 0, 100);
$console->success('✅ Score : '.$score.'/100');
$console->line();

// ========================================================================
// 4. NUMBER - Avec min seulement
// ========================================================================

$console->info('4. Saisie avec min seulement (≥ 1)');

$quantity = $console->number('Quantité :', 1);
$console->success('✅ Quantité : '.$quantity);
$console->line();

// ========================================================================
// 5. NUMBER - Avec max seulement
// ========================================================================

$console->info('5. Saisie avec max seulement (≤ 100)');

$percentage = $console->number('Pourcentage :', null, 100);
$console->success('✅ Pourcentage : '.$percentage.'%');
$console->line();

// ========================================================================
// 6. NUMBER - Avec min, max et valeur par défaut
// ========================================================================

$console->info('6. Saisie avec min, max et valeur par défaut (1-10, défaut: 5)');

$rating = $console->number('Note (1-10) :', 1, 10, 5);
$console->success('✅ Note : '.$rating.'/10');
$console->line();

// ========================================================================
// 7. NUMBER - Avec couleur personnalisée
// ========================================================================

$console->info('7. Saisie avec couleur personnalisée (jaune)');

$temperature = $console->number('Température (°C) :', -273, 1000, null, 'yellow');
$console->success('✅ Température : '.$temperature.'°C');
$console->line();

// ========================================================================
// 8. NUMBER - Avec couleur magenta
// ========================================================================

$console->info('8. Saisie avec couleur personnalisée (magenta)');

$year = $console->number('Année de naissance :', 1900, 2026, null, 'magenta');
$console->success('✅ Année de naissance : '.$year);
$console->line();

// ========================================================================
// 9. FORMULAIRE COMPLET AVEC NUMBER
// ========================================================================

$console->info('9. Formulaire complet avec Number');

$name = $console->ask('Nom complet :', null, 'yellow');
$email = $console->ask('Email :', null, 'cyan');
$age = $console->number('Âge :', 1, 120);
$weight = $console->number('Poids (kg) :', 1, 500);
$height = $console->number('Taille (cm) :', 50, 250);
$rating = $console->number('Note (1-5) :', 1, 5, 3);

$console->line();
$console->title('📊 RÉCAPITULATIF');
$console->line();

$console->keyValueWithValueColor([
    'Nom' => $name,
    'Email' => $email,
    'Âge' => $age,
    'Poids' => $weight.' kg',
    'Taille' => $height.' cm',
    'Note' => $rating.'/5',
], 'green');

$console->line();
$console->success('✅ Formulaire complété avec succès !');
$console->render();
