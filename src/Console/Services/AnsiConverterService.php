<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Services;

use AndyDefer\ConsoleWriter\Console\Enums\BgColor;
use AndyDefer\ConsoleWriter\Console\Enums\FgColor;
use AndyDefer\ConsoleWriter\Console\Enums\Options;
use AndyDefer\ConsoleWriter\Contracts\Services\AnsiConverterInterface;

final class AnsiConverterService implements AnsiConverterInterface
{
    private const RESET = "\033[0m";

    private const FG_RESET = "\033[39m";

    private const BG_RESET = "\033[49m";

    /**
     * Mapping des noms de couleurs pour la fonction color()
     */
    private const COLOR_MAP = [
        'black' => '30',
        'red' => '31',
        'green' => '32',
        'yellow' => '33',
        'blue' => '34',
        'magenta' => '35',
        'cyan' => '36',
        'white' => '37',
        'gray' => '90',
        'bold' => '1',
    ];

    private const BG_COLOR_MAP = [
        'black' => '40',
        'red' => '41',
        'green' => '42',
        'yellow' => '43',
        'blue' => '44',
        'magenta' => '45',
        'cyan' => '46',
        'white' => '47',
    ];

    public function convert(string $text): string
    {
        // Couleurs de texte (via enum)
        foreach (FgColor::cases() as $color) {
            $text = str_replace(
                '<fg='.$color->getTagName().'>',
                $color->getAnsiCode(),
                $text
            );
        }

        // Couleurs de fond (via enum)
        foreach (BgColor::cases() as $color) {
            $text = str_replace(
                '<bg='.$color->getTagName().'>',
                $color->getAnsiCode(),
                $text
            );
        }

        // Options (via enum)
        foreach (Options::cases() as $option) {
            $text = str_replace(
                '<options='.$option->getTagName().'>',
                $option->getAnsiCode(),
                $text
            );
            $text = str_replace(
                '</options='.$option->getTagName().'>',
                $option->getResetCode(),
                $text
            );
        }

        // Fermetures génériques
        $text = preg_replace('/<\/fg=[^>]+>/', self::FG_RESET, $text);
        $text = preg_replace('/<\/bg=[^>]+>/', self::BG_RESET, $text);
        $text = preg_replace('/<\/options=[^>]+>/', "\033[22m", $text);
        $text = preg_replace('/<\/[^>]+>/', self::RESET, $text);

        return $text.self::RESET;
    }

    /**
     * Colore un texte avec une couleur (style fonction color())
     * Correspond à la fonction color() de l'exemple
     */
    public function color(string $text, string $color): string
    {
        $code = self::COLOR_MAP[$color] ?? '37';

        return "\033[".$code.'m'.$text."\033[0m";
    }

    /**
     * Colore un texte avec une couleur de fond
     * Correspond à la fonction bgColor() de l'exemple
     */
    public function bgColor(string $text, string $color): string
    {
        $code = self::BG_COLOR_MAP[$color] ?? '47';

        return "\033[".$code.'m'.$text."\033[0m";
    }

    /**
     * Colore un texte avec une couleur de premier plan (via enum)
     */
    public function colorEnum(string $text, FgColor $color): string
    {
        return $color->getAnsiCode().$text.self::FG_RESET;
    }

    /**
     * Colore un texte avec une couleur de fond (via enum)
     */
    public function bgColorEnum(string $text, BgColor $color): string
    {
        return $color->getAnsiCode().$text.self::BG_RESET;
    }

    /**
     * Applique une option (via enum)
     */
    public function option(string $text, Options $option): string
    {
        return $option->getAnsiCode().$text.$option->getResetCode();
    }

    /**
     * Style combiné avec plusieurs options (via enum)
     */
    public function style(string $text, ?FgColor $fg = null, ?BgColor $bg = null, Options ...$options): string
    {
        $codes = [];

        if ($fg !== null) {
            $codes[] = $fg->getAnsiCode();
        }
        if ($bg !== null) {
            $codes[] = $bg->getAnsiCode();
        }
        foreach ($options as $option) {
            $codes[] = $option->getAnsiCode();
        }

        $open = implode('', $codes);
        $close = self::RESET;

        return $open.$text.$close;
    }

    public function reset(): string
    {
        return self::RESET;
    }

    public function stripTags(string $text): string
    {
        return preg_replace('/<[^>]+>/', '', $text);
    }
}
