<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Tests\Unit\Components;

use AndyDefer\ConsoleWriter\Console\Components\Separator;

final class SeparatorTest extends ComponentTestCase
{
    public function test_render_default_separator(): void
    {
        $result = Separator::render();

        $this->assertStringContainsString(str_repeat('-', 80), $result);
        $this->assertStringNotContainsString('=', $result);
    }

    public function test_render_with_custom_length(): void
    {
        $result = Separator::render(40);

        $this->assertStringContainsString(str_repeat('-', 40), $result);
        $this->assertStringNotContainsString(str_repeat('-', 41), $result);
    }

    public function test_render_with_custom_color(): void
    {
        $result = Separator::render(20, 'cyan');

        $this->assertStringContainsString("\033[36m", $result);
        $this->assertStringContainsString(str_repeat('-', 20), $result);
    }

    public function test_render_double(): void
    {
        $result = Separator::renderDouble();

        $this->assertStringContainsString(str_repeat('=', 80), $result);
        $this->assertStringNotContainsString('-', $result);
    }

    public function test_render_double_with_custom_length(): void
    {
        $result = Separator::renderDouble(40);

        $this->assertStringContainsString(str_repeat('=', 40), $result);
        $this->assertStringNotContainsString(str_repeat('=', 41), $result);
    }

    public function test_render_with_char(): void
    {
        $result = Separator::renderWithChar('*');

        $this->assertStringContainsString(str_repeat('*', 80), $result);
        $this->assertStringNotContainsString('-', $result);
        $this->assertStringNotContainsString('=', $result);
    }

    public function test_render_with_char_and_custom_length(): void
    {
        $result = Separator::renderWithChar('.', 30);

        $this->assertStringContainsString(str_repeat('.', 30), $result);
        $this->assertStringNotContainsString(str_repeat('.', 31), $result);
    }

    public function test_render_with_title(): void
    {
        $result = Separator::renderWithTitle('TEST');

        // ✅ Vérifier que le titre est présent
        $this->assertStringContainsString('TEST', $result);
        // ✅ Vérifier qu'il y a des espaces autour du titre
        $this->assertStringContainsString('  TEST  ', $result);
        // ✅ Vérifier la longueur totale (80)
        $this->assertEquals(80, mb_strlen(strip_tags($this->stripAnsi($result))));
        // ✅ Vérifier que le séparateur contient le caractère par défaut
        $this->assertStringContainsString('-', $result);
    }

    public function test_render_with_title_and_custom_char(): void
    {
        $result = Separator::renderWithTitle('TEST', '=');

        // ✅ Vérifier que le titre est présent
        $this->assertStringContainsString('TEST', $result);
        // ✅ Vérifier qu'il y a des espaces autour du titre
        $this->assertStringContainsString('  TEST  ', $result);
        // ✅ Vérifier que le séparateur contient le caractère personnalisé
        $this->assertStringContainsString('=', $result);
        $this->assertStringNotContainsString('-', $result);
        // ✅ Vérifier la longueur totale (80)
        $this->assertEquals(80, mb_strlen(strip_tags($this->stripAnsi($result))));
    }

    public function test_render_with_title_and_custom_length(): void
    {
        $result = Separator::renderWithTitle('TEST', '-', 40);

        // ✅ Vérifier que le titre est présent
        $this->assertStringContainsString('TEST', $result);
        // ✅ Vérifier qu'il y a des espaces autour du titre
        $this->assertStringContainsString('  TEST  ', $result);
        // ✅ Vérifier la longueur totale (40)
        $this->assertEquals(40, mb_strlen(strip_tags($this->stripAnsi($result))));
    }

    public function test_render_with_title_and_color(): void
    {
        $result = Separator::renderWithTitle('TEST', '-', 40, 'cyan');

        // ✅ Vérifier la couleur ANSI
        $this->assertStringContainsString("\033[36m", $result);
        // ✅ Vérifier que le titre est présent
        $this->assertStringContainsString('TEST', $result);
        // ✅ Vérifier la longueur totale (40)
        $this->assertEquals(40, mb_strlen(strip_tags($this->stripAnsi($result))));
    }

    public function test_render_with_title_centered(): void
    {
        $result = Separator::renderWithTitle('SHORT');

        $cleaned = $this->stripAnsi($result);
        $length = mb_strlen($cleaned);

        // ✅ Vérifier la longueur totale (80)
        $this->assertEquals(80, $length);

        // ✅ Vérifier que le titre est centré
        $this->assertStringContainsString('  SHORT  ', $cleaned);

        // ✅ Vérifier qu'il y a des caractères avant et après le titre
        $parts = explode('  SHORT  ', $cleaned);
        $this->assertCount(2, $parts);
        $this->assertNotEmpty($parts[0]);
        $this->assertNotEmpty($parts[1]);

        // ✅ Vérifier que les deux côtés ont à peu près la même longueur (tolérance de 1)
        $leftLen = mb_strlen($parts[0]);
        $rightLen = mb_strlen($parts[1]);
        $this->assertLessThanOrEqual(1, abs($leftLen - $rightLen));
    }

    public function test_render_with_title_long(): void
    {
        $longTitle = 'This is a very long title for testing purposes';
        $result = Separator::renderWithTitle($longTitle, '-', 80);

        $cleaned = $this->stripAnsi($result);

        // ✅ Vérifier que le titre est présent
        $this->assertStringContainsString($longTitle, $cleaned);
        // ✅ Vérifier qu'il y a des espaces autour du titre
        $this->assertStringContainsString('  '.$longTitle.'  ', $cleaned);
        // ✅ Vérifier la longueur totale (80)
        $this->assertEquals(80, mb_strlen($cleaned));
    }

    public function test_render_empty(): void
    {
        $result = Separator::render(0);

        $this->assertStringContainsString('', $result);
    }

    public function test_render_with_emoji_char(): void
    {
        $result = Separator::renderWithChar('⭐', 30);

        $this->assertStringContainsString(str_repeat('⭐', 30), $result);
    }

    public function test_render_separator_method_alias(): void
    {
        $result1 = Separator::render(50, 'green');
        $result2 = Separator::renderWithChar('-', 50, 'green');

        $this->assertSame($result1, $result2);
    }

    public function test_render_separator_with_different_colors(): void
    {
        $colors = ['red', 'green', 'yellow', 'blue', 'magenta', 'cyan', 'gray', 'white'];

        foreach ($colors as $color) {
            $result = Separator::render(20, $color);
            $this->assertStringContainsString("\033[", $result);
            $this->assertStringContainsString(str_repeat('-', 20), $result);
        }
    }
}
