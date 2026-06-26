<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Tests\Unit\Components;

use AndyDefer\ConsoleWriter\Console\Components\TableList;
use AndyDefer\DomainStructures\Utils\ListCollection;
use PHPUnit\Framework\TestCase;

final class TableListTest extends TestCase
{
    public function test_render_basic_list(): void
    {
        $headers = ListCollection::from(['ID', 'Name', 'Description', 'Price']);
        $rows = ListCollection::from([
            ListCollection::from(['1', 'Laptop', 'High-performance laptop', '1299.99']),
            ListCollection::from(['2', 'Mouse', 'Wireless mouse', '29.99']),
        ]);

        $result = TableList::render($headers, $rows);
        $plainResult = strip_tags($result);

        $this->assertStringContainsString('4 colonnes → affichage en liste', $plainResult);
        $this->assertMatchesRegularExpression('/ID\s+:\s+1/', $plainResult);
        $this->assertMatchesRegularExpression('/Name\s+:\s+Laptop/', $plainResult);
        $this->assertMatchesRegularExpression('/Description\s+:\s+High-performance laptop/', $plainResult);
        $this->assertMatchesRegularExpression('/Price\s+:\s+1299.99/', $plainResult);
        $this->assertMatchesRegularExpression('/ID\s+:\s+2/', $plainResult);
        $this->assertMatchesRegularExpression('/Name\s+:\s+Mouse/', $plainResult);
        $this->assertMatchesRegularExpression('/Description\s+:\s+Wireless mouse/', $plainResult);
        $this->assertMatchesRegularExpression('/Price\s+:\s+29.99/', $plainResult);
    }

    public function test_render_empty_rows(): void
    {
        $headers = ListCollection::from(['ID', 'Name']);
        $rows = ListCollection::from([]);

        $result = TableList::render($headers, $rows);

        $this->assertStringContainsString('No data to display', $result);
        $this->assertStringContainsString('<fg=yellow>', $result);
        $this->assertStringContainsString('</fg=yellow>', $result);
    }

    public function test_render_with_title(): void
    {
        $headers = ListCollection::from(['Name', 'Email', 'Role']);
        $rows = ListCollection::from([
            ListCollection::from(['John Doe', 'john@example.com', 'Admin']),
        ]);

        $result = TableList::renderWithTitle($headers, $rows, '📦 Users List');
        $plainResult = strip_tags($result);

        $this->assertStringContainsString('📦 Users List', $plainResult);
        $this->assertMatchesRegularExpression('/Name\s+:\s+John Doe/', $plainResult);
        $this->assertMatchesRegularExpression('/Email\s+:\s+john@example.com/', $plainResult);
        $this->assertMatchesRegularExpression('/Role\s+:\s+Admin/', $plainResult);
    }

    public function test_render_with_long_text_wrapping(): void
    {
        $headers = ListCollection::from(['ID', 'Description']);
        $rows = ListCollection::from([
            ListCollection::from(['1', 'This is a very long description that should be wrapped because it exceeds the maximum width of 60 characters']),
        ]);

        $result = TableList::render($headers, $rows);
        $plainResult = strip_tags($result);

        $this->assertMatchesRegularExpression('/ID\s+:\s+1/', $plainResult);

        // ✅ Vérifier que le texte est présent (même coupé sur plusieurs lignes)
        $this->assertStringContainsString('This is a very long description that should', $plainResult);
        $this->assertStringContainsString('be wrapped because it exceeds the maximum', $plainResult);
        $this->assertStringContainsString('width of 60 characters', $plainResult);
    }

    public function test_render_with_multiple_rows(): void
    {
        $headers = ListCollection::from(['Product', 'Price', 'Stock']);
        $rows = ListCollection::from([
            ListCollection::from(['Laptop', '999.99', '15']),
            ListCollection::from(['Mouse', '29.99', '42']),
            ListCollection::from(['Keyboard', '79.99', '28']),
        ]);

        $result = TableList::render($headers, $rows);
        $plainResult = strip_tags($result);

        $this->assertMatchesRegularExpression('/Product\s+:\s+Laptop/', $plainResult);
        $this->assertMatchesRegularExpression('/Price\s+:\s+999.99/', $plainResult);
        $this->assertMatchesRegularExpression('/Stock\s+:\s+15/', $plainResult);
        $this->assertMatchesRegularExpression('/Product\s+:\s+Mouse/', $plainResult);
        $this->assertMatchesRegularExpression('/Price\s+:\s+29.99/', $plainResult);
        $this->assertMatchesRegularExpression('/Stock\s+:\s+42/', $plainResult);
        $this->assertMatchesRegularExpression('/Product\s+:\s+Keyboard/', $plainResult);
        $this->assertMatchesRegularExpression('/Price\s+:\s+79.99/', $plainResult);
        $this->assertMatchesRegularExpression('/Stock\s+:\s+28/', $plainResult);
    }

