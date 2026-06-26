<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Console;
use AndyDefer\ConsoleWriter\Console\Enums\ListStyle;

$console = new Console;

$console->title('📝 Démonstration du composant Input');

// ========================================================================
// 1. ASK - Saisie simple
// ========================================================================

$console->line();
$console->info('1. ASK - Saisie simple');

$name = $console->ask('Quel est votre nom ?');
$console->success('Bonjour '.$name.' !');
$console->line();

$city = $console->ask('Ville de résidence ?', 'Paris');
$console->info('Vous habitez à '.$city);
$console->line();

$color = $console->ask('Couleur préférée ?', 'Bleu', 'yellow');
$console->info('Couleur : '.$color);
$console->line();

// ========================================================================
// 2. SECRET - Mot de passe masqué
// ========================================================================

$console->line();
$console->info('2. SECRET - Mot de passe masqué');

$password = $console->secret('Entrez votre mot de passe :');
$console->success('✅ Mot de passe enregistré !');
$console->line();

$apiKey = $console->secret('Entrez votre clé API :', 'yellow');
$console->success('✅ Clé API enregistrée !');
$console->line();

// ========================================================================
// 3. CONFIRM - Confirmation Oui/Non
// ========================================================================

$console->line();
$console->info('3. CONFIRM - Oui/Non');

if ($console->confirm('Voulez-vous continuer ?', true)) {
    $console->success('✅ Vous avez choisi de continuer');
} else {
    $console->error('❌ Vous avez annulé');
}
$console->line();

if ($console->confirm('Supprimer tous les fichiers ?', false)) {
    $console->error('❌ Suppression effectuée');
} else {
    $console->success('✅ Suppression annulée');
}
$console->line();

$confirm = $console->confirm('Accepter les conditions ?', true, 'yellow');
$console->line('Résultat : '.($confirm ? '✅ Accepté' : '❌ Refusé'));
$console->line();

// ========================================================================
// 4. CHOICE - Choix unique
// ========================================================================

$console->line();
$console->info('4. CHOICE - Choix unique');

$languages = ['PHP', 'JavaScript', 'Python', 'Go', 'Rust', 'Ruby'];
$lang = $console->choice('Choisissez votre langage préféré :', $languages, 0);
$console->success('✅ Vous avez choisi : '.$lang);
$console->line();

$frameworks = ['Laravel', 'Symfony', 'React', 'Vue.js', 'Angular'];
$framework = $console->choice('Choisissez votre framework :', $frameworks, null, 'yellow');
$console->success('✅ Framework choisi : '.$framework);
$console->line();

// ========================================================================
// 5. SUGGEST - Autocomplétion
// ========================================================================

$console->line();
$console->info('5. SUGGEST - Autocomplétion');

$colors = ['red', 'green', 'blue', 'yellow', 'cyan', 'magenta', 'white', 'black', 'orange', 'purple'];
$color = $console->suggest('Choisissez une couleur :', $colors);
$console->success('✅ Couleur choisie : '.$color);
$console->line();

$countries = ['France', 'Belgique', 'Suisse', 'Canada', 'Allemagne', 'Espagne', 'Italie', 'Portugal'];
$country = $console->suggest('Choisissez un pays :', $countries);
$console->success('✅ Pays choisi : '.$country);
$console->line();

// ========================================================================
// 6. NUMBER - Saisie avec validation
// ========================================================================

$console->line();
$console->info('6. NUMBER - Saisie avec validation');

$age = $console->number('Quel est votre âge ?', 0, 150);
$console->success('✅ Vous avez '.$age.' ans');
$console->line();

$score = $console->number('Entrez votre score (0-100) :', 0, 100);
$console->success('✅ Score : '.$score.'/100');
$console->line();

$quantity = $console->number('Quantité :', 1, null, 1);
$console->success('✅ Quantité : '.$quantity);
$console->line();

$temperature = $console->number('Température (°C) :', -273, 1000);
$console->success('✅ Température : '.$temperature.'°C');
$console->line();

