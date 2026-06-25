<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Console;
use AndyDefer\ConsoleWriter\Console\Enums\BgColor;
use AndyDefer\ConsoleWriter\Console\Enums\FgColor;
use AndyDefer\ConsoleWriter\Console\Enums\ListStyle;
use AndyDefer\ConsoleWriter\Console\Enums\Options;
use AndyDefer\DomainStructures\Utils\ListCollection;
use AndyDefer\DomainStructures\Utils\MapCollection;

echo "\n";

// ========================================================================
// 1. CRÉATION DE LA CONSOLE
// ========================================================================

$console = new Console;

// ========================================================================
// 2. DÉMONSTRATION DES COMPOSANTS DE BASE
// ========================================================================

$console
    ->title('🎨 Console Writer - Démonstration Complète')
    ->line()
    ->info('Bienvenue dans la démonstration de tous les composants')
    ->line();

// ========================================================================
// 3. MESSAGES SIMPLES
// ========================================================================

$console
    ->info('1. Message d\'information (bleu)')
    ->success('2. Message de succès (vert)')
    ->error('3. Message d\'erreur (rouge avec fond)')
    ->alert('4. Alerte importante (encadrée jaune)')
    ->line();

// ========================================================================
// 4. TITRE
// ========================================================================

$console
    ->title('5. Titre encadré')
    ->line();

// ========================================================================
// 5. TABLEAU 4 COLONNES
// ========================================================================

$console
    ->info('6. Tableau 4 colonnes')
    ->table(
        ListCollection::from(['Service', 'Status', 'Port', 'Version']),
        ListCollection::from([
            ListCollection::from(['PHP-FPM', '✅ Running', '9000', '8.2.15']),
            ListCollection::from(['MySQL', '✅ Running', '3306', '8.0.35']),
            ListCollection::from(['Redis', '❌ Failed', '6379', '7.2.4']),
            ListCollection::from(['Nginx', '✅ Running', '80', '1.24.0']),
        ])
    )
    ->line();

// ========================================================================
// 6. TABLEAU 5 COLONNES
// ========================================================================

$console
    ->info('7. Tableau 5 colonnes')
    ->table(
        ListCollection::from(['Service', 'Status', 'Port', 'Version', 'Uptime']),
        ListCollection::from([
            ListCollection::from(['PHP-FPM', '✅ Running', '9000', '8.2.15', '72h']),
            ListCollection::from(['MySQL', '✅ Running', '3306', '8.0.35', '168h']),
            ListCollection::from(['Redis', '❌ Failed', '6379', '7.2.4', '0h']),
            ListCollection::from(['Nginx', '✅ Running', '80', '1.24.0', '720h']),
        ])
    )
    ->line();

// ========================================================================
// 7. TABLEAU 6 COLONNES
// ========================================================================

$console
    ->info('8. Tableau 6 colonnes')
    ->table(
        ListCollection::from(['Service', 'Status', 'Port', 'Version', 'Uptime', 'Memory']),
        ListCollection::from([
            ListCollection::from(['PHP-FPM', '✅ Running', '9000', '8.2.15', '72h', '128 MB']),
            ListCollection::from(['MySQL', '✅ Running', '3306', '8.0.35', '168h', '512 MB']),
            ListCollection::from(['Redis', '❌ Failed', '6379', '7.2.4', '0h', '256 MB']),
            ListCollection::from(['Nginx', '✅ Running', '80', '1.24.0', '720h', '64 MB']),
        ])
    )
    ->line();

// ========================================================================
// 8. TABLEAU 7 COLONNES
// ========================================================================

$console
    ->info('9. Tableau 7 colonnes')
    ->table(
        ListCollection::from(['Service', 'Status', 'Port', 'Version', 'Uptime', 'Memory', 'CPU']),
        ListCollection::from([
            ListCollection::from(['PHP-FPM', '✅ Running', '9000', '8.2.15', '72h', '128 MB', '5%']),
            ListCollection::from(['MySQL', '✅ Running', '3306', '8.0.35', '168h', '512 MB', '15%']),
            ListCollection::from(['Redis', '❌ Failed', '6379', '7.2.4', '0h', '256 MB', '0%']),
            ListCollection::from(['Nginx', '✅ Running', '80', '1.24.0', '720h', '64 MB', '2%']),
        ])
    )
    ->line();

// ========================================================================
// 9. TABLEAU AVEC LIST COLLECTION (3 colonnes)
// ========================================================================

$console
    ->info('10. Tableau avec ListCollection (3 colonnes)')
    ->table(
        ListCollection::from(['Product', 'Price', 'Stock']),
        ListCollection::from([
            ListCollection::from(['Laptop', '999.99', '15']),
            ListCollection::from(['Mouse', '29.99', '42']),
            ListCollection::from(['Keyboard', '79.99', '28']),
        ])
    )
    ->line();

// ========================================================================
// 10. TABLEAU AVEC ÉMOJIS (3 colonnes)
// ========================================================================

