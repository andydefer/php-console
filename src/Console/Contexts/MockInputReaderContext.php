<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Contexts;

final class MockInputReaderContext
{
    private array $config = [];

    private array $queue = [];

    private mixed $testStream = null;

    private int $readCount = 0;

    private string $lastLine = '';

    private bool $lastSecret = false;

    private ?int $lastTimeout = null;

    private bool $lastTimedOut = false;

    public function addConfig(string $key, string $value): void
    {
        $this->config[$key] = $value;
    }

    public function getConfig(string $key): ?string
    {
        return $this->config[$key] ?? null;
    }

    public function addQueue(string $key, array $values): void
    {
        $this->queue[$key] = $values;
    }

    public function getQueue(string $key): array
    {
        return $this->queue[$key] ?? [];
    }

    public function setQueue(string $key, array $values): void
    {
        $this->queue[$key] = $values;
    }

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
        $this->config = [];
        $this->queue = [];
        $this->readCount = 0;
        $this->lastLine = '';
        $this->lastSecret = false;
        $this->lastTimeout = null;
        $this->lastTimedOut = false;
    }
}
