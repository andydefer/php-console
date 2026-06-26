<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Tests\Unit\Components;

use AndyDefer\ConsoleWriter\Console\Components\Notification;

final class NotificationTest extends ComponentTestCase
{
    public function test_render_notification(): void
    {
        $result = Notification::render('Hello World');

        $this->assertStringContainsString('🔔', $result);
        $this->assertStringContainsString('Hello World', $result);
        $this->assertStringContainsString("\033[1m", $result);
        $this->assertStringContainsString("\033[22m", $result);
    }

    public function test_render_notification_with_type(): void
    {
        $result = Notification::render('Success', 'success');

        $this->assertStringContainsString('✅', $result);
        $this->assertStringContainsString('Success', $result);
        $this->assertStringContainsString("\033[32m", $result);
    }

    public function test_render_notification_with_custom_icon(): void
    {
        $result = Notification::render('Deploy', 'info', '🚀');

        $this->assertStringContainsString('🚀', $result);
        $this->assertStringContainsString('Deploy', $result);
        $this->assertStringContainsString("\033[34m", $result);
    }

    public function test_success_notification(): void
    {
        $result = Notification::success('OK');

        $this->assertStringContainsString('✅', $result);
        $this->assertStringContainsString('OK', $result);
        $this->assertStringContainsString("\033[32m", $result);
    }

    public function test_error_notification(): void
    {
        $result = Notification::error('Failed');

        $this->assertStringContainsString('❌', $result);
        $this->assertStringContainsString('Failed', $result);
        $this->assertStringContainsString("\033[31m", $result);
    }

    public function test_warning_notification(): void
    {
        $result = Notification::warning('Warning');

        $this->assertStringContainsString('⚠️', $result);
        $this->assertStringContainsString('Warning', $result);
        $this->assertStringContainsString("\033[33m", $result);
    }

    public function test_info_notification(): void
    {
        $result = Notification::info('Info');

        $this->assertStringContainsString('ℹ️', $result);
        $this->assertStringContainsString('Info', $result);
        $this->assertStringContainsString("\033[34m", $result);
    }

    public function test_notification_with_color(): void
    {
        $result = Notification::withColor('Cyan', 'cyan', '💡');

        $this->assertStringContainsString('💡', $result);
        $this->assertStringContainsString('Cyan', $result);
        $this->assertStringContainsString("\033[36m", $result);
    }

    public function test_notification_with_color_default_icon(): void
    {
        $result = Notification::withColor('Green', 'green');

        $this->assertStringContainsString('🔔', $result);
        $this->assertStringContainsString('Green', $result);
        $this->assertStringContainsString("\033[32m", $result);
    }

    public function test_notification_bold(): void
    {
        $result = Notification::render('Bold Text');

        $this->assertStringContainsString("\033[1m", $result);
        $this->assertStringContainsString("\033[22m", $result);
        $this->assertStringContainsString('Bold Text', $result);
    }

    public function test_notification_with_icon_only(): void
    {
        $result = Notification::withIcon('Message', '📨', 'info');

        $this->assertStringContainsString('📨', $result);
        $this->assertStringContainsString('Message', $result);
        $this->assertStringContainsString("\033[34m", $result);
    }

    public function test_all_notification_types(): void
    {
        $types = [
            'success' => ['✅', "\033[32m"],
            'error' => ['❌', "\033[31m"],
            'warning' => ['⚠️', "\033[33m"],
            'info' => ['ℹ️', "\033[34m"],
        ];

        foreach ($types as $type => [$icon, $color]) {
            $result = Notification::render('Test', $type);
            $this->assertStringContainsString($icon, $result, "Type '{$type}' should contain icon '{$icon}'");
            $this->assertStringContainsString($color, $result, "Type '{$type}' should contain color '{$color}'");
            $this->assertStringContainsString('Test', $result, "Type '{$type}' should contain message 'Test'");
        }
    }

    public function test_notification_with_unknown_type(): void
    {
        $result = Notification::render('Test', 'unknown');

        $this->assertStringContainsString('🔔', $result);
        $this->assertStringContainsString('Test', $result);
        $this->assertStringContainsString("\033[37m", $result); // white
    }

    public function test_notification_with_icon_and_type_override(): void
    {
        // ✅ L'icône personnalisée doit remplacer celle du type
        $result = Notification::render('Test', 'success', '⭐');

        $this->assertStringContainsString('⭐', $result);
        $this->assertStringNotContainsString('✅', $result);
        $this->assertStringContainsString("\033[32m", $result); // couleur green du type success
    }
}
