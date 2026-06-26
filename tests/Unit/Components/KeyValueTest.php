<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Tests\Unit\Components;

use AndyDefer\ConsoleWriter\Console\Components\KeyValue;
use AndyDefer\DomainStructures\Utils\MapCollection;
use PHPUnit\Framework\TestCase;

final class KeyValueTest extends TestCase
{
    private function stripAnsi(string $text): string
    {
        return preg_replace('/\033\[[0-9;]+m/', '', $text);
    }

    public function test_render_key_value(): void
    {
        $data = MapCollection::from(['Name' => 'John', 'Age' => 30, 'City' => 'Paris']);
        $result = KeyValue::render($data);

        $plainResult = $this->stripAnsi($result);

        $this->assertMatchesRegularExpression('/Name\s*:\s*John/', $plainResult);
        $this->assertMatchesRegularExpression('/Age\s*:\s*30/', $plainResult);
        $this->assertMatchesRegularExpression('/City\s*:\s*Paris/', $plainResult);
    }

    public function test_render_empty_data(): void
    {
        $data = MapCollection::from([]);
        $result = KeyValue::render($data);

        $plainResult = $this->stripAnsi($result);
        $this->assertStringContainsString('No data to display', $plainResult);
    }

    public function test_render_with_color(): void
    {
        $data = MapCollection::from(['Name' => 'John', 'Age' => 30]);
        $result = KeyValue::renderWithColor($data, 'green');

        $plainResult = $this->stripAnsi($result);
        $this->assertMatchesRegularExpression('/Name\s*:\s*John/', $plainResult);
        $this->assertMatchesRegularExpression('/Age\s*:\s*30/', $plainResult);
    }

    public function test_render_with_value_color(): void
    {
        $data = MapCollection::from(['Name' => 'John', 'Age' => 30]);
        $result = KeyValue::renderWithValueColor($data, 'yellow');

        $plainResult = $this->stripAnsi($result);
        $this->assertMatchesRegularExpression('/Name\s*:\s*John/', $plainResult);
        $this->assertMatchesRegularExpression('/Age\s*:\s*30/', $plainResult);
    }

    public function test_render_with_separator(): void
    {
        $data = MapCollection::from(['Name' => 'John', 'Age' => 30]);
        $result = KeyValue::renderWithSeparator($data, ' → ');

        $plainResult = $this->stripAnsi($result);
        $this->assertMatchesRegularExpression('/Name\s*→\s*John/', $plainResult);
        $this->assertMatchesRegularExpression('/Age\s*→\s*30/', $plainResult);
    }

    public function test_render_with_indent(): void
    {
        $data = MapCollection::from(['Name' => 'John']);
        $result = KeyValue::render($data, 2);

        $plainResult = $this->stripAnsi($result);
        $this->assertMatchesRegularExpression('/\s{4}Name\s*:\s*John/', $plainResult);
    }

    public function test_render_with_mixed_data_types(): void
    {
        $data = MapCollection::from([
            'String' => 'Hello',
            'Integer' => 42,
            'Boolean' => true,
            'Null' => null,
            'Float' => 3.14,
        ]);
        $result = KeyValue::render($data);

        $plainResult = $this->stripAnsi($result);
        $this->assertMatchesRegularExpression('/String\s*:\s*Hello/', $plainResult);
        $this->assertMatchesRegularExpression('/Integer\s*:\s*42/', $plainResult);
        $this->assertMatchesRegularExpression('/Boolean\s*:\s*true/', $plainResult);
        $this->assertMatchesRegularExpression('/Null\s*:\s*/', $plainResult);
        $this->assertMatchesRegularExpression('/Float\s*:\s*3.14/', $plainResult);
    }

    public function test_render_with_special_characters(): void
    {
        $data = MapCollection::from([
            'Email' => 'user@example.com',
            'URL' => 'https://example.com/page?param=value',
            'Path' => '/home/user/documents/file.txt',
        ]);
        $result = KeyValue::render($data);

        $plainResult = $this->stripAnsi($result);
        $this->assertMatchesRegularExpression('/Email\s*:\s*user@example\.com/', $plainResult);
        $this->assertMatchesRegularExpression('/URL\s*:\s*https:\/\/example\.com\/page\?param=value/', $plainResult);
        $this->assertMatchesRegularExpression('/Path\s*:\s*\/home\/user\/documents\/file\.txt/', $plainResult);
    }

    public function test_render_with_unicode(): void
    {
        $data = MapCollection::from([
            'Nom' => 'Jean-Pierre',
            'Ville' => 'Montréal',
            'Pays' => 'Canada 🇨🇦',
        ]);
        $result = KeyValue::render($data);

        $plainResult = $this->stripAnsi($result);
        $this->assertMatchesRegularExpression('/Nom\s*:\s*Jean-Pierre/', $plainResult);
        $this->assertMatchesRegularExpression('/Ville\s*:\s*Montréal/', $plainResult);
        $this->assertMatchesRegularExpression('/Pays\s*:\s*Canada 🇨🇦/', $plainResult);
    }

    public function test_render_with_object_to_string(): void
    {
        $data = MapCollection::from([
            'Object' => new class
            {
                public function __toString(): string
                {
                    return 'Custom object string';
                }
            },
        ]);
        $result = KeyValue::render($data);

        $plainResult = $this->stripAnsi($result);
        $this->assertMatchesRegularExpression('/Object\s*:\s*Custom object string/', $plainResult);
    }

    public function test_render_with_array_value(): void
    {
        $data = MapCollection::from([
            'Array' => ['a', 'b', 'c'],
        ]);
        $result = KeyValue::render($data);

        $plainResult = $this->stripAnsi($result);
        $this->assertMatchesRegularExpression('/Array\s*:\s*\["a","b","c"\]/', $plainResult);
    }
}
