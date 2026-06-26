<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Tests\Unit;

use AndyDefer\ConsoleWriter\Console\Console;
use AndyDefer\ConsoleWriter\Console\Enums\ListStyle;
use AndyDefer\DomainStructures\Utils\ListCollection;
use AndyDefer\DomainStructures\Utils\MapCollection;
use AndyDefer\DomainStructures\Utils\SetCollection;
use PHPUnit\Framework\TestCase;

final class ConsoleTest extends TestCase
{
    private Console $console;

    protected function setUp(): void
    {
        parent::setUp();

        // Démarrer le buffer de sortie pour capturer tout ce qui serait affiché
        ob_start();

        $this->console = new Console;
        $this->console->startBuffer();
    }

    protected function tearDown(): void
    {
        $this->console->render();

        // Vider et arrêter le buffer de sortie
        ob_end_clean();

        parent::tearDown();
    }

    // ========== TESTS DE BASE ==========

    public function test_info(): void
    {
        $this->console->info('Hello World');

        $lines = $this->console->getLines();
        $this->assertCount(1, $lines);
        $this->assertStringContainsString('ℹ️', $lines[0]);
        $this->assertStringContainsString('Hello World', $lines[0]);
        $this->assertStringContainsString('<fg=blue>', $lines[0]);
        $this->assertStringContainsString('</fg=blue>', $lines[0]);
    }

    public function test_success(): void
    {
        $this->console->success('Task completed');

        $lines = $this->console->getLines();
        $this->assertCount(1, $lines);
        $this->assertStringContainsString('✅', $lines[0]);
        $this->assertStringContainsString('Task completed', $lines[0]);
        $this->assertStringContainsString('<fg=green>', $lines[0]);
        $this->assertStringContainsString('</fg=green>', $lines[0]);
    }

    public function test_error(): void
    {
        $this->console->error('Something went wrong');

        $lines = $this->console->getLines();
        $this->assertCount(1, $lines);
        $this->assertStringContainsString('ERROR', $lines[0]);
        $this->assertStringContainsString('Something went wrong', $lines[0]);
        $this->assertStringContainsString('<bg=red>', $lines[0]);
        $this->assertStringContainsString('<fg=white>', $lines[0]);
        $this->assertStringContainsString('<options=bold>', $lines[0]);
    }

    public function test_alert(): void
    {
        $this->console->alert('Important message');

        $lines = $this->console->getLines();
        $this->assertCount(1, $lines);
        $this->assertStringContainsString('⚠️', $lines[0]);
        $this->assertStringContainsString('Important message', $lines[0]);
        $this->assertStringContainsString('┌', $lines[0]);
        $this->assertStringContainsString('└', $lines[0]);
        $this->assertStringContainsString('<fg=yellow>', $lines[0]);
        $this->assertStringContainsString('</fg=yellow>', $lines[0]);
    }

    public function test_title(): void
    {
        $this->console->title('System Status');

        $lines = $this->console->getLines();
        $this->assertCount(1, $lines);
        $this->assertStringContainsString('System Status', $lines[0]);
        $this->assertStringContainsString('╔', $lines[0]);
        $this->assertStringContainsString('╚', $lines[0]);
        $this->assertStringContainsString('<fg=cyan>', $lines[0]);
        $this->assertStringContainsString('</fg=cyan>', $lines[0]);
        $this->assertStringContainsString('<options=bold>', $lines[0]);
        $this->assertStringContainsString('</options=bold>', $lines[0]);
    }

    // ========== TESTS DE TABLE ==========

    public function test_table_with_arrays(): void
    {
        $this->console->table(
            ['Name', 'Age', 'City'],
            [
                ['Alice', '30', 'Paris'],
                ['Bob', '25', 'London'],
            ]
        );

        $lines = $this->console->getLines();
        $this->assertCount(1, $lines);
        $this->assertStringContainsString('Name', $lines[0]);
        $this->assertStringContainsString('Age', $lines[0]);
        $this->assertStringContainsString('City', $lines[0]);
        $this->assertStringContainsString('Alice', $lines[0]);
        $this->assertStringContainsString('Bob', $lines[0]);
        $this->assertStringContainsString('┌', $lines[0]);
        $this->assertStringContainsString('└', $lines[0]);
        $this->assertStringContainsString('<fg=cyan>', $lines[0]);
        $this->assertStringContainsString('</fg=cyan>', $lines[0]);
    }

