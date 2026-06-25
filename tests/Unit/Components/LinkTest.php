<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Tests\Unit\Components;

use AndyDefer\ConsoleWriter\Console\Components\Link;
use PHPUnit\Framework\TestCase;

final class LinkTest extends TestCase
{
    public function test_render_link(): void
    {
        $result = Link::render('https://example.com');

        $this->assertSame(
            '<href=https://example.com>https://example.com</href>',
            $result
        );
    }

    public function test_render_link_with_text(): void
    {
        $result = Link::renderWithText('https://example.com', 'Visit Example');

        $this->assertSame(
            '<href=https://example.com>Visit Example</href>',
            $result
        );
    }
}
