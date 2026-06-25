<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components;

use AndyDefer\ConsoleWriter\Contracts\Renderable;

final class Error implements Renderable
{
    public static function render(string $message): string
    {
        return '<bg=red><fg=white><options=bold> ERROR </options=bold></fg=white></bg=red> '.$message;
    }
}
