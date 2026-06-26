<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Tests\Unit\Components;

use AndyDefer\ConsoleWriter\Console\Components\JsonViewer;

final class JsonViewerTest extends ComponentTestCase
{
    private array $testData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->testData = [
            'user' => [
                'id' => 1,
                'name' => 'Andy',
                'email' => 'andy@example.com',
                'active' => true,
                'score' => 98.5,
                'tags' => ['php', 'javascript', 'python'],
                'address' => null,
            ],
        ];
    }

    public function test_render_json(): void
    {
        $result = JsonViewer::render($this->testData);

        $this->assertStringContainsString('"user"', $result);
        $this->assertStringContainsString('"id"', $result);
        $this->assertStringContainsString('"name"', $result);
        $this->assertStringContainsString('"Andy"', $result);
        $this->assertStringContainsString('1', $result);
        $this->assertStringContainsString('true', $result);
        $this->assertStringContainsString('98.5', $result);
        $this->assertStringContainsString('null', $result);
        $this->assertStringContainsString('"php"', $result);

        // Vérifier les codes ANSI pour les couleurs
        $this->assertStringContainsString("\033[36m", $result); // cyan - clés
        $this->assertStringContainsString("\033[32m", $result); // vert - chaînes
        $this->assertStringContainsString("\033[33m", $result); // jaune - nombres
        $this->assertStringContainsString("\033[35m", $result); // magenta - booléens
        $this->assertStringContainsString("\033[90m", $result); // gray - null
    }

    public function test_render_json_raw(): void
    {
        $result = JsonViewer::renderRaw($this->testData);

        // Le RAW ne doit pas contenir de codes ANSI
        $this->assertStringNotContainsString("\033[", $result);
        $this->assertStringContainsString('"user"', $result);
        $this->assertStringContainsString('"id"', $result);
        $this->assertStringContainsString('"name"', $result);
        $this->assertStringContainsString('"Andy"', $result);
        $this->assertStringContainsString('1', $result);
        $this->assertStringContainsString('true', $result);
        $this->assertStringContainsString('98.5', $result);
    }

    public function test_render_json_compact(): void
    {
        $result = JsonViewer::renderCompact($this->testData);

        // Une seule ligne, pas de sauts de ligne
        $this->assertStringNotContainsString("\n", $result);
        $this->assertStringContainsString('{"user":', $result);
        $this->assertStringContainsString('"id":1', $result);
        $this->assertStringContainsString('"name":"Andy"', $result);
    }

    public function test_render_json_from_string(): void
    {
        $jsonString = '{"status":"ok","data":{"id":1}}';
        $result = JsonViewer::render($jsonString);

        $this->assertStringContainsString('"status"', $result);
        $this->assertStringContainsString('"ok"', $result);
        $this->assertStringContainsString('"data"', $result);
        $this->assertStringContainsString('"id"', $result);
        $this->assertStringContainsString('1', $result);
    }

    public function test_render_invalid_json(): void
    {
        $result = JsonViewer::render('{invalid json}');

        $this->assertStringContainsString('Invalid JSON', $result);
        $this->assertStringContainsString('⚠️', $result);
    }

    public function test_render_with_depth(): void
    {
        $deepData = [
            'level1' => [
                'level2' => [
                    'level3' => [
                        'level4' => 'deep value',
                    ],
                ],
            ],
        ];

        $result = JsonViewer::renderWithDepth($deepData, 3);

        $this->assertStringContainsString('"level1"', $result);
        $this->assertStringContainsString('"level2"', $result);
        $this->assertStringContainsString('"level3"', $result);
        $this->assertStringContainsString('...', $result);
        $this->assertStringNotContainsString('"level4"', $result);
    }

    public function test_render_empty_array(): void
    {
        $result = JsonViewer::render([]);

        // ✅ Vérifier que le résultat n'est pas vide et contient des accolades
        $this->assertNotEmpty($result);
        $this->assertStringContainsString('{}', $result);
    }

    public function test_render_array_of_strings(): void
    {
        $data = ['apple', 'banana', 'cherry'];
        $result = JsonViewer::render($data);

        // ✅ Vérifier les éléments individuellement (car formatés avec couleurs)
        $this->assertStringContainsString('apple', $result);
        $this->assertStringContainsString('banana', $result);
        $this->assertStringContainsString('cherry', $result);
        $this->assertStringContainsString("\033[32m", $result); // couleur verte
    }

    public function test_render_array_of_numbers(): void
    {
        $data = [1, 2, 3, 4, 5];
        $result = JsonViewer::render($data);

        // ✅ Vérifier les nombres individuellement
        $this->assertStringContainsString('1', $result);
        $this->assertStringContainsString('2', $result);
        $this->assertStringContainsString('3', $result);
        $this->assertStringContainsString('4', $result);
        $this->assertStringContainsString('5', $result);
        $this->assertStringContainsString("\033[33m", $result); // couleur jaune
    }

    public function test_render_complex_nested(): void
    {
        $data = [
            'users' => [
                ['id' => 1, 'name' => 'Alice', 'active' => true],
                ['id' => 2, 'name' => 'Bob', 'active' => false],
            ],
            'total' => 2,
            'metadata' => [
                'timestamp' => '2024-01-01',
                'version' => '1.0.0',
            ],
        ];

        $result = JsonViewer::render($data);

        // ✅ Vérifier les clés et valeurs présentes
        $this->assertStringContainsString('"users"', $result);
        $this->assertStringContainsString('Alice', $result);
        $this->assertStringContainsString('Bob', $result);
        $this->assertStringContainsString('true', $result);
        $this->assertStringContainsString('false', $result);
        $this->assertStringContainsString('"total"', $result);
        $this->assertStringContainsString('"metadata"', $result);
        $this->assertStringContainsString('"timestamp"', $result);
        $this->assertStringContainsString('"version"', $result);
    }

    public function test_render_with_special_characters(): void
    {
        $data = [
            'text' => 'Hello "world"!',
            'url' => 'https://example.com?param=value',
            'emoji' => '🚀',
        ];

        $result = JsonViewer::render($data);

        // ✅ Vérifier les parties du texte (les guillemets sont échappés)
        $this->assertStringContainsString('Hello', $result);
        $this->assertStringContainsString('world', $result);
        $this->assertStringContainsString('https://example.com?param=value', $result);
        $this->assertStringContainsString('🚀', $result);
    }

    public function test_render_raw_with_special_characters(): void
    {
        $data = [
            'text' => 'Hello "world"!',
            'emoji' => '🚀',
        ];

        $result = JsonViewer::renderRaw($data);

        // ✅ Vérifier les parties du texte
        $this->assertStringContainsString('Hello', $result);
        $this->assertStringContainsString('world', $result);
        $this->assertStringContainsString('🚀', $result);
        $this->assertStringNotContainsString("\033[", $result);
    }

    public function test_render_null_value(): void
    {
        $data = ['key' => null];
        $result = JsonViewer::render($data);

        $this->assertStringContainsString('null', $result);
        $this->assertStringContainsString("\033[90m", $result);
    }

    public function test_render_boolean_values(): void
    {
        $data = ['true' => true, 'false' => false];
        $result = JsonViewer::render($data);

        $this->assertStringContainsString('true', $result);
        $this->assertStringContainsString('false', $result);
        $this->assertStringContainsString("\033[35m", $result);
    }

    public function test_render_array_with_mixed_types(): void
    {
        $data = ['string', 42, true, null, 3.14];
        $result = JsonViewer::render($data);

        $this->assertStringContainsString('string', $result);
        $this->assertStringContainsString('42', $result);
        $this->assertStringContainsString('true', $result);
        $this->assertStringContainsString('null', $result);
        $this->assertStringContainsString('3.14', $result);
    }

    public function test_render_deep_nested_object(): void
    {
        $data = [
            'level1' => [
                'level2' => [
                    'level3' => [
                        'level4' => [
                            'level5' => 'deep',
                        ],
                    ],
                ],
            ],
        ];

        $result = JsonViewer::render($data);

        $this->assertStringContainsString('"level1"', $result);
        $this->assertStringContainsString('"level2"', $result);
        $this->assertStringContainsString('"level3"', $result);
        $this->assertStringContainsString('"level4"', $result);
        $this->assertStringContainsString('"level5"', $result);
        $this->assertStringContainsString('deep', $result);
    }

    public function test_render_json_with_unicode(): void
    {
        $data = ['name' => 'Jean-Pierre', 'city' => 'Montréal', 'country' => 'Canada 🇨🇦'];
        $result = JsonViewer::render($data);

        $this->assertStringContainsString('Jean-Pierre', $result);
        $this->assertStringContainsString('Montréal', $result);
        $this->assertStringContainsString('Canada 🇨🇦', $result);
    }
}
