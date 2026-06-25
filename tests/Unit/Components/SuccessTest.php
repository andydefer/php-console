<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Tests\Unit\Components;

use AndyDefer\ConsoleWriter\Console\Components\Success;
use AndyDefer\ConsoleWriter\Tests\TestCase;

final class SuccessTest extends TestCase
{
    public function test_render_success(): void
    {
        $result = Success::render('Task completed');

        $this->assertStringContainsString('✅', $result);
        $this->assertStringContainsString('Task completed', $result);
        $this->assertStringContainsString('<fg=green>', $result);
    }
}