    public function test_table_with_list_collections(): void
    {
        $headers = ListCollection::from(['Product', 'Price', 'Stock']);
        $rows = ListCollection::from([
            ListCollection::from(['Laptop', '999.99', '15']),
            ListCollection::from(['Mouse', '29.99', '42']),
        ]);

        $this->console->table($headers, $rows);

        $lines = $this->console->getLines();
        $this->assertCount(1, $lines);
        $this->assertStringContainsString('Product', $lines[0]);
        $this->assertStringContainsString('Price', $lines[0]);
        $this->assertStringContainsString('Stock', $lines[0]);
        $this->assertStringContainsString('Laptop', $lines[0]);
        $this->assertStringContainsString('Mouse', $lines[0]);
    }

    public function test_table_with_mixed_types(): void
    {
        $this->console->table(
            ['ID', 'Name', 'Active'],
            [
                [1, 'John Doe', true],
                [2, 'Jane Smith', false],
            ]
        );

        $lines = $this->console->getLines();
        $this->assertCount(1, $lines);
        $this->assertStringContainsString('ID', $lines[0]);
        $this->assertStringContainsString('Name', $lines[0]);
        $this->assertStringContainsString('Active', $lines[0]);
        $this->assertStringContainsString('John Doe', $lines[0]);
        $this->assertStringContainsString('Jane Smith', $lines[0]);
    }

    // ========== TESTS DE TABLE AVEC PLUS DE 5 COLONNES ==========

    public function test_table_with_6_columns_adaptive(): void
    {
        $headers = ListCollection::from(['A', 'B', 'C', 'D', 'E', 'F']);
        $rows = ListCollection::from([
            ListCollection::from(['1', '2', '3', '4', '5', '6']),
        ]);

        $this->console->adaptiveTable($headers, $rows);

        $lines = $this->console->getLines();
        $this->assertCount(1, $lines);
        $this->assertStringContainsString('6 colonnes → affichage en liste', $lines[0]);
        $this->assertStringContainsString('A', $lines[0]);
        $this->assertStringContainsString('B', $lines[0]);
        $this->assertStringContainsString('C', $lines[0]);
        $this->assertStringContainsString('D', $lines[0]);
        $this->assertStringContainsString('E', $lines[0]);
        $this->assertStringContainsString('F', $lines[0]);
    }

    // ========== TESTS DE LINK ==========

    public function test_link(): void
    {
        $this->console->link('https://example.com');

        $lines = $this->console->getLines();
        $this->assertCount(1, $lines);
        $this->assertStringContainsString('<href=https://example.com>', $lines[0]);
        $this->assertStringContainsString('https://example.com', $lines[0]);
        $this->assertStringContainsString('</href>', $lines[0]);
    }

    public function test_link_with_text(): void
    {
        $this->console->link('https://example.com', 'Visit Example');

        $lines = $this->console->getLines();
        $this->assertCount(1, $lines);
        $this->assertStringContainsString('<href=https://example.com>', $lines[0]);
        $this->assertStringContainsString('Visit Example', $lines[0]);
        $this->assertStringContainsString('</href>', $lines[0]);
    }

    public function test_link_in_chaining(): void
    {
        $this->console
            ->info('Visit our website:')
            ->link('https://example.com', 'Click here')
            ->line();

        $lines = $this->console->getLines();
        $this->assertCount(3, $lines);
        $this->assertStringContainsString('Visit our website:', $lines[0]);
        $this->assertStringContainsString('<href=https://example.com>Click here</href>', $lines[1]);
        $this->assertSame('', $lines[2]);
    }

    // ========== TESTS DE LIST ==========

    public function test_list_with_arrays(): void
    {
        $this->console->list(
            ['Item 1', 'Item 2', 'Item 3'],
            ListStyle::BULLET
        );

        $lines = $this->console->getLines();
        $this->assertCount(1, $lines);
        $this->assertStringContainsString('• Item 1', $lines[0]);
        $this->assertStringContainsString('• Item 2', $lines[0]);
        $this->assertStringContainsString('• Item 3', $lines[0]);
    }

    public function test_list_with_set_collection(): void
    {
        $items = SetCollection::from(['Apple', 'Banana', 'Cherry']);
        $this->console->list($items, ListStyle::ARROW);

        $lines = $this->console->getLines();
        $this->assertCount(1, $lines);
        $this->assertStringContainsString('→ Apple', $lines[0]);
        $this->assertStringContainsString('→ Banana', $lines[0]);
        $this->assertStringContainsString('→ Cherry', $lines[0]);
    }

