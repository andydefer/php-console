# PHP Console

**Un package d'écriture console élégant et fluide pour PHP avec des composants stylisés.**

[![PHP Version](https://img.shields.io/badge/PHP-8.0%2B-blue)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green)](LICENSE)

---

## Table des matières

1. [Installation](#installation)
2. [Concepts fondamentaux](#concepts-fondamentaux)
3. [Démarrage rapide](#démarrage-rapide)
4. [Messages stylisés](#messages-stylisés)
5. [Tableaux](#tableaux)
6. [Listes](#listes)
7. [Clés → Valeurs](#clés--valeurs)
8. [Liens](#liens)
9. [Buffer et affichage différé](#buffer-et-affichage-différé)
10. [Service ANSI avancé](#service-ansi-avancé)
11. [Référence complète des méthodes](#référence-complète-des-méthodes)
12. [Exemples complets](#exemples-complets)
13. [Licence](#licence)

---

## Installation

```bash
composer require andy-defer/php-console-writer
```

### Prérequis

- PHP 8.0 ou supérieur
- Dépendance : `andydefer/php-vo: ^0.10.0`

---

### Principe clé

Tout est conçu pour le **chaînage fluide** :

```php
$console
    ->title('Dashboard')
    ->line()
    ->info('Chargement...')
    ->success('Terminé !')
    ->render();
```

---

## Démarrage rapide

```php
<?php

require_once 'vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Console;

$console = new Console();

$console
    ->title('🎨 Mon Application')
    ->line()
    ->info('Bienvenue dans la démonstration')
    ->success('✅ Opération réussie !')
    ->error('❌ Erreur de connexion')
    ->alert('⚠️  Attention, une action est requise')
    ->line()
    ->link('https://github.com', 'Voir le projet')
    ->render();
```

---

## Messages stylisés

### Types de messages

```php
// Information (bleu)
$console->info('Chargement en cours...');

// Succès (vert)
$console->success('✅ Opération terminée avec succès');

// Erreur (rouge avec fond)
$console->error('❌ Erreur : impossible de se connecter');

// Alerte (encadrée jaune)
$console->alert('⚠️  Redis est hors ligne !');

// Titre (encadré cyan gras)
$console->title('📊 Dashboard Système');
```

### Méthodes avancées d'Alert

```php
use AndyDefer\ConsoleWriter\Console\Components\Alert;

// Padding personnalisé
Alert::renderWithPadding('Important message', 8);

// Icône personnalisée
Alert::renderWithIcon('Success!', '✅', 4);

// Couleur personnalisée
Alert::renderWithColor('Warning!', 'red', 4);

// Bordure personnalisée
Alert::renderWithBorder('Important!', '=', 'cyan', 4);
```

### Méthodes avancées de Title

```php
use AndyDefer\ConsoleWriter\Console\Components\Title;

// Titre avec padding automatique
Title::render('📊 Dashboard');

// Padding personnalisé
Title::renderWithPadding('Dashboard', 8);

// Largeur fixe
Title::renderWithWidth('Dashboard', 40);

// Bordure personnalisée
Title::renderWithBorder('Dashboard', '─', 4);
```

---

## Tableaux

### Tableau basique (4 colonnes)

```php
$console->table(
    ['Service', 'Status', 'Port', 'Version'],
    [
        ['PHP-FPM', '✅ Running', '9000', '8.2.15'],
        ['MySQL', '✅ Running', '3306', '8.0.35'],
        ['Redis', '❌ Failed', '6379', '7.2.4'],
        ['Nginx', '✅ Running', '80', '1.24.0'],
    ]
);
```

**Résultat :**
```
┌────────────────────────────────────────────────┐
│  Service   │  Status      │  Port  │  Version  │
├────────────────────────────────────────────────┤
│  PHP-FPM   │  ✅ Running  │  9000  │  8.2.15   │
│  MySQL     │  ✅ Running  │  3306  │  8.0.35   │
│  Redis     │  ❌ Failed   │  6379  │  7.2.4    │
│  Nginx     │  ✅ Running  │   80   │  1.24.0   │
└────────────────────────────────────────────────┘
```

### Tableau avec ListCollection

```php
use AndyDefer\DomainStructures\Utils\ListCollection;

$headers = ListCollection::from(['Product', 'Price', 'Stock']);
$rows = ListCollection::from([
    ListCollection::from(['Laptop', '999.99', '15']),
    ListCollection::from(['Mouse', '29.99', '42']),
]);

$console->table($headers, $rows);
```

### Tableau adaptatif (> 5 colonnes → liste automatique)

```php
$console->adaptiveTable(
    ['ID', 'Name', 'Description', 'Category', 'Price', 'Stock'],
    [
        ['1', 'Laptop Pro', 'High-performance laptop with 16GB RAM', 'Electronics', '1299.99', '25'],
        ['2', 'Wireless Mouse', 'Ergonomic wireless mouse with Bluetooth 5.0', 'Accessories', '29.99', '100'],
    ]
);
```

**Résultat :**
```
📋 6 colonnes → affichage en liste

┌─ Item #1 ──────────────────────────────────────────────
  ID          : 1
  Name        : Laptop Pro
  Description : High-performance laptop with 16GB RAM
  Category    : Electronics
  Price       : 1299.99
  Stock       : 25
└────────────────────────────────────────────────────────

┌─ Item #2 ──────────────────────────────────────────────
  ID          : 2
  Name        : Wireless Mouse
  Description : Ergonomic wireless mouse with Bluetooth 5.0
  Category    : Accessories
  Price       : 29.99
  Stock       : 100
└────────────────────────────────────────────────────────
```

### Forcer l'affichage en liste ou en tableau

```php
// Forcer la liste (même avec peu de colonnes)
$console->tableAsList(
    ['Name', 'Email', 'Role'],
    [
        ['John Doe', 'john@example.com', 'Admin'],
        ['Jane Smith', 'jane@example.com', 'User'],
    ]
);

// Forcer le tableau (même avec beaucoup de colonnes)
$console->tableForced(
    ['A', 'B', 'C', 'D', 'E', 'F'],
    [
        ['1', '2', '3', '4', '5', '6'],
    ]
);
```

### TableList - Options avancées

```php
use AndyDefer\ConsoleWriter\Console\Components\TableList;

// Avec titre personnalisé
TableList::renderWithTitle($headers, $rows, '📦 Produits en stock');

// Avec couleur personnalisée
TableList::renderWithColor($headers, $rows, 'yellow');

// Version compacte (sans bordures)
TableList::renderCompact($headers, $rows);
```

---

## Listes

### Styles disponibles

```php
use AndyDefer\ConsoleWriter\Console\Enums\ListStyle;

$items = ['Item 1', 'Item 2', 'Item 3'];

// Puces (•)
$console->list($items, ListStyle::BULLET);
// • Item 1
// • Item 2

// Flèches (→)
$console->list($items, ListStyle::ARROW);
// → Item 1
// → Item 2

// Tiret (—)
$console->list($items, ListStyle::DASH);
// — Item 1
// — Item 2

// Numérotée (1.)
$console->list($items, ListStyle::NUMBER);
// 1. Item 1
// 2. Item 2

// Alphabétique (a.)
$console->list($items, ListStyle::ALPHA);
// a. Item 1
// b. Item 2

// Romain (i.)
$console->list($items, ListStyle::ROMAN);
// i. Item 1
// ii. Item 2

// Check (✓)
$console->list($items, ListStyle::CHECK);
// ✓ Item 1
// ✓ Item 2

// Croix (✗)
$console->list($items, ListStyle::CROSS);
// ✗ Item 1
// ✗ Item 2

// Étoile (★)
$console->list($items, ListStyle::STAR);
// ★ Item 1
// ★ Item 2
```

### Liste colorée

```php
$console->listColored(
    ['✅ Tâche terminée', '✅ Tests passés', '✅ Déploiement réussi'],
    ListStyle::CHECK,
    'green'
);

$console->listColored(
    ['❌ Échec du build', '❌ Erreur de compilation'],
    ListStyle::CROSS,
    'red'
);
```

**Résultat :**
```
✓ Tâche terminée    (en vert)
✓ Tests passés      (en vert)
✓ Déploiement réussi (en vert)
✗ Échec du build    (en rouge)
✗ Erreur de compilation (en rouge)
```

### Liste avec indentation

```php
$console->list(
    ['Sous-item 1', 'Sous-item 2'],
    ListStyle::BULLET,
    2  // Indentation de 2 niveaux
);
```

---

## Clés → Valeurs

### KeyValue basique

```php
$console->keyValue([
    'Nom' => 'Jean Dupont',
    'Âge' => 42,
    'Ville' => 'Paris 🇫🇷',
    'Email' => 'jean@example.com',
    'Status' => '✅ Actif',
]);
```

**Résultat :**
```
Nom    : Jean Dupont
Âge    : 42
Ville  : Paris 🇫🇷
Email  : jean@example.com
Status : ✅ Actif
```

### KeyValue avec couleurs

```php
// Clés en jaune
$console->keyValueWithColor(
    ['CPU' => '45%', 'RAM' => '8.2 Go', 'DISQUE' => '256 Go'],
    'yellow'
);

// Valeurs en vert
$console->keyValueWithValueColor(
    ['Service' => 'PHP-FPM', 'Status' => '✅ Running', 'Port' => '9000'],
    'green'
);
```

### KeyValue avec séparateur personnalisé

```php
$console->keyValueWithSeparator(
    [
        'Utilisateur' => 'admin',
        'Rôle' => 'Administrateur',
        'Dernière connexion' => '2026-06-25 14:30:00',
    ],
    ' → '
);
```

**Résultat :**
```
Utilisateur → admin
Rôle → Administrateur
Dernière connexion → 2026-06-25 14:30:00
```

### KeyValue avec indentation

```php
$console->keyValue(
    ['Name' => 'John'],
    2  // Indentation de 2 niveaux
);
```

### Types de données supportés

```php
$console->keyValue([
    'String' => 'Hello World',
    'Integer' => 42,
    'Boolean' => true,
    'Null' => null,
    'Float' => 3.14159,
    'Array' => ['a', 'b', 'c'],
    'Object' => new class {
        public function __toString(): string {
            return 'Custom object';
        }
    },
]);
```

---

## Liens

```php
// Lien avec l'URL comme texte
$console->link('https://github.com/andydefer/php-console-writer');

// Lien avec texte personnalisé
$console->link('https://github.com', '📦 Voir le projet sur GitHub');

// Lien dans un chaînage
$console
    ->info('Visitez notre site web :')
    ->link('https://example.com', 'Cliquez ici')
    ->line();
```

---

## Buffer et affichage différé

### Stockage et affichage différé

```php
$console
    ->startBuffer()  // Démarrer le buffer
    ->info('Ligne 1')
    ->info('Ligne 2')
    ->info('Ligne 3')
    ->render();       // Affiche tout d'un coup
```

### Méthodes de gestion du buffer

```php
// Démarrer le buffer
$console->startBuffer();

// Ajouter des lignes
$console->info('Test 1');
$console->info('Test 2');

// Récupérer les lignes sans afficher
$lines = $console->getLines(); // ['Test 1', 'Test 2']

// Vider le buffer sans afficher
$console->clear();

// Afficher et vider le buffer
$console->render();

// Vérifier si le buffer est actif
$isBuffered = $console->isBuffered(); // bool
```

### Exemple avec plusieurs buffers

```php
$console
    ->startBuffer()
    ->info('Batch 1')
    ->render()      // Affiche "Batch 1"
    ->startBuffer()
    ->info('Batch 2')
    ->render();     // Affiche "Batch 2"
```

---

## Service ANSI avancé

### Accès au service

```php
$ansi = $console->getAnsiConverter();
```

### Méthodes du service

```php
use AndyDefer\ConsoleWriter\Console\Enums\FgColor;
use AndyDefer\ConsoleWriter\Console\Enums\BgColor;
use AndyDefer\ConsoleWriter\Console\Enums\Options;

// Colorer un texte (style fonction color())
echo $ansi->color('Texte en rouge', 'red');
echo $ansi->color('Texte en vert', 'green');
echo $ansi->color('Texte en gras', 'bold');

// Colorer un fond
echo $ansi->bgColor('Fond rouge', 'red');
echo $ansi->bgColor('Fond vert', 'green');

// Utilisation avec enum
echo $ansi->colorEnum('Texte en cyan', FgColor::CYAN);
echo $ansi->bgColorEnum('Fond jaune', BgColor::YELLOW);

// Options
echo $ansi->option('Texte en gras', Options::BOLD);
echo $ansi->option('Texte souligné', Options::UNDERLINE);

// Style combiné
echo $ansi->style(
    'Texte vert gras souligné',
    FgColor::GREEN,
    null,
    Options::BOLD,
    Options::UNDERLINE
);

// Conversion de balises
echo $ansi->convert('<fg=green><options=bold>Hello World</options=bold></fg=green>');

// Réinitialisation
echo $ansi->reset();

// Suppression des balises
$plain = $ansi->stripTags('<fg=green>Hello</fg=green>'); // 'Hello'
```

### Utilisation directe dans Console

```php
$console->ansi('<fg=green><options=bold>▶  Balises Symfony converties en ANSI</options=bold></fg=green>');
```

---

## Exemples complets

### Exemple 1 : Dashboard système

```php
<?php

require_once 'vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Console;
use AndyDefer\ConsoleWriter\Console\Enums\ListStyle;
use AndyDefer\DomainStructures\Utils\MapCollection;

$console = new Console();

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
        ['Service', 'Status', 'Port', 'Version'],
        [
            ['PHP-FPM', '✅ Running', '9000', '8.2.15'],
            ['MySQL', '✅ Running', '3306', '8.0.35'],
            ['Redis', '❌ Failed', '6379', '7.2.4'],
            ['Nginx', '✅ Running', '80', '1.24.0'],
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
    ->render();
```

### Exemple 2 : Déploiement d'application

```php
<?php

require_once 'vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Console;
use AndyDefer\ConsoleWriter\Console\Enums\ListStyle;
use AndyDefer\DomainStructures\Utils\ListCollection;

$console = new Console();

$console
    ->title('🚀 Déploiement v2.5.0')
    ->line()
    ->info('Démarrage du déploiement...')
    ->line()
    ->info('Étape 1/5 : Téléchargement des sources')
    ->success('✅ Sources téléchargées (2.4 MB)')
    ->line()
    ->info('Étape 2/5 : Compilation des assets')
    ->success('✅ Assets compilés (12 fichiers)')
    ->line()
    ->info('Étape 3/5 : Migration de la base de données')
    ->success('✅ Migrations exécutées (4 migrations)')
    ->line()
    ->list(
        ['Nouvelle fonctionnalité : Authentication JWT'],
        ListStyle::CHECK
    )
    ->list(
        ['Correction bug #1234 : Erreur de validation'],
        ListStyle::CHECK
    )
    ->list(
        ['Optimisation : Cache Redis'],
        ListStyle::CHECK
    )
    ->line()
    ->info('Étape 4/5 : Redémarrage des services')
    ->success('✅ Services redémarrés')
    ->line()
    ->info('Étape 5/5 : Vérification post-déploiement')
    ->table(
        ['Service', 'Status', 'Response Time'],
        [
            ['API Gateway', '✅ OK', '45ms'],
            ['Auth Service', '✅ OK', '32ms'],
            ['Database', '✅ OK', '12ms'],
            ['Cache', '✅ OK', '3ms'],
        ]
    )
    ->line()
    ->success('🎉 Déploiement terminé avec succès !')
    ->line()
    ->link('https://app.example.com', '🌐 Accéder à l\'application')
    ->render();
```

### Exemple 3 : Monitoring avec buffer

```php
<?php

require_once 'vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Console;
use AndyDefer\DomainStructures\Utils\MapCollection;

$console = new Console();

// Collecter toutes les données avant d'afficher
$console
    ->startBuffer()
    ->title('📈 Monitoring - ' . date('Y-m-d H:i:s'))
    ->line()
    ->info('Collecte des données en cours...')
    ->line()
    ->keyValueWithValueColor(
        MapCollection::from([
            'CPU' => '45%',
            'RAM' => '8.2 / 16.0 Go',
            'DISQUE' => '256 / 512 Go',
            'UPTIME' => '72h 34m',
            'SERVICES' => '4/5 OK',
            'REQUÊTES' => '1 234 /s',
            'ERREURS' => '12 / min',
        ]),
        'green'
    )
    ->line()
    ->table(
        ['Service', 'Status', 'Uptime'],
        [
            ['PHP-FPM', '✅ OK', '72h 34m'],
            ['MySQL', '✅ OK', '72h 34m'],
            ['Redis', '❌ KO', '0h'],
            ['Nginx', '✅ OK', '72h 34m'],
            ['RabbitMQ', '✅ OK', '72h 34m'],
        ]
    )
    ->line()
    ->alert('⚠️  Redis est hors ligne depuis 2h 15m')
    ->line()
    ->success('✅ Monitoring terminé')
    ->render();
```

### Exemple 4 : Rapport utilisateur

```php
<?php

require_once 'vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Console;
use AndyDefer\ConsoleWriter\Console\Enums\ListStyle;
use AndyDefer\DomainStructures\Utils\MapCollection;

$console = new Console();

$console
    ->title('👤 Profil Utilisateur')
    ->line()
    ->keyValueWithColor(
        MapCollection::from([
            'ID' => 'USR-001',
            'Nom' => 'Jean Dupont',
            'Email' => 'jean@example.com',
            'Rôle' => 'Administrateur',
            'Inscrit depuis' => '2024-01-15',
            'Dernière connexion' => '2026-06-25 14:30:00',
            'Statut' => '✅ Actif',
        ]),
        'yellow'
    )
    ->line()
    ->info('📋 Activités récentes')
    ->list(
        [
            '🔹 Connexion à l\'application (14:30:00)',
            '🔹 Modification du mot de passe (14:15:00)',
            '🔹 Consultation du rapport (13:45:00)',
            '🔹 Export des données (13:30:00)',
        ],
        ListStyle::BULLET
    )
    ->line()
    ->info('📊 Statistiques')
    ->keyValueWithValueColor(
        MapCollection::from([
            'Total des connexions' => '847',
            'Moyenne de connexions/semaine' => '12',
            'Dernière activité' => 'il y a 2 minutes',
            'Temps de session moyen' => '2h 15m',
        ]),
        'green'
    )
    ->line()
    ->success('✅ Rapport généré avec succès')
    ->render();
```

---

## Licence

MIT © [Andy Defer](https://github.com/andydefer)