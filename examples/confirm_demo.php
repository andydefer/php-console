<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Components\KeyValue;
use AndyDefer\ConsoleWriter\Console\Console;
use AndyDefer\DomainStructures\Utils\MapCollection;

$console = new Console;

$console->title('❓ Démonstration du composant Confirm');

// ========================================================================
// 1. CONFIRM - Simple
// ========================================================================

$console->line();
$console->info('1. Confirmation simple');

if ($console->confirm('Voulez-vous continuer ?')) {
    $console->success('✅ Vous avez choisi de continuer');
} else {
    $console->error('❌ Vous avez annulé');
}
$console->line();

// ========================================================================
// 2. CONFIRM - Avec valeur par défaut false
// ========================================================================

$console->info('2. Confirmation avec valeur par défaut false');

if ($console->confirm('Supprimer tous les fichiers ?', false)) {
    $console->error('❌ Suppression effectuée');
} else {
    $console->success('✅ Suppression annulée');
}
$console->line();

// ========================================================================
// 3. CONFIRM - Avec couleur personnalisée
// ========================================================================

$console->info('3. Confirmation avec couleur personnalisée (jaune)');

if ($console->confirm('Accepter les conditions générales ?', true, 'yellow')) {
    $console->success('✅ Conditions acceptées');
} else {
    $console->error('❌ Conditions refusées');
}
$console->line();

// ========================================================================
// 4. CONFIRM - Avec couleur magenta
// ========================================================================

$console->info('4. Confirmation avec couleur personnalisée (magenta)');

if ($console->confirm('Voulez-vous recevoir la newsletter ?', true, 'magenta')) {
    $console->success('✅ Abonnement à la newsletter confirmé');
} else {
    $console->info('❌ Abonnement refusé');
}
$console->line();

// ========================================================================
// 5. CONFIRM - Dans un flux de travail
// ========================================================================

$console->info('5. Flux de travail avec confirmation');

$name = $console->ask('Nom du fichier :', 'data.csv', 'yellow');

if ($console->confirm('Voulez-vous vraiment supprimer '.$name.' ?', false, 'red')) {
    $console->error('❌ Fichier '.$name.' supprimé !');
} else {
    $console->success('✅ Suppression annulée, fichier '.$name.' conservé');
}
$console->line();

// ========================================================================
// 6. CONFIRM - Dans un formulaire
// ========================================================================

$console->info('6. Formulaire avec confirmation');

$name = $console->ask('Nom :', null, 'yellow');
$email = $console->ask('Email :', null, 'cyan');
$age = $console->number('Âge :', 1, 120);

$console->line();
$console->info('📊 Récapitulatif');
$console->raw(KeyValue::renderWithValueColor(
    MapCollection::from([
        'Nom' => $name,
        'Email' => $email,
        'Âge' => $age,
    ]),
    'green'
));
$console->line();

if ($console->confirm('Confirmer l\'enregistrement ?', true, 'cyan')) {
    $console->success('✅ Données enregistrées avec succès !');
} else {
    $console->error('❌ Enregistrement annulé');
}
$console->render();
