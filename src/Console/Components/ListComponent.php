<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components;

use AndyDefer\ConsoleWriter\Console\Abstracts\Component;
use AndyDefer\ConsoleWriter\Console\Enums\ListStyle;
use AndyDefer\DomainStructures\Utils\ListCollection;
use AndyDefer\DomainStructures\Utils\SetCollection;
use AndyDefer\PhpVo\ValueObjects\Types\FloatVO;
use AndyDefer\PhpVo\ValueObjects\Types\StringVO;

/**
 * Affiche une liste avec différents styles
 *
 * @example
 * ListComponent::render(
 *     SetCollection::from(['Item 1', 'Item 2', 'Item 3']),
 *     ListStyle::BULLET
 * )
 * // Sortie:
 * // • Item 1
 * // • Item 2
 * // • Item 3
 */
final class ListComponent extends Component
{
    private const INDENT = '  ';

    public static function render(SetCollection $items, ListStyle $style = ListStyle::BULLET, int $indent = 0): string
    {
        if ($items->isEmpty()) {
            return self::fg('⚠️  No items to display', 'yellow');
        }

        $padding = StringVO::from('')->concat(str_repeat(self::INDENT, $indent));
        $lines = ListCollection::from([]);
        $itemsArray = $items->toArray();
        $total = FloatVO::from(count($itemsArray));

        foreach ($itemsArray as $index => $item) {
            $position = FloatVO::from($index + 1);
            $prefix = self::getPrefix($style, $position, $total);
            $line = $padding->concat($prefix)->concat(StringVO::from($item));
            $lines = $lines->add($line->getValue());
        }

        return $lines->reduce(
            fn ($carry, $line) => $carry === '' ? $line : $carry.PHP_EOL.$line,
            ''
        );
    }

    /**
     * Liste avec des puces colorées
     */
    public static function renderColored(SetCollection $items, ListStyle $style = ListStyle::BULLET, string $color = 'green'): string
    {
        if ($items->isEmpty()) {
            return self::fg('⚠️  No items to display', 'yellow');
        }

        $lines = ListCollection::from([]);
        $itemsArray = $items->toArray();
        $total = FloatVO::from(count($itemsArray));

        foreach ($itemsArray as $index => $item) {
            $position = FloatVO::from($index + 1);
            $prefix = self::getPrefix($style, $position, $total);
            $line = StringVO::from('')
                ->concat(self::fg($prefix, $color))
                ->concat(StringVO::from($item));
            $lines = $lines->add($line->getValue());
        }

        return $lines->reduce(
            fn ($carry, $line) => $carry === '' ? $line : $carry.PHP_EOL.$line,
            ''
        );
    }

    private static function getPrefix(ListStyle $style, FloatVO $position, FloatVO $total): string
    {
        $pos = $position->toInt();

        return match ($style) {
            ListStyle::BULLET => '• ',
            ListStyle::ARROW => '→ ',
            ListStyle::DASH => '— ',
            ListStyle::CHECK => '✓ ',
            ListStyle::CROSS => '✗ ',
            ListStyle::STAR => '★ ',
            ListStyle::NUMBER => $pos.'. ',
            ListStyle::ALPHA => self::alphaPrefix($pos),
            ListStyle::ROMAN => self::romanPrefix($pos),
        };
    }

    private static function alphaPrefix(int $position): string
    {
        $letters = range('a', 'z');
        $index = ($position - 1) % 26;
        $repetitions = (int) floor(($position - 1) / 26);

        $prefix = str_repeat($letters[$index], $repetitions + 1);

        return $prefix.'. ';
    }

    private static function romanPrefix(int $position): string
    {
        $romanNumerals = [
            1 => 'i', 2 => 'ii', 3 => 'iii', 4 => 'iv', 5 => 'v',
            6 => 'vi', 7 => 'vii', 8 => 'viii', 9 => 'ix', 10 => 'x',
            11 => 'xi', 12 => 'xii', 13 => 'xiii', 14 => 'xiv', 15 => 'xv',
            16 => 'xvi', 17 => 'xvii', 18 => 'xviii', 19 => 'xix', 20 => 'xx',
        ];

        if ($position <= 20) {
            return $romanNumerals[$position].'. ';
        }

        return $position.'. ';
    }
}
