<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Enums;

enum FgColor: string
{
    case BLACK = '30';
    case RED = '31';
    case GREEN = '32';
    case YELLOW = '33';
    case BLUE = '34';
    case MAGENTA = '35';
    case CYAN = '36';
    case WHITE = '37';
    case GRAY = '90';
    case BOLD = '1';  // Ajout pour correspondre à la fonction color()

    public function getAnsiCode(): string
    {
        return "\033[".$this->value.'m';
    }

    public function getTagName(): string
    {
        return strtolower($this->name);
    }
}
