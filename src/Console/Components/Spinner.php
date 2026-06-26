<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components;

final class Spinner
{
    private const FRAMES = ['⠋', '⠙', '⠹', '⠸', '⠼', '⠴', '⠦', '⠧', '⠇', '⠏'];

    private const DOTS = ['', '.', '..', '...'];

    private const FRAME_INTERVAL = 100000;

    private string $message;

    private string $prefix = '';

    private string $suffix = '';

    private bool $running = false;

    private bool $finished = false;

    private int $frameIndex = 0;

    private int $dotIndex = 0;

    private ?int $pid = null;

    public function __construct(string $message, string $prefix = '', string $suffix = '')
    {
        $this->message = $message;
        $this->prefix = $prefix;
        $this->suffix = $suffix;
    }

    /**
     * Démarre le spinner avec une tâche
     */
    public function start(callable $task): self
    {
        $this->running = true;
        $this->finished = false;
        $this->frameIndex = 0;
        $this->dotIndex = 0;

        // Démarrer l'animation dans un processus séparé
        if (function_exists('pcntl_fork')) {
            $pid = pcntl_fork();

            if ($pid === -1) {
                return $this->startSync($task);
            }

            if ($pid === 0) {
                // Processus enfant : animation du spinner
                $this->animate();
                exit(0);
            }

            $this->pid = $pid;

            // Processus parent : exécute la tâche
            try {
                $task($this);
            } catch (\Exception $e) {
                $this->running = false;
                $this->killChild();
                $this->error($e->getMessage());
                throw $e;
            }

            // Arrêter l'animation
            $this->running = false;
            $this->killChild();

            if (! $this->finished) {
                $this->success();
            }

            return $this;
        }

        return $this->startSync($task);
    }

    /**
     * Tue le processus enfant
     */
    private function killChild(): void
    {
        if ($this->pid !== null) {
            posix_kill($this->pid, SIGTERM);
            pcntl_wait($status);
            $this->pid = null;
        }
    }

    /**
     * Version synchrone (sans fork)
     */
    public function startSync(callable $task): self
    {
        $this->running = true;
        $this->finished = false;
        $this->frameIndex = 0;
        $this->dotIndex = 0;

        // Exécuter la tâche
        try {
            $task($this);
        } catch (\Exception $e) {
            $this->running = false;
            $this->error($e->getMessage());
            throw $e;
        }

        // Arrêter l'animation
        $this->running = false;

        if (! $this->finished) {
            $this->success();
        }

        return $this;
    }

    /**
     * Exécute le spinner en attendant une condition
     */
    public function wait(callable $isComplete, int $checkInterval = 500000): self
    {
        $this->running = true;
        $this->finished = false;
        $this->frameIndex = 0;
        $this->dotIndex = 0;

        // Boucle de vérification
        while ($this->running) {
            $this->render();
            usleep($checkInterval);

            if ($isComplete()) {
                $this->running = false;
                $this->success();
                break;
            }
        }

        return $this;
    }

    /**
     * Boucle d'animation
     */
    private function animate(): void
    {
        while ($this->running) {
            $this->render();
            usleep(self::FRAME_INTERVAL);
        }
    }

    /**
     * Rendu du spinner
     */
    private function render(): void
    {
        $frame = self::FRAMES[$this->frameIndex % count(self::FRAMES)];
        $dots = self::DOTS[$this->dotIndex % count(self::DOTS)];

        $output = '';

        if ($this->prefix !== '') {
            $output .= $this->prefix.' ';
        }

        $output .= $frame.' '.$this->message.$dots;

        if ($this->suffix !== '') {
            $output .= ' '.$this->suffix;
        }

        echo "\r\033[K".$output;

        $this->frameIndex++;
        $this->dotIndex++;
    }

    public function success(string $message = ''): self
    {
        $this->running = false;
        $this->finished = true;
        $this->killChild();
        $this->stop('✅', $message);

        return $this;
    }

    public function error(string $message = ''): self
    {
        $this->running = false;
        $this->finished = true;
        $this->killChild();
        $this->stop('❌', $message);

        return $this;
    }

    public function info(string $message = ''): self
    {
        $this->running = false;
        $this->finished = true;
        $this->killChild();
        $this->stop('ℹ️', $message);

        return $this;
    }

    public function warning(string $message = ''): self
    {
        $this->running = false;
        $this->finished = true;
        $this->killChild();
        $this->stop('⚠️', $message);

        return $this;
    }

    public function stop(string $icon = '✅', string $message = ''): self
    {
        $finalMessage = $message !== '' ? $message : $this->message;
        $output = $icon.' '.$finalMessage;

        if ($this->suffix !== '') {
            $output .= ' '.$this->suffix;
        }

        echo "\r\033[K".$output.PHP_EOL;

        return $this;
    }

    public function isRunning(): bool
    {
        return $this->running;
    }

    public function isFinished(): bool
    {
        return $this->finished;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Nettoie le processus enfant (appelé en destructeur)
     */
    public function __destruct()
    {
        $this->killChild();
    }
}
