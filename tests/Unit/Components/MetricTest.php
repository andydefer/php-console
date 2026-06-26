<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Tests\Unit\Components;

use AndyDefer\ConsoleWriter\Console\Components\Metric;
use PHPUnit\Framework\TestCase;

final class MetricTest extends TestCase
{
    public function test_render_metric(): void
    {
        $result = Metric::render('CPU', '45%');

        $this->assertStringContainsString('CPU', $result);
        $this->assertStringContainsString('45%', $result);
        $this->assertStringContainsString("\033[1m", $result); // Bold
        $this->assertStringContainsString("\033[22m", $result);
    }

    public function test_render_metric_with_color(): void
    {
        $result = Metric::render('CPU', '45%', 'green');

        $this->assertStringContainsString('CPU', $result);
        $this->assertStringContainsString('45%', $result);
        $this->assertStringContainsString("\033[32m", $result);
        $this->assertStringContainsString("\033[39m", $result);
    }

    public function test_render_with_icon(): void
    {
        $result = Metric::renderWithIcon('CPU', '45%', '🖥️');

        $this->assertStringContainsString('🖥️', $result);
        $this->assertStringContainsString('CPU', $result);
        $this->assertStringContainsString('45%', $result);
        $this->assertStringContainsString("\033[1m", $result);
    }

    public function test_render_with_icon_and_color(): void
    {
        $result = Metric::renderWithIcon('CPU', '45%', '🖥️', 'yellow');

        $this->assertStringContainsString('🖥️', $result);
        $this->assertStringContainsString('CPU', $result);
        $this->assertStringContainsString('45%', $result);
        $this->assertStringContainsString("\033[33m", $result);
    }

    public function test_render_with_trend(): void
    {
        $result = Metric::renderWithTrend('CPU', '45%', '↑ 5%', 'green');

        $this->assertStringContainsString('CPU', $result);
        $this->assertStringContainsString('45%', $result);
        $this->assertStringContainsString('↑ 5%', $result);
        $this->assertStringContainsString("\033[32m", $result);
    }

    public function test_render_with_trend_red(): void
    {
        $result = Metric::renderWithTrend('RAM', '8.2 GB', '↓ 2%', 'red');

        $this->assertStringContainsString('RAM', $result);
        $this->assertStringContainsString('8.2 GB', $result);
        $this->assertStringContainsString('↓ 2%', $result);
        $this->assertStringContainsString("\033[31m", $result);
    }

    public function test_render_inline(): void
    {
        $result = Metric::renderInline('CPU', '45%');

        $this->assertStringContainsString('CPU:', $result);
        $this->assertStringContainsString('45%', $result);
        $this->assertStringContainsString("\033[1m", $result);
    }

    public function test_render_inline_with_color(): void
    {
        $result = Metric::renderInline('CPU', '45%', 'yellow');

        $this->assertStringContainsString('CPU:', $result);
        $this->assertStringContainsString('45%', $result);
        $this->assertStringContainsString("\033[33m", $result);
    }

    public function test_render_metric_with_all_colors(): void
    {
        $colors = ['red', 'green', 'yellow', 'blue', 'magenta', 'cyan', 'white'];
        $expectedAnsi = ["\033[31m", "\033[32m", "\033[33m", "\033[34m", "\033[35m", "\033[36m", "\033[37m"];

        foreach ($colors as $index => $color) {
            $result = Metric::render('CPU', '45%', $color);
            $this->assertStringContainsString('CPU', $result);
            $this->assertStringContainsString('45%', $result);
            $this->assertStringContainsString($expectedAnsi[$index], $result);
        }
    }

    public function test_metric_with_trend_and_value_color(): void
    {
        $result = Metric::renderWithTrend('CPU', '45%', '↑ 5%', 'green', 'yellow');

        $this->assertStringContainsString('CPU', $result);
        $this->assertStringContainsString('45%', $result);
        $this->assertStringContainsString('↑ 5%', $result);
        $this->assertStringContainsString("\033[33m", $result); // Value color
        $this->assertStringContainsString("\033[32m", $result); // Trend color
    }

    public function test_multiple_metrics(): void
    {
        $metric1 = Metric::render('CPU', '45%', 'yellow');
        $metric2 = Metric::render('RAM', '8.2 GB', 'green');
        $metric3 = Metric::render('Requests', '1,234/s', 'cyan');

        $this->assertStringContainsString('CPU', $metric1);
        $this->assertStringContainsString('RAM', $metric2);
        $this->assertStringContainsString('Requests', $metric3);
        $this->assertStringContainsString('45%', $metric1);
        $this->assertStringContainsString('8.2 GB', $metric2);
        $this->assertStringContainsString('1,234/s', $metric3);
    }

    public function test_metric_with_unicode_value(): void
    {
        $result = Metric::render('Status', '✅ OK', 'green');

        $this->assertStringContainsString('Status', $result);
        $this->assertStringContainsString('✅ OK', $result);
        $this->assertStringContainsString("\033[32m", $result);
    }

    public function test_metric_with_long_value(): void
    {
        $result = Metric::render('Memory', '8.2 / 16.0 GB', 'yellow');

        $this->assertStringContainsString('Memory', $result);
        $this->assertStringContainsString('8.2 / 16.0 GB', $result);
        $this->assertStringContainsString("\033[33m", $result);
    }

    public function test_metric_with_trend_icon(): void
    {
        $result = Metric::renderWithTrend('CPU', '45%', '📈 +5%', 'green');

        $this->assertStringContainsString('CPU', $result);
        $this->assertStringContainsString('45%', $result);
        $this->assertStringContainsString('📈 +5%', $result);
        $this->assertStringContainsString("\033[32m", $result);
    }

    public function test_metric_with_trend_down(): void
    {
        $result = Metric::renderWithTrend('CPU', '45%', '📉 -5%', 'red');

        $this->assertStringContainsString('CPU', $result);
        $this->assertStringContainsString('45%', $result);
        $this->assertStringContainsString('📉 -5%', $result);
        $this->assertStringContainsString("\033[31m", $result);
    }

    public function test_metric_inline_multiple(): void
    {
        $metric1 = Metric::renderInline('CPU', '45%', 'yellow');
        $metric2 = Metric::renderInline('RAM', '8.2 GB', 'green');

        $this->assertStringContainsString('CPU:', $metric1);
        $this->assertStringContainsString('RAM:', $metric2);
        $this->assertStringContainsString('45%', $metric1);
        $this->assertStringContainsString('8.2 GB', $metric2);
    }

    public function test_metric_without_bold(): void
    {
        $result = Metric::render('CPU', '45%');

        // Le label doit être en bold
        $this->assertStringContainsString("\033[1mCPU\033[22m", $result);
    }

    public function test_metric_with_emoji_label(): void
    {
        $result = Metric::renderWithIcon('💻 CPU', '45%', '🖥️');

        $this->assertStringContainsString('🖥️', $result);
        $this->assertStringContainsString('💻 CPU', $result);
        $this->assertStringContainsString('45%', $result);
    }
}