    public function test_list_numbered(): void
    {
        $this->console->list(
            ['First', 'Second', 'Third'],
            ListStyle::NUMBER
        );

        $lines = $this->console->getLines();
        $this->assertCount(1, $lines);
        $this->assertStringContainsString('1. First', $lines[0]);
        $this->assertStringContainsString('2. Second', $lines[0]);
        $this->assertStringContainsString('3. Third', $lines[0]);
    }

    public function test_list_with_indent(): void
    {
        $this->console->list(
            ['Item 1', 'Item 2'],
            ListStyle::BULLET,
            2
        );

        $lines = $this->console->getLines();
        $this->assertCount(1, $lines);
        $this->assertStringContainsString('    • Item 1', $lines[0]);
        $this->assertStringContainsString('    • Item 2', $lines[0]);
    }

    public function test_list_colored(): void
    {
        $items = SetCollection::from(['Item 1', 'Item 2']);
        $this->console->listColored($items, ListStyle::CHECK, 'green');

        $lines = $this->console->getLines();
        $this->assertCount(1, $lines);
        $this->assertStringContainsString('<fg=green>✓ </fg>Item 1', $lines[0]);
        $this->assertStringContainsString('<fg=green>✓ </fg>Item 2', $lines[0]);
    }

    public function test_list_all_styles(): void
    {
        $items = ['Item 1', 'Item 2'];

        foreach (ListStyle::cases() as $style) {
            $this->console->clear();
            $this->console->list($items, $style);
            $lines = $this->console->getLines();
            $this->assertCount(1, $lines);
            $this->assertNotEmpty($lines[0]);
        }
    }

    // ========== TESTS DE KEY VALUE ==========

    public function test_key_value_with_array(): void
    {
        $this->console->keyValue([
            'Name' => 'John',
            'Age' => 30,
            'City' => 'Paris',
        ]);

        $lines = $this->console->getLines();
        $this->assertCount(1, $lines);
        $this->assertMatchesRegularExpression('/Name\s+:\s+John/', strip_tags($lines[0]));
        $this->assertMatchesRegularExpression('/Age\s+:\s+30/', strip_tags($lines[0]));
        $this->assertMatchesRegularExpression('/City\s+:\s+Paris/', strip_tags($lines[0]));
        $this->assertStringContainsString('<fg=cyan>', $lines[0]);
        $this->assertStringContainsString('</fg>', $lines[0]);
    }

    public function test_key_value_with_map_collection(): void
    {
        $data = MapCollection::from([
            'Framework' => 'PHP',
            'Version' => '8.2',
            'Status' => 'OK',
        ]);

        $this->console->keyValue($data);

        $lines = $this->console->getLines();
        $this->assertCount(1, $lines);
        $this->assertMatchesRegularExpression('/Framework\s+:\s+PHP/', strip_tags($lines[0]));
        $this->assertMatchesRegularExpression('/Version\s+:\s+8.2/', strip_tags($lines[0]));
        $this->assertMatchesRegularExpression('/Status\s+:\s+OK/', strip_tags($lines[0]));
    }

    public function test_key_value_with_color(): void
    {
        $data = MapCollection::from([
            'Name' => 'John',
            'Age' => 30,
        ]);

        $this->console->keyValueWithColor($data, 'yellow');

        $lines = $this->console->getLines();
        $this->assertCount(1, $lines);
        $this->assertStringContainsString('<fg=yellow>', $lines[0]);
        $this->assertMatchesRegularExpression('/Name\s+:\s+John/', strip_tags($lines[0]));
        $this->assertMatchesRegularExpression('/Age\s+:\s+30/', strip_tags($lines[0]));
    }

    public function test_key_value_with_value_color(): void
    {
        $data = MapCollection::from([
            'Name' => 'John',
            'Age' => 30,
        ]);

        $this->console->keyValueWithValueColor($data, 'green');

        $lines = $this->console->getLines();
        $this->assertCount(1, $lines);
        $this->assertStringContainsString('<fg=green>', $lines[0]);
        $this->assertMatchesRegularExpression('/Name\s+:\s+John/', strip_tags($lines[0]));
        $this->assertMatchesRegularExpression('/Age\s+:\s+30/', strip_tags($lines[0]));
    }

