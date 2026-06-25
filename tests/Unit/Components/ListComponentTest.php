<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Tests\Unit\Components;

use AndyDefer\ConsoleWriter\Console\Components\ListComponent;
use AndyDefer\ConsoleWriter\Console\Enums\ListStyle;
use AndyDefer\DomainStructures\Utils\SetCollection;
use PHPUnit\Framework\TestCase;

final class ListComponentTest extends TestCase
{
    public function test_render_bullet_list(): void
    {
        $items = SetCollection::from(['Item 1', 'Item 2', 'Item 3']);
        $result = ListComponent::render($items, ListStyle::BULLET);

        $this->assertStringContainsString('• Item 1', $result);
        $this->assertStringContainsString('• Item 2', $result);
        $this->assertStringContainsString('• Item 3', $result);
    }

    public function test_render_arrow_list(): void
    {
        $items = SetCollection::from(['Item 1', 'Item 2']);
        $result = ListComponent::render($items, ListStyle::ARROW);

        $this->assertStringContainsString('→ Item 1', $result);
        $this->assertStringContainsString('→ Item 2', $result);
    }

    public function test_render_numbered_list(): void
    {
        $items = SetCollection::from(['First', 'Second', 'Third']);
        $result = ListComponent::render($items, ListStyle::NUMBER);

        $this->assertStringContainsString('1. First', $result);
        $this->assertStringContainsString('2. Second', $result);
        $this->assertStringContainsString('3. Third', $result);
    }

    public function test_render_empty_list(): void
    {
        $items = SetCollection::from([]);
        $result = ListComponent::render($items);

        $this->assertStringContainsString('No items to display', $result);
        $this->assertStringContainsString('<fg=yellow>', $result);
    }

    public function test_render_colored_list(): void
    {
        $items = SetCollection::from(['Item 1', 'Item 2']);
        $result = ListComponent::renderColored($items, ListStyle::BULLET, 'red');

        $this->assertStringContainsString('<fg=red>• ', $result);
        $this->assertStringContainsString('Item 1', $result);
        $this->assertStringContainsString('</fg>', $result);
    }

    public function test_render_with_indent(): void
    {
        $items = SetCollection::from(['Item 1', 'Item 2']);
        $result = ListComponent::render($items, ListStyle::BULLET, 2);

        $this->assertStringContainsString('    • Item 1', $result);
        $this->assertStringContainsString('    • Item 2', $result);
    }
}
