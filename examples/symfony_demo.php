<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Components\KeyValue;
use AndyDefer\DomainStructures\Utils\MapCollection;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\ConsoleOutput;

$output = new ConsoleOutput;

// Ajouter des styles personnalisés
$output->getFormatter()->setStyle('title', new OutputFormatterStyle('cyan', null, ['bold']));
$output->getFormatter()->setStyle('info', new OutputFormatterStyle('blue'));
$output->getFormatter()->setStyle('success', new OutputFormatterStyle('green'));
$output->getFormatter()->setStyle('warning', new OutputFormatterStyle('yellow'));

$output->writeln('');
$output->writeln('<title>  ╔═════════════════════════════════════╗</title>');
$output->writeln('<title>  ║   ✨ KeyValue Demo avec Symfony   ║</title>');
$output->writeln('<title>  ╚═════════════════════════════════════╝</title>');
$output->writeln('');

$output->writeln('<info>ℹ️  Voici le rendu avec les couleurs interprétées</info>');
$output->writeln('');

// Rendu KeyValue
$data = MapCollection::from([
    'Nom' => 'Jean Dupont',
    'Âge' => 42,
    'Ville' => 'Paris 🇫🇷',
    'Email' => 'jean@example.com',
    'Status' => '✅ Actif',
]);

$result = KeyValue::renderWithValueColor($data, 'green');
$output->writeln($result);
$output->writeln('');

$output->writeln('<success>✅ Démonstration terminée !</success>');
$output->writeln('');
