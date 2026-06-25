<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\ValueObjects;

use AndyDefer\PhpVo\ValueObjects\Types\StringVO;

final class CleanedTextVO extends StringVO
{
    public function __construct(string $value = '')
    {
        parent::__construct($value);
    }

    /**
     * Retourne une nouvelle instance avec les émojis retirés
     */
    public function withoutEmojis(): self
    {
        $cleaned = preg_replace('/\p{Extended_Pictographic}/u', '', $this->value);

        return new self($cleaned);
    }

    /**
     * Retourne une nouvelle instance avec les émojis remplacés par 2 espaces
     * Cela permet de conserver la largeur d'affichage
     */
    public function withoutEmojisWithSpaces(): self
    {
        // Remplacer chaque émoji par 2 espaces pour conserver la largeur
        $cleaned = preg_replace('/\p{Extended_Pictographic}/u', ' ', $this->value);

        return new self($cleaned);
    }

    /**
     * Retourne une nouvelle instance avec les émojis retirés (alias)
     */
    public function clean(): self
    {
        return $this->withoutEmojis();
    }

    /**
     * Retourne une nouvelle instance avec les émojis remplacés par 2 espaces (alias)
     */
    public function cleanWithSpaces(): self
    {
        return $this->withoutEmojisWithSpaces();
    }

    /**
     * Retourne la valeur brute sans les émojis
     */
    public function getCleanValue(): string
    {
        return preg_replace('/\p{Extended_Pictographic}/u', '  ', $this->value);
    }

    /**
     * Retourne la valeur brute avec les émojis remplacés par 2 espaces
     */
    public function getCleanValueWithSpaces(): string
    {
        return preg_replace('/\p{Extended_Pictographic}/u', '  ', $this->value);
    }

    /**
     * Vérifie si la chaîne contient des émojis
     */
    public function hasEmojis(): bool
    {
        $cleaned = preg_replace('/\p{Extended_Pictographic}/u', '', $this->value);

        return $cleaned !== $this->value;
    }

    /**
     * Compte le nombre d'émojis dans la chaîne
     */
    public function countEmojis(): int
    {
        preg_match_all('/\p{Extended_Pictographic}/u', $this->value, $matches);

        return count($matches[0] ?? []);
    }

    /**
     * Factory method from StringVO
     */
    public static function fromString(StringVO $string): self
    {
        return new self($string->getValue());
    }

    /**
     * Factory method from string
     */
    public static function fromStringValue(string $string): self
    {
        return new self($string);
    }
}
