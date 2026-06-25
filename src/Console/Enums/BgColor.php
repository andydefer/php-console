<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Enums;

enum BgColor: string
{
    case BLACK = '40';
    case RED = '41';
    case GREEN = '42';
    case YELLOW = '43';
    case BLUE = '44';
    case MAGENTA = '45';
    case CYAN = '46';
    case WHITE = '47';

    /**
     * Retourne le code ANSI pour la couleur de fond
     */
    public function getAnsiCode(): string
    {
        return "\033[".$this->value.'m';
    }

    /**
     * Retourne le nom de la couleur en minuscule pour l'utiliser dans les balises
     */
    public function getTagName(): string
    {
        return strtolower($this->name);
    }

    /**
     * Retourne le nom affichable de la couleur
     */
    public function getDisplayName(): string
    {
        return ucfirst(strtolower($this->name));
    }
}
