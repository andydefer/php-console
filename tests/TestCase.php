<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    // Plus besoin de Writer::clear() car on n'utilise plus l'API statique
    protected function setUp(): void
    {
        parent::setUp();
        // Rien à nettoyer
    }

    protected function assertOutputContains(string $expected, string $output): void
    {
        $this->assertStringContainsString($expected, $output);
    }
}
