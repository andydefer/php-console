<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Tests\Unit\Components;

use AndyDefer\ConsoleWriter\Console\Components\Alert;
use AndyDefer\ConsoleWriter\Tests\TestCase;

final class AlertTest extends TestCase
{
    public function test_render_alert(): void
    {
        $result = Alert::render('Important message');

        $this->assertStringContainsString('⚠️', $result);
        $this->assertStringContainsString('Important message', $result);
        $this->assertStringContainsString('┌', $result);
        $this->assertStringContainsString('└', $result);
        $this->assertStringContainsString('<fg=yellow>', $result);
        $this->assertStringContainsString('</fg=yellow>', $result);
    }
}
