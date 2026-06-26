<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Console;

$console = new Console;

$console->title('📝 Démonstration du composant Form');

// ========================================================================
// 1. FORMULAIRE D'INSCRIPTION
// ========================================================================

$console->line();
$console->info('1. Formulaire d\'inscription');

$answers1 = $console->form()
    ->title('📝 Inscription utilisateur')
    ->line()
    ->ask('Nom complet :', 'name', null, 'yellow')
    ->ask('Email :', 'email', null, 'cyan')
    ->number('Âge :', 'age', 1, 120)
    ->secret('Mot de passe :', 'password')
    ->confirm('S\'abonner à la newsletter ?', 'newsletter', true)
    ->choice('Langage préféré :', 'lang', ['PHP', 'JavaScript', 'Python', 'Go'])
    ->submit();

$console->line();
$console->title('📊 RÉCAPITULATIF');
$console->line();

$console->keyValueWithValueColor([
    'Nom' => $answers1->get('name'),
    'Email' => $answers1->get('email'),
    'Âge' => $answers1->get('age'),
    'Mot de passe' => '••••••••',
    'Newsletter' => $answers1->get('newsletter') ? '✅ Oui' : '❌ Non',
    'Langage' => $answers1->get('lang'),
], 'green');

// ========================================================================
// 2. FORMULAIRE DE COMMANDE
// ========================================================================

$console->line();
$console->info('2. Formulaire de commande');

$answers2 = $console->form()
    ->title('🛒 Commande')
    ->line()
    ->ask('Nom complet :', 'name', null, 'yellow')
    ->ask('Adresse de livraison :', 'address', null, 'cyan')
    ->choice('Mode de paiement :', 'payment', ['Carte bancaire', 'PayPal', 'Virement'])
    ->multiChoice('Hobbies :', 'hobbies', ['Lecture', 'Sport', 'Musique', 'Voyage'], ['Lecture', 'Musique'])
    ->submit();

$console->line();
$console->title('📊 RÉCAPITULATIF COMMANDE');
$console->line();

$console->keyValueWithValueColor([
    'Nom' => $answers2->get('name'),
    'Adresse' => $answers2->get('address'),
    'Paiement' => $answers2->get('payment'),
    'Hobbies' => implode(', ', $answers2->get('hobbies')),
], 'green');

// ========================================================================
// 3. FORMULAIRE DE CONFIGURATION
// ========================================================================

$console->line();
$console->info('3. Formulaire de configuration');

$answers3 = $console->form()
    ->title('⚙️ Configuration')
    ->line()
    ->ask('Nom du projet :', 'project', null, 'yellow')
    ->ask('Environnement :', 'env', null, 'cyan')
    ->number('Port :', 'port', 1, 65535, 8000)
    ->choice('Base de données :', 'db', ['MySQL', 'PostgreSQL', 'SQLite'])
    ->confirm('Activer le cache ?', 'cache', true)
    ->submit();

$console->line();
$console->title('📊 RÉCAPITULATIF CONFIGURATION');
$console->line();

$console->keyValueWithValueColor([
    'Projet' => $answers3->get('project'),
    'Environnement' => $answers3->get('env'),
    'Port' => $answers3->get('port'),
    'Base de données' => $answers3->get('db'),
    'Cache' => $answers3->get('cache') ? '✅ Activé' : '❌ Désactivé',
], 'cyan');

// ========================================================================
// 4. FORMULAIRE DE CONTACT
// ========================================================================

$console->line();
$console->info('4. Formulaire de contact');

$answers4 = $console->form()
    ->title('📧 Contact')
    ->line()
    ->ask('Votre nom :', 'name', null, 'yellow')
    ->ask('Votre email :', 'email', null, 'cyan')
    ->ask('Sujet :', 'subject', null, 'yellow')
    ->ask('Message :', 'message', null, 'white')
    ->confirm('Envoyer une copie ?', 'copy', false)
    ->submit();

$console->line();
$console->title('📊 RÉCAPITULATIF CONTACT');
$console->line();

$console->keyValueWithValueColor([
    'Nom' => $answers4->get('name'),
    'Email' => $answers4->get('email'),
    'Sujet' => $answers4->get('subject'),
    'Message' => $answers4->get('message'),
    'Copie' => $answers4->get('copy') ? '✅ Oui' : '❌ Non',
], 'yellow');

// ========================================================================
// 5. FORMULAIRE D'ÉVALUATION
// ========================================================================

$console->line();
$console->info('5. Formulaire d\'évaluation');

