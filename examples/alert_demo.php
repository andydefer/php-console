<?php

require_once 'vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Console;

$console = new Console;

$console->title('🔔 Alertes stylisées');

// Alertes simples
$console
    ->alertSuccess('✅ Déploiement réussi !')
    ->alertError('❌ Erreur critique détectée')
    ->alertWarning('⚠️  Attention, espace disque faible')
    ->alertInfo('ℹ️  Mise à jour disponible');

// Alertes personnalisées
$console
    ->alertWithIcon('Nouveau message reçu', '📬')
    ->alertWithColor('Alerte rouge !', 'red', 6)
    ->alertWithBorder('Important !', '=', 'magenta', 8)
    ->alertWithIconAndColor('🎉 Félicitations !', '🎉', 'green', 6)
    ->alertFull('Message complet', '🚀', 'cyan', '═', 6);

$console->render();
