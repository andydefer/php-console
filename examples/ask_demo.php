<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Components\KeyValue;
use AndyDefer\ConsoleWriter\Console\Console;
use AndyDefer\DomainStructures\Utils\MapCollection;

$console = new Console;

$console->title('📝 Démonstration du composant Ask');

// ========================================================================
// 1. ASK - Saisie simple
// ========================================================================

$console->line();
$console->info('1. Saisie simple');

$name = $console->ask('Quel est votre nom ?');
$console->success('Bonjour '.$name.' !');
$console->line();

// ========================================================================
// 2. ASK - Avec valeur par défaut
// ========================================================================

$console->info('2. Saisie avec valeur par défaut');

$city = $console->ask('Ville de résidence ?', 'Paris');
$console->info('Vous habitez à '.$city);
$console->line();

// ========================================================================
// 3. ASK - Avec couleur personnalisée
// ========================================================================

$console->info('3. Saisie avec couleur personnalisée (jaune)');

$color = $console->ask('Couleur préférée ?', 'Bleu', 'yellow');
$console->info('Couleur : '.$color);
$console->line();

// ========================================================================
// 4. ASK - Avec couleur magenta
// ========================================================================

$console->info('4. Saisie avec couleur personnalisée (magenta)');

$food = $console->ask('Plat préféré ?', null, 'magenta');
$console->success('✅ '.$food.' ! Bon appétit !');
$console->line();

// ========================================================================
// 5. ASK - Formulaire complet
// ========================================================================

$console->line();
$console->info('5. Formulaire complet');

$firstName = $console->ask('Prénom :', null, 'yellow');
$lastName = $console->ask('Nom :', null, 'yellow');
$email = $console->ask('Email :', null, 'cyan');
$phone = $console->ask('Téléphone :', null, 'cyan');
$company = $console->ask('Entreprise :', 'Inconnue', 'green');

$console->line();
$console->title('📊 RÉCAPITULATIF');
$console->line();

KeyValue::renderWithValueColor(
    MapCollection::from([
        'Prénom' => $firstName,
        'Nom' => $lastName,
        'Email' => $email,
        'Téléphone' => $phone,
        'Entreprise' => $company,
    ]),
    'green',
    0
);

$console->line();
$console->success('✅ Formulaire complété avec succès !');
$console->render();
