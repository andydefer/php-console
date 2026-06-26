<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Tests\Unit\Components;

use AndyDefer\ConsoleWriter\Console\Components\Error;
use PHPUnit\Framework\TestCase;

final class ErrorTest extends TestCase
{
    public function test_render_error(): void
    {
        $result = Error::render('Something went wrong');
        $clean = preg_replace('/\033\[[0-9;]+m/', '', $result);

        $this->assertStringContainsString('ERROR', $clean);
        $this->assertStringContainsString('Something went wrong', $clean);
    }
}
