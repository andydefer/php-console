<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Tests\Unit\Components;

use PHPUnit\Framework\TestCase;

abstract class ComponentTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        ob_start();
    }

    protected function tearDown(): void
    {
        ob_end_clean();
        parent::tearDown();
    }

    protected function getCapturedOutput(): string
    {
        return ob_get_contents() ?: '';
    }

    protected function stripAnsi(string $text): string
    {
        return preg_replace('/\033\[[0-9;]+m/', '', $text);
    }
}
