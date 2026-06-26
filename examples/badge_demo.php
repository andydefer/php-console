<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Console;

$console = new Console;

$console->title('🏷️ Démonstration du composant Badge');

// ========================================================================
// 1. BADGE - Styles prédéfinis
// ========================================================================

$console->line();
$console->info('1. Badges avec styles prédéfinis');

$console
    ->badgeSuccess('SUCCESS')
    ->badgeDanger('FAILED')
    ->badgeWarning('PENDING')
    ->badgeInfo('INFO')
    ->badgePrimary('PRIMARY')
    ->badgeDark('DARK')
    ->badgeLight('LIGHT');

$console->line();

// ========================================================================
// 2. BADGE - Avec texte personnalisé
// ========================================================================

$console->info('2. Badges avec texte personnalisé');

$console
    ->badgeSuccess('✅ OK')
    ->badgeDanger('❌ KO')
    ->badgeWarning('⚠️ WARN')
    ->badgeInfo('ℹ️ NOTE');

$console->line();

// ========================================================================
// 3. BADGE - Avec render direct
// ========================================================================

$console->info('3. Badges avec render direct');

use AndyDefer\ConsoleWriter\Console\Components\Badge;

echo Badge::render('ACTIF', 'success').PHP_EOL;
echo Badge::render('INACTIF', 'danger').PHP_EOL;
echo Badge::render('EN ATTENTE', 'warning').PHP_EOL;
echo Badge::render('INFORMATIF', 'info').PHP_EOL;
echo PHP_EOL;

// ========================================================================
// 4. BADGE - Avec icônes
// ========================================================================

$console->info('4. Badges avec icônes');

echo Badge::renderWithIcon('DÉPLOIEMENT', '🚀', 'success').PHP_EOL;
echo Badge::renderWithIcon('ERREUR', '💥', 'danger').PHP_EOL;
echo Badge::renderWithIcon('EN COURS', '⏳', 'warning').PHP_EOL;
echo Badge::renderWithIcon('NOTIFICATION', '📬', 'info').PHP_EOL;
echo PHP_EOL;

// ========================================================================
// 5. BADGE - Icônes seules
// ========================================================================

$console->info('5. Icônes seules');

echo Badge::renderIconOnly('🟢').PHP_EOL;
echo Badge::renderIconOnly('🔴').PHP_EOL;
echo Badge::renderIconOnly('🟡').PHP_EOL;
echo Badge::renderIconOnly('🔵').PHP_EOL;
echo PHP_EOL;

// ========================================================================
// 6. BADGE - Avec style personnalisé
// ========================================================================

$console->info('6. Badges avec style personnalisé');

Badge::addStyle('custom', 'magenta', '💜', 'CUSTOM');
echo Badge::render('PERSONNALISÉ', 'custom').PHP_EOL;

Badge::addStyle('rainbow', 'cyan', '🌈', 'RAINBOW');
echo Badge::render('ARC-EN-CIEL', 'rainbow').PHP_EOL;

echo PHP_EOL;

// ========================================================================
// 7. BADGE - Dans une KeyValue
// ========================================================================

$console->line();
$console->info('7. Badges dans une KeyValue');

$console->keyValue([
    'CPU' => '45% '.Badge::success('OK'),
    'RAM' => '8.2 GB '.Badge::warning('80%'),
    'DISQUE' => '256 GB '.Badge::success('OK'),
    'REDIS' => 'Hors ligne '.Badge::danger('KO'),
]);

// ========================================================================
// 8. BADGE - Combinaison avec d'autres composants
// ========================================================================

$console->line();
$console->info('8. Combinaison avec d\'autres composants');

$console
    ->line()
    ->alertWithColor('Badge de succès : '.Badge::success('OK'), 'green', 3)
    ->alertWithColor('Badge d\'erreur : '.Badge::danger('KO'), 'red', 3)
    ->alertWithColor('Badge d\'avertissement : '.Badge::warning('WARN'), 'yellow', 3)
    ->alertWithColor('Badge d\'information : '.Badge::info('INFO'), 'blue', 3)
    ->line()
    ->success('✅ Tous les badges fonctionnent !');

// ========================================================================
// 9. BADGE - Tous les styles
// ========================================================================

$console->line();
$console->info('9. Tous les styles de badges');

$console->line('  '.Badge::render('DEFAULT', 'default'));
$console->line('  '.Badge::render('SUCCESS', 'success'));
$console->line('  '.Badge::render('DANGER', 'danger'));
$console->line('  '.Badge::render('WARNING', 'warning'));
$console->line('  '.Badge::render('INFO', 'info'));
$console->line('  '.Badge::render('PRIMARY', 'primary'));
$console->line('  '.Badge::render('DARK', 'dark'));
$console->line('  '.Badge::render('LIGHT', 'light'));
$console->line('  '.Badge::render('SUCCESS-DARK', 'success-dark'));
$console->line('  '.Badge::render('DANGER-DARK', 'danger-dark'));
$console->line('  '.Badge::render('WARNING-DARK', 'warning-dark'));
$console->line('  '.Badge::render('INFO-DARK', 'info-dark'));
$console->line('  '.Badge::render('PRIMARY-DARK', 'primary-dark'));

// ========================================================================
// FIN
// ========================================================================

$console->line();
$console->success('✅ Tous les badges ont été affichés avec succès !');
$console->render();
