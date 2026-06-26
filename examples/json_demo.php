<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Components\JsonViewer;
use AndyDefer\ConsoleWriter\Console\Console;

$console = new Console;

$console->title('📄 Démonstration du composant JsonViewer');

// ========================================================================
// 1. JSON SIMPLE
// ========================================================================

$console->line();
$console->info('1. JSON simple');

$data = [
    'name' => 'Andy Defer',
    'age' => 30,
    'active' => true,
    'score' => 98.5,
    'email' => 'andy@example.com',
];

echo JsonViewer::render($data)."\n";

// ========================================================================
// 2. JSON AVEC OBJETS IMBRIQUÉS
// ========================================================================

$console->line();
$console->info('2. JSON avec objets imbriqués');

$data = [
    'user' => [
        'id' => 1,
        'name' => 'Andy Defer',
        'email' => 'andy@example.com',
        'profile' => [
            'age' => 30,
            'city' => 'Paris',
            'country' => 'France',
            'interests' => ['PHP', 'JavaScript', 'Python', 'Go'],
        ],
    ],
    'settings' => [
        'theme' => 'dark',
        'notifications' => true,
        'language' => 'fr',
    ],
    'metadata' => [
        'created_at' => '2026-06-26T10:00:00Z',
        'updated_at' => '2026-06-26T14:30:00Z',
        'version' => '2.5.0',
    ],
];

echo JsonViewer::render($data)."\n";

// ========================================================================
// 3. JSON AVEC TABLEAUX
// ========================================================================

$console->line();
$console->info('3. JSON avec tableaux');

$data = [
    'users' => [
        ['id' => 1, 'name' => 'Alice', 'active' => true],
        ['id' => 2, 'name' => 'Bob', 'active' => false],
        ['id' => 3, 'name' => 'Charlie', 'active' => true],
    ],
    'roles' => ['admin', 'user', 'guest'],
    'permissions' => [
        'read' => true,
        'write' => false,
        'delete' => false,
    ],
];

echo JsonViewer::render($data)."\n";

// ========================================================================
// 4. JSON RENDER RAW (sans couleurs)
// ========================================================================

$console->line();
$console->info('4. JSON render raw (sans couleurs)');

$data = [
    'name' => 'Andy Defer',
    'age' => 30,
    'active' => true,
];

echo JsonViewer::renderRaw($data)."\n";

// ========================================================================
// 5. JSON RENDER COMPACT (une seule ligne)
// ========================================================================

$console->line();
$console->info('5. JSON render compact (une seule ligne)');

$data = [
    'name' => 'Andy Defer',
    'age' => 30,
    'active' => true,
];

echo JsonViewer::renderCompact($data)."\n";

// ========================================================================
// 6. JSON AVEC PROFONDEUR LIMITÉE
// ========================================================================

$console->line();
$console->info('6. JSON avec profondeur limitée (maxDepth=2)');

$data = [
    'level1' => [
        'level2' => [
            'level3' => [
                'level4' => [
                    'level5' => 'valeur profonde',
                ],
            ],
        ],
    ],
];

echo JsonViewer::renderWithDepth($data, 2)."\n";

// ========================================================================
// 7. JSON AVEC TYPES DE DONNÉES MIXTES
// ========================================================================

$console->line();
$console->info('7. JSON avec types de données mixtes');

$data = [
    'string' => 'Hello World',
    'integer' => 42,
    'float' => 3.14159,
    'boolean_true' => true,
    'boolean_false' => false,
    'null' => null,
    'array' => [1, 2, 3, 4, 5],
    'nested_array' => [
        ['a' => 1, 'b' => 2],
        ['c' => 3, 'd' => 4],
    ],
    'empty_object' => (object) [],
    'empty_array' => [],
];

echo JsonViewer::render($data)."\n";

// ========================================================================
// 8. JSON DEPUIS UNE CHAÎNE
// ========================================================================

$console->line();
$console->info('8. JSON depuis une chaîne');

$jsonString = '{"name":"Andy Defer","age":30,"active":true,"skills":["PHP","JavaScript","Python"]}';

echo JsonViewer::render($jsonString)."\n";

// ========================================================================
// 9. JSON INVALIDE
// ========================================================================

$console->line();
$console->info('9. JSON invalide');

$invalidJson = '{name: "Andy", age: 30,}'; // JSON invalide

echo JsonViewer::render($invalidJson)."\n";

// ========================================================================
// 10. JSON DANS UNE KEYVALUE AVEC FORMATAGE
// ========================================================================

$console->line();
$console->info('10. JSON dans une KeyValue (formaté)');

$userData = [
    'role' => 'admin',
    'status' => 'active',
    'permissions' => ['read', 'write', 'delete'],
    'last_login' => '2026-06-26T14:30:00Z',
];

$console->keyValue([
    'Utilisateur' => 'Andy',
    'ID' => '12345',
    'Données JSON' => "\n".JsonViewer::render($userData),
    'Version' => '2.5.0',
]);

// ========================================================================
// 11. JSON AVEC TITRE
// ========================================================================

$console->line();
$console->info('11. JSON avec titre');

$apiResponse = [
    'status' => 'success',
    'code' => 200,
    'data' => [
        'user' => [
            'id' => 12345,
            'username' => 'jdupont',
            'email' => 'jean.dupont@example.com',
            'created_at' => '2024-01-15T08:30:00Z',
        ],
        'stats' => [
            'posts' => 42,
            'comments' => 128,
            'likes' => 512,
        ],
    ],
];

$console->title('📊 Réponse API');
$console->line();
echo JsonViewer::render($apiResponse)."\n";

// ========================================================================
// 12. COMPARAISON JSON / TABLEAU
// ========================================================================

$console->line();
$console->info('12. Comparaison : JSON formaté vs tableau');

$console->line();
$console->info('📋 Tableau (pour les données structurées)');
$console->table(
    ['ID', 'Nom', 'Rôle', 'Statut'],
    [
        [1, 'Andy', 'Admin', 'Actif'],
        [2, 'Bob', 'User', 'Inactif'],
        [3, 'Charlie', 'Guest', 'En attente'],
    ]
);

$console->line();
$console->info('📄 JSON (pour les données complexes)');
$users = [
    'users' => [
        ['id' => 1, 'name' => 'Andy', 'role' => 'admin', 'status' => 'active'],
        ['id' => 2, 'name' => 'Bob', 'role' => 'user', 'status' => 'inactive'],
        ['id' => 3, 'name' => 'Charlie', 'role' => 'guest', 'status' => 'pending'],
    ],
];
echo JsonViewer::render($users)."\n";

// ========================================================================
// 13. JSON AVEC DONNÉES DE CONFIGURATION
// ========================================================================

$console->line();
$console->info('13. Données de configuration');

$config = [
    'app' => [
        'name' => 'PHP Console Writer',
        'version' => '1.0.0',
        'environment' => 'production',
        'debug' => false,
    ],
    'database' => [
        'driver' => 'mysql',
        'host' => 'localhost',
        'port' => 3306,
        'database' => 'app',
        'username' => 'root',
        'password' => '••••••••',
    ],
    'cache' => [
        'driver' => 'redis',
        'host' => 'localhost',
        'port' => 6379,
        'ttl' => 3600,
    ],
];

$console->title('⚙️ Configuration');
$console->line();
echo JsonViewer::render($config)."\n";

// ========================================================================
// FIN
// ========================================================================

$console->line();
$console->success('✅ Tous les JSON ont été affichés avec succès !');
$console->render();
