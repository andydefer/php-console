<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components;

use AndyDefer\ConsoleWriter\Console\Abstracts\Component;
use AndyDefer\PhpVo\ValueObjects\Types\FloatVO;
use AndyDefer\PhpVo\ValueObjects\Types\StringVO;

/**
 * Component for rendering separator lines in the console.
 *
 * Provides various styles of separators: simple, double, with title, etc.
 *
 * @example
 * echo Separator::render();              // '--------------------------------------------------------------------------------'
 * echo Separator::renderDouble();        // '================================================================================'
 * echo Separator::renderWithTitle('Header'); // '----------------------------------- Header -----------------------------------'
 */
final class Separator extends Component
{
    private const DEFAULT_LENGTH = 80;

    private const DEFAULT_CHAR = '-';

    private const DEFAULT_COLOR = 'gray';

    private const DOUBLE_CHAR = '=';

    /**
     * Renders a standard separator line.
     *
     * @param  int  $length  The length of the separator (default: 80)
     * @param  string  $color  The color of the separator (default: 'gray')
     */
    public static function render(int $length = self::DEFAULT_LENGTH, string $color = self::DEFAULT_COLOR): string
    {
        return self::renderSeparator(
            character: self::DEFAULT_CHAR,
            length: $length,
            color: $color
        );
    }

    /**
     * Renders a separator with a custom character.
     *
     * @param  string  $character  The character to repeat
     * @param  int  $length  The length of the separator (default: 80)
     * @param  string  $color  The color of the separator (default: 'gray')
     */
    public static function renderWithChar(string $character, int $length = self::DEFAULT_LENGTH, string $color = self::DEFAULT_COLOR): string
    {
        return self::renderSeparator(
            character: $character,
            length: $length,
            color: $color
        );
    }

    /**
     * Renders a double separator line (using '=').
     *
     * @param  int  $length  The length of the separator (default: 80)
     * @param  string  $color  The color of the separator (default: 'gray')
     */
    public static function renderDouble(int $length = self::DEFAULT_LENGTH, string $color = self::DEFAULT_COLOR): string
    {
        return self::renderSeparator(
            character: self::DOUBLE_CHAR,
            length: $length,
            color: $color
        );
    }

    /**
     * Renders a separator with a centered title.
     *
     * @param  string  $title  The title to display in the center
     * @param  string  $character  The character to repeat (default: '-')
     * @param  int  $length  The length of the separator (default: 80)
     * @param  string  $color  The color of the separator (default: 'gray')
     */
    public static function renderWithTitle(
        string $title,
        string $character = self::DEFAULT_CHAR,
        int $length = self::DEFAULT_LENGTH,
        string $color = self::DEFAULT_COLOR
    ): string {
        $titleVO = StringVO::from($title);
        $titleLength = FloatVO::from(mb_strlen($titleVO->getValue()));

        // Calculate padding on each side
        $totalPadding = FloatVO::from($length)->subtract($titleLength)->subtract(FloatVO::from(4)); // 4 = spaces around title
        $leftPadding = FloatVO::from(max(0, $totalPadding->toInt() / 2));
        $rightPadding = FloatVO::from(max(0, $totalPadding->toInt() - $leftPadding->toInt()));

        $left = str_repeat($character, $leftPadding->toInt());
        $right = str_repeat($character, $rightPadding->toInt());

        $separator = $left.'  '.$titleVO->getValue().'  '.$right;

        return self::fg($separator, $color);
    }

    /**
     * Core method to render a separator line.
     */
    private static function renderSeparator(string $character, int $length, string $color): string
    {
        $separator = str_repeat($character, $length);

        return self::fg($separator, $color);
    }
}
