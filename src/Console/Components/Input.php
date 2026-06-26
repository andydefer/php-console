<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components;

use AndyDefer\ConsoleWriter\Console\Components\Input\Ask;
use AndyDefer\ConsoleWriter\Console\Components\Input\Choice;
use AndyDefer\ConsoleWriter\Console\Components\Input\Confirm;
use AndyDefer\ConsoleWriter\Console\Components\Input\ConfirmWithTimeout;
use AndyDefer\ConsoleWriter\Console\Components\Input\MultiChoice;
use AndyDefer\ConsoleWriter\Console\Components\Input\Number;
use AndyDefer\ConsoleWriter\Console\Components\Input\Secret;
use AndyDefer\ConsoleWriter\Console\Components\Input\Suggest;
use AndyDefer\ConsoleWriter\Console\Contracts\InputReaderInterface;
use AndyDefer\ConsoleWriter\Console\Services\AnsiConverterService;
use AndyDefer\ConsoleWriter\Console\Services\StandardInputReaderService;
use AndyDefer\ConsoleWriter\Contracts\Services\AnsiConverterInterface;

final class Input
{
    private AnsiConverterInterface $ansi;

    private InputReaderInterface $reader;

    public function __construct(
        ?AnsiConverterInterface $ansi = null,
        ?InputReaderInterface $reader = null
    ) {
        $this->ansi = $ansi ?? new AnsiConverterService;
        $this->reader = $reader ?? new StandardInputReaderService;
    }

    public function getReader(): InputReaderInterface
    {
        return $this->reader;
    }

    public function ask(string $question, ?string $default = null, string $color = 'cyan'): string
    {
        return Ask::execute($this->ansi, $this->reader, $question, $default, $color);
    }

    public function secret(string $question, string $color = 'cyan'): string
    {
        return Secret::execute($this->ansi, $this->reader, $question, $color);
    }

    public function confirm(string $question, bool $default = true, string $color = 'cyan'): bool
    {
        return Confirm::execute($this->ansi, $this->reader, $question, $default, $color);
    }

    public function choice(string $question, array $choices, ?int $default = null, string $color = 'cyan'): string
    {
        return Choice::execute($this->ansi, $this->reader, $question, $choices, $default, $color);
    }

    public function suggest(string $question, array $suggestions, string $color = 'cyan'): string
    {
        return Suggest::execute($this->ansi, $this->reader, $question, $suggestions, $color);
    }

    public function number(string $question, ?int $min = null, ?int $max = null, ?int $default = null, string $color = 'cyan'): int
    {
        return Number::execute($this->ansi, $this->reader, $question, $min, $max, $default, $color);
    }

    public function confirmWithTimeout(string $question, int $timeout = 5, bool $default = true, string $color = 'cyan'): bool
    {
        return ConfirmWithTimeout::execute($this->ansi, $this->reader, $question, $timeout, $default, $color);
    }

    public function multiChoice(string $question, array $options, array $selected = [], string $color = 'cyan'): array
    {
        return MultiChoice::execute($this->ansi, $this->reader, $question, $options, $selected, $color);
    }
}