    public function test_render_with_special_characters(): void
    {
        $headers = ListCollection::from(['URL', 'Path']);
        $rows = ListCollection::from([
            ListCollection::from(['https://example.com/page?param=value', '/home/user/documents/file.txt']),
        ]);

        $result = TableList::render($headers, $rows);
        $plainResult = strip_tags($result);

        $this->assertMatchesRegularExpression('/URL\s+:\s+https:\/\/example.com\/page\?param=value/', $plainResult);
        $this->assertMatchesRegularExpression('/Path\s+:\s+\/home\/user\/documents\/file.txt/', $plainResult);
    }

    public function test_render_with_unicode(): void
    {
        $headers = ListCollection::from(['Nom', 'Ville', 'Pays']);
        $rows = ListCollection::from([
            ListCollection::from(['Jean-Pierre', 'Montréal', 'Canada 🇨🇦']),
        ]);

        $result = TableList::render($headers, $rows);
        $plainResult = strip_tags($result);

        $this->assertMatchesRegularExpression('/Nom\s+:\s+Jean-Pierre/', $plainResult);
        $this->assertMatchesRegularExpression('/Ville\s+:\s+Montréal/', $plainResult);
        $this->assertMatchesRegularExpression('/Pays\s+:\s+Canada 🇨🇦/', $plainResult);
    }

    public function test_render_with_mixed_data_types(): void
    {
        $headers = ListCollection::from(['String', 'Integer', 'Boolean', 'Null', 'Float']);
        $rows = ListCollection::from([
            ListCollection::from(['Hello', 42, true, null, 3.14]),
        ]);

        $result = TableList::render($headers, $rows);
        $plainResult = strip_tags($result);

        $this->assertMatchesRegularExpression('/String\s+:\s+Hello/', $plainResult);
        $this->assertMatchesRegularExpression('/Integer\s+:\s+42/', $plainResult);
        $this->assertMatchesRegularExpression('/Boolean\s+:\s+1/', $plainResult);
        $this->assertMatchesRegularExpression('/Null\s+:\s*/', $plainResult);
        $this->assertMatchesRegularExpression('/Float\s+:\s+3.14/', $plainResult);
    }

    public function test_render_with_long_keys(): void
    {
        $headers = ListCollection::from([
            'A very long key name that exceeds 25 characters',
            'Short key',
        ]);
        $rows = ListCollection::from([
            ListCollection::from(['Value 1', 'Value 2']),
        ]);

        $result = TableList::render($headers, $rows);
        $plainResult = strip_tags($result);

        $this->assertStringContainsString('A very long key name that exceeds 25 characters', $plainResult);
        $this->assertStringContainsString('Value 1', $plainResult);
        $this->assertStringContainsString('Short key', $plainResult);
        $this->assertStringContainsString('Value 2', $plainResult);
    }

    public function test_render_with_single_row(): void
    {
        $headers = ListCollection::from(['Name', 'Email']);
        $rows = ListCollection::from([
            ListCollection::from(['John Doe', 'john@example.com']),
        ]);

        $result = TableList::render($headers, $rows);
        $plainResult = strip_tags($result);

        $this->assertMatchesRegularExpression('/Name\s+:\s+John Doe/', $plainResult);
        $this->assertMatchesRegularExpression('/Email\s+:\s+john@example.com/', $plainResult);
    }

    public function test_render_with_empty_values(): void
    {
        $headers = ListCollection::from(['Name', 'Email', 'Phone']);
        $rows = ListCollection::from([
            ListCollection::from(['John Doe', '', '']),
        ]);

        $result = TableList::render($headers, $rows);
        $plainResult = strip_tags($result);

        $this->assertMatchesRegularExpression('/Name\s+:\s+John Doe/', $plainResult);
        $this->assertMatchesRegularExpression('/Email\s+:\s*/', $plainResult);
        $this->assertMatchesRegularExpression('/Phone\s+:\s*/', $plainResult);
    }
}