$answers5 = $console->form()
    ->title('⭐ Évaluation')
    ->line()
    ->ask('Service :', 'service', null, 'yellow')
    ->number('Note (1-5) :', 'rating', 1, 5, 3)
    ->choice('Recommandation :', 'recommend', ['Oui', 'Non', 'Peut-être'])
    ->multiChoice('Points forts :', 'strengths', ['Qualité', 'Rapidité', 'Prix', 'Support'])
    ->multiChoice('Points faibles :', 'weaknesses', ['Lenteur', 'Prix élevé', 'Support', 'Qualité'])
    ->submit();

$console->line();
$console->title('📊 RÉCAPITULATIF ÉVALUATION');
$console->line();

$console->keyValueWithValueColor([
    'Service' => $answers5->get('service'),
    'Note' => $answers5->get('rating').'/5',
    'Recommandation' => $answers5->get('recommend'),
    'Points forts' => implode(', ', $answers5->get('strengths')),
    'Points faibles' => implode(', ', $answers5->get('weaknesses')),
], 'magenta');

// ========================================================================
// 6. FORMULAIRE AVEC RÉCAPITULATIF INTÉGRÉ
// ========================================================================

$console->line();
$console->info('6. Formulaire avec récapitulatif intégré');

$answers6 = $console->form()
    ->title('📝 Profil complet')
    ->line()
    ->ask('Prénom :', 'firstname', null, 'yellow')
    ->ask('Nom :', 'lastname', null, 'yellow')
    ->ask('Email :', 'email', null, 'cyan')
    ->ask('Téléphone :', 'phone', null, 'cyan')
    ->number('Âge :', 'age', 1, 120)
    ->choice('Genre :', 'gender', ['Homme', 'Femme', 'Autre'])
    ->secret('Mot de passe :', 'password')
    ->confirm('Newsletter ?', 'newsletter', true)
    ->multiChoice('Centres d\'intérêt :', 'interests', ['Technologie', 'Sport', 'Art', 'Musique', 'Lecture', 'Voyage'], ['Technologie', 'Musique'])
    ->summaryTable('📊 RÉCAPITULATIF COMPLET')
    ->submit();

// ========================================================================
// 7. FORMULAIRE DE RECHERCHE
// ========================================================================

$console->line();
$console->info('7. Formulaire de recherche');

$answers7 = $console->form()
    ->title('🔍 Recherche avancée')
    ->line()
    ->ask('Mots-clés :', 'keywords', null, 'yellow')
    ->ask('Auteur :', 'author', null, 'cyan')
    ->number('Année début :', 'year_start', 1900, 2026, 2000)
    ->number('Année fin :', 'year_end', 1900, 2026, 2026)
    ->choice('Catégorie :', 'category', ['Tous', 'Articles', 'Vidéos', 'Images', 'Documents'])
    ->choice('Ordre de tri :', 'sort', ['Pertinence', 'Date', 'Titre', 'Auteur'])
    ->confirm('Recherche exacte ?', 'exact', false)
    ->submit();

$console->line();
$console->title('📊 RÉCAPITULATIF RECHERCHE');
$console->line();

$console->keyValueWithValueColor([
    'Mots-clés' => $answers7->get('keywords'),
    'Auteur' => $answers7->get('author'),
    'Période' => $answers7->get('year_start').' - '.$answers7->get('year_end'),
    'Catégorie' => $answers7->get('category'),
    'Tri' => $answers7->get('sort'),
    'Recherche exacte' => $answers7->get('exact') ? '✅ Oui' : '❌ Non',
], 'blue');

// ========================================================================
// 8. FORMULAIRE AVEC SUGGEST
// ========================================================================

$console->line();
$console->info('8. Formulaire avec suggest');

$answers8 = $console->form()
    ->title('🎨 Personnalisation')
    ->line()
    ->ask('Nom d\'utilisateur :', 'username', null, 'yellow')
    ->suggest('Couleur préférée :', 'color', ['rouge', 'vert', 'bleu', 'jaune', 'orange', 'violet', 'rose', 'noir', 'blanc', 'gris'])
    ->choice('Thème :', 'theme', ['Clair', 'Sombre', 'Automne', 'Hiver'])
    ->number('Taille police :', 'font_size', 8, 24, 14)
    ->submit();

$console->line();
$console->title('📊 RÉCAPITULATIF PERSONNALISATION');
$console->line();

$console->keyValueWithValueColor([
    'Nom d\'utilisateur' => $answers8->get('username'),
    'Couleur préférée' => $answers8->get('color'),
    'Thème' => $answers8->get('theme'),
    'Taille police' => $answers8->get('font_size').'px',
], 'magenta');

// ========================================================================
// FIN
// ========================================================================

$console->line();
$console->success('✅ Tous les formulaires ont été complétés avec succès !');
$console->render();
