<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Tests\Unit\Components;

use AndyDefer\ConsoleWriter\Console\Components\Info;
use AndyDefer\ConsoleWriter\Tests\TestCase;

final class InfoTest extends TestCase
{
    public function test_render_info(): void
    {
        $result = Info::render('Hello World');

        $this->assertStringContainsString('ℹ️', $result);
        $this->assertStringContainsString('Hello World', $result);
        $this->assertStringContainsString('<fg=blue>', $result);
    }
}
