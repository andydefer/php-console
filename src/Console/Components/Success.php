<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components;

use AndyDefer\ConsoleWriter\Console\Abstracts\Component;
use AndyDefer\ConsoleWriter\Console\Enums\BgColor;
use AndyDefer\ConsoleWriter\Console\Enums\FgColor;
use AndyDefer\ConsoleWriter\Console\Enums\Options;

final class Success extends Component
{
    public static function render(string $message): string
    {
        $ansi = self::getAnsi();

        // Badge SUCCESS en vert avec fond vert et texte blanc gras
        $badge = $ansi->bgColorEnum(
            $ansi->colorEnum(
                $ansi->option(' SUCCESS ', Options::BOLD),
                FgColor::WHITE
            ),
            BgColor::GREEN
        );

        return $badge.' '.$message;
    }
}
