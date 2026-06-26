<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Tests\Unit\Components;

use AndyDefer\ConsoleWriter\Console\Components\Badge;

final class BadgeTest extends ComponentTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // ✅ Réinitialiser les styles avant chaque test
        $reflection = new \ReflectionClass(Badge::class);
        $property = $reflection->getProperty('styles');
        $property->setValue(null, null);
    }

    public function test_render_badge(): void
    {
        $result = Badge::render('SUCCESS', 'success-dark');

        $this->assertStringContainsString('[SUCCESS]', $result);
        $this->assertStringContainsString("\033[32m", $result);
        $this->assertStringContainsString("\033[39m", $result);
        $this->assertStringContainsString("\033[1m", $result);
        $this->assertStringContainsString("\033[22m", $result);
    }

    public function test_render_badge_default(): void
    {
        $result = Badge::render('TEXT', 'default');

        $this->assertStringContainsString('[TEXT]', $result);
        $this->assertStringContainsString("\033[37m", $result);
        $this->assertStringContainsString("\033[39m", $result);
    }

    public function test_render_badge_without_style(): void
    {
        $result = Badge::render('TEXT');

        $this->assertStringContainsString('[TEXT]', $result);
        $this->assertStringContainsString("\033[37m", $result);
        $this->assertStringContainsString("\033[39m", $result);
    }

    public function test_render_with_icon(): void
    {
        $result = Badge::renderWithIcon('SUCCESS', '🟢', 'success-dark');

        $this->assertStringContainsString('🟢', $result);
        $this->assertStringContainsString('[SUCCESS]', $result);
        $this->assertStringContainsString("\033[32m", $result);
        $this->assertStringContainsString("\033[39m", $result);
    }

    public function test_render_icon_only(): void
    {
        $result = Badge::renderIconOnly('🟢', 'OK');

        // ✅ Vérifier que l'icône est présente
        $this->assertStringContainsString('🟢', $result);
        // ✅ Vérifier que le texte 'OK' est présent (peu importe les codes ANSI)
        $this->assertStringContainsString('OK', $result);
        // ✅ Vérifier que le crochet ouvrant est présent
        $this->assertStringContainsString('[', $result);
        // ✅ Vérifier que le crochet fermant est présent
        $this->assertStringContainsString(']', $result);
    }

    public function test_render_icon_only_without_text(): void
    {
        $result = Badge::renderIconOnly('🟢');

        $this->assertSame('🟢', $result);
    }

    public function test_success_badge(): void
    {
        $result = Badge::success();

        $this->assertStringContainsString('🟢', $result);
        $this->assertStringContainsString('[SUCCESS]', $result);
        $this->assertStringContainsString("\033[32m", $result);
        $this->assertStringContainsString("\033[39m", $result);
    }

    public function test_success_badge_with_custom_text(): void
    {
        $result = Badge::success('OK');

        $this->assertStringContainsString('🟢', $result);
        $this->assertStringContainsString('[OK]', $result);
        $this->assertStringContainsString("\033[32m", $result);
        $this->assertStringContainsString("\033[39m", $result);
    }

    public function test_danger_badge(): void
    {
        $result = Badge::danger();

        $this->assertStringContainsString('🔴', $result);
        $this->assertStringContainsString('[FAILED]', $result);
        $this->assertStringContainsString("\033[31m", $result);
        $this->assertStringContainsString("\033[39m", $result);
    }

    public function test_warning_badge(): void
    {
        $result = Badge::warning();

        $this->assertStringContainsString('🟡', $result);
        $this->assertStringContainsString('[PENDING]', $result);
        $this->assertStringContainsString("\033[33m", $result);
        $this->assertStringContainsString("\033[39m", $result);
    }

    public function test_info_badge(): void
    {
        $result = Badge::info();

        $this->assertStringContainsString('🔵', $result);
        $this->assertStringContainsString('[INFO]', $result);
        $this->assertStringContainsString("\033[34m", $result);
        $this->assertStringContainsString("\033[39m", $result);
    }

    public function test_primary_badge(): void
    {
        $result = Badge::primary();

        $this->assertStringContainsString('🟣', $result);
        $this->assertStringContainsString('[PRIMARY]', $result);
        $this->assertStringContainsString("\033[36m", $result);
        $this->assertStringContainsString("\033[39m", $result);
    }

    public function test_dark_badge(): void
    {
        $result = Badge::dark();

        $this->assertStringContainsString('⚫', $result);
        $this->assertStringContainsString('[DARK]', $result);
        $this->assertStringContainsString("\033[90m", $result);
        $this->assertStringContainsString("\033[39m", $result);
    }

    public function test_light_badge(): void
    {
        $result = Badge::light();

        $this->assertStringContainsString('⚪', $result);
        $this->assertStringContainsString('[LIGHT]', $result);
        $this->assertStringContainsString("\033[37m", $result);
        $this->assertStringContainsString("\033[39m", $result);
    }

    public function test_add_custom_style(): void
    {
        Badge::addStyle('custom', 'magenta', '⭐', 'CUSTOM');

        $result = Badge::render('CUSTOM', 'custom');

        $this->assertStringContainsString('[CUSTOM]', $result);
        $this->assertStringContainsString("\033[35m", $result);
        $this->assertStringContainsString("\033[39m", $result);
    }

    public function test_invalid_style_fallback(): void
    {
        $result = Badge::render('TEXT', 'invalid_style');

        $this->assertStringContainsString('[TEXT]', $result);
        $this->assertStringContainsString("\033[37m", $result);
        $this->assertStringContainsString("\033[39m", $result);
    }

    public function test_all_styles(): void
    {
        $styles = ['success-dark', 'danger-dark', 'warning-dark', 'info-dark', 'primary-dark', 'dark', 'light'];

        foreach ($styles as $style) {
            $result = Badge::render('TEST', $style);
            $this->assertStringContainsString('[TEST]', $result);
            $this->assertStringContainsString("\033[", $result);
        }
    }

    public function test_badge_in_table(): void
    {
        $badge = Badge::success('OK');

        $this->assertStringContainsString('[OK]', $badge);
        $this->assertStringContainsString("\033[32m", $badge);
        $this->assertStringContainsString("\033[39m", $badge);
    }

    public function test_style_success_dark(): void
    {
        $result = Badge::renderWithIcon('TEST', '🟢', 'success-dark');

        $this->assertStringContainsString("\033[32m", $result);
        $this->assertStringContainsString("\033[39m", $result);
    }

    public function test_style_danger_dark(): void
    {
        $result = Badge::renderWithIcon('TEST', '🔴', 'danger-dark');

        $this->assertStringContainsString("\033[31m", $result);
        $this->assertStringContainsString("\033[39m", $result);
    }

    public function test_style_warning_dark(): void
    {
        $result = Badge::renderWithIcon('TEST', '🟡', 'warning-dark');

        $this->assertStringContainsString("\033[33m", $result);
        $this->assertStringContainsString("\033[39m", $result);
    }

    public function test_render_with_icon_default_style(): void
    {
        $result = Badge::renderWithIcon('TEST', '🟢', 'default');

        $this->assertStringContainsString('🟢', $result);
        $this->assertStringContainsString('[TEST]', $result);
        $this->assertStringContainsString("\033[37m", $result);
        $this->assertStringContainsString("\033[39m", $result);
    }

    public function test_success_badge_contains_icon_and_text(): void
    {
        $result = Badge::success();

        $this->assertStringContainsString('🟢', $result);
        $this->assertStringContainsString('[SUCCESS]', $result);
        $this->assertStringContainsString("\033[32m", $result);
        $this->assertStringContainsString("\033[39m", $result);
    }

    public function test_danger_badge_contains_icon_and_text(): void
    {
        $result = Badge::danger();

        $this->assertStringContainsString('🔴', $result);
        $this->assertStringContainsString('[FAILED]', $result);
        $this->assertStringContainsString("\033[31m", $result);
        $this->assertStringContainsString("\033[39m", $result);
    }

    public function test_warning_badge_contains_icon_and_text(): void
    {
        $result = Badge::warning();

        $this->assertStringContainsString('🟡', $result);
        $this->assertStringContainsString('[PENDING]', $result);
        $this->assertStringContainsString("\033[33m", $result);
        $this->assertStringContainsString("\033[39m", $result);
    }

    public function test_info_badge_contains_icon_and_text(): void
    {
        $result = Badge::info();

        $this->assertStringContainsString('🔵', $result);
        $this->assertStringContainsString('[INFO]', $result);
        $this->assertStringContainsString("\033[34m", $result);
        $this->assertStringContainsString("\033[39m", $result);
    }

    public function test_primary_badge_contains_icon_and_text(): void
    {
        $result = Badge::primary();

        $this->assertStringContainsString('🟣', $result);
        $this->assertStringContainsString('[PRIMARY]', $result);
        $this->assertStringContainsString("\033[36m", $result);
        $this->assertStringContainsString("\033[39m", $result);
    }

    public function test_add_style_and_use_it(): void
    {
        Badge::addStyle('test_style', 'green', '⭐', 'TEST');

        $result = Badge::render('TEST', 'test_style');

        $this->assertStringContainsString('[TEST]', $result);
        $this->assertStringContainsString("\033[32m", $result);
        $this->assertStringContainsString("\033[39m", $result);
    }

    public function test_render_badge_with_all_colors(): void
    {
        $colors = ['success-dark', 'danger-dark', 'warning-dark', 'info-dark', 'primary-dark'];
        $expectedAnsi = ["\033[32m", "\033[31m", "\033[33m", "\033[34m", "\033[36m"];

        foreach ($colors as $index => $style) {
            $result = Badge::render('TEST', $style);
            $this->assertStringContainsString('[TEST]', $result);
            $this->assertStringContainsString($expectedAnsi[$index], $result);
            $this->assertStringContainsString("\033[39m", $result);
        }
    }

    public function test_render_badge_without_icon(): void
    {
        $result = Badge::render('TEXT', 'success');

        $this->assertStringContainsString('[TEXT]', $result);
        $this->assertStringNotContainsString('🟢', $result);
        $this->assertStringContainsString("\033[32m", $result);
        $this->assertStringContainsString("\033[39m", $result);
    }

    public function test_render_badge_default_without_bg(): void
    {
        $result = Badge::render('TEXT');

        $this->assertStringContainsString('[TEXT]', $result);
        // ✅ Pas de codes de fond (bg)
        $this->assertStringNotContainsString("\033[4", $result);
    }

    public function test_badge_uses_bold(): void
    {
        $result = Badge::render('TEST', 'success');

        $this->assertStringContainsString("\033[1m", $result);
        $this->assertStringContainsString("\033[22m", $result);
    }

    public function test_badge_ansi_color_codes(): void
    {
        $result = Badge::render('TEST', 'success-dark');

        // ✅ Vérifie que le texte est entre les codes ANSI
        $this->assertMatchesRegularExpression('/\033\[32m.*TEST.*\033\[39m/', $result);
    }

    public function test_badge_reset_after_text(): void
    {
        $result = Badge::render('TEST', 'success');

        // ✅ Vérifier que le reset FG est présent (FG_RESET = \033[39m)
        $this->assertStringContainsString("\033[39m", $result);
    }

    public function test_multiple_badges_different_colors(): void
    {
        $badge1 = Badge::render('OK', 'success');
        $badge2 = Badge::render('KO', 'danger');

        $this->assertStringContainsString("\033[32m", $badge1);
        $this->assertStringContainsString("\033[31m", $badge2);
    }

    public function test_render_badge_with_style_success(): void
    {
        $result = Badge::render('OK', 'success');

        $this->assertStringContainsString('[OK]', $result);
        $this->assertStringContainsString("\033[32m", $result);
        $this->assertStringContainsString("\033[39m", $result);
        $this->assertStringContainsString("\033[1m", $result);
        $this->assertStringContainsString("\033[22m", $result);
    }

    public function test_render_badge_with_style_danger(): void
    {
        $result = Badge::render('KO', 'danger');

        $this->assertStringContainsString('[KO]', $result);
        $this->assertStringContainsString("\033[31m", $result);
        $this->assertStringContainsString("\033[39m", $result);
        $this->assertStringContainsString("\033[1m", $result);
        $this->assertStringContainsString("\033[22m", $result);
    }

    public function test_render_badge_with_style_warning(): void
    {
        $result = Badge::render('WARN', 'warning');

        $this->assertStringContainsString('[WARN]', $result);
        $this->assertStringContainsString("\033[33m", $result);
        $this->assertStringContainsString("\033[39m", $result);
        $this->assertStringContainsString("\033[1m", $result);
        $this->assertStringContainsString("\033[22m", $result);
    }
}
