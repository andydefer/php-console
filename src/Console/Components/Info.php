<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components;

use AndyDefer\ConsoleWriter\Contracts\Renderable;

final class Info implements Renderable
{
    public static function render(string $message): string
    {
        return '<fg=blue>ℹ️  '.$message.'</fg=blue>';
    }
}