// ========================================================================
// 7. CONFIRM WITH TIMEOUT - Confirmation avec délai
// ========================================================================

$console->line();
$console->info('7. CONFIRM WITH TIMEOUT - Confirmation avec délai');

$console->info('⏳ Vous avez 5 secondes pour répondre...');
$result = $console->confirmWithTimeout('Confirmer l\'opération ?', 5, true);
if ($result) {
    $console->success('✅ Opération confirmée');
} else {
    $console->error('❌ Opération annulée (timeout ou refus)');
}
$console->line();

$console->info('⏳ Vous avez 3 secondes pour répondre...');
$result = $console->confirmWithTimeout('Accepter les risques ?', 3, false);
if ($result) {
    $console->success('✅ Risques acceptés');
} else {
    $console->error('❌ Risques refusés');
}
$console->line();

// ========================================================================
// 8. MULTI CHOICE - Sélection multiple
// ========================================================================

$console->line();
$console->info('8. MULTI CHOICE - Sélection multiple');

$options = ['PHP', 'JavaScript', 'Python', 'Go', 'Rust', 'Ruby', 'TypeScript', 'Java', 'C#'];
$selected = $console->multiChoice(
    'Choisissez vos langages préférés :',
    $options,
    ['PHP', 'JavaScript'],
    'cyan'
);

$console->line();
if (empty($selected)) {
    $console->error('❌ Aucun langage sélectionné');
} else {
    $console->success('✅ Vous avez sélectionné :');
    $console->list($selected, ListStyle::CHECK);
}
$console->line();

$frameworks = ['Laravel', 'Symfony', 'React', 'Vue.js', 'Angular', 'Svelte', 'Django', 'Rails'];
$selectedFrameworks = $console->multiChoice(
    'Choisissez vos frameworks préférés :',
    $frameworks,
    ['Laravel', 'React'],
    'yellow'
);

$console->line();
if (empty($selectedFrameworks)) {
    $console->error('❌ Aucun framework sélectionné');
} else {
    $console->success('✅ Frameworks sélectionnés :');
    $console->list($selectedFrameworks, ListStyle::NUMBER);
}
$console->line();

// ========================================================================
// 9. FORMULAIRE COMPLET
// ========================================================================

$console->line();
$console->info('9. Formulaire complet');

$console->info('--- Remplissez le formulaire ---');
$console->line();

$fullName = $console->ask('Nom complet :', null, 'yellow');
$email = $console->ask('Email :', null, 'cyan');
$age = $console->number('Âge :', 1, 120);
$password = $console->secret('Mot de passe :');
$newsletter = $console->confirm('S\'abonner à la newsletter ?', true);
$lang = $console->choice('Langage préféré :', ['PHP', 'JavaScript', 'Python', 'Go'], null, 'green');
$color = $console->suggest('Couleur favorite :', ['bleu', 'rouge', 'vert', 'jaune', 'noir', 'blanc']);
$hobbies = $console->multiChoice(
    'Choisissez vos hobbies :',
    ['Lecture', 'Sport', 'Musique', 'Voyage', 'Jeux vidéo', 'Cinéma', 'Photographie'],
    ['Lecture', 'Musique'],
    'magenta'
);

$console->line();
$console->title('📊 RÉCAPITULATIF DU FORMULAIRE');
$console->line();

$console->keyValueWithValueColor([
    'Nom complet' => $fullName,
    'Email' => $email,
    'Âge' => $age,
    'Mot de passe' => '••••••••',
    'Newsletter' => $newsletter ? '✅ Oui' : '❌ Non',
    'Langage préféré' => $lang,
    'Couleur favorite' => $color,
    'Hobbies' => implode(', ', $hobbies),
], 'green');

$console->line();
$console->success('✅ Formulaire complété avec succès !');

// ========================================================================
// 10. MENU INTERACTIF COMPLET
// ========================================================================

$console->line();
$console->info('10. Menu interactif');

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
// FIN
// ========================================================================

$console->line();
$console->success('✅ Toutes les démonstrations sont terminées !');
$console->render();
