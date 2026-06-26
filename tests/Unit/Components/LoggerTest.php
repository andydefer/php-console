<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Tests\Unit\Components;

use AndyDefer\ConsoleWriter\Console\Components\Logger;

final class LoggerTest extends ComponentTestCase
{
    public function test_info_log(): void
    {
        $result = Logger::info('Test message');

        $this->assertStringContainsString('INFO', $result);
        $this->assertStringContainsString('Test message', $result);
        $this->assertStringContainsString('[', $result);
        $this->assertStringContainsString(']', $result);
        $this->assertStringContainsString(' - ', $result);
        $this->assertStringContainsString("\033[34m", $result); // blue
    }

    public function test_success_log(): void
    {
        $result = Logger::success('Test message');

        $this->assertStringContainsString('SUCCESS', $result);
        $this->assertStringContainsString('Test message', $result);
        $this->assertStringContainsString("\033[32m", $result); // green
    }

    public function test_error_log(): void
    {
        $result = Logger::error('Test message');

        $this->assertStringContainsString('ERROR', $result);
        $this->assertStringContainsString('Test message', $result);
        $this->assertStringContainsString("\033[31m", $result); // red
    }

    public function test_warning_log(): void
    {
        $result = Logger::warning('Test message');

        $this->assertStringContainsString('WARNING', $result);
        $this->assertStringContainsString('Test message', $result);
        $this->assertStringContainsString("\033[33m", $result); // yellow
    }

    public function test_debug_log(): void
    {
        $result = Logger::debug('Test message');

        $this->assertStringContainsString('DEBUG', $result);
        $this->assertStringContainsString('Test message', $result);
        $this->assertStringContainsString("\033[90m", $result); // gray
    }

    public function test_notice_log(): void
    {
        $result = Logger::notice('Test message');

        $this->assertStringContainsString('NOTICE', $result);
        $this->assertStringContainsString('Test message', $result);
        $this->assertStringContainsString("\033[36m", $result); // cyan
    }

    public function test_critical_log(): void
    {
        $result = Logger::critical('Test message');

        $this->assertStringContainsString('CRITICAL', $result);
        $this->assertStringContainsString('Test message', $result);
        $this->assertStringContainsString("\033[35m", $result); // magenta
    }

    public function test_custom_log(): void
    {
        $result = Logger::log('CUSTOM', 'Test message', 'magenta');

        $this->assertStringContainsString('CUSTOM', $result);
        $this->assertStringContainsString('Test message', $result);
        $this->assertStringContainsString("\033[35m", $result);
    }

    public function test_timestamp_format(): void
    {
        // Sauvegarder le format actuel
        $oldFormat = Logger::getTimeFormat();

        // Changer le format
        Logger::setTimeFormat('Y-m-d H:i:s');
        $result = Logger::info('Test');

        // Vérifier que la date est présente
        $this->assertMatchesRegularExpression('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/', $result);

        // Restaurer
        Logger::setTimeFormat($oldFormat);
    }

    public function test_bold_level(): void
    {
        $result = Logger::info('Test');

        $this->assertStringContainsString("\033[1m", $result);
        $this->assertStringContainsString("\033[22m", $result);
    }

    public function test_timestamp_color(): void
    {
        $result = Logger::info('Test');

        $this->assertStringContainsString("\033[90m", $result); // gray pour le timestamp
    }
}
