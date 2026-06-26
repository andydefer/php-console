<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Console;

$console = new Console;

$console->title('🔒 Démonstration du composant Secret');

// ========================================================================
// 1. SECRET - Saisie de mot de passe simple
// ========================================================================

$console->line();
$console->info('1. Saisie de mot de passe simple');

$password = $console->secret('Entrez votre mot de passe :');
$console->success('✅ Mot de passe enregistré !');
$console->line();

// ========================================================================
// 2. SECRET - Saisie avec couleur personnalisée
// ========================================================================

$console->info('2. Saisie avec couleur personnalisée (jaune)');

$apiKey = $console->secret('Entrez votre clé API :', 'yellow');
$console->success('✅ Clé API enregistrée !');
$console->line();

// ========================================================================
// 3. SECRET - Saisie avec couleur magenta
// ========================================================================

$console->info('3. Saisie avec couleur personnalisée (magenta)');

$token = $console->secret('Entrez votre token d\'accès :', 'magenta');
$console->success('✅ Token enregistré !');
$console->line();

// ========================================================================
// 4. SECRET - Saisie avec couleur cyan
// ========================================================================

$console->info('4. Saisie avec couleur personnalisée (cyan)');

$sshKey = $console->secret('Entrez votre clé SSH :', 'cyan');
$console->success('✅ Clé SSH enregistrée !');
$console->line();

// ========================================================================
// 5. FORMULAIRE COMPLET AVEC SECRET
// ========================================================================

$console->info('5. Formulaire complet avec Secret');

$username = $console->ask('Nom d\'utilisateur :', null, 'yellow');
$email = $console->ask('Email :', null, 'cyan');
$password = $console->secret('Mot de passe :');
$confirmPassword = $console->secret('Confirmer le mot de passe :');

if ($password !== $confirmPassword) {
    $console->error('❌ Les mots de passe ne correspondent pas !');
    exit(1);
}

$console->line();
$console->title('📊 RÉCAPITULATIF');
$console->line();

$console->keyValueWithValueColor([
    'Nom d\'utilisateur' => $username,
    'Email' => $email,
    'Mot de passe' => '••••••••',
], 'green');

$console->line();
$console->success('✅ Compte créé avec succès !');
$console->render();
