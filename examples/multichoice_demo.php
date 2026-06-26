<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Components\KeyValue;
use AndyDefer\ConsoleWriter\Console\Console;
use AndyDefer\ConsoleWriter\Console\Enums\ListStyle;
use AndyDefer\DomainStructures\Utils\MapCollection;

$console = new Console;

$console->title('📋 Démonstration du composant MultiChoice');

// ========================================================================
// 1. MULTICHOICE - Sélection multiple simple
// ========================================================================

$console->line();
$console->info('1. Sélection multiple simple');

$options = ['PHP', 'JavaScript', 'Python', 'Go', 'Rust', 'Ruby', 'TypeScript', 'Java', 'C#'];
$selected = $console->multiChoice(
    'Choisissez vos langages préférés :',
    $options,
    ['PHP', 'JavaScript']
);

$console->line();
if (empty($selected)) {
    $console->error('❌ Aucun langage sélectionné');
} else {
    $console->success('✅ Vous avez sélectionné :');
    $console->list($selected, ListStyle::CHECK);
}
$console->line();

// ========================================================================
// 2. MULTICHOICE - Avec couleur personnalisée
// ========================================================================

$console->info('2. Sélection multiple avec couleur personnalisée (magenta)');

$frameworks = ['Laravel', 'Symfony', 'React', 'Vue.js', 'Angular', 'Svelte', 'Django', 'Rails'];
$selectedFrameworks = $console->multiChoice(
    'Choisissez vos frameworks préférés :',
    $frameworks,
    ['Laravel', 'React'],
    'magenta'
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
// 3. MULTICHOICE - Hobbies
// ========================================================================

$console->info('3. Sélection de hobbies');

$hobbies = ['Lecture', 'Sport', 'Musique', 'Voyage', 'Jeux vidéo', 'Cinéma', 'Photographie', 'Cuisine'];
$selectedHobbies = $console->multiChoice(
    'Choisissez vos hobbies :',
    $hobbies,
    ['Lecture', 'Musique'],
    'cyan'
);

$console->line();
if (empty($selectedHobbies)) {
    $console->error('❌ Aucun hobby sélectionné');
} else {
    $console->success('✅ Hobbies sélectionnés :');
    $console->list($selectedHobbies, ListStyle::BULLET);
}
$console->line();

// ========================================================================
// 4. FORMULAIRE COMPLET AVEC MULTICHOICE
// ========================================================================

$console->info('4. Formulaire complet avec MultiChoice');

$name = $console->ask('Nom complet :', null, 'yellow');
$email = $console->ask('Email :', null, 'cyan');
$age = $console->number('Âge :', 1, 120);

$skills = ['PHP', 'JavaScript', 'Python', 'SQL', 'Docker', 'Kubernetes', 'AWS', 'Git'];
$selectedSkills = $console->multiChoice(
    'Sélectionnez vos compétences :',
    $skills,
    ['PHP', 'Git'],
    'green'
);

$interests = ['Web', 'Mobile', 'IA', 'Data Science', 'DevOps', 'Cloud', 'Cybersécurité'];
$selectedInterests = $console->multiChoice(
    'Sélectionnez vos centres d\'intérêt :',
    $interests,
    ['Web', 'Cloud'],
    'yellow'
);

$console->line();
$console->title('📊 RÉCAPITULATIF');
$console->line();

KeyValue::renderWithValueColor(MapCollection::from([
    'Nom' => $name,
    'Email' => $email,
    'Âge' => $age,
    'Compétences' => implode(', ', $selectedSkills),
    'Centres d\'intérêt' => implode(', ', $selectedInterests),
]), 'green');

$console->line();
$console->success('✅ Formulaire complété avec succès !');
$console->render();