    public function test_key_value_with_indent(): void
    {
        $this->console->keyValue(
            ['Name' => 'John'],
            2
        );

        $lines = $this->console->getLines();
        $this->assertCount(1, $lines);
        $this->assertMatchesRegularExpression('/\s{4}Name\s+:\s+John/', strip_tags($lines[0]));
    }

    public function test_key_value_with_separator(): void
    {
        $data = MapCollection::from([
            'Name' => 'John',
            'Age' => 30,
        ]);

        $this->console->keyValueWithSeparator($data, ' → ');

        $lines = $this->console->getLines();
        $this->assertCount(1, $lines);
        $this->assertMatchesRegularExpression('/Name\s+→\s+John/', strip_tags($lines[0]));
        $this->assertMatchesRegularExpression('/Age\s+→\s+30/', strip_tags($lines[0]));
    }

    public function test_key_value_with_long_keys(): void
    {
        $this->console->keyValue([
            'A very long key name' => 'Value 1',
            'Short' => 'Value 2',
        ]);

        $lines = $this->console->getLines();
        $this->assertCount(1, $lines);
        $this->assertMatchesRegularExpression('/A very long key name\s+:\s+Value 1/', strip_tags($lines[0]));
        $this->assertMatchesRegularExpression('/Short\s+:\s+Value 2/', strip_tags($lines[0]));
    }

    // ========== TESTS DE CHAÎNAGE ==========

    public function test_chaining(): void
    {
        $this->console
            ->title('Test')
            ->line()
            ->info('Info message')
            ->success('Success message')
            ->line()
            ->error('Error message');

        $lines = $this->console->getLines();
        $this->assertCount(6, $lines);
        $this->assertStringContainsString('Test', $lines[0]);
        $this->assertStringContainsString('Info message', $lines[2]);
        $this->assertStringContainsString('Success message', $lines[3]);
        $this->assertStringContainsString('Error message', $lines[5]);
    }

    public function test_clear(): void
    {
        $this->console->info('Test');
        $this->console->clear();

        $lines = $this->console->getLines();
        $this->assertCount(0, $lines);
    }

    public function test_render(): void
    {
        $this->console->info('Test 1');
        $this->console->info('Test 2');

        $lines = $this->console->getLines();
        $this->assertCount(2, $lines);

        $this->console->render();
        $this->assertCount(0, $this->console->getLines());
        $this->assertFalse($this->console->isBuffered());
    }

    public function test_start_buffer(): void
    {
        $this->assertTrue($this->console->isBuffered());

        $this->console->info('Test');
        $this->assertCount(1, $this->console->getLines());
    }

    public function test_new_line(): void
    {
        $this->console->newLine(2);

        $lines = $this->console->getLines();
        $this->assertCount(1, $lines);
        $this->assertSame(PHP_EOL.PHP_EOL, $lines[0]);
    }

    public function test_multiple_new_lines(): void
    {
        $this->console
            ->line('Start')
            ->newLine(3)
            ->line('End');

        $lines = $this->console->getLines();
        $this->assertCount(3, $lines);
        $this->assertSame('Start', $lines[0]);
        $this->assertSame(PHP_EOL.PHP_EOL.PHP_EOL, $lines[1]);
        $this->assertSame('End', $lines[2]);
    }

    public function test_clear_after_buffer(): void
    {
        $this->console
            ->startBuffer()
            ->info('Test 1')
            ->info('Test 2')
            ->clear();

        $this->assertCount(0, $this->console->getLines());
        $this->console->render();
        $this->assertFalse($this->console->isBuffered());
    }

    public function test_render_empty_buffer(): void
    {
        $this->console->startBuffer();
        $this->console->render();

        $this->assertCount(0, $this->console->getLines());
        $this->assertFalse($this->console->isBuffered());
    }

    public function test_multiple_buffers(): void
    {
        $this->console
            ->startBuffer()
            ->info('Batch 1')
            ->render()
            ->startBuffer()
            ->info('Batch 2')
            ->render();

        $this->assertCount(0, $this->console->getLines());
        $this->assertFalse($this->console->isBuffered());
    }

    // ========== TESTS DE ANSI CONVERTER ==========

    public function test_ansi_converter_injection(): void
    {
        $console = new Console;
        $this->assertNotNull($console->getAnsiConverter());
    }

