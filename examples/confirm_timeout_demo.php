<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Components\KeyValue;
use AndyDefer\ConsoleWriter\Console\Console;
use AndyDefer\DomainStructures\Utils\MapCollection;

$console = new Console;

$console->title('⏱️ Démonstration du composant ConfirmWithTimeout');

// ========================================================================
// 1. CONFIRMATION AVEC TIMEOUT PAR DÉFAUT (5 secondes)
// ========================================================================

$console->line();
$console->info('1. Confirmation avec timeout par défaut (5 secondes)');

$console->info('⏳ Vous avez 5 secondes pour répondre...');
$result = $console->confirmWithTimeout('Confirmer l\'opération ?', 5, true);

if ($result) {
    $console->success('✅ Opération confirmée');
} else {
    $console->error('❌ Opération annulée (timeout ou refus)');
}
$console->line();

// ========================================================================
// 2. CONFIRMATION AVEC TIMEOUT COURT (3 secondes)
// ========================================================================

$console->info('2. Confirmation avec timeout court (3 secondes)');

$console->info('⏳ Vous avez 3 secondes pour répondre...');
$result = $console->confirmWithTimeout('Accepter les risques ?', 3, false);

if ($result) {
    $console->success('✅ Risques acceptés');
} else {
    $console->error('❌ Risques refusés');
}
$console->line();

// ========================================================================
// 3. CONFIRMATION AVEC TIMEOUT ET COULEUR PERSONNALISÉE
// ========================================================================

$console->info('3. Confirmation avec timeout et couleur personnalisée (jaune)');

$console->info('⏳ Vous avez 4 secondes pour répondre...');
$result = $console->confirmWithTimeout('Supprimer le fichier ?', 4, false, 'yellow');

if ($result) {
    $console->error('❌ Fichier supprimé !');
} else {
    $console->success('✅ Fichier conservé');
}
$console->line();

// ========================================================================
// 4. CONFIRMATION AVEC TIMEOUT ET COULEUR MAGENTA
// ========================================================================

$console->info('4. Confirmation avec timeout et couleur personnalisée (magenta)');

$console->info('⏳ Vous avez 6 secondes pour répondre...');
$result = $console->confirmWithTimeout('Envoyer la notification ?', 6, true, 'magenta');

if ($result) {
    $console->success('✅ Notification envoyée');
} else {
    $console->info('❌ Notification annulée');
}
$console->line();

// ========================================================================
// 5. CONFIRMATION DANS UN FLUX DE TRAVAIL
// ========================================================================

$console->info('5. Flux de travail avec confirmation timeout');

$name = $console->ask('Nom du projet :', 'projet-2026', 'yellow');

$console->info('⏳ Vous avez 5 secondes pour confirmer la suppression...');
$result = $console->confirmWithTimeout(
    'Voulez-vous vraiment supprimer le projet "'.$name.'" ?',
    5,
    false,
    'red'
);

if ($result) {
    $console->error('❌ Projet "'.$name.'" supprimé !');
} else {
    $console->success('✅ Projet "'.$name.'" conservé');
}
$console->line();

// ========================================================================
// 6. CONFIRMATION AVEC TIMEOUT DANS UN FORMULAIRE
// ========================================================================

$console->info('6. Formulaire avec confirmation timeout');

$email = $console->ask('Email :', null, 'cyan');
$message = $console->ask('Message :', null, 'yellow');

$console->line();
$console->info('📊 Récapitulatif');

KeyValue::renderWithValueColor(
    MapCollection::from([
        'Email' => $email,
        'Message' => $message,
    ]),
    'green',
    0
);

$console->line();

$console->info('⏳ Vous avez 5 secondes pour confirmer l\'envoi...');
$result = $console->confirmWithTimeout('Envoyer le message ?', 5, true, 'cyan');

if ($result) {
    $console->success('✅ Message envoyé avec succès !');
} else {
    $console->error('❌ Envoi annulé');
}
$console->render();
