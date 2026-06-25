<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Tests\Unit\Components;

use AndyDefer\ConsoleWriter\Console\Components\Title;
use AndyDefer\ConsoleWriter\Tests\TestCase;

final class TitleTest extends TestCase
{
    public function test_render_title(): void
    {
        $result = Title::render('System Status');

        $this->assertStringContainsString('System Status', $result);
        $this->assertStringContainsString('╔', $result);
        $this->assertStringContainsString('╚', $result);
        $this->assertStringContainsString('<fg=cyan>', $result);
        $this->assertStringContainsString('<options=bold>', $result);
    }
}
