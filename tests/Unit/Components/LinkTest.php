<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Tests\Unit\Components;

use AndyDefer\ConsoleWriter\Console\Components\Link;
use PHPUnit\Framework\TestCase;

final class LinkTest extends TestCase
{
    private function stripAnsi(string $text): string
    {
        return preg_replace('/\033\[[0-9;]+m/', '', $text);
    }

    private function stripOsc8(string $text): string
    {
        // Supprimer les séquences OSC 8: \033]8;;URL\033\\ ... \033]8;;\033\\
        return preg_replace('/\033]8;;[^\033]*\033\\\\/', '', $text);
    }

    public function test_render_link(): void
    {
        $result = Link::render('https://example.com');

        // Vérifier le contenu texte
        $clean = $this->stripAnsi($this->stripOsc8($result));
        $this->assertStringContainsString('https://example.com', $clean);

        // Vérifier que la séquence OSC 8 est présente
        $this->assertStringContainsString("\033]8;;https://example.com\033\\", $result);
        $this->assertStringContainsString("\033]8;;\033\\", $result);

        // Vérifier que la couleur est appliquée
        $this->assertStringContainsString("\033[36m", $result);
    }

    public function test_render_link_with_text(): void
    {
        $result = Link::renderWithText('https://example.com', 'Visit Example');

        $clean = $this->stripAnsi($this->stripOsc8($result));
        $this->assertStringContainsString('Visit Example', $clean);
        $this->assertStringNotContainsString('https://example.com', $clean);

        $this->assertStringContainsString("\033]8;;https://example.com\033\\", $result);
        $this->assertStringContainsString("\033]8;;\033\\", $result);
        $this->assertStringContainsString("\033[36m", $result);
    }

    public function test_render_link_with_icon(): void
    {
        $result = Link::renderWithIcon('https://example.com', 'Visit Example', '🔗');

        $clean = $this->stripAnsi($this->stripOsc8($result));
        $this->assertStringContainsString('🔗 Visit Example', $clean);

        $this->assertStringContainsString("\033]8;;https://example.com\033\\", $result);
        $this->assertStringContainsString("\033]8;;\033\\", $result);
    }

    public function test_render_link_with_color(): void
    {
        $result = Link::renderWithColor('https://example.com', 'Visit Example', 'red');

        $clean = $this->stripAnsi($this->stripOsc8($result));
        $this->assertStringContainsString('Visit Example', $clean);

        // Vérifier la couleur rouge
        $this->assertStringContainsString("\033[31m", $result);
    }

    public function test_render_link_with_underline(): void
    {
        $result = Link::renderWithUnderline('https://example.com', 'Visit Example', 'green');

        $clean = $this->stripAnsi($this->stripOsc8($result));
        $this->assertStringContainsString('Visit Example', $clean);

        // Vérifier la couleur verte
        $this->assertStringContainsString("\033[32m", $result);
        // Vérifier le souligné
        $this->assertStringContainsString("\033[4m", $result);
    }

    public function test_render_multiple_links(): void
    {
        $link1 = Link::render('https://github.com');
        $link2 = Link::renderWithText('https://packagist.org', 'Packagist');

        $clean1 = $this->stripAnsi($this->stripOsc8($link1));
        $clean2 = $this->stripAnsi($this->stripOsc8($link2));

        $this->assertStringContainsString('https://github.com', $clean1);
        $this->assertStringContainsString('Packagist', $clean2);
        $this->assertStringNotContainsString('https://packagist.org', $clean2);
    }

    public function test_render_link_in_chaining(): void
    {
        $result = Link::renderWithText('https://example.com', 'Click here');

        // Vérifier la structure OSC 8 complète
        $this->assertMatchesRegularExpression('/\033]8;;https:\/\/example\.com\033\\\\/', $result);
        $this->assertMatchesRegularExpression('/Click here/', $result);
        $this->assertMatchesRegularExpression('/\033]8;;\033\\\\/', $result);
    }
}
