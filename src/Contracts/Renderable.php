<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Contracts;

interface Renderable
{
    public static function render(string $message): string;
}
