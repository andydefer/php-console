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
 * Interface principale de la Console
 * Combine toutes les interfaces de composants
 */
interface ConsoleInterface extends BufferInterface, InteractiveInterface, ProgressInterface, RenderableInterface, StyledComponentsInterface, SystemInterface {}
