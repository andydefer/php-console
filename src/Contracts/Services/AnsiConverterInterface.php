<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Contracts\Services;

use AndyDefer\ConsoleWriter\Console\Enums\BgColor;
use AndyDefer\ConsoleWriter\Console\Enums\FgColor;
use AndyDefer\ConsoleWriter\Console\Enums\Options;

interface AnsiConverterInterface
{
    /**
     * Convertit une chaîne avec balises Symfony en codes ANSI
     */
    public function convert(string $text): string;

    /**
     * Colore un texte avec une couleur (style fonction color())
     */
    public function color(string $text, string $color): string;

    /**
     * Colore un texte avec une couleur de fond (style fonction bgColor())
     */
    public function bgColor(string $text, string $color): string;

    /**
     * Colore un texte avec une couleur de premier plan (via enum)
     */
    public function colorEnum(string $text, FgColor $color): string;

    /**
     * Colore un texte avec une couleur de fond (via enum)
     */
    public function bgColorEnum(string $text, BgColor $color): string;

    /**
     * Applique une option (bold, underline, etc.)
     */
    public function option(string $text, Options $option): string;

    /**
     * Combine plusieurs styles
     */
    public function style(string $text, ?FgColor $fg = null, ?BgColor $bg = null, Options ...$options): string;

    /**
     * Réinitialise tous les styles
     */
    public function reset(): string;

    /**
     * Supprime toutes les balises pour obtenir le texte brut
     */
    public function stripTags(string $text): string;
}
