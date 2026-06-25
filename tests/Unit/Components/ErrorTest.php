<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Tests\Unit\Components;

use AndyDefer\ConsoleWriter\Console\Components\Error;
use AndyDefer\ConsoleWriter\Tests\TestCase;

final class ErrorTest extends TestCase
{
    public function test_render_error(): void
    {
        $result = Error::render('Something went wrong');

        $this->assertStringContainsString('ERROR', $result);
        $this->assertStringContainsString('Something went wrong', $result);
        $this->assertStringContainsString('<bg=red>', $result);
        $this->assertStringContainsString('<fg=white>', $result);
        $this->assertStringContainsString('<options=bold>', $result);
        $this->assertStringContainsString('</options=bold>', $result);
        $this->assertStringContainsString('</fg=white>', $result);
        $this->assertStringContainsString('</bg=red>', $result);
    }
}
