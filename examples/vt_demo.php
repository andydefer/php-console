<?php

require_once __DIR__.'/../vendor/autoload.php';

use AndyDefer\ConsoleWriter\Console\Components\Input\ConfirmWithTimeout;
use AndyDefer\ConsoleWriter\Console\Services\AnsiConverterService;
use AndyDefer\ConsoleWriter\Console\Services\StandardInputReaderService;

$ansi = new AnsiConverterService;
$reader = new StandardInputReaderService;

// Cas 1 : Réponse Oui
$result = ConfirmWithTimeout::execute(
    $ansi,
    $reader,
    'Voulez-vous continuer ?',
    5,
    true,
    'cyan'
);

// Cas 2 : Réponse Non
$result2 = ConfirmWithTimeout::execute(
    $ansi,
    $reader,
    'Voulez-vous supprimer ce fichier ?',
    5,
    false,
    'cyan'
);

// Cas 3 : Timeout
$result3 = ConfirmWithTimeout::execute(
    $ansi,
    $reader,
    'Voulez-vous enregistrer ?',
    3,
    true,
    'cyan'
);
