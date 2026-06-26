<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Components\Sound;
use AndyDefer\ConsoleWriter\Console\Console;
use AndyDefer\ConsoleWriter\Console\Enums\SoundType;

$console = new Console;

$console->title('🔊 Démonstration du composant Sound');

// ========================================================================
// 1. SOUND - Types prédéfinis
// ========================================================================

$console->line();
$console->info('1. Sons prédéfinis');

$console->info('🔊 Son de succès...');
$console->soundSuccess();

$console->info('🔊 Son d\'erreur...');
$console->soundError();

$console->info('🔊 Son d\'information...');
$console->soundInfo();

// ========================================================================
// 2. SOUND - Personnalisé
// ========================================================================

$console->line();
$console->info('2. Son personnalisé');

$console->info('🔊 Son de succès personnalisé...');
$console->sound(SoundType::SUCCESS);

$console->info('🔊 Son d\'erreur personnalisé...');
$console->sound(SoundType::ERROR);

$console->info('🔊 Son d\'information personnalisé...');
$console->sound(SoundType::INFO);

// ========================================================================
// 3. SOUND - Asynchrone
// ========================================================================

$console->line();
$console->info('3. Son asynchrone');

$console->info('🔊 Son de succès en arrière-plan...');
$console->soundAsync(SoundType::SUCCESS);

$console->info('🔊 Son d\'erreur en arrière-plan...');
$console->soundAsync(SoundType::ERROR);

// ========================================================================
// 4. SOUND - Avec vérification de disponibilité
// ========================================================================

$console->line();
$console->info('4. Vérification de disponibilité');

$types = SoundType::cases();
foreach ($types as $type) {
    $available = Sound::isAvailable($type) ? '✅ Disponible' : '❌ Indisponible';
    $console->line('  '.$type->value.' : '.$available);
}

// ========================================================================
// 5. SOUND - Dans un flux de travail
// ========================================================================

$console->line();
$console->info('5. Sons dans un flux de travail');

$console
    ->info('🚀 Démarrage du déploiement...')
    ->soundAsync(SoundType::INFO)
    ->line()
    ->info('📦 Téléchargement des sources...')
    ->success('✅ Téléchargement terminé')
    ->soundAsync(SoundType::SUCCESS)
    ->line()
    ->info('⚙️  Compilation...')
    ->success('✅ Compilation réussie')
    ->soundAsync(SoundType::SUCCESS)
    ->line()
    ->info('🚀 Déploiement...')
    ->success('🎉 Déploiement terminé !')
    ->soundAsync(SoundType::SUCCESS);

// ========================================================================
// 6. SOUND - Combinaison
// ========================================================================

$console->line();
$console->info('6. Combinaison avec d\'autres composants');

$console
    ->title('📦 Installation du package')
    ->line()
    ->info('Téléchargement...')
    ->progressBar(30, 30, '⬇️  Download');

for ($i = 0; $i < 30; $i++) {
    usleep(50000);
    $console->advance();
}

$console
    ->finish()
    ->soundAsync(SoundType::SUCCESS)
    ->success('✅ Téléchargement terminé')
    ->line()
    ->info('Extraction...')
    ->progressBar(20, 30, '📦 Extraction');

for ($i = 0; $i < 20; $i++) {
    usleep(50000);
    $console->advance();
}

$console
    ->finish()
    ->soundAsync(SoundType::SUCCESS)
    ->success('✅ Extraction terminée')
    ->line()
    ->info('Installation...')
    ->progressBar(30, 30, '⚙️  Installation');

for ($i = 0; $i < 30; $i++) {
    usleep(50000);
    $console->advance();
}

$console
    ->finish()
    ->soundAsync(SoundType::SUCCESS)
    ->success('🎉 Installation terminée !');

// ========================================================================
// FIN
// ========================================================================

$console->line();
$console->success('✅ Tous les sons ont été joués avec succès !');
$console->render();
