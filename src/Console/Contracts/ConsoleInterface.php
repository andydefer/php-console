<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Contracts;

use AndyDefer\ConsoleWriter\Console\Contracts\Interfaces\BufferInterface;
use AndyDefer\ConsoleWriter\Console\Contracts\Interfaces\InteractiveInterface;
use AndyDefer\ConsoleWriter\Console\Contracts\Interfaces\ProgressInterface;
use AndyDefer\ConsoleWriter\Console\Contracts\Interfaces\RenderableInterface;
use AndyDefer\ConsoleWriter\Console\Contracts\Interfaces\StyledComponentsInterface;
use AndyDefer\ConsoleWriter\Console\Contracts\Interfaces\SystemInterface;

/**
 * Interface principale de la console
 * Étend toutes les sous-interfaces pour une API complète
 *
 * @example
 * $console = new Console();
 * $console->title('Dashboard')->info('Loading...')->render();
 */
interface ConsoleInterface extends BufferInterface, InteractiveInterface, ProgressInterface, RenderableInterface, StyledComponentsInterface, SystemInterface {}
