# PHP Console Writer

**Un package d'écriture console élégant et fluide pour PHP avec des composants stylisés.**

[![PHP Version](https://img.shields.io/badge/PHP-8.0%2B-blue)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green)](LICENSE)

---

## Table des matières

1. [Installation](#installation)
2. [Concepts fondamentaux](#concepts-fondamentaux)
3. [Démarrage rapide](#démarrage-rapide)
4. [Messages stylisés](#messages-stylisés)
5. [Séparateurs](#séparateurs)
6. [Alertes](#alertes)
7. [Tableaux](#tableaux)
8. [Tableau adaptatif](#tableau-adaptatif)
9. [Listes](#listes)
10. [Clés → Valeurs](#clés--valeurs)
11. [Liens](#liens)
12. [Badges](#badges)
13. [Métriques](#métriques)
14. [Colonnes](#colonnes)
15. [Timeline](#timeline)
16. [Arborescence (Tree)](#arborescence-tree)
17. [JSON Viewer](#json-viewer)
18. [Barre de progression](#barre-de-progression)
19. [Spinner](#spinner)
20. [Logger](#logger)
21. [Notifications](#notifications)
22. [Sons](#sons)
23. [Saisies utilisateur](#saisies-utilisateur)
24. [Formulaire](#formulaire)
25. [Buffer et affichage différé](#buffer-et-affichage-différé)
26. [VirtualTerminalService](#virtualterminalservice)
27. [Référence complète des méthodes](#référence-complète-des-méthodes)
28. [Exemples complets](#exemples-complets)
29. [Licence](#licence)

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
    ├── Renderable (Messages, Titres, Alertes, Séparateurs)
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

## Séparateurs

Les séparateurs permettent de délimiter visuellement les sections d'une sortie console.

### Séparateur standard

```php
$console->separator();
// --------------------------------------------------------------------------------
```

### Séparateur avec caractère personnalisé

```php
$console->separator('*');
// ********************************************************************************

$console->separator('═', 40);
// ════════════════════════════════════════
```

### Séparateur avec couleur

```php
$console->separator('-', 80, 'cyan');
$console->separator('=', 50, 'green');
$console->separator('~', 60, 'magenta');
```

### Séparateur double

```php
$console->separatorDouble();
// ================================================================================

$console->separatorDouble(40);
// ========================================
```

### Séparateur avec titre centré

```php
$console->separatorWithTitle('📋 RAPPORT D\'AUDIT');
// ------------------------------- 📋 RAPPORT D'AUDIT ------------------------------

$console->separatorWithTitle('⭐ CHAPITRE 1', '=', 80, 'cyan');
// ============================= ⭐ CHAPITRE 1 =============================
```

### Combinaison avec d'autres composants

```php
$console
    ->title('📊 Dashboard')
    ->separator('=', 60)
    ->info('Contenu du dashboard')
    ->separator('-', 60)
    ->alert('⚠️  Alerte importante')
    ->separator('=', 60)
    ->success('✅ Dashboard chargé');
```

### Rendu complet

```
==========================================================================
📊 Dashboard
==========================================================================
ℹ️  Contenu du dashboard
------------------------------------------------------------
⚠️  Alerte importante
==========================================================================
✅ Dashboard chargé
```

### Avec rendu manuel (classe statique)

```php
use AndyDefer\ConsoleWriter\Console\Components\Separator;

echo Separator::render(40, 'cyan');
echo Separator::renderDouble(50, 'green');
echo Separator::renderWithTitle('TITRE', '-', 60, 'magenta');
```

### Toutes les méthodes du séparateur

| Méthode | Description | Exemple |
|---------|-------------|---------|
| `separator(string $char, int $length, string $color)` | Séparateur standard | `$console->separator('*', 50, 'cyan')` |
| `separatorDouble(int $length, string $color)` | Séparateur double | `$console->separatorDouble(60, 'green')` |
| `separatorWithTitle(string $title, string $char, int $length, string $color)` | Séparateur avec titre centré | `$console->separatorWithTitle('TITRE', '=', 60, 'magenta')` |

### Méthodes statiques de la classe Separator

| Méthode | Description | Exemple |
|---------|-------------|---------|
| `render(int $length, string $color)` | Séparateur standard | `Separator::render(50, 'cyan')` |
| `renderDouble(int $length, string $color)` | Séparateur double | `Separator::renderDouble(50, 'green')` |
| `renderWithChar(string $char, int $length, string $color)` | Séparateur avec caractère | `Separator::renderWithChar('*', 50, 'yellow')` |
| `renderWithTitle(string $title, string $char, int $length, string $color)` | Séparateur avec titre | `Separator::renderWithTitle('TITRE', '=', 60, 'magenta')` |

---

## Alertes

### Alertes prédéfinies

```php
$console
    ->alertSuccess('✅ Déploiement réussi !')
    ->alertError('❌ Erreur critique détectée')
    ->alertWarning('⚠️  Attention, espace disque faible')
    ->alertInfo('ℹ️  Mise à jour disponible');
```

### Alertes personnalisées

```php
// Avec icône personnalisée
$console->alertWithIcon('Nouveau message reçu', '📬');

// Avec couleur personnalisée
$console->alertWithColor('Alerte rouge !', 'red', 6);

// Avec bordure personnalisée
$console->alertWithBorder('Important !', '=', 'magenta', 8);

// Avec icône et couleur personnalisées
$console->alertWithIconAndColor('🎉 Félicitations !', '🎉', 'green', 6);

// Version complète avec tous les paramètres
$console->alertFull('Message complet', '🚀', 'cyan', '═', 6);
```

### Toutes les méthodes d'Alert

| Méthode | Description | Exemple |
|---------|-------------|---------|
| `alertSuccess(string $message)` | Alerte de succès (✅ vert) | `$console->alertSuccess('OK')` |
| `alertError(string $message)` | Alerte d'erreur (❌ rouge) | `$console->alertError('KO')` |
| `alertWarning(string $message)` | Alerte d'avertissement (⚠️ jaune) | `$console->alertWarning('WARN')` |
| `alertInfo(string $message)` | Alerte d'information (ℹ️ bleu) | `$console->alertInfo('INFO')` |
| `alertWithIcon(string $message, string $icon, int $padding)` | Alerte avec icône | `$console->alertWithIcon('Message', '📬')` |
| `alertWithColor(string $message, string $color, int $padding)` | Alerte avec couleur | `$console->alertWithColor('Message', 'red')` |
| `alertWithIconAndColor(string $message, string $icon, string $color, int $padding)` | Alerte avec icône et couleur | `$console->alertWithIconAndColor('Message', '📬', 'red')` |
| `alertWithBorder(string $message, string $borderChar, string $color, int $padding)` | Alerte avec bordure | `$console->alertWithBorder('Message', '=', 'cyan')` |
| `alertFull(string $message, string $icon, string $color, string $borderChar, int $padding)` | Alerte complète | `$console->alertFull('Message', '🚀', 'cyan', '═', 6)` |

---

## Tableaux

### Tableau basique (2-5 colonnes)

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

### Rendu

```
┌────────────────────────────────────────────────────────────┐
│  Service   │  Status      │  Port  │  Version  │
├────────────────────────────────────────────────────────────┤
│  PHP-FPM   │  ✅ Running  │  9000  │  8.2.15   │
│  MySQL     │  ✅ Running  │  3306  │  8.0.35   │
│  Redis     │  ❌ Failed   │  6379  │  7.2.4    │
│  Nginx     │  ✅ Running  │   80   │  1.24.0   │
└────────────────────────────────────────────────────────────┘
```

### Avec ListCollection

```php
use AndyDefer\DomainStructures\Utils\ListCollection;

$headers = ListCollection::from(['Product', 'Price', 'Stock']);
$rows = ListCollection::from([
    ListCollection::from(['Laptop', '999.99', '15']),
    ListCollection::from(['Mouse', '29.99', '42']),
]);

$console->table($headers, $rows);
```

---

## Tableau adaptatif

Le composant `AdaptiveTable` détecte automatiquement le nombre de colonnes :
- **≤ 5 colonnes** → affichage en tableau
- **> 5 colonnes** → affichage en liste KeyValue

```php
$console->adaptiveTable(
    ['Service', 'Status', 'Port', 'Version', 'Uptime', 'Memory'],
    [
        ['PHP-FPM', '✅ Running', '9000', '8.2.15', '72h', '128 MB'],
        ['MySQL', '✅ Running', '3306', '8.0.35', '168h', '512 MB'],
    ]
);
```

### Rendu pour > 5 colonnes

```
📋 6 colonnes → affichage en liste

┌─ Item #1 ──────────────────────────────────────────────
  Service  : PHP-FPM
  Status   : ✅ Running
  Port     : 9000
  Version  : 8.2.15
  Uptime   : 72h
  Memory   : 128 MB
└────────────────────────────────────────────────────────
```

---

## Listes

### Styles disponibles

```php
use AndyDefer\ConsoleWriter\Console\Enums\ListStyle;

$items = ['Item 1', 'Item 2', 'Item 3'];

// Puces (•)
$console->list($items, ListStyle::BULLET);

// Flèches (→)
$console->list($items, ListStyle::ARROW);

// Tiret (—)
$console->list($items, ListStyle::DASH);

// Numérotée (1.)
$console->list($items, ListStyle::NUMBER);

// Alphabétique (a.)
$console->list($items, ListStyle::ALPHA);

// Romain (i.)
$console->list($items, ListStyle::ROMAN);

// Check (✓)
$console->list($items, ListStyle::CHECK);

// Croix (✗)
$console->list($items, ListStyle::CROSS);

// Étoile (★)
$console->list($items, ListStyle::STAR);
```

### Liste colorée

```php
$console->listColored(
    ['✅ Tâche terminée', '✅ Tests passés'],
    ListStyle::CHECK,
    'green'
);

$console->listColored(
    ['❌ Échec du build'],
    ListStyle::CROSS,
    'red'
);
```

### Avec indentation

```php
$console->list(
    ['Sous-item 1', 'Sous-item 2'],
    ListStyle::BULLET,
    2  // Indentation de 2 niveaux
);
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

### Liens cliquables (OSC 8)

```php
$console->link('https://github.com/andydefer/php-console-writer');
$console->link('https://github.com', '📦 Voir le projet sur GitHub');
```

### Avec icône

```php
use AndyDefer\ConsoleWriter\Console\Components\Link;

echo Link::renderWithIcon(
    'https://packagist.org/packages/andy-defer/php-console-writer',
    'Packagist',
    '📦'
);
```

### Avec couleur personnalisée

```php
echo Link::renderWithColor(
    'https://github.com/andydefer/php-console-writer',
    'GitHub Repository',
    'magenta'
);
```

### Avec soulignement

```php
echo Link::renderWithUnderline(
    'https://example.com',
    'Example',
    'green'
);
```

---

## Badges

### Badges prédéfinis

```php
$console
    ->badgeSuccess('SUCCESS')
    ->badgeDanger('FAILED')
    ->badgeWarning('PENDING')
    ->badgeInfo('INFO')
    ->badgePrimary('PRIMARY')
    ->badgeDark('DARK')
    ->badgeLight('LIGHT');
```

### Avec icône

```php
$console->badgeWithIcon('Déploiement', '🚀', 'success');
```

### Badge personnalisé

```php
$console->badge('PERSONNALISÉ', 'custom');

// Ajouter un style personnalisé
Badge::addStyle('custom', 'magenta', '💜', 'CUSTOM');
```

### Rendu

```
[SUCCESS]   (vert)
[FAILED]    (rouge)
[PENDING]   (jaune)
[INFO]      (bleu)
[PRIMARY]   (cyan)

🚀 [DÉPLOIEMENT] (vert avec icône)
[PERSONNALISÉ]  (magenta)
```

---

## Métriques

```php
// Métrique simple
$console->metric('CPU', '45%', 'yellow');

// Avec icône
$console->metricWithIcon('RAM', '8.2 GB', '💾', 'green');

// Avec tendance
$console->metricWithTrend('CPU', '45%', '↑ 5%', 'green');

// En ligne (compact)
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

### Arbre simple

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
```

### Avec couleurs

```php
$console->treeWithColors($tree, '📦 Project', 'cyan', 'white');
```

### Avec icônes

```php
$console->treeWithIcons($tree, '📦 Project', '📂', '📄');
```

### À partir de chemins (méthode la plus simple)

```php
use AndyDefer\DomainStructures\Utils\SetCollection;

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
$console->jsonWithDepth($data, 2);
```

### Rendu

```
"user": {
  "id": 1,       (jaune)
  "name": "Andy", (vert)
  "active": true  (magenta)
}

{"user":{"id":1,"name":"Andy","active":true}}

"user": {
  "id": 1,
  "name": "Andy",
  "active": true
}
```

---

## Barre de progression

### Barre simple

```php
$console->progressBar(100, 40, '📦 Téléchargement');

for ($i = 0; $i < 100; $i++) {
    usleep(30000);
    $console->advance();
}

$console->finish();
```

### Styles prédéfinis

| Style | Description |
|-------|-------------|
| `default` | Style par défaut |
| `download` | Style téléchargement |
| `processing` | Style traitement |
| `upload` | Style upload |
| `install` | Style installation |
| `cleanup` | Style nettoyage |

```php
$console->progressBarStyled(50, 'processing', 40);
```

### Avancement par pas

```php
$console->progressBar(100, 40, '📊 Chargement');

for ($i = 0; $i < 10; $i++) {
    usleep(100000);
    $console->advance(10); // Avance de 10% à chaque étape
}

$console->finish();
```

---

## Spinner

### Tâche simple

```php
$console->spinner('Connexion à Redis...', function($spinner) {
    sleep(3);
    $spinner->success('Connecté');
});
```

### Avec erreur

```php
$console->spinner('Connexion à Redis...', function($spinner) {
    sleep(2);
    $spinner->error('Connexion échouée');
});
```

### Avec changement de message

```php
$console->spinner('Étape 1 : Analyse...', function($spinner) {
    sleep(1);
    $spinner->setMessage('Étape 2 : Téléchargement...');
    sleep(1);
    $spinner->setMessage('Étape 3 : Installation...');
    sleep(1);
    $spinner->success('Installation terminée !');
});
```

### Attente conditionnelle

```php
$counter = 0;
$console->spinnerWait('En attente du service...', function() use (&$counter) {
    $counter++;
    return $counter >= 5;
});

$console->success('✅ Service prêt !');
```

---

## Logger

### Niveaux de logs

```php
$console
    ->logInfo('Chargement...')
    ->logSuccess('✅ Terminé')
    ->logError('❌ Erreur')
    ->logWarning('⚠️ Attention')
    ->logDebug('Debug info')
    ->logNotice('Maintenance programmée')
    ->logCritical('Service hors ligne !');
```

### Format personnalisé

```php
use AndyDefer\ConsoleWriter\Console\Components\Logger;

Logger::setTimeFormat('Y-m-d H:i:s');
echo Logger::info('Format Y-m-d H:i:s');

// Restaurer le format par défaut
Logger::setTimeFormat('H:i:s');
```

### Log personnalisé

```php
echo Logger::log('CUSTOM', 'Message personnalisé', 'magenta');
```

### Rendu

```
[14:30:00] INFO     - Chargement...
[14:30:01] SUCCESS  - ✅ Terminé
[14:30:02] ERROR    - ❌ Erreur
[14:30:03] WARNING  - ⚠️ Attention
[14:30:04] DEBUG    - Debug info
[14:30:05] NOTICE   - Maintenance programmée
[14:30:06] CRITICAL - Service hors ligne !
[14:30:07] CUSTOM   - Message personnalisé
```

---

## Notifications

```php
$console
    ->notifySuccess('Déploiement réussi')
    ->notifyError('Erreur critique')
    ->notifyWarning('Cache à nettoyer')
    ->notifyInfo('Nouvelle mise à jour');
```

### Rendu

```
🔔 Déploiement réussi   (vert)
🔔 Erreur critique      (rouge)
🔔 Cache à nettoyer     (jaune)
🔔 Nouvelle mise à jour (bleu)
```

---

## Sons

```php
$console->soundSuccess();  // Son de succès
$console->soundError();    // Son d'erreur
$console->soundInfo();     // Son d'information

// Son personnalisé
$console->sound(SoundType::SUCCESS);

// Son asynchrone (ne bloque pas)
$console->soundAsync(SoundType::SUCCESS);
```

---

## Saisies utilisateur

### Ask - Saisie simple

```php
$name = $console->ask('Quel est votre nom ?');
$city = $console->ask('Ville ?', 'Paris'); // Valeur par défaut
$color = $console->ask('Couleur ?', null, 'yellow'); // Couleur personnalisée
```

### Secret - Mot de passe masqué

```php
$password = $console->secret('Mot de passe :');
$apiKey = $console->secret('Clé API :', 'yellow');
```

### Confirm - Oui/Non

```php
if ($console->confirm('Voulez-vous continuer ?', true)) {
    // Oui
} else {
    // Non
}

// Avec couleur personnalisée
if ($console->confirm('Accepter ?', true, 'yellow')) {
    // Oui
}
```

### Choice - Choix unique

```php
$lang = $console->choice(
    'Choisissez votre langage :',
    ['PHP', 'JavaScript', 'Python', 'Go'],
    0 // Index par défaut
);

// Avec couleur personnalisée
$lang = $console->choice('Langage :', $options, null, 'yellow');
```

### Suggest - Autocomplétion

```php
$colors = ['red', 'green', 'blue', 'yellow'];
$color = $console->suggest('Choisissez une couleur :', $colors);
```

### Number - Saisie numérique

```php
$age = $console->number('Âge :', 0, 150);       // Min 0, Max 150
$score = $console->number('Score :', 0, 100);   // Avec validation
$quantity = $console->number('Quantité :', 1);  // Min 1
```

### ConfirmWithTimeout - Confirmation avec délai

```php
$console->info('⏳ Vous avez 5 secondes pour répondre...');
$result = $console->confirmWithTimeout(
    'Confirmer l\'opération ?',
    5,      // Timeout en secondes
    true    // Valeur par défaut si timeout
);
```

### MultiChoice - Sélection multiple

```php
$selected = $console->multiChoice(
    'Choisissez vos langages préférés :',
    ['PHP', 'JavaScript', 'Python', 'Go'],
    ['PHP', 'JavaScript'] // Sélectionnés par défaut
);
```

---

## Formulaire

```php
$answers = $console->form()
    ->title('📝 Formulaire d\'inscription')
    ->line()
    ->ask('Nom complet :', 'name', null, 'yellow')
    ->ask('Email :', 'email', null, 'cyan')
    ->number('Âge :', 'age', 1, 120)
    ->secret('Mot de passe :', 'password')
    ->confirm('Newsletter ?', 'newsletter', true)
    ->choice('Langage :', 'lang', ['PHP', 'JavaScript', 'Python'])
    ->multiChoice('Frameworks :', 'frameworks', ['Laravel', 'React'], ['Laravel'])
    ->summaryTable('📊 Récapitulatif')
    ->submit();

$console->title('📊 Réponses');
$console->line();

$console->keyValueWithValueColor([
    'Nom' => $answers->get('name'),
    'Email' => $answers->get('email'),
    'Âge' => $answers->get('age'),
    'Newsletter' => $answers->get('newsletter') ? '✅ Oui' : '❌ Non',
    'Langage' => $answers->get('lang'),
    'Frameworks' => implode(', ', $answers->get('frameworks')),
], 'green');
```

### Méthodes du formulaire

| Méthode | Description | Exemple |
|---------|-------------|---------|
| `title(string $title)` | Titre du formulaire | `->title('📝 Inscription')` |
| `line()` | Saut de ligne | `->line()` |
| `ask(string $question, string $key, string $default, string $color)` | Saisie texte | `->ask('Nom :', 'name', null, 'yellow')` |
| `secret(string $question, string $key, string $color)` | Mot de passe | `->secret('Mot de passe :', 'password')` |
| `confirm(string $question, string $key, bool $default)` | Oui/Non | `->confirm('Newsletter ?', 'newsletter', true)` |
| `number(string $question, string $key, int $min, int $max, int $default)` | Saisie numérique | `->number('Âge :', 'age', 1, 120)` |
| `choice(string $question, string $key, array $options, int $default)` | Choix unique | `->choice('Langage :', 'lang', ['PHP', 'JS'])` |
| `multiChoice(string $question, string $key, array $options, array $selected)` | Choix multiple | `->multiChoice('Frameworks :', 'frameworks', ['Laravel', 'React'])` |
| `summaryTable(string $title)` | Tableau récapitulatif | `->summaryTable('📊 Récapitulatif')` |
| `submit()` | Récupère les réponses | `->submit()` |

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

## Référence complète des méthodes

### Console

| Méthode | Description | Exemple |
|---------|-------------|---------|
| **Messages** | | |
| `info(string $message)` | Message d'information (bleu) | `$console->info('Chargement...')` |
| `success(string $message)` | Message de succès (vert) | `$console->success('Terminé !')` |
| `error(string $message)` | Message d'erreur (rouge) | `$console->error('Erreur !')` |
| `title(string $message)` | Titre encadré (cyan) | `$console->title('Dashboard')` |
| **Séparateurs** | | |
| `separator(string $char, int $length, string $color)` | Séparateur standard | `$console->separator('*', 50, 'cyan')` |
| `separatorDouble(int $length, string $color)` | Séparateur double | `$console->separatorDouble(60, 'green')` |
| `separatorWithTitle(string $title, string $char, int $length, string $color)` | Séparateur avec titre centré | `$console->separatorWithTitle('TITRE', '=', 60, 'magenta')` |
| **Alertes** | | |
| `alert(string $message)` | Alerte encadrée (jaune) | `$console->alert('Attention !')` |
| `alertSuccess(string $message)` | Alerte de succès (✅ vert) | `$console->alertSuccess('OK')` |
| `alertError(string $message)` | Alerte d'erreur (❌ rouge) | `$console->alertError('KO')` |
| `alertWarning(string $message)` | Alerte d'avertissement (⚠️ jaune) | `$console->alertWarning('WARN')` |
| `alertInfo(string $message)` | Alerte d'information (ℹ️ bleu) | `$console->alertInfo('INFO')` |
| `alertWithIcon(string $message, string $icon, int $padding)` | Alerte avec icône | `$console->alertWithIcon('Message', '📬')` |
| `alertWithColor(string $message, string $color, int $padding)` | Alerte avec couleur | `$console->alertWithColor('Message', 'red')` |
| `alertWithBorder(string $message, string $border, string $color, int $padding)` | Alerte avec bordure | `$console->alertWithBorder('Message', '=')` |
| `alertWithIconAndColor(string $message, string $icon, string $color, int $padding)` | Alerte avec icône et couleur | `$console->alertWithIconAndColor('Message', '📬', 'red')` |
| `alertFull(string $message, string $icon, string $color, string $border, int $padding)` | Alerte complète | `$console->alertFull('Message', '🚀', 'cyan', '═', 6)` |
| **Tableaux** | | |
| `table($headers, $rows)` | Tableau formaté | `$console->table(['A'], [['1']])` |
| `adaptiveTable($headers, $rows)` | Tableau ou liste auto | `$console->adaptiveTable($h, $r)` |
| **Listes** | | |
| `list($items, ListStyle, int $indent)` | Liste à puces | `$console->list(['A'], BULLET)` |
| `listColored($items, ListStyle, string $color)` | Liste colorée | `$console->listColored(['A'], BULLET, 'green')` |
| **KeyValue** | | |
| `keyValue($data, int $indent)` | Clés → valeurs | `$console->keyValue(['A'=>'1'])` |
| `keyValueWithColor($data, string $color, int $indent)` | Clés colorées | `$console->keyValueWithColor($d, 'yellow')` |
| `keyValueWithValueColor($data, string $color, int $indent)` | Valeurs colorées | `$console->keyValueWithValueColor($d, 'green')` |
| `keyValueWithSeparator($data, string $separator, int $indent)` | Séparateur | `$console->keyValueWithSeparator($d, ' → ')` |
| **Arbres** | | |
| `tree(MapCollection $tree, string $root)` | Arbre | `$console->tree($tree, 'Root')` |
| `treeWithColors($tree, $root, $nodeColor, $leafColor)` | Arbre avec couleurs | `$console->treeWithColors($tree, 'Root', 'green', 'yellow')` |
| `treeWithIcons($tree, $root, $folderIcon, $fileIcon)` | Arbre avec icônes | `$console->treeWithIcons($tree, 'Root', '📂', '📄')` |
| `treeFromPaths(SetCollection $paths, string $root)` | Arbre à partir de chemins | `$console->treeFromPaths($paths, 'Project')` |
| **Liens** | | |
| `link(string $url, ?string $text)` | Lien cliquable | `$console->link('https://...')` |
| **Badges** | | |
| `badge(string $text, string $style)` | Badge personnalisé | `$console->badge('OK', 'success')` |
| `badgeWithIcon(string $text, string $icon, string $style)` | Badge avec icône | `$console->badgeWithIcon('OK', '✅', 'success')` |
| `badgeSuccess(string $text)` | Badge succès | `$console->badgeSuccess('OK')` |
| `badgeDanger(string $text)` | Badge danger | `$console->badgeDanger('KO')` |
| `badgeWarning(string $text)` | Badge avertissement | `$console->badgeWarning('WARN')` |
| `badgeInfo(string $text)` | Badge information | `$console->badgeInfo('INFO')` |
| `badgePrimary(string $text)` | Badge primaire | `$console->badgePrimary('PRIMARY')` |
| **Métriques** | | |
| `metric(string $label, string $value, string $color)` | Métrique | `$console->metric('CPU', '45%')` |
| `metricWithIcon(string $label, string $value, string $icon, string $color)` | Métrique avec icône | `$console->metricWithIcon('CPU', '45%', '💻')` |
| `metricWithTrend(string $label, string $value, string $trend, string $trendColor, string $valueColor)` | Métrique avec tendance | `$console->metricWithTrend('CPU', '45%', '↑ 5%')` |
| `metricInline(string $label, string $value, string $color)` | Métrique en ligne | `$console->metricInline('CPU', '45%')` |
| **Colonnes** | | |
| `columns($columns, int $width, string $separator)` | Colonnes | `$console->columns($columns)` |
| `columnsWithColors($columns, array $colors, int $width, string $separator)` | Colonnes avec couleurs | `$console->columnsWithColors($columns, ['cyan', 'green'])` |
| `columnsWithHeaders($columns, int $width, string $separator)` | Colonnes avec en-têtes | `$console->columnsWithHeaders($columns)` |
| `columnsCompact($columns, string $separator)` | Colonnes compactes | `$console->columnsCompact($columns)` |
| **Timeline** | | |
| `timeline($events, string $color)` | Timeline | `$console->timeline($events)` |
| `timelineWithColors($events, array $colors)` | Timeline avec couleurs | `$console->timelineWithColors($events, ['green', 'red'])` |
| `timelineWithIcons($events, string $icon, string $color)` | Timeline avec icônes | `$console->timelineWithIcons($events, '★')` |
| `timelineWithStatus($events, array $statuses)` | Timeline avec statuts | `$console->timelineWithStatus($events, ['success', 'error'])` |
| **JSON** | | |
| `json(array\|string $data)` | JSON formaté | `$console->json($data)` |
| `jsonCompact(array\|string $data)` | JSON compact | `$console->jsonCompact($data)` |
| `jsonWithDepth(array\|string $data, int $depth)` | JSON avec profondeur | `$console->jsonWithDepth($data, 2)` |
| **Progression** | | |
| `progressBar(int $total, int $width, string $prefix, string $suffix)` | Barre de progression | `$console->progressBar(100, 50, 'Download')` |
| `progressBarStyled(int $total, string $style, int $width)` | Barre avec style | `$console->progressBarStyled(100, 'download')` |
| `advance(int $steps)` | Avancer la barre | `$console->advance(10)` |
| `finish()` | Terminer la barre | `$console->finish()` |
| `spinner(string $message, callable $task, string $prefix, string $suffix)` | Spinner | `$console->spinner('Loading...', fn($s) => sleep(2))` |
| `spinnerWait(string $message, callable $isComplete, string $prefix, string $suffix)` | Spinner en attente | `$console->spinnerWait('Waiting...', fn() => $done)` |
| **Logger** | | |
| `logInfo(string $message)` | Log INFO (bleu) | `$console->logInfo('Message')` |
| `logSuccess(string $message)` | Log SUCCESS (vert) | `$console->logSuccess('OK')` |
| `logError(string $message)` | Log ERROR (rouge) | `$console->logError('KO')` |
| `logWarning(string $message)` | Log WARNING (jaune) | `$console->logWarning('WARN')` |
| `logDebug(string $message)` | Log DEBUG (gris) | `$console->logDebug('Debug')` |
| `logNotice(string $message)` | Log NOTICE (cyan) | `$console->logNotice('Notice')` |
| `logCritical(string $message)` | Log CRITICAL (magenta) | `$console->logCritical('Critical')` |
| `log(string $level, string $message, string $color)` | Log personnalisé | `$console->log('CUSTOM', 'Message', 'magenta')` |
| **Notifications** | | |
| `notify(string $message, string $type, string $icon)` | Notification | `$console->notify('Message', 'success')` |
| `notifySuccess(string $message)` | Notification succès | `$console->notifySuccess('OK')` |
| `notifyError(string $message)` | Notification erreur | `$console->notifyError('KO')` |
| `notifyWarning(string $message)` | Notification avertissement | `$console->notifyWarning('WARN')` |
| `notifyInfo(string $message)` | Notification information | `$console->notifyInfo('INFO')` |
| **Sons** | | |
| `soundSuccess()` | Son de succès | `$console->soundSuccess()` |
| `soundError()` | Son d'erreur | `$console->soundError()` |
| `soundInfo()` | Son d'information | `$console->soundInfo()` |
| `sound(SoundType $type)` | Son personnalisé | `$console->sound(SoundType::SUCCESS)` |
| `soundAsync(SoundType $type)` | Son asynchrone | `$console->soundAsync(SoundType::SUCCESS)` |
| **Saisies** | | |
| `ask(string $question, ?string $default, string $color)` | Saisie texte | `$console->ask('Nom :')` |
| `secret(string $question, string $color)` | Mot de passe | `$console->secret('Mot de passe :')` |
| `confirm(string $question, bool $default, string $color)` | Oui/Non | `$console->confirm('Continuer ?')` |
| `choice(string $question, array $choices, ?int $default, string $color)` | Choix unique | `$console->choice('Langage :', ['PHP', 'JS'])` |
| `suggest(string $question, array $suggestions, string $color)` | Autocomplétion | `$console->suggest('Couleur :', ['red', 'blue'])` |
| `number(string $question, ?int $min, ?int $max, ?int $default, string $color)` | Saisie numérique | `$console->number('Âge :', 1, 120)` |
| `confirmWithTimeout(string $question, int $timeout, bool $default, string $color)` | Confirmation avec délai | `$console->confirmWithTimeout('Continuer ?', 5)` |
| `multiChoice(string $question, array $options, array $selected, string $color)` | Sélection multiple | `$console->multiChoice('Langages :', ['PHP', 'JS'])` |
| `form()` | Formulaire | `$console->form()->ask(...)->submit()` |
| **Utilitaires** | | |
| `line(string $message)` | Ligne simple | `$console->line('Texte')` |
| `newLine(int $count)` | Saut de ligne | `$console->newLine(2)` |
| `space(int $count)` | Espaces | `$console->space(2)` |
| `raw(string $line)` | Ligne brute | `$console->raw('<fg=green>Texte</fg=green>')` |
| `ansi(string $text)` | Texte avec balises ANSI | `$console->ansi('<fg=green>...')` |
| **Buffer** | | |
| `startBuffer()` | Démarrer le buffer | `$console->startBuffer()` |
| `render()` | Afficher le buffer | `$console->render()` |
| `clear()` | Vider le buffer | `$console->clear()` |
| `getLines(): array` | Récupérer les lignes | `$lines = $console->getLines()` |
| `isBuffered(): bool` | Vérifier le buffer | `$console->isBuffered()` |

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
    ->separatorWithTitle('📊 Services en cours', '=', 80, 'cyan')
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
    ->separator('=', 60, 'cyan')
    ->logInfo('Démarrage du déploiement...')
    ->logDebug('Vérification des prérequis...')
    ->logSuccess('✅ Prérequis vérifiés')
    ->separator('-', 60, 'gray')
    ->logInfo('Téléchargement des sources...')
    ->logSuccess('✅ Sources téléchargées (2.4 MB)')
    ->logInfo('Installation des dépendances...')
    ->logWarning('⚠️ Certaines dépendances sont obsolètes')
    ->separator('=', 60, 'green')
    ->logSuccess('✅ Déploiement terminé !')
    ->render();
```

### Exemple 3 : Formulaire interactif complet

```php
<?php

require_once 'vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Console;

$console = new Console();

$answers = $console->form()
    ->title('📝 Formulaire d\'inscription')
    ->line()
    ->ask('Nom complet :', 'name', null, 'yellow')
    ->ask('Email :', 'email', null, 'cyan')
    ->number('Âge :', 'age', 1, 120)
    ->secret('Mot de passe :', 'password')
    ->confirm('S\'abonner à la newsletter ?', 'newsletter', true)
    ->choice('Langage préféré :', 'lang', ['PHP', 'JavaScript', 'Python', 'Go'], null, 'green')
    ->multiChoice(
        'Choisissez vos hobbies :',
        'hobbies',
        ['Lecture', 'Sport', 'Musique', 'Voyage'],
        ['Lecture', 'Musique'],
        'magenta'
    )
    ->summaryTable('📊 Récapitulatif du formulaire')
    ->submit();

$console->line();
$console->separator('=', 60, 'cyan');
$console->title('📊 Réponses');
$console->separator('=', 60, 'cyan');

$console->keyValueWithValueColor([
    'Nom' => $answers->get('name'),
    'Email' => $answers->get('email'),
    'Âge' => $answers->get('age'),
    'Mot de passe' => '••••••••',
    'Newsletter' => $answers->get('newsletter') ? '✅ Oui' : '❌ Non',
    'Langage' => $answers->get('lang'),
    'Hobbies' => implode(', ', $answers->get('hobbies')),
], 'green');

$console->line();
$console->success('✅ Formulaire complété avec succès !');
$console->render();
```

### Exemple 4 : Rapport d'audit avec séparateurs

```php
<?php

require_once 'vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Console;

$console = new Console();

$console
    ->title('🔍 Rapport d\'audit système')
    ->separator('=', 80, 'blue')
    ->line()
    ->info('📅 Date : ' . date('Y-m-d H:i:s'))
    ->info('🖥️  Serveur : ' . php_uname('n'))
    ->line()
    ->separatorWithTitle('📊 Métriques système', '-', 80, 'yellow')
    ->line()
    ->keyValueWithValueColor([
        'CPU' => '45%',
        'RAM' => '8.2/16.0 Go',
        'Disque' => '256/512 Go',
        'Uptime' => '72h 34m',
    ], 'green')
    ->line()
    ->separatorWithTitle('📋 Services', '-', 80, 'yellow')
    ->table(
        ['Service', 'Status', 'Port', 'Version'],
        [
            ['PHP-FPM', '✅ Running', '9000', '8.2.15'],
            ['MySQL', '✅ Running', '3306', '8.0.35'],
            ['Redis', '❌ Failed', '6379', '7.2.4'],
        ]
    )
    ->line()
    ->separatorWithTitle('⚠️  Alertes', '-', 80, 'red')
    ->alertWarning('Redis est hors ligne depuis 2h')
    ->line()
    ->separatorWithTitle('✅ Conclusion', '-', 80, 'green')
    ->success('Audit terminé avec 1 alerte')
    ->separator('=', 80, 'blue')
    ->render();
```

### Exemple 5 : Dashboard dynamique avec VirtualTerminalService

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