    public function test_ansi_method(): void
    {
        $this->console->ansi('<fg=green>Hello <options=bold>World</options=bold></fg=green>');

        $lines = $this->console->getLines();
        $this->assertCount(1, $lines);
        $this->assertStringContainsString("\033[32m", $lines[0]);
        $this->assertStringContainsString('Hello', $lines[0]);
        $this->assertStringContainsString("\033[1m", $lines[0]);
        $this->assertStringContainsString('World', $lines[0]);
        $this->assertStringContainsString("\033[0m", $lines[0]);
    }

    // ========== TESTS COMPLETS ==========

    public function test_all_components_together(): void
    {
        $this->console
            ->title('Complete Demo')
            ->line()
            ->info('Here is a complete demonstration')
            ->line()
            ->link('https://github.com', 'GitHub Repository')
            ->line()
            ->list(
                ['Feature 1', 'Feature 2', 'Feature 3'],
                ListStyle::CHECK
            )
            ->line()
            ->keyValue([
                'Name' => 'Console Writer',
                'Version' => '1.0.0',
                'Status' => '✅ Active',
            ])
            ->line()
            ->success('All components working!');

        $lines = $this->console->getLines();
        $this->assertGreaterThan(10, count($lines));

        $fullOutput = implode(PHP_EOL, $lines);
        $this->assertStringContainsString('Complete Demo', $fullOutput);
        $this->assertStringContainsString('GitHub Repository', $fullOutput);
        $this->assertStringContainsString('✓ Feature 1', $fullOutput);
        $this->assertStringContainsString('Console Writer', $fullOutput);
        $this->assertStringContainsString('All components working!', $fullOutput);
    }

    public function test_complex_console_output(): void
    {
        $this->console
            ->title('System Dashboard')
            ->line()
            ->info('Loading configuration...')
            ->success('Configuration loaded')
            ->line()
            ->table(
                ['Service', 'Status', 'Port'],
                [
                    ['PHP-FPM', '✅ Running', '9000'],
                    ['MySQL', '✅ Running', '3306'],
                    ['Redis', '❌ Failed', '6379'],
                ]
            )
            ->line()
            ->alert('Redis service is down!')
            ->line()
            ->keyValueWithValueColor(
                MapCollection::from([
                    'Environment' => 'Production',
                    'PHP Version' => '8.2.15',
                    'Memory Usage' => '512 MB',
                ]),
                'green'
            )
            ->line()
            ->error('Please check Redis configuration')
            ->line()
            ->success('Dashboard loaded successfully');

        $lines = $this->console->getLines();

        // ✅ Ajuster le nombre attendu de lignes
        $this->assertGreaterThanOrEqual(14, count($lines));

        $fullOutput = implode(PHP_EOL, $lines);
        $this->assertStringContainsString('System Dashboard', $fullOutput);
        $this->assertStringContainsString('PHP-FPM', $fullOutput);
        $this->assertStringContainsString('Redis service is down!', $fullOutput);
        $this->assertStringContainsString('Please check Redis configuration', $fullOutput);
        $this->assertStringContainsString('Dashboard loaded successfully', $fullOutput);
    }

    public function test_line_method(): void
    {
        $this->console->line('Custom message');

        $lines = $this->console->getLines();
        $this->assertCount(1, $lines);
        $this->assertSame('Custom message', $lines[0]);
    }

    public function test_empty_line(): void
    {
        $this->console->line();

        $lines = $this->console->getLines();
        $this->assertCount(1, $lines);
        $this->assertSame('', $lines[0]);
    }

    public function test_multiple_lines(): void
    {
        $this->console
            ->info('Line 1')
            ->success('Line 2')
            ->error('Line 3');

        $lines = $this->console->getLines();
        $this->assertCount(3, $lines);
        $this->assertStringContainsString('Line 1', $lines[0]);
        $this->assertStringContainsString('Line 2', $lines[1]);
        $this->assertStringContainsString('Line 3', $lines[2]);
    }

    public function test_get_lines_returns_array(): void
    {
        $this->console->info('Test');
        $lines = $this->console->getLines();
        $this->assertIsArray($lines);
        $this->assertCount(1, $lines);
    }

    public function test_is_buffered_returns_bool(): void
    {
        $this->assertTrue($this->console->isBuffered());
        $this->console->render();
        $this->assertFalse($this->console->isBuffered());
    }
}
