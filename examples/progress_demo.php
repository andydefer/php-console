<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Console;

$console = new Console;

$console->title('📊 Démonstration du composant ProgressBar');

// ========================================================================
// 1. PROGRESS BAR - Simple
// ========================================================================

$console->line();
$console->info('1. Barre de progression simple');

$console->progressBar(100, 50, '📦 Téléchargement');

for ($i = 0; $i < 100; $i++) {
    usleep(30000);
    $console->advance();
}

$console->finish();

// ========================================================================
// 2. PROGRESS BAR - Avec suffixe
// ========================================================================

$console->line();
$console->info('2. Barre de progression avec suffixe');

$console->progressBar(50, 40, '⚙️  Traitement', 'fichiers restants');

for ($i = 0; $i < 50; $i++) {
    usleep(30000);
    $console->advance();
}

$console->finish();

// ========================================================================
// 3. PROGRESS BAR - Style prédéfini
// ========================================================================

$console->line();
$console->info('3. Barre de progression avec style prédéfini (download)');

$console->progressBarStyled(100, 'download', 40);

for ($i = 0; $i < 100; $i++) {
    usleep(30000);
    $console->advance();
}

$console->finish();

// ========================================================================
// 4. PROGRESS BAR - Avancement par pas
// ========================================================================

$console->line();
$console->info('4. Avancement par pas (10%)');

$console->progressBar(100, 40, '📊 Chargement');

for ($i = 0; $i < 10; $i++) {
    usleep(100000);
    $console->advance(10);
}

$console->finish();

// ========================================================================
// 5. PROGRESS BAR - Avec changement de préfixe
// ========================================================================

$console->line();
$console->info('5. Changement de préfixe en cours de route');

$console->progressBar(100, 40, '🔍 Étape 1/3');

for ($i = 0; $i < 33; $i++) {
    usleep(30000);
    $console->advance();
}

$console->setPrefix('⚙️  Étape 2/3');

for ($i = 0; $i < 33; $i++) {
    usleep(30000);
    $console->advance();
}

$console->setPrefix('✅ Étape 3/3');

for ($i = 0; $i < 34; $i++) {
    usleep(30000);
    $console->advance();
}

$console->finish();

// ========================================================================
// 6. PROGRESS BAR - Style custom
// ========================================================================

$console->line();
$console->info('6. Style custom (processing)');

$console->progressBarStyled(50, 'processing', 40);

for ($i = 0; $i < 50; $i++) {
    usleep(30000);
    $console->advance();
}

$console->finish();

// ========================================================================
// 7. PROGRESS BAR - Style upload
// ========================================================================

$console->line();
$console->info('7. Style upload');

$console->progressBarStyled(80, 'upload', 40);

for ($i = 0; $i < 80; $i++) {
    usleep(30000);
    $console->advance();
}

$console->finish();

// ========================================================================
// 8. PROGRESS BAR - Style install
// ========================================================================

$console->line();
$console->info('8. Style install');

$console->progressBarStyled(60, 'install', 40);

for ($i = 0; $i < 60; $i++) {
    usleep(30000);
    $console->advance();
}

$console->finish();

// ========================================================================
// 9. PROGRESS BAR - Style cleanup
// ========================================================================

$console->line();
$console->info('9. Style cleanup');

$console->progressBarStyled(30, 'cleanup', 40);

for ($i = 0; $i < 30; $i++) {
    usleep(30000);
    $console->advance();
}

$console->finish();

// ========================================================================
// 10. PROGRESS BAR - Combinaison
// ========================================================================

$console->line();
$console->info('10. Combinaison avec d\'autres composants');

$console
    ->title('📦 Installation du package')
    ->line()
    ->info('Téléchargement...')
    ->progressBar(100, 30, '⬇️  Download');

for ($i = 0; $i < 100; $i++) {
    usleep(20000);
    $console->advance();
}

$console
    ->finish()
    ->success('✅ Téléchargement terminé')
    ->line()
    ->info('Extraction...')
    ->progressBar(50, 30, '📦 Extraction');

for ($i = 0; $i < 50; $i++) {
    usleep(20000);
    $console->advance();
}

$console
    ->finish()
    ->success('✅ Extraction terminée')
    ->line()
    ->info('Installation...')
    ->progressBar(80, 30, '⚙️  Installation');

for ($i = 0; $i < 80; $i++) {
    usleep(20000);
    $console->advance();
}

$console
    ->finish()
    ->success('🎉 Installation terminée !');

// ========================================================================
// FIN
// ========================================================================

$console->line();
$console->success('✅ Toutes les barres de progression ont été affichées avec succès !');
$console->render();
