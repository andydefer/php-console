<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Tests\Unit\Components;

use AndyDefer\ConsoleWriter\Console\Components\ListComponent;
use AndyDefer\ConsoleWriter\Console\Enums\ListStyle;
use AndyDefer\DomainStructures\Utils\SetCollection;
use PHPUnit\Framework\TestCase;

final class ListComponentTest extends TestCase
{
    private function stripAnsi(string $text): string
    {
        return preg_replace('/\033\[[0-9;]+m/', '', $text);
    }

    public function test_render_bullet_list(): void
    {
        $items = SetCollection::from(['Item 1', 'Item 2', 'Item 3']);
        $result = ListComponent::render($items, ListStyle::BULLET);
        $clean = $this->stripAnsi($result);

        $this->assertStringContainsString('• Item 1', $clean);
        $this->assertStringContainsString('• Item 2', $clean);
        $this->assertStringContainsString('• Item 3', $clean);
    }

    public function test_render_arrow_list(): void
    {
        $items = SetCollection::from(['Item 1', 'Item 2']);
        $result = ListComponent::render($items, ListStyle::ARROW);
        $clean = $this->stripAnsi($result);

        $this->assertStringContainsString('→ Item 1', $clean);
        $this->assertStringContainsString('→ Item 2', $clean);
    }

    public function test_render_numbered_list(): void
    {
        $items = SetCollection::from(['First', 'Second', 'Third']);
        $result = ListComponent::render($items, ListStyle::NUMBER);
        $clean = $this->stripAnsi($result);

        $this->assertStringContainsString('1. First', $clean);
        $this->assertStringContainsString('2. Second', $clean);
        $this->assertStringContainsString('3. Third', $clean);
    }

    public function test_render_empty_list(): void
    {
        $items = SetCollection::from([]);
        $result = ListComponent::render($items);
        $clean = $this->stripAnsi($result);

        $this->assertStringContainsString('No items to display', $clean);
    }

    public function test_render_colored_list(): void
    {
        $items = SetCollection::from(['Item 1', 'Item 2']);
        $result = ListComponent::renderColored($items, ListStyle::BULLET, 'red');
        $clean = $this->stripAnsi($result);

        // Vérifier le texte
        $this->assertStringContainsString('• Item 1', $clean);
        $this->assertStringContainsString('• Item 2', $clean);

        // Vérifier les codes ANSI pour la couleur rouge
        $this->assertStringContainsString("\033[31m", $result);
        $this->assertStringContainsString("\033[39m", $result);
    }

    public function test_render_with_indent(): void
    {
        $items = SetCollection::from(['Item 1', 'Item 2']);
        $result = ListComponent::render($items, ListStyle::BULLET, 2);
        $clean = $this->stripAnsi($result);

        $this->assertStringContainsString('    • Item 1', $clean);
        $this->assertStringContainsString('    • Item 2', $clean);
    }
}
