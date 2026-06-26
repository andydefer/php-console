<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Services;

use AndyDefer\ConsoleWriter\Console\Contracts\InputReaderInterface;

final class StandardInputReaderService implements InputReaderInterface
{
    private mixed $testStream = null;

    private mixed $stdin = null;

    public function readLine(): string
    {
        $stream = $this->getStream();
        $oldStty = shell_exec('stty -g');
        shell_exec('stty -icanon -echo min 1 time 0');

        $buffer = '';

        try {
            while (true) {
                $key = $this->readChar();

                if ($key === 'ENTER') {
                    echo PHP_EOL;
                    break;
                }

                if ($key === 'BACKSPACE') {
                    if ($buffer !== '') {
                        $buffer = mb_substr($buffer, 0, -1);
                        echo "\033[1D\033[K";
                    }

                    continue;
                }

                if ($key === 'ESC' || $key === 'UNKNOWN' || in_array($key, ['UP', 'DOWN', 'LEFT', 'RIGHT'], true)) {
                    continue;
                }

                if ($key === 'SPACE') {
                    $buffer .= ' ';
                    echo ' ';

                    continue;
                }

                $buffer .= $key;
                echo $key;
            }
        } finally {
            if ($oldStty !== null) {
                shell_exec('stty '.$oldStty);
            }
        }

        return $buffer;
    }

    public function readSecretLine(): string
    {
        $stream = $this->getStream();
        $oldStty = shell_exec('stty -g');
        shell_exec('stty -icanon -echo min 1 time 0');

        $buffer = '';

        try {
            while (true) {
                $key = $this->readChar();

                if ($key === 'ENTER') {
                    echo PHP_EOL;
                    break;
                }

                if ($key === 'BACKSPACE') {
                    if ($buffer !== '') {
                        $buffer = mb_substr($buffer, 0, -1);
                    }

                    continue;
                }

                if ($key === 'ESC' || $key === 'UNKNOWN' || in_array($key, ['UP', 'DOWN', 'LEFT', 'RIGHT'], true)) {
                    continue;
                }

                if ($key === 'SPACE') {
                    $buffer .= ' ';

                    continue;
                }

                $buffer .= $key;
            }
        } finally {
            if ($oldStty !== null) {
                shell_exec('stty '.$oldStty);
            }
        }

        return $buffer;
    }

    public function readLineWithTimeout(int $timeout, ?callable $onTick = null): string
    {
        $stream = $this->getStream();
        $oldStty = shell_exec('stty -g');
        shell_exec('stty -icanon -echo min 1 time 0');

        $start = time();
        $buffer = '';

        try {
            while (time() - $start < $timeout) {
                $remaining = $timeout - (time() - $start);

                if ($onTick !== null) {
                    // Modification du callback pour lui passer également l'état actuel du texte saisi ($buffer)
                    $onTick($remaining, $buffer);
                }

                $key = $this->readChar();

                // Sortie immédiate dès que l'utilisateur appuie sur Entrée
                if ($key === 'ENTER') {
                    break;
                }

                if ($key === 'BACKSPACE') {
                    if ($buffer !== '') {
                        $buffer = mb_substr($buffer, 0, -1);
                    }

                    continue;
                }

                if ($key === 'ESC' || $key === 'UNKNOWN' || in_array($key, ['UP', 'DOWN', 'LEFT', 'RIGHT'], true)) {
                    continue;
                }

                if ($key === 'SPACE') {
                    $buffer .= ' ';

                    continue;
                }

                $buffer .= $key;
            }
        } finally {
            if ($oldStty !== null) {
                shell_exec('stty '.$oldStty);
            }
        }

        return $buffer;
    }

    public function readKey(): string
    {
        $stream = $this->getStream();

        $read = [$stream];
        $write = null;
        $except = null;

        if (stream_select($read, $write, $except, 0, 100000) > 0) {
            $char = fread($stream, 3);

            if ($char === false || $char === '') {
                return 'UNKNOWN';
            }

            if (strlen($char) === 3 && $char[0] === "\033" && $char[1] === '[') {
                return match ($char[2]) {
                    'A' => 'UP',
                    'B' => 'DOWN',
                    'C' => 'RIGHT',
                    'D' => 'LEFT',
                    default => 'UNKNOWN'
                };
            }

            if ($char === "\033") {
                return 'ESC';
            }

            return match ($char) {
                ' ' => 'SPACE',
                "\r", "\n" => 'ENTER',
                "\x7f", "\x08" => 'BACKSPACE',
                default => 'UNKNOWN',
            };
        }

        return 'UNKNOWN';
    }

    public function readChar(): string
    {
        $stream = $this->getStream();

        $read = [$stream];
        $write = null;
        $except = null;

        if (stream_select($read, $write, $except, 0, 100000) > 0) {
            $char = fread($stream, 3);

            if ($char === false || $char === '') {
                return 'UNKNOWN';
            }

            if (strlen($char) === 3 && $char[0] === "\033" && $char[1] === '[') {
                return match ($char[2]) {
                    'A' => 'UP',
                    'B' => 'DOWN',
                    'C' => 'RIGHT',
                    'D' => 'LEFT',
                    default => 'UNKNOWN',
                };
            }

            if ($char === "\033") {
                return 'ESC';
            }

            return match ($char) {
                ' ' => 'SPACE',
                "\r", "\n" => 'ENTER',
                "\x7f", "\x08" => 'BACKSPACE',
                default => $char,
            };
        }

        return 'UNKNOWN';
    }

    public function setInputStream(mixed $stream): void
    {
        $this->testStream = $stream;
    }

    public function resetInputStream(): void
    {
        if ($this->testStream !== null) {
            fclose($this->testStream);
            $this->testStream = null;
        }
    }

    private function getStream()
    {
        if ($this->testStream !== null) {
            return $this->testStream;
        }

        if ($this->stdin === null) {
            $this->stdin = fopen('php://stdin', 'r');
        }

        return $this->stdin;
    }

    public function __destruct()
    {
        if ($this->stdin !== null) {
            fclose($this->stdin);
            $this->stdin = null;
        }
    }
}
