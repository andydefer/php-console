<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Tests\Unit\Components;

use AndyDefer\ConsoleWriter\Console\Components\Table;
use AndyDefer\ConsoleWriter\Tests\TestCase;
use AndyDefer\DomainStructures\Utils\ListCollection;

final class TableTest extends TestCase
{
    public function test_render_table_with_arrays(): void
    {
        $headers = ListCollection::from(['Name', 'Age', 'City']);
        $rows = ListCollection::from([
            ['Alice', '30', 'Paris'],
            ['Bob', '25', 'London'],
        ]);

        $result = Table::render($headers, $rows);

        $this->assertStringContainsString('Name', $result);
        $this->assertStringContainsString('Age', $result);
        $this->assertStringContainsString('City', $result);
        $this->assertStringContainsString('Alice', $result);
        $this->assertStringContainsString('Bob', $result);
        $this->assertStringContainsString('┌', $result);
        $this->assertStringContainsString('└', $result);
        $this->assertStringContainsString('<fg=cyan>', $result);
        $this->assertStringContainsString('</fg=cyan>', $result);
        $this->assertStringContainsString('<options=bold>', $result);
        $this->assertStringContainsString('</options=bold>', $result);
    }

    public function test_render_empty_table(): void
    {
        $headers = ListCollection::from(['Name']);
        $rows = ListCollection::from([]);

        $result = Table::render($headers, $rows);

        $this->assertStringContainsString('No data to display', $result);
        $this->assertStringContainsString('<fg=yellow>', $result);
        $this->assertStringContainsString('</fg=yellow>', $result);
    }

    public function test_render_table_with_mixed_data_types(): void
    {
        $headers = ListCollection::from(['ID', 'Name', 'Active']);
        $rows = ListCollection::from([
            ListCollection::from([1, 'John Doe', true]),
            ListCollection::from([2, 'Jane Smith', false]),
            ListCollection::from([3, 'Bob Johnson', true]),
        ]);

        $result = Table::render($headers, $rows);

        $this->assertStringContainsString('ID', $result);
        $this->assertStringContainsString('Name', $result);
        $this->assertStringContainsString('Active', $result);
        $this->assertStringContainsString('1', $result);
        $this->assertStringContainsString('John Doe', $result);
        $this->assertStringContainsString('2', $result);
        $this->assertStringContainsString('Jane Smith', $result);
        $this->assertStringContainsString('3', $result);
        $this->assertStringContainsString('Bob Johnson', $result);
    }

    public function test_render_table_with_different_column_widths(): void
    {
        $headers = ListCollection::from(['Short', 'VeryLongHeader', 'Med']);
        $rows = ListCollection::from([
            ListCollection::from(['A', 'Very long content here', 'XYZ']),
            ListCollection::from(['B', 'Short', 'ABCDEFGHIJ']),
        ]);

        $result = Table::render($headers, $rows);

        $this->assertStringContainsString('Short', $result);
        $this->assertStringContainsString('VeryLongHeader', $result);
        $this->assertStringContainsString('Med', $result);
        $this->assertStringContainsString('Very long content here', $result);
        $this->assertStringContainsString('ABCDEFGHIJ', $result);
    }
}
