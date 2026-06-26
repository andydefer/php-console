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
9. [Badges](#badges)
10. [Métriques](#métriques)
11. [Colonnes](#colonnes)
12. [Timeline](#timeline)
13. [Arborescence (Tree)](#arborescence-tree)
14. [JSON Viewer](#json-viewer)
15. [Barre de progression](#barre-de-progression)
16. [Spinner](#spinner)
17. [Logger](#logger)
18. [Notifications](#notifications)
19. [Sons](#sons)
20. [Saisies utilisateur](#saisies-utilisateur)
21. [Buffer et affichage différé](#buffer-et-affichage-différé)
22. [VirtualTerminalService](#virtualterminalservice)
23. [Exemples complets](#exemples-complets)
24. [Licence](#licence)

---

## Installation

```bash
composer require andy-defer/php-console-writer
```

### Prérequis

- PHP 8.0 ou supérieur
- Dépendance : `andydefer/php-vo: ^0.10.0`

---

## Concepts fondamentaux

### Architecture

Le package repose sur une architecture fluide en couches :

```
Console (API principale)
    ├── Renderable (Messages, Titres, Alertes)
    ├── StyledComponents (Badge, Metric, Columns, Timeline, Tree, JSON)
    ├── Interactive (Ask, Confirm, Choice, Suggest, Number, MultiChoice)
    ├── Progress (ProgressBar, Spinner)
    └── System (Logger, Notification, Sound)
```

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
    ->badgeSuccess('OK')
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

### Rendu

```
ℹ️  Chargement en cours...
✅ Opération terminée avec succès
 ERROR  ❌ Erreur : impossible de se connecter
┌─────────────────────────────────────┐
│  ⚠️  Redis est hors ligne !         │
└─────────────────────────────────────┘
╔══════════════════════════╗
║   📊 Dashboard Système   ║
╚══════════════════════════╝
```

---

## Tableaux

### Tableau basique

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

### Tableau adaptatif (> 5 colonnes → liste automatique)

```php
$console->adaptiveTable(
    ['ID', 'Name', 'Description', 'Category', 'Price', 'Stock'],
    [
        ['1', 'Laptop Pro', 'High-performance laptop', 'Electronics', '1299.99', '25'],
        ['2', 'Wireless Mouse', 'Ergonomic wireless mouse', 'Accessories', '29.99', '100'],
    ]
);
```

### Rendu

```
┌────────────────────────────────────────────────┐
│  Service   │  Status      │  Port  │  Version  │
├────────────────────────────────────────────────┤
│  PHP-FPM   │  ✅ Running  │  9000  │  8.2.15   │
│  MySQL     │  ✅ Running  │  3306  │  8.0.35   │
│  Redis     │  ❌ Failed   │  6379  │  7.2.4    │
│  Nginx     │  ✅ Running  │   80   │  1.24.0   │
└────────────────────────────────────────────────┘

📋 6 colonnes → affichage en liste

┌─ Item #1 ──────────────────────────────────────────────
  ID          : 1
  Name        : Laptop Pro
  Description : High-performance laptop
  Category    : Electronics
  Price       : 1299.99
  Stock       : 25
└────────────────────────────────────────────────────────
```

---

## Listes

### Types de listes

```php
use AndyDefer\ConsoleWriter\Console\Enums\ListStyle;

$items = ['Item 1', 'Item 2', 'Item 3'];

$console->list($items, ListStyle::BULLET);   // Puces
$console->list($items, ListStyle::ARROW);    // Flèches
$console->list($items, ListStyle::NUMBER);   // Numérotée
$console->list($items, ListStyle::CHECK);    // ✓
$console->list($items, ListStyle::STAR);     // ★

// Liste colorée
$console->listColored(
    ['✅ Tâche terminée', '✅ Tests passés'],
    ListStyle::CHECK,
    'green'
);
```

### Rendu

```
• Item 1
• Item 2
• Item 3

→ Item 1
→ Item 2
→ Item 3

1. Item 1
2. Item 2
3. Item 3

✓ Item 1
✓ Item 2
✓ Item 3

★ Item 1
★ Item 2
★ Item 3

✓ Tâche terminée    (en vert)
✓ Tests passés      (en vert)
```

---

## Clés → Valeurs

```php
$console->keyValue([
    'Nom' => 'Jean Dupont',
    'Âge' => 42,
    'Ville' => 'Paris 🇫🇷',
]);

$console->keyValueWithColor(['CPU' => '45%'], 'yellow');
$console->keyValueWithValueColor(['Status' => 'OK'], 'green');
$console->keyValueWithSeparator(['Nom' => 'Jean'], ' → ');
```

### Rendu

```
Nom    : Jean Dupont
Âge    : 42
Ville  : Paris 🇫🇷

CPU    : 45%    (clés en jaune)

Status : OK     (valeurs en vert)

Nom → Jean      (séparateur personnalisé)
```

---

## Liens

```php
$console->link('https://github.com/andydefer/php-console-writer');
$console->link('https://github.com', '📦 Voir le projet sur GitHub');
```

### Rendu

```
https://github.com/andydefer/php-console-writer

📦 Voir le projet sur GitHub
```

---

## Badges

```php
$console->badge('SUCCESS', 'success');
$console->badge('FAILED', 'danger');
$console->badge('PENDING', 'warning');
$console->badgeWithIcon('SUCCESS', '🟢', 'success');

$console->badgeSuccess('OK');
$console->badgeDanger('KO');
$console->badgeWarning('WARN');
$console->badgeInfo('INFO');
```

### Rendu

```
[SUCCESS]   (vert)
[FAILED]    (rouge)
[PENDING]   (jaune)

🟢 [SUCCESS]  (vert avec icône)
🟢 [OK]       (succès)
🔴 [KO]       (danger)
🟡 [WARN]     (avertissement)
🔵 [INFO]     (info)
```

---

## Métriques

```php
$console->metric('CPU', '45%', 'yellow');
$console->metricWithIcon('RAM', '8.2 GB', '💾', 'green');
$console->metricWithTrend('CPU', '45%', '↑ 5%', 'green');
$console->metricInline('Uptime', '72h', 'cyan');
```

### Rendu

```
CPU
45%

💾 RAM
8.2 GB

CPU
45% ↑ 5%

Uptime: 72h
```

---

## Colonnes

```php
$console->columns([
    ['Users', '123'],
    ['Servers', '5'],
    ['Logs', '42']
]);

$console->columnsWithColors($columns, ['cyan', 'green', 'yellow']);
$console->columnsWithHeaders($columns);
$console->columnsCompact($columns);
```

### Rendu

```
  Users        Servers        Logs
   123           5             42

  Users        Servers        Logs   (cyan, green, yellow)
   123           5             42

┌─────────┬──────────┬─────────┐
│ Users   │ Servers  │ Logs    │
├─────────┼──────────┼─────────┤
│ 123     │ 5        │ 42      │
└─────────┴──────────┴─────────┘

Users    Servers    Logs
123      5          42
```

---

## Timeline

```php
$console->timeline([
    ['12:00', 'Application démarrée', 'Service web initialisé'],
    ['12:01', 'Connexion DB', 'Connexion établie en 45ms'],
    ['12:02', 'Serveur prêt', 'En attente des requêtes'],
]);

$console->timelineWithStatus($events, ['success', 'warning', 'error']);
```

### Rendu

```
  ● 12:00      Application démarrée
    Service web initialisé
  │
  ● 12:01      Connexion DB
    Connexion établie en 45ms
  │
  ● 12:02      Serveur prêt
    En attente des requêtes

  ✅ 12:00      Application démarrée   (succès)
  ⚠️ 12:01      Connexion DB           (warning)
  ❌ 12:02      Serveur prêt           (error)
```

---

## Arborescence (Tree)

```php
use AndyDefer\DomainStructures\Utils\MapCollection;

$tree = MapCollection::from([
    'src' => MapCollection::from([
        'Console' => MapCollection::from([
            'Components' => MapCollection::from([
                'Table.php' => MapCollection::from([]),
                'Tree.php' => MapCollection::from([]),
            ]),
        ]),
    ]),
]);

$console->tree($tree, '📦 Project');
$console->treeWithColors($tree, '📦 Project', 'cyan', 'white');
$console->treeWithIcons($tree, '📦 Project', '📂', '📄');

// À partir de chemins
$paths = SetCollection::from([
    'src/Console/Components',
    'src/Console/Services',
    'tests/Unit',
]);

$console->treeFromPaths($paths, '📁 Project');
```

### Rendu

```
📦 Project
├─ src
│  ├─ Console
│  │  ├─ Components
│  │  │  ├─ Table.php
│  │  │  └─ Tree.php
│  │  └─ Services
│  └─ Contracts
└─ tests
   └─ Unit

📦 Project
├─ 📂 src
│  ├─ 📂 Console
│  │  ├─ 📂 Components
│  │  │  ├─ 📄 Table.php
│  │  │  └─ 📄 Tree.php
│  │  └─ 📂 Services
│  └─ 📂 Contracts
└─ 📂 tests
   └─ 📂 Unit
```

---

## JSON Viewer

```php
$data = ['user' => ['id' => 1, 'name' => 'Andy', 'active' => true]];

$console->json($data);
$console->jsonCompact($data);
```

### Rendu

```
"user": {
  "id": 1,       (jaune)
  "name": "Andy", (vert)
  "active": true  (magenta)
}

{"user":{"id":1,"name":"Andy","active":true}}
```

---

## Barre de progression

```php
$console->progressBar(100, 40, '📦 Téléchargement');

for ($i = 0; $i < 100; $i++) {
    usleep(30000);
    $console->advance();
}

$console->finish();

$console->progressBarStyled(50, 'processing', 40);
```

### Rendu

```
📦 Téléchargement [████████████████████████████████████████] 100%
⚙️  Processing    [██████████████████████████████░░░░░░░]  70%
```

---

## Spinner

```php
$console->spinner('Connexion à Redis...', function($spinner) {
    sleep(3);
    $spinner->success('Connecté');
});

$console->spinnerWait('En attente du service...', function() {
    return $service->isReady();
});
```

### Rendu

```
⠋ Connexion à Redis...
⠙ Connexion à Redis...
⠹ Connexion à Redis...
✅ Connecté

⏳ En attente du service...
✅ Service prêt
```

---

## Logger

```php
$console->logInfo('Chargement...');
$console->logSuccess('✅ Terminé');
$console->logError('❌ Erreur');
$console->logWarning('⚠️ Attention');
$console->logDebug('Debug info');
```

### Rendu

```
[14:30:00] INFO     - Chargement...
[14:30:01] SUCCESS  - ✅ Terminé
[14:30:02] ERROR    - ❌ Erreur
[14:30:03] WARNING  - ⚠️ Attention
[14:30:04] DEBUG    - Debug info
```

---

## Notifications

```php
$console->notifySuccess('Déploiement réussi');
$console->notifyError('Erreur critique');
$console->notifyWarning('Cache à nettoyer');
$console->notifyInfo('Nouvelle mise à jour');
```

### Rendu

```
🔔 Déploiement réussi   (vert)
🔔 Erreur critique      (rouge)
🔔 Cache à nettoyer     (jaune)
🔔 Nouvelle mise à jour (bleu)
```

---

## Saisies utilisateur

### Ask - Saisie simple

```php
$name = $console->ask('Quel est votre nom ?');
$city = $console->ask('Ville ?', 'Paris'); // Valeur par défaut
```

### Secret - Mot de passe masqué

```php
$password = $console->secret('Mot de passe :');
```

### Confirm - Oui/Non

```php
if ($console->confirm('Voulez-vous continuer ?', true)) {
    // Oui
} else {
    // Non
}
```

### Choice - Choix unique

```php
$lang = $console->choice(
    'Choisissez votre langage :',
    ['PHP', 'JavaScript', 'Python', 'Go'],
    0 // Index par défaut
);
```

### Rendu

```
Quel est votre nom ? : Jean
Ville ? [Paris] : Paris
Mot de passe : ****
Voulez-vous continuer ? [Y/n] : y
Choisissez votre langage : (PHP, JavaScript, Python, Go) [PHP] : JavaScript
```

---

## Buffer

```php
$console
    ->startBuffer()
    ->info('Ligne 1')
    ->info('Ligne 2')
    ->info('Ligne 3')
    ->render(); // Affiche tout d'un coup
```

### Rendu

```
ℹ️  Ligne 1
ℹ️  Ligne 2
ℹ️  Ligne 3
```

---

## VirtualTerminalService

```php
use AndyDefer\ConsoleWriter\Console\Services\VirtualTerminalService;

$vt = new VirtualTerminalService();
$vt->add('title', '<fg=cyan><options=bold>📊 Dashboard</options=bold></fg=cyan>');
$vt->add('cpu', '<fg=yellow>CPU : 45%</fg=yellow>');
$vt->add('ram', '<fg=green>RAM : 8.2 GB</fg=green>');

$vt->render();

$vt->update('cpu', '<fg=red>CPU : 85% ⚠️</fg=red>');
$vt->render();
```

### Rendu

```
📊 Dashboard          (cyan gras)
CPU : 45%             (jaune)
RAM : 8.2 GB          (vert)

📊 Dashboard          (cyan gras)
CPU : 85% ⚠️          (rouge)
RAM : 8.2 GB          (vert)
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

### Exemple 2 : Script de déploiement

```php
<?php

require_once 'vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Console;

$console = new Console();

$console
    ->title('🚀 Script de déploiement')
    ->line()
    ->logInfo('Démarrage du déploiement...')
    ->logDebug('Vérification des prérequis...')
    ->logSuccess('✅ Prérequis vérifiés')
    ->logInfo('Téléchargement des sources...')
    ->logSuccess('✅ Sources téléchargées (2.4 MB)')
    ->logInfo('Installation des dépendances...')
    ->logWarning('⚠️ Certaines dépendances sont obsolètes')
    ->logSuccess('✅ Déploiement terminé !')
    ->render();
```

### Exemple 3 : Formulaire interactif

```php
<?php

require_once 'vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Console;

$console = new Console();

$console->title('📝 Formulaire utilisateur');

$name = $console->ask('Nom complet :', null, 'yellow');
$email = $console->ask('Email :', null, 'cyan');
$age = $console->number('Âge :', 1, 120);
$password = $console->secret('Mot de passe :');
$newsletter = $console->confirm('S\'abonner à la newsletter ?', true);
$lang = $console->choice('Langage préféré :', ['PHP', 'JavaScript', 'Python', 'Go']);

$console->line();
$console->title('📊 Récapitulatif');
$console->line();

$console->keyValueWithValueColor([
    'Nom' => $name,
    'Email' => $email,
    'Âge' => $age,
    'Mot de passe' => '••••••••',
    'Newsletter' => $newsletter ? '✅ Oui' : '❌ Non',
    'Langage' => $lang,
], 'green');

$console->line();
$console->success('✅ Formulaire complété avec succès !');
$console->render();
```

### Exemple 4 : Dashboard dynamique avec VT

```php
<?php

require_once 'vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Services\VirtualTerminalService;

$vt = new VirtualTerminalService();

$vt->add('title', '<fg=cyan><options=bold>📊 Monitoring en temps réel</options=bold></fg=cyan>');
$vt->add('separator', '<fg=gray>─────────────────────────────────</fg=gray>');
$vt->add('cpu', '<fg=yellow>CPU    : 0%</fg=yellow>');
$vt->add('ram', '<fg=green>RAM    : 0%</fg=green>');
$vt->add('disk', '<fg=blue>DISQUE : 0%</fg=blue>');
$vt->add('status', '<fg=yellow>⏳ En attente de données...</fg=yellow>');

$vt->render();

// Simulation de mise à jour
sleep(1);
$vt->update('cpu', '<fg=yellow>CPU    : 45%</fg=yellow>');
$vt->update('ram', '<fg=green>RAM    : 65%</fg=green>');
$vt->update('disk', '<fg=blue>DISQUE : 32%</fg=blue>');
$vt->update('status', '<fg=green>✅ Système OK</fg=green>');
$vt->render();
```

---

## Licence

MIT © [Andy Defer](https://github.com/andydefer)