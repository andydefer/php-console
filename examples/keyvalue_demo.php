<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/ansi_helper.php';

use AndyDefer\ConsoleWriter\Console\Components\KeyValue;
use AndyDefer\DomainStructures\Utils\MapCollection;

// ========== FONCTIONS D'AFFICHAGE ==========

function renderTitle(string $text): void
{
    $width = strlen($text) + 8;
    $border = str_repeat('═', $width);
    echo ansi('<fg=cyan><options=bold>╔'.$border.'╗
║  '.$text.'  ║
╚'.$border.'╝</options=bold></fg=cyan>')."\n\n";
}

function renderInfo(string $text): void
{
    echo ansi('<fg=blue>ℹ️  '.$text.'</fg=blue>')."\n\n";
}

function renderSuccess(string $text): void
{
    echo ansi('<fg=green>✅ '.$text.'</fg=green>')."\n\n";
}

function renderKeyValue(MapCollection $data, ?string $color = null, ?string $valueColor = null, ?string $separator = null): void
{
    if ($color && $valueColor === null && $separator === null) {
        $result = KeyValue::renderWithColor($data, $color);
    } elseif ($valueColor && $color === null) {
        $result = KeyValue::renderWithValueColor($data, $valueColor);
    } elseif ($separator) {
        $result = KeyValue::renderWithSeparator($data, $separator);
    } else {
        $result = KeyValue::render($data);
    }

    echo ansi($result)."\n\n";
}

// ========== DÉMONSTRATION ==========

echo color("\n=== ✨ KEY VALUE DEMO COLORÉ ===\n", 'cyan');

// 1. Simple
echo color("\n📋 1. RENDU SIMPLE\n", 'yellow');
$data = MapCollection::from([
    'Nom' => 'Jean Dupont',
    'Âge' => 42,
    'Ville' => 'Paris 🇫🇷',
    'Email' => 'jean@example.com',
    'Status' => '✅ Actif',
]);
renderKeyValue($data);

// 2. Avec couleur jaune
echo color("\n🌟 2. RENDU AVEC COULEUR JAUNE\n", 'yellow');
renderKeyValue($data, 'yellow');

// 3. Avec valeurs vertes
echo color("\n✅ 3. RENDU AVEC VALEURS VERTES\n", 'yellow');
renderKeyValue($data, null, 'green');

// 4. Avec séparateur
echo color("\n➜ 4. RENDU AVEC SÉPARATEUR →\n", 'yellow');
renderKeyValue($data, null, null, ' → ');

// 5. Données système
echo color("\n💻 5. DONNÉES SYSTÈME\n", 'yellow');
$system = MapCollection::from([
    'CPU' => '45%',
    'RAM' => '8.2 Go',
    'DISQUE' => '256 Go',
    'RÉSEAU' => '1.2 Gbps',
    'UPTIME' => '72h 34m',
]);
renderKeyValue($system, null, 'green');

// 6. Longues clés
echo color("\n📂 6. LONGUES CLÉS\n", 'yellow');
$long = MapCollection::from([
    'Nom d\'utilisateur' => 'jdupont',
    'Dernière connexion' => '2026-06-25 14:30:00',
    'Adresse IP' => '192.168.1.100',
    'Permissions' => 'Administrateur',
    'Dossier personnel' => '/home/jdupont',
]);
renderKeyValue($long);

// 7. Types mixtes
echo color("\n🔀 7. TYPES MIXTES\n", 'yellow');
$mixed = MapCollection::from([
    'String' => 'Hello World',
    'Integer' => 42,
    'Boolean' => true,
    'Null' => null,
    'Float' => 3.14159,
    'Array' => ['a', 'b', 'c'],
]);
renderKeyValue($mixed);

echo color("\n✅ Démonstration terminée !\n", 'green');
