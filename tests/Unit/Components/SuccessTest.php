<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Tests\Unit\Components;

use AndyDefer\ConsoleWriter\Console\Components\Success;
use PHPUnit\Framework\TestCase;

final class SuccessTest extends TestCase
{
    public function test_render_success(): void
    {
        $result = Success::render('Task completed');
        $clean = preg_replace('/\033\[[0-9;]+m/', '', $result);

        $this->assertStringContainsString('SUCCESS', $clean);
        $this->assertStringContainsString('Task completed', $clean);
    }
}
