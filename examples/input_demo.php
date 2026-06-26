<?php

require_once 'vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Console;

$console = new Console;

$answers = $console->form()
    // Affichage
    ->title('📝 Formulaire d\'inscription')
    ->line()
    ->info('Veuillez remplir les informations suivantes :')
    ->line()

    // Questions
    ->ask('Nom complet :', 'nom', null, 'yellow')
    ->ask('Email :', 'email', null, 'cyan')
    ->number('Âge :', 'age', 1, 120)
    ->secret('Mot de passe :', 'password')
    ->confirm('S\'abonner à la newsletter ?', 'newsletter', true)
    ->choice('Langage préféré :', 'lang', ['PHP', 'JavaScript', 'Python', 'Go'])
    ->multiChoice('Frameworks :', 'frameworks', ['Laravel', 'React', 'Vue.js', 'Django'], ['Laravel'])

    // Récapitulatif
    ->line()
    ->summaryTable('📊 Récapitulatif des réponses')
    ->line()
    ->badgeSuccess('Formulaire complété')
    ->submit();

// Accès aux réponses
$nom = $answers->get('nom');
$email = $answers->get('email');
