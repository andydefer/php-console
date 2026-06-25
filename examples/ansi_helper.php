<?php

declare(strict_types=1);

/**
 * Convertit les balises Symfony Console en codes ANSI
 */
function ansi(string $text): string
{
    $colors = [
        'black' => '30',
        'red' => '31',
        'green' => '32',
        'yellow' => '33',
        'blue' => '34',
        'magenta' => '35',
        'cyan' => '36',
        'white' => '37',
        'gray' => '90',
    ];

    $bgColors = [
        'black' => '40',
        'red' => '41',
        'green' => '42',
        'yellow' => '43',
        'blue' => '44',
        'magenta' => '45',
        'cyan' => '46',
        'white' => '47',
    ];

    // Couleurs de texte
    foreach ($colors as $name => $code) {
        $text = str_replace('<fg='.$name.'>', "\033[{$code}m", $text);
    }

    // Couleurs de fond
    foreach ($bgColors as $name => $code) {
        $text = str_replace('<bg='.$name.'>', "\033[{$code}m", $text);
    }

    // Options
    $text = str_replace('<options=bold>', "\033[1m", $text);
    $text = str_replace('<options=underline>', "\033[4m", $text);
    $text = str_replace('<options=italic>', "\033[3m", $text);
    $text = str_replace('<options=dim>', "\033[2m", $text);
    $text = str_replace('<options=reverse>', "\033[7m", $text);

    // Fermetures
    $text = preg_replace('/<\/fg=[^>]+>/', "\033[39m", $text);
    $text = preg_replace('/<\/bg=[^>]+>/', "\033[49m", $text);
    $text = preg_replace('/<\/options=[^>]+>/', "\033[22m", $text);
    $text = preg_replace('/<\/[^>]+>/', "\033[0m", $text);

    return $text."\033[0m";
}

/**
 * Affiche avec des couleurs
 */
function color(string $text, string $color): string
{
    $colors = [
        'black' => '30',
        'red' => '31',
        'green' => '32',
        'yellow' => '33',
        'blue' => '34',
        'magenta' => '35',
        'cyan' => '36',
        'white' => '37',
        'gray' => '90',
        'bold' => '1',
    ];

    return "\033[".($colors[$color] ?? '37').'m'.$text."\033[0m";
}
