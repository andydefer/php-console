<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Enums;

enum SoundType: string
{
    case SUCCESS = 'success';
    case ERROR = 'error';
    case INFO = 'info';

    /**
     * Retourne le chemin du fichier son
     */
    public function getFilePath(): string
    {
        $basePath = __DIR__.'/../../../assets/notifications/';

        return $basePath.$this->value.'.mp3';
    }

    /**
     * Vérifie si le fichier son existe
     */
    public function fileExists(): bool
    {
        return file_exists($this->getFilePath());
    }
}
