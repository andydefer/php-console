<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Contexts;

final class InputReaderContext
{
    private mixed $testStream = null;

    private mixed $stdin = null;

    private int $readCount = 0;

    private string $lastLine = '';

    private bool $lastSecret = false;

    private ?int $lastTimeout = null;

    private bool $lastTimedOut = false;

    public function getTestStream(): mixed
    {
        return $this->testStream;
    }

    public function setTestStream(mixed $stream): void
    {
        $this->testStream = $stream;
    }

    public function resetTestStream(): void
    {
        if ($this->testStream !== null) {
            fclose($this->testStream);
            $this->testStream = null;
        }
    }

    public function getStdin(): mixed
    {
        return $this->stdin;
    }

    public function setStdin(mixed $stdin): void
    {
        $this->stdin = $stdin;
    }

    public function getReadCount(): int
    {
        return $this->readCount;
    }

    public function incrementReadCount(): void
    {
        $this->readCount++;
    }

    public function getLastLine(): string
    {
        return $this->lastLine;
    }

    public function setLastLine(string $line): void
    {
        $this->lastLine = $line;
    }

    public function wasLastSecret(): bool
    {
        return $this->lastSecret;
    }

    public function setLastSecret(bool $secret): void
    {
        $this->lastSecret = $secret;
    }

    public function getLastTimeout(): ?int
    {
        return $this->lastTimeout;
    }

    public function setLastTimeout(?int $timeout): void
    {
        $this->lastTimeout = $timeout;
    }

    public function wasLastTimedOut(): bool
    {
        return $this->lastTimedOut;
    }

    public function setLastTimedOut(bool $timedOut): void
    {
        $this->lastTimedOut = $timedOut;
    }

    public function reset(): void
    {
        $this->readCount = 0;
        $this->lastLine = '';
        $this->lastSecret = false;
        $this->lastTimeout = null;
        $this->lastTimedOut = false;
    }
}
