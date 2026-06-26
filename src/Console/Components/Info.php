<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components;

use AndyDefer\ConsoleWriter\Console\Abstracts\Component;
use AndyDefer\ConsoleWriter\Console\Enums\BgColor;
use AndyDefer\ConsoleWriter\Console\Enums\FgColor;
use AndyDefer\ConsoleWriter\Console\Enums\Options;

final class Info extends Component
{
    public static function render(string $message): string
    {
        $ansi = self::getAnsi();

        // Badge INFO en bleu avec fond bleu et texte blanc gras
        $badge = $ansi->bgColorEnum(
            $ansi->colorEnum(
                $ansi->option(' INFO ', Options::BOLD),
                FgColor::WHITE
            ),
            BgColor::BLUE
        );

        return $badge.' '.$message;
    }
}
