<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Enums;

enum Options: string
{
    case BOLD = '1';
    case DIM = '2';
    case ITALIC = '3';
    case UNDERLINE = '4';
    case REVERSE = '7';

    public function getAnsiCode(): string
    {
        return "\033[".$this->value.'m';
    }

    public function getResetCode(): string
    {
        return match ($this) {
            self::BOLD => "\033[22m",
            self::DIM => "\033[22m",
            self::ITALIC => "\033[23m",
            self::UNDERLINE => "\033[24m",
            self::REVERSE => "\033[27m",
        };
    }

    /**
     * Retourne le nom de l'option en minuscule pour l'utiliser dans les balises
     */
    public function getTagName(): string
    {
        return strtolower($this->name);
    }
}
