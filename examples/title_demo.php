<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Components\Title;
use AndyDefer\ConsoleWriter\Console\Console;

$console = new Console;

$console->title('📋 Démonstration du composant Title');

// ========================================================================
// 1. TITLE - Simple
// ========================================================================

$console->line();
$console->info('1. Titre simple');

$console->title('📊 Dashboard Système');

// ========================================================================
// 2. TITLE - Avec émoji
// ========================================================================

$console->line();
$console->info('2. Titre avec émoji');

$console->title('🚀 Application Console');

// ========================================================================
// 3. TITLE - Rendu direct
// ========================================================================

$console->line();
$console->info('3. Rendu direct avec Title::render()');

echo Title::render('📦 Package Installation')."\n";

// ========================================================================
// 4. TITLE - Avec padding personnalisé
// ========================================================================

$console->line();
$console->info('4. Titre avec padding personnalisé (8)');

echo Title::renderWithPadding('📊 Dashboard', 8)."\n";

// ========================================================================
// 5. TITLE - Avec largeur personnalisée
// ========================================================================

$console->line();
$console->info('5. Titre avec largeur personnalisée (40)');

echo Title::renderWithWidth('📊 Dashboard', 40)."\n";

// ========================================================================
// 6. TITLE - Avec bordure personnalisée
// ========================================================================

$console->line();
$console->info('6. Titre avec bordure personnalisée (─)');

echo Title::renderWithBorder('📊 Dashboard', '─', 6)."\n";

// ========================================================================
// 7. TITLE - Long titre
// ========================================================================

$console->line();
$console->info('7. Titre long');

$console->title('📊 Dashboard Système - Version 2.5.0 - Environnement Production');

// ========================================================================
// 8. TITLE - Avec padding auto
// ========================================================================

$console->line();
$console->info('8. Titre avec padding auto');

$console->title('📊 Dashboard');

// ========================================================================
// 9. TITLE - Combinaison
// ========================================================================

$console->line();
$console->info('9. Combinaison avec d\'autres composants');

$console
    ->title('📦 Installation du package')
    ->line()
    ->success('✅ Téléchargement terminé')
    ->success('✅ Installation terminée')
    ->line()
    ->title('🚀 Application prête')
    ->line()
    ->badgeSuccess('✅ OK')
    ->space()
    ->badgeInfo('v1.0.0');

// ========================================================================
// 10. TITLE - Tous les styles
// ========================================================================

$console->line();
$console->info('10. Tous les styles de titres');

echo Title::render('📊 Dashboard')."\n";
echo Title::renderWithPadding('📊 Dashboard', 8)."\n";
echo Title::renderWithWidth('📊 Dashboard', 40)."\n";
echo Title::renderWithBorder('📊 Dashboard', '─', 6)."\n";

// ========================================================================
// FIN
// ========================================================================

$console->line();
$console->success('✅ Tous les titres ont été affichés avec succès !');
$console->render();