$console
    ->info('11. Tableau avec émojis (3 colonnes)')
    ->table(
        ListCollection::from(['✅ Status', '📊 Data', '📈 Trend']),
        ListCollection::from([
            ListCollection::from(['✅ OK', '📈 100%', '↑ 12%']),
            ListCollection::from(['❌ KO', '📉 50%', '↓ 8%']),
            ListCollection::from(['⚠️ WARN', '📊 75%', '→ 0%']),
        ])
    )
    ->line();

// ========================================================================
// 11. LIEN
// ========================================================================

$console
    ->info('12. Lien cliquable')
    ->link('https://github.com/andydefer/php-console-writer', '📦 Voir le projet sur GitHub')
    ->line();

// ========================================================================
// 12. LISTE À PUCES
// ========================================================================

$console
    ->info('13. Liste à puces')
    ->list(
        ['Authentification JWT', 'Validation des données', 'Logging avancé', 'Cache Redis'],
        ListStyle::CHECK
    )
    ->line();

// ========================================================================
// 13. LISTE NUMÉROTÉE
// ========================================================================

$console
    ->info('14. Liste numérotée')
    ->list(
        ['Installer les dépendances', 'Configurer l\'environnement', 'Lancer les migrations', 'Démarrer le serveur'],
        ListStyle::NUMBER
    )
    ->line();

// ========================================================================
// 14. LISTE AVEC FLÈCHE
// ========================================================================

$console
    ->info('15. Liste avec flèches')
    ->list(
        ['Étape 1 : Analyse', 'Étape 2 : Conception', 'Étape 3 : Développement', 'Étape 4 : Test'],
        ListStyle::ARROW
    )
    ->line();

// ========================================================================
// 15. LISTE COLORÉE
// ========================================================================

$console
    ->info('16. Liste colorée')
    ->listColored(
        ['✅ Tâche terminée', '✅ Tests passés', '✅ Déploiement réussi'],
        ListStyle::CHECK,
        'green'
    )
    ->listColored(
        ['❌ Échec du build', '❌ Erreur de compilation', '❌ Test échoué'],
        ListStyle::CROSS,
        'red'
    )
    ->line();

// ========================================================================
// 16. KEY VALUE (Clés => Valeurs)
// ========================================================================

$console
    ->info('17. Clés => Valeurs')
    ->keyValue(
        MapCollection::from([
            'Nom' => 'Jean Dupont',
            'Âge' => 42,
            'Ville' => 'Paris 🇫🇷',
            'Email' => 'jean@example.com',
            'Status' => '✅ Actif',
        ])
    )
    ->line();

// ========================================================================
// 17. KEY VALUE AVEC COULEUR
// ========================================================================

$console
    ->info('18. Clés => Valeurs avec couleur jaune')
    ->keyValueWithColor(
        MapCollection::from([
            'CPU' => '45%',
            'RAM' => '8.2 Go',
            'DISQUE' => '256 Go',
            'RÉSEAU' => '1.2 Gbps',
            'UPTIME' => '72h 34m',
        ]),
        'yellow'
    )
    ->line();

// ========================================================================
// 18. KEY VALUE AVEC VALEURS COLORÉES
// ========================================================================

$console
    ->info('19. Clés => Valeurs avec valeurs vertes')
    ->keyValueWithValueColor(
        MapCollection::from([
            'Service' => 'PHP-FPM',
            'Status' => '✅ Running',
            'Port' => '9000',
            'Memory' => '128 MB',
        ]),
        'green'
    )
    ->line();

// ========================================================================
// 19. KEY VALUE AVEC SÉPARATEUR PERSONNALISÉ
// ========================================================================

$console
    ->info('20. Clés => Valeurs avec séparateur →')
    ->keyValueWithSeparator(
        MapCollection::from([
            'Utilisateur' => 'admin',
            'Rôle' => 'Administrateur',
            'Dernière connexion' => '2026-06-25 14:30:00',
            'IP' => '192.168.1.100',
        ]),
        ' → '
    )
    ->line();

// ========================================================================
// 20. KEY VALUE AVEC DONNÉES MIXTES
// ========================================================================

$console
    ->info('21. Clés => Valeurs avec types mixtes')
    ->keyValue(
        MapCollection::from([
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
        ])
    )
    ->line();

// ========================================================================
// 21. KEY VALUE AVEC LONGUES CLÉS
// ========================================================================

$console
    ->info('22. Clés => Valeurs avec longues clés')
    ->keyValue(
        MapCollection::from([
            'Nom d\'utilisateur' => 'jdupont',
            'Dernière connexion' => '2026-06-25 14:30:00',
            'Adresse IP' => '192.168.1.100',
            'Permissions' => 'Administrateur',
            'Dossier personnel' => '/home/jdupont',
            'Date de création' => '2024-01-15',
        ])
    )
    ->line();

// ========================================================================
// 22. LIGNES PERSONNALISÉES
// ========================================================================

