<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Contracts\Interfaces;

/**
 * Interface for display components (info, success, error, title, alert, separator).
 */
interface RenderableInterface
{
    /**
     * Displays an information message (blue).
     */
    public function info(string $message): self;

    /**
     * Displays a success message (green).
     */
    public function success(string $message): self;

    /**
     * Displays an error message (red with background).
     */
    public function error(string $message): self;

    /**
     * Displays a framed title (cyan bold).
     */
    public function title(string $message): self;

    /**
     * Displays a framed alert (yellow).
     */
    public function alert(string $message): self;

    /**
     * Displays an alert with a custom icon.
     */
    public function alertWithIcon(string $message, string $icon, int $padding = 4): self;

    /**
     * Displays an alert with a custom color.
     */
    public function alertWithColor(string $message, string $color, int $padding = 4): self;

    /**
     * Displays an alert with a custom border.
     */
    public function alertWithBorder(string $message, string $borderChar, string $color = 'yellow', int $padding = 4): self;

    /**
     * Displays a success alert (✅ green).
     */
    public function alertSuccess(string $message): self;

    /**
     * Displays an error alert (❌ red).
     */
    public function alertError(string $message): self;

    /**
     * Displays a warning alert (⚠️ yellow).
     */
    public function alertWarning(string $message): self;

    /**
     * Displays an information alert (ℹ️ blue).
     */
    public function alertInfo(string $message): self;

    /**
     * Displays a separator line.
     *
     * @param  string  $character  The character to repeat (default: '-')
     * @param  int  $length  The length of the separator (default: 80)
     * @param  string  $color  The color of the separator (default: 'gray')
     */
    public function separator(string $character = '-', int $length = 80, string $color = 'gray'): self;

    /**
     * Displays a double separator line.
     *
     * @param  int  $length  The length of the separator (default: 80)
     * @param  string  $color  The color of the separator (default: 'gray')
     */
    public function separatorDouble(int $length = 80, string $color = 'gray'): self;

    /**
     * Displays a titled separator line.
     *
     * @param  string  $title  The title to display in the separator
     * @param  string  $character  The character to repeat (default: '-')
     * @param  int  $length  The length of the separator (default: 80)
     * @param  string  $color  The color of the separator (default: 'gray')
     */
    public function separatorWithTitle(string $title, string $character = '-', int $length = 80, string $color = 'gray'): self;

    /**
     * Adds a raw line (already formatted).
     */
    public function raw(string $line): self;

    /**
     * Adds a plain text line.
     */
    public function line(string $message = ''): self;

    /**
     * Adds line breaks.
     */
    public function newLine(int $count = 1): self;
}
