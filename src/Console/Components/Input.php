<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components;

use AndyDefer\ConsoleWriter\Console\Abstracts\InteractiveComponent;
use AndyDefer\ConsoleWriter\Console\Components\Input\Ask;
use AndyDefer\ConsoleWriter\Console\Components\Input\Choice;
use AndyDefer\ConsoleWriter\Console\Components\Input\Confirm;
use AndyDefer\ConsoleWriter\Console\Components\Input\ConfirmWithTimeout;
use AndyDefer\ConsoleWriter\Console\Components\Input\MultiChoice;
use AndyDefer\ConsoleWriter\Console\Components\Input\Number;
use AndyDefer\ConsoleWriter\Console\Components\Input\Secret;
use AndyDefer\ConsoleWriter\Console\Components\Input\Suggest;

final class Input extends InteractiveComponent
{
    public function ask(string $question, ?string $default = null, string $color = 'cyan'): string
    {
        return Ask::execute($question, $default, $color);
    }

    public function secret(string $question, string $color = 'cyan'): string
    {
        return Secret::execute($question, $color);
    }

    public function confirm(string $question, bool $default = true, string $color = 'cyan'): bool
    {
        return Confirm::execute($question, $default, $color);
    }

    public function choice(string $question, array $choices, ?int $default = null, string $color = 'cyan'): string
    {
        return Choice::execute($question, $choices, $default, $color);
    }

    public function suggest(string $question, array $suggestions, string $color = 'cyan'): string
    {
        return Suggest::execute($question, $suggestions, $color);
    }

    public function number(string $question, ?int $min = null, ?int $max = null, ?int $default = null, string $color = 'cyan'): int
    {
        return Number::execute($question, $min, $max, $default, $color);
    }

    public function confirmWithTimeout(string $question, int $timeout = 5, bool $default = true, string $color = 'cyan'): bool
    {
        return ConfirmWithTimeout::execute($question, $timeout, $default, $color);
    }

    public function multiChoice(string $question, array $options, array $selected = [], string $color = 'cyan'): array
    {
        return MultiChoice::execute($question, $options, $selected, $color);
    }
}