$console
    ->info('23. Lignes personnalisées')
    ->line('─────────────────────────────────────────────────')
    ->line('  Ceci est une ligne personnalisée sans style')
    ->line('  Avec des '.'<fg=yellow>couleurs</fg=yellow> '.'<fg=green>intégrées</fg=green>')
    ->line('─────────────────────────────────────────────────')
    ->line();

// ========================================================================
// 23. UTILISATION AVANCÉE DU SERVICE ANSI
// ========================================================================

$console
    ->info('24. Utilisation avancée du service ANSI')
    ->line();

$ansi = $console->getAnsiConverter();

echo $ansi->color('▶  Texte en rouge', 'red')."\n";
echo $ansi->color('▶  Texte en vert', 'green')."\n";
echo $ansi->color('▶  Texte en gras', 'bold')."\n";
echo $ansi->color('▶  Texte en cyan', 'cyan')."\n";

echo $ansi->bgColor('▶  Fond rouge', 'red')."\n";
echo $ansi->bgColor('▶  Fond vert', 'green')."\n";
echo $ansi->bgColor('▶  Fond jaune', 'yellow')."\n";

echo $ansi->style(
    '▶  Texte vert gras souligné',
    FgColor::GREEN,
    null,
    Options::BOLD,
    Options::UNDERLINE
)."\n";

echo $ansi->style(
    '▶  Texte blanc sur fond bleu en gras',
    FgColor::WHITE,
    BgColor::BLUE,
    Options::BOLD
)."\n";

echo $ansi->style(
    '▶  Texte jaune sur fond noir en italique',
    FgColor::YELLOW,
    BgColor::BLACK,
    Options::ITALIC
)."\n";

echo $ansi->convert('<fg=cyan><options=bold>▶  Balises Symfony converties en ANSI</options=bold></fg=cyan>')."\n";
echo "\n";

// ========================================================================
// 24. TABLEAU 8 COLONNES (cas extrême)
// ========================================================================

$console
    ->info('25. Tableau 8 colonnes (cas extrême)')
    ->adaptiveTable(
        ListCollection::from(['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H']),
        ListCollection::from([
            ListCollection::from(['1', '2', '3', '4', '5', '6', '7', '8']),
            ListCollection::from(['9', '10', '11', '12', '13', '14', '15', '16']),
            ListCollection::from(['17', '18', '19', '20', '21', '22', '23', '24']),
        ])
    )
    ->line();

// ========================================================================
// 25. TABLEAU AVEC TEXTE TRÈS LONG
// ========================================================================

$console
    ->info('26. Tableau avec texte très long (6 colonnes)')
    ->adaptiveTable(
        ListCollection::from(['ID', 'Name', 'Description', 'Category', 'Price', 'Stock']),
        ListCollection::from([
            ListCollection::from(['1', 'Laptop Pro', 'High-performance laptop with 16GB RAM and 512GB SSD', 'Electronics', '1299.99', '25']),
            ListCollection::from(['2', 'Wireless Mouse', 'Ergonomic wireless mouse with Bluetooth 5.0', 'Accessories', '29.99', '100']),
            ListCollection::from(['3', 'USB-C Hub', '7-in-1 USB-C hub with HDMI, Ethernet and USB 3.0', 'Accessories', '49.99', '50']),
        ])
    )
    ->line();

// ========================================================================
// 26. DASHBOARD COMPLET
// ========================================================================

$console
    ->title('📊 Dashboard Système')
    ->line()
    ->keyValueWithValueColor(
        MapCollection::from([
            'Serveur' => 'Production - Web01',
            'PHP' => '8.2.15',
            'MySQL' => '8.0.35',
            'Redis' => '7.2.4',
            'Nginx' => '1.24.0',
            'Uptime' => '72h 34m 12s',
            'Charge CPU' => '45%',
            'Mémoire' => '8.2 / 16.0 Go',
            'Disque' => '256 / 512 Go',
            'Requêtes/s' => '1 234',
        ]),
        'green'
    )
    ->line()
    ->table(
        ['Endpoint', 'Méthode', 'Status', 'Temps'],
        [
            ['/api/users', 'GET', '✅ 200', '45ms'],
            ['/api/posts', 'POST', '✅ 201', '78ms'],
            ['/api/comments', 'GET', '❌ 500', '120ms'],
            ['/api/auth', 'POST', '✅ 200', '32ms'],
        ]
    )
    ->line()
    ->listColored(
        ['Services en ligne : PHP-FPM, MySQL, Nginx'],
        ListStyle::CHECK,
        'green'
    )
    ->listColored(
        ['Services hors ligne : Redis'],
        ListStyle::CROSS,
        'red'
    )
    ->line()
    ->alert('⚠️  Redis est hors ligne. Vérifiez la configuration.')
    ->line()
    ->success('✅ Dashboard chargé avec succès !')
    ->line()
    ->info('Fin de la démonstration')
    ->render();

echo "\n";
