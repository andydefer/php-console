<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Console;

$console = new Console;

$console->title('📊 Démonstration du composant Separator');

// ========================================================================
// 1. SEPARATEUR STANDARD
// ========================================================================

$console->line();
$console->info('1. Séparateur standard (défaut)');
$console->separator();
$console->line('Texte entre les séparateurs');
$console->separator();

// ========================================================================
// 2. SEPARATEUR AVEC CARACTÈRE PERSONNALISÉ
// ========================================================================

$console->line();
$console->info('2. Séparateur avec caractère personnalisé (*)');
$console->separator('*');
$console->line('Texte entre les séparateurs');
$console->separator('*');

// ========================================================================
// 3. SEPARATEUR AVEC COULEUR PERSONNALISÉE
// ========================================================================

$console->line();
$console->info('3. Séparateur avec couleur personnalisée (cyan)');
$console->separator('-', 80, 'cyan');
$console->line('Texte entre les séparateurs');
$console->separator('-', 80, 'cyan');

// ========================================================================
// 4. SEPARATEUR DOUBLE
// ========================================================================

$console->line();
$console->info('4. Séparateur double (avec "=")');
$console->separatorDouble();
$console->line('Texte entre les séparateurs');
$console->separatorDouble();

// ========================================================================
// 5. SEPARATEUR AVEC TITRE CENTRÉ
// ========================================================================

$console->line();
$console->info('5. Séparateur avec titre centré');
$console->separatorWithTitle('📋 RAPPORT D\'AUDIT');
$console->line('Contenu du rapport...');
$console->separatorWithTitle('📋 FIN DU RAPPORT');

// ========================================================================
// 6. SEPARATEUR AVEC TITRE ET CARACTÈRE PERSONNALISÉ
// ========================================================================

$console->line();
$console->info('6. Séparateur avec titre centré et caractère "="');
$console->separatorWithTitle('⭐ CHAPITRE 1', '=', 80, 'cyan');
$console->line('Contenu du chapitre 1...');
$console->separatorWithTitle('⭐ CHAPITRE 2', '=', 80, 'yellow');

// ========================================================================
// 7. SEPARATEUR AVEC LONGUEUR PERSONNALISÉE
// ========================================================================

$console->line();
$console->info('7. Séparateur avec longueur personnalisée (50 caractères)');
$console->separator('-', 50);
$console->line('Texte entre les séparateurs');
$console->separator('-', 50);

// ========================================================================
// 8. SEPARATEUR MULTIPLE
// ========================================================================

$console->line();
$console->info('8. Séparateurs multiples');
$console->separator('•', 60, 'magenta');
$console->line('Ligne 1');
$console->separator('•', 60, 'magenta');
$console->line('Ligne 2');
$console->separator('•', 60, 'magenta');
$console->line('Ligne 3');
$console->separator('•', 60, 'magenta');

// ========================================================================
// 9. SÉPARATEUR AVEC TITRE DANS UN TABLEAU
// ========================================================================

$console->line();
$console->info('9. Utilisation dans un tableau');
$console->separatorWithTitle('📊 STATISTIQUES', '=', 80, 'green');
$console->line('  📈 CPU: 45%');
$console->line('  📈 RAM: 8.2 GB');
$console->line('  📈 DISQUE: 256 GB');
$console->separator('─', 80, 'gray');

// ========================================================================
// 10. RENDU MANUEL AVEC LA CLASSE STATIQUE
// ========================================================================

$console->line();
$console->info('10. Rendu manuel avec Separator::render()');

use AndyDefer\ConsoleWriter\Console\Components\Separator;

$separator1 = Separator::render(40, 'cyan');
$separator2 = Separator::renderDouble(40, 'yellow');
$separator3 = Separator::renderWithTitle('MANUAL', '-', 50, 'magenta');

echo $separator1."\n";
echo "  Ligne manuelle\n";
echo $separator2."\n";
echo "  Ligne manuelle\n";
echo $separator3."\n";

// ========================================================================
// 11. SÉPARATEUR AVEC TITRE ET COULEUR DANS UN CONTEXTE D'AUDIT
// ========================================================================

$console->line();
$console->info('11. Audit context avec séparateurs');

$console->separatorWithTitle('🔍 DÉBUT DE L\'AUDIT', '=', 80, 'blue');
$console->line('  🔹 Vérification des services...');
$console->line('  🔹 Analyse des logs...');
$console->line('  🔹 Validation des configurations...');
$console->separator('─', 80, 'gray');
$console->line('  ✅ 3 services OK');
$console->line('  ⚠️ 1 service en alerte');
$console->line('  ❌ 0 service critique');
$console->separatorWithTitle('📋 FIN DE L\'AUDIT', '=', 80, 'green');

// ========================================================================
// 12. SÉPARATEUR AVEC TITRE ET ICÔNE
// ========================================================================

$console->line();
$console->info('12. Séparateur avec titre et icône');

$console->separatorWithTitle('🚀 DEPLOYMENT START', '=', 80, 'cyan');
$console->line('  📦 Building application...');
$console->line('  🧪 Running tests...');
$console->line('  📤 Deploying to production...');
$console->separatorWithTitle('✅ DEPLOYMENT COMPLETE', '=', 80, 'green');

// ========================================================================
// 13. SÉPARATEUR AVEC DIFFÉRENTES COULEURS
// ========================================================================

$console->line();
$console->info('13. Différentes couleurs de séparateurs');

$console->separator('-', 60, 'red');
$console->line('  Rouge');
$console->separator('-', 60, 'green');
$console->line('  Vert');
$console->separator('-', 60, 'yellow');
$console->line('  Jaune');
$console->separator('-', 60, 'blue');
$console->line('  Bleu');
$console->separator('-', 60, 'magenta');
$console->line('  Magenta');
$console->separator('-', 60, 'cyan');
$console->line('  Cyan');

// ========================================================================
// 14. SÉPARATEUR AVEC TITRE LONG
// ========================================================================

$console->line();
$console->info('14. Séparateur avec titre long');

$longTitle = '📊 Ceci est un titre très long pour tester le centrage du séparateur';
$console->separatorWithTitle($longTitle, '-', 80, 'yellow');

// ========================================================================
// 15. SÉPARATEUR EN MODE TEXTE
// ========================================================================

$console->line();
$console->info('15. Séparateur en mode texte (sans ANSI)');

// Pour les environnements sans ANSI, on peut utiliser les méthodes standards
$console->line(str_repeat('-', 80));
$console->line('  Mode texte simple');
$console->line(str_repeat('=', 80));

// ========================================================================
// FIN
// ========================================================================

$console->line();
$console->separatorDouble(60, 'green');
$console->success('✅ Toutes les démonstrations de Separator sont terminées !');
$console->separatorDouble(60, 'green');
$console->render();
