<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Tests\Unit\Components;

use AndyDefer\ConsoleWriter\Console\Components\Info;
use PHPUnit\Framework\TestCase;

final class InfoTest extends TestCase
{
    public function test_render_info(): void
    {
        $result = Info::render('Hello World');
        $clean = preg_replace('/\033\[[0-9;]+m/', '', $result);

        $this->assertStringContainsString('INFO', $clean);
        $this->assertStringContainsString('Hello World', $clean);
    }
}
