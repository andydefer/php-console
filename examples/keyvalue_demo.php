<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Components\KeyValue;
use AndyDefer\ConsoleWriter\Console\Console;
use AndyDefer\DomainStructures\Utils\MapCollection;

$console = new Console;

$console->title('🔑 Démonstration du composant KeyValue');

// ========================================================================
// 1. KEYVALUE - Basique
// ========================================================================

$console->line();
$console->info('1. KeyValue basique');

$data = MapCollection::from([
    'Nom' => 'Jean Dupont',
    'Âge' => 42,
    'Ville' => 'Paris 🇫🇷',
    'Email' => 'jean@example.com',
    'Status' => '✅ Actif',
]);

$console->keyValue($data);

// ========================================================================
// 2. KEYVALUE - Avec couleur personnalisée (jaune)
// ========================================================================

$console->line();
$console->info('2. KeyValue avec couleur personnalisée (jaune)');

$data = MapCollection::from([
    'CPU' => '45%',
    'RAM' => '8.2 Go',
    'DISQUE' => '256 Go',
    'RÉSEAU' => '1.2 Gbps',
    'UPTIME' => '72h 34m',
]);

$console->raw(KeyValue::renderWithValueColor($data, 'yellow'));

// ========================================================================
// 3. KEYVALUE - Avec valeurs colorées (vert)
// ========================================================================

$console->line();
$console->info('3. KeyValue avec valeurs colorées (vert)');

$data = MapCollection::from([
    'Service' => 'PHP-FPM',
    'Status' => '✅ Running',
    'Port' => '9000',
    'Memory' => '128 MB',
]);

$console->raw(KeyValue::renderWithValueColor($data, 'green'));

// ========================================================================
// 4. KEYVALUE - Avec séparateur personnalisé
// ========================================================================

$console->line();
$console->info('4. KeyValue avec séparateur personnalisé (→)');

$data = MapCollection::from([
    'Utilisateur' => 'admin',
    'Rôle' => 'Administrateur',
    'Dernière connexion' => '2026-06-25 14:30:00',
    'IP' => '192.168.1.100',
]);

$console->keyValueWithSeparator($data, ' → ');

// ========================================================================
// 5. KEYVALUE - Avec indentation
// ========================================================================

$console->line();
$console->info('5. KeyValue avec indentation (2 niveaux)');

$data = MapCollection::from([
    'Nom' => 'John',
    'Email' => 'john@example.com',
]);

$console->keyValue($data, 2);

// ========================================================================
// 6. KEYVALUE - Types de données mixtes
// ========================================================================

$console->line();
$console->info('6. KeyValue avec types de données mixtes');

$data = MapCollection::from([
    'String' => 'Hello World',
    'Integer' => 42,
    'Boolean' => true,
    'Null' => null,
    'Float' => 3.14159,
    'Array' => ['a', 'b', 'c'],
    'Object' => new class
    {
        public function __toString(): string
        {
            return 'Custom object';
        }
    },
]);

$console->keyValue($data);

// ========================================================================
// 7. KEYVALUE - Longues clés
// ========================================================================

$console->line();
$console->info('7. KeyValue avec longues clés');

$data = MapCollection::from([
    'Nom d\'utilisateur' => 'jdupont',
    'Dernière connexion' => '2026-06-25 14:30:00',
    'Adresse IP' => '192.168.1.100',
    'Permissions' => 'Administrateur',
    'Dossier personnel' => '/home/jdupont',
    'Date de création' => '2024-01-15',
]);

$console->keyValue($data);

// ========================================================================
// 8. KEYVALUE - Avec espaces supplémentaires
// ========================================================================

$console->line();
$console->info('8. KeyValue avec espaces supplémentaires (5 espaces)');

$data = MapCollection::from([
    'Name' => 'John',
    'Age' => 30,
]);

$console->raw(KeyValue::renderWithValueColor($data, 'cyan'));

// ========================================================================
// 9. KEYVALUE - Debug
// ========================================================================

$console->line();
$console->info('9. KeyValue - Mode debug');

$data = MapCollection::from([
    'Short' => 'Value',
    'MediumKey' => '12345',
    'VeryLongKeyNameHere' => 'Test',
]);

echo KeyValue::debug($data)."\n";

// ========================================================================
// 10. KEYVALUE - Combinaison avec d'autres composants
// ========================================================================

$console->line();
$console->info('10. KeyValue combiné avec d\'autres composants');

$data = MapCollection::from([
    'Projet' => 'PHP Console Writer',
    'Version' => '1.0.0',
    'Auteur' => 'Andy Defer',
    'License' => 'MIT',
    'PHP' => '8.2.15',
    'Status' => '✅ Stable',
]);

$console
    ->title('📦 Informations du package')
    ->line()
    ->raw(KeyValue::renderWithValueColor($data, 'cyan'))
    ->line()
    ->badgeSuccess('Package prêt')
    ->space()
    ->badgeInfo('v1.0.0')
    ->line();

// ========================================================================
// 11. KEYVALUE - Données système
// ========================================================================

$console->line();
$console->info('11. KeyValue - Données système');

$system = MapCollection::from([
    'Système d\'exploitation' => 'Linux 5.15.0',
    'Architecture' => 'x86_64',
    'PHP Version' => '8.2.15',
    'Memory Limit' => '256 MB',
    'Max Execution Time' => '30s',
    'Upload Max Filesize' => '2 MB',
    'Post Max Size' => '8 MB',
]);

$console->raw(KeyValue::renderWithValueColor($system, 'magenta'));

// ========================================================================
// 12. KEYVALUE - Données utilisateur
// ========================================================================

$console->line();
$console->info('12. KeyValue - Profil utilisateur');

$profile = MapCollection::from([
    'ID' => 'USR-001',
    'Nom complet' => 'Jean Dupont',
    'Email' => 'jean@example.com',
    'Téléphone' => '+33 6 12 34 56 78',
    'Date de naissance' => '1984-06-25',
    'Inscrit le' => '2024-01-15',
    'Dernière connexion' => '2026-06-25 14:30:00',
    'Statut' => '✅ Actif',
    'Rôle' => 'Administrateur',
]);

$console
    ->title('👤 Profil utilisateur')
    ->line()
    ->raw(KeyValue::renderWithValueColor($profile, 'green'))
    ->line()
    ->badgeSuccess('✅ Compte vérifié')
    ->space()
    ->badgePrimary('Admin');

// ========================================================================
// FIN
// ========================================================================

$console->line();
$console->success('✅ Tous les KeyValue ont été affichés avec succès !');
$console->render();
