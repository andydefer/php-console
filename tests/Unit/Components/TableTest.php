<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Tests\Unit\Components;

use AndyDefer\ConsoleWriter\Console\Components\Table;
use AndyDefer\DomainStructures\Utils\ListCollection;

final class TableTest extends ComponentTestCase
{
    public function test_render_table_with_arrays(): void
    {
        $headers = ListCollection::from(['Name', 'Age', 'City']);
        $rows = ListCollection::from([
            ['Alice', '30', 'Paris'],
            ['Bob', '25', 'London'],
        ]);

        $result = Table::render($headers, $rows);
        $plainResult = $this->stripAnsi($result);

        $this->assertStringContainsString('Name', $plainResult);
        $this->assertStringContainsString('Age', $plainResult);
        $this->assertStringContainsString('City', $plainResult);
        $this->assertStringContainsString('Alice', $plainResult);
        $this->assertStringContainsString('Bob', $plainResult);
        $this->assertStringContainsString('┌', $plainResult);
        $this->assertStringContainsString('└', $plainResult);
    }

    public function test_render_empty_table(): void
    {
        $headers = ListCollection::from(['Name']);
        $rows = ListCollection::from([]);

        $result = Table::render($headers, $rows);
        $plainResult = $this->stripAnsi($result);

        $this->assertStringContainsString('No data to display', $plainResult);
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
        $plainResult = $this->stripAnsi($result);

        $this->assertStringContainsString('ID', $plainResult);
        $this->assertStringContainsString('Name', $plainResult);
        $this->assertStringContainsString('Active', $plainResult);
        $this->assertStringContainsString('1', $plainResult);
        $this->assertStringContainsString('John Doe', $plainResult);
        $this->assertStringContainsString('2', $plainResult);
        $this->assertStringContainsString('Jane Smith', $plainResult);
        $this->assertStringContainsString('3', $plainResult);
        $this->assertStringContainsString('Bob Johnson', $plainResult);
    }

    public function test_render_table_with_different_column_widths(): void
    {
        $headers = ListCollection::from(['Short', 'VeryLongHeader', 'Med']);
        $rows = ListCollection::from([
            ListCollection::from(['A', 'Very long content here', 'XYZ']),
            ListCollection::from(['B', 'Short', 'ABCDEFGHIJ']),
        ]);

        $result = Table::render($headers, $rows);
        $plainResult = $this->stripAnsi($result);

        $this->assertStringContainsString('Short', $plainResult);
        $this->assertStringContainsString('VeryLongHeader', $plainResult);
        $this->assertStringContainsString('Med', $plainResult);
        $this->assertStringContainsString('Very long content here', $plainResult);
        $this->assertStringContainsString('ABCDEFGHIJ', $plainResult);
    }
}
