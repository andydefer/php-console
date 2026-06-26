<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Components\Metric;
use AndyDefer\ConsoleWriter\Console\Console;

$console = new Console;

$console->title('📊 Démonstration du composant Metric');

// ========================================================================
// 1. METRIC - Simple
// ========================================================================

$console->line();
$console->info('1. Métriques simples');

echo Metric::render('CPU', '45%')."\n\n";
echo Metric::render('RAM', '8.2 GB', 'green')."\n\n";
echo Metric::render('DISQUE', '256 GB', 'yellow')."\n\n";
echo Metric::render('RÉSEAU', '1.2 Gbps', 'cyan')."\n\n";

// ========================================================================
// 2. METRIC - Avec icône
// ========================================================================

$console->line();
$console->info('2. Métriques avec icônes');

echo Metric::renderWithIcon('CPU', '45%', '💻')."\n\n";
echo Metric::renderWithIcon('RAM', '8.2 GB', '🧠', 'green')."\n\n";
echo Metric::renderWithIcon('DISQUE', '256 GB', '💾', 'yellow')."\n\n";
echo Metric::renderWithIcon('RÉSEAU', '1.2 Gbps', '🌐', 'cyan')."\n\n";

// ========================================================================
// 3. METRIC - Avec tendance
// ========================================================================

$console->line();
$console->info('3. Métriques avec tendance');

echo Metric::renderWithTrend('CPU', '45%', '↑ 12%', 'green')."\n\n";
echo Metric::renderWithTrend('RAM', '8.2 GB', '↓ 2%', 'red')."\n\n";
echo Metric::renderWithTrend('DISQUE', '256 GB', '→ 0%', 'yellow')."\n\n";
echo Metric::renderWithTrend('REQUÊTES', '1 234/s', '↑ 5%', 'green')."\n\n";

// ========================================================================
// 4. METRIC - En ligne
// ========================================================================

$console->line();
$console->info('4. Métriques en ligne');

echo Metric::renderInline('CPU', '45%')."\n";
echo Metric::renderInline('RAM', '8.2 GB', 'green')."\n";
echo Metric::renderInline('DISQUE', '256 GB', 'yellow')."\n";
echo Metric::renderInline('RÉSEAU', '1.2 Gbps', 'cyan')."\n";

// ========================================================================
// 5. METRIC - Dashboard
// ========================================================================

$console->line();
$console->info('5. Dashboard de métriques');

$console
    ->title('📊 Dashboard Système')
    ->line()
    ->raw(Metric::renderWithIcon('CPU', '45%', '💻'))
    ->raw(Metric::renderWithIcon('RAM', '8.2 GB', '🧠', 'green'))
    ->raw(Metric::renderWithIcon('DISQUE', '256 GB', '💾', 'yellow'))
    ->raw(Metric::renderWithIcon('RÉSEAU', '1.2 Gbps', '🌐', 'cyan'))
    ->line()
    ->success('✅ Dashboard chargé');

// ========================================================================
// 6. METRIC - Combinaison avec Badge
// ========================================================================

$console->line();
$console->info('6. Métriques avec badges');

$console
    ->line()
    ->raw(Metric::renderWithTrend('UPTIME', '72h 34m', '✅ OK', 'green'))
    ->raw(Metric::renderWithTrend('ERREURS', '12', '↓ 8%', 'green'))
    ->raw(Metric::renderWithTrend('TEMPS RÉPONSE', '45ms', '↑ 12ms', 'red'))
    ->line()
    ->badgeSuccess('Système stable')
    ->space()
    ->badgeWarning('1 service critique');

// ========================================================================
// FIN
// ========================================================================

$console->line();
$console->success('✅ Toutes les métriques ont été affichées avec succès !');
$console->render();
