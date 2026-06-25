<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components;

use AndyDefer\ConsoleWriter\Contracts\Renderable;

final class Success implements Renderable
{
    public static function render(string $message): string
    {
        return '<fg=green>✅ '.$message.'</fg=green>';
    }
}
