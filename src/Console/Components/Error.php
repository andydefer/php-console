<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components;

use AndyDefer\ConsoleWriter\Console\Abstracts\Component;
use AndyDefer\ConsoleWriter\Console\Enums\BgColor;
use AndyDefer\ConsoleWriter\Console\Enums\FgColor;
use AndyDefer\ConsoleWriter\Console\Enums\Options;
use AndyDefer\ConsoleWriter\Contracts\Renderable;

final class Error extends Component implements Renderable
{
    public static function render(string $message): string
    {
        $ansi = self::getAnsi();

        // Badge ERROR en rouge avec fond rouge et texte blanc gras
        $badge = $ansi->bgColorEnum(
            $ansi->colorEnum(
                $ansi->option(' ERROR ', Options::BOLD),
                FgColor::WHITE
            ),
            BgColor::RED
        );

        return $badge.' '.$message;
    }
}
