<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Enums;

enum ListStyle: string
{
    case BULLET = '•';      // ● • ◦
    case ARROW = '→';       // → ➜ ➤
    case DASH = '—';        // — – -
    case NUMBER = '1.';     // 1. 2. 3.
    case ALPHA = 'a.';      // a. b. c.
    case ROMAN = 'i.';      // i. ii. iii.
    case CHECK = '✓';       // ✓ ✅
    case CROSS = '✗';       // ✗ ❌
    case STAR = '★';        // ★ ☆
}
