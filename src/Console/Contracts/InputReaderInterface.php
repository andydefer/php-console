<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Contracts;

interface InputReaderInterface
{
    public function readLine(): string;

    public function readSecretLine(): string;

    public function readLineWithTimeout(int $timeout, ?callable $onTick = null): string;

    public function readKey(): string;

    public function readChar(): string;

    public function setInputStream(mixed $stream): void;

    public function resetInputStream(): void;
}
