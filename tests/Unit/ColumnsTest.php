<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Tests\Unit\Components;

use AndyDefer\ConsoleWriter\Console\Components\Columns;
use AndyDefer\DomainStructures\Utils\ListCollection;
use PHPUnit\Framework\TestCase;

final class ColumnsTest extends TestCase
{
    public function test_render_columns_with_list_collection(): void
    {
        $columns = ListCollection::from([
            ListCollection::from(['Users', '123']),
            ListCollection::from(['Servers', '5']),
            ListCollection::from(['Logs', '42']),
        ]);

        $result = Columns::render($columns);

        $this->assertStringContainsString('Users', $result);
        $this->assertStringContainsString('Servers', $result);
        $this->assertStringContainsString('Logs', $result);
        $this->assertStringContainsString('123', $result);
        $this->assertStringContainsString('5', $result);
        $this->assertStringContainsString('42', $result);
    }

    public function test_render_columns_with_array(): void
    {
        $columns = [
            ['Users', '123'],
            ['Servers', '5'],
            ['Logs', '42'],
        ];

        $result = Columns::render($columns);

        $this->assertStringContainsString('Users', $result);
        $this->assertStringContainsString('Servers', $result);
        $this->assertStringContainsString('Logs', $result);
        $this->assertStringContainsString('123', $result);
        $this->assertStringContainsString('5', $result);
        $this->assertStringContainsString('42', $result);
    }

    public function test_render_columns_centered(): void
    {
        $columns = [
            ['A', '1'],
            ['Long Title', '100'],
            ['X', '9999'],
        ];

        $result = Columns::render($columns, 15);

        // Vérifier que le texte est centré (espaces avant et après)
        $this->assertMatchesRegularExpression('/\s+A\s+/', $result);
        $this->assertMatchesRegularExpression('/\s+1\s+/', $result);
    }

    public function test_render_with_icons(): void
    {
        $columns = [
            ['📊 Users', '123'],
            ['🖥️ Servers', '5'],
        ];

        $result = Columns::renderWithIcons($columns);

        $this->assertStringContainsString('📊', $result);
        $this->assertStringContainsString('🖥️', $result);
        $this->assertStringContainsString('Users', $result);
        $this->assertStringContainsString('Servers', $result);
        $this->assertStringContainsString('123', $result);
        $this->assertStringContainsString('5', $result);
    }

    public function test_render_with_colors(): void
    {
        $columns = [
            ['Users', '123'],
            ['Servers', '5'],
        ];

        $colors = ['cyan', 'green'];

        $result = Columns::renderWithColors($columns, $colors);

        $this->assertStringContainsString("\033[36m", $result);
        $this->assertStringContainsString("\033[32m", $result);
    }

    public function test_render_with_headers(): void
    {
        $columns = [
            ['Metric', 'Users', 'Servers'],
            ['Value', '123', '5'],
            ['Trend', '↑ 12%', '↓ 2%'],
        ];

        $result = Columns::renderWithHeaders($columns);

        $this->assertStringContainsString('Metric', $result);
        $this->assertStringContainsString('Users', $result);
        $this->assertStringContainsString('Servers', $result);
        $this->assertStringContainsString('123', $result);
        $this->assertStringContainsString('5', $result);
        $this->assertStringContainsString('─', $result);
    }

    public function test_render_compact(): void
    {
        $columns = [
            ['CPU', '45%'],
            ['RAM', '8.2 GB'],
            ['DISK', '256 GB'],
        ];

        $result = Columns::renderCompact($columns);

        $this->assertStringContainsString('CPU', $result);
        $this->assertStringContainsString('45%', $result);
        $this->assertStringContainsString('RAM', $result);
        $this->assertStringContainsString('8.2 GB', $result);
        $this->assertStringContainsString('DISK', $result);
        $this->assertStringContainsString('256 GB', $result);
    }

    public function test_render_empty(): void
    {
        $result = Columns::render([]);

        $this->assertStringContainsString('No data to display', $result);
        $this->assertStringContainsString('⚠️', $result);
    }

    public function test_render_single_column(): void
    {
        $columns = [
            ['Users', '123', 'Active'],
        ];

        $result = Columns::render($columns);

        $this->assertStringContainsString('Users', $result);
        $this->assertStringContainsString('123', $result);
        $this->assertStringContainsString('Active', $result);
    }
}
