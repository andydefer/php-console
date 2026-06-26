<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Tests\Unit\Components;

use AndyDefer\ConsoleWriter\Console\Components\ProgressBar;

final class ProgressBarTest extends ComponentTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        ob_start();
    }

    protected function tearDown(): void
    {
        ob_end_clean();
        parent::tearDown();
    }

    public function test_create_progress_bar(): void
    {
        $progress = new ProgressBar(100);
        ob_clean();

        $this->assertSame(100, $progress->getTotal());
        $this->assertSame(0, $progress->getCurrent());
        $this->assertFalse($progress->isFinished());
    }

    public function test_advance(): void
    {
        $progress = new ProgressBar(100);
        ob_clean();

        $progress->advance();

        $this->assertSame(1, $progress->getCurrent());
    }

    public function test_advance_multiple_steps(): void
    {
        $progress = new ProgressBar(100);
        ob_clean();

        $progress->advance(10);

        $this->assertSame(10, $progress->getCurrent());
    }

    public function test_set_progress(): void
    {
        $progress = new ProgressBar(100);
        ob_clean();

        $progress->setProgress(50);

        $this->assertSame(50, $progress->getCurrent());
    }

    public function test_set_progress_bounds(): void
    {
        $progress = new ProgressBar(100);
        ob_clean();

        $progress->setProgress(-10);

        $this->assertSame(0, $progress->getCurrent());

        $progress->setProgress(150);

        $this->assertSame(100, $progress->getCurrent());
    }

    public function test_finish(): void
    {
        $progress = new ProgressBar(100);
        ob_clean();

        $progress->advance(50);
        $progress->finish();

        $this->assertSame(100, $progress->getCurrent());
        $this->assertTrue($progress->isFinished());
    }

    public function test_get_percentage(): void
    {
        $progress = new ProgressBar(100);
        ob_clean();

        $progress->advance(25);

        $this->assertSame(25.0, $progress->getPercentage());

        $progress->advance(25);

        $this->assertSame(50.0, $progress->getPercentage());
    }

    public function test_set_prefix(): void
    {
        $progress = new ProgressBar(100, 20, 'Initial');
        ob_clean();

        $progress->setPrefix('Updated');

        $progress->advance();
        $output = ob_get_clean();

        $this->assertStringContainsString('Updated', $output);

        ob_start();
    }

    public function test_set_suffix(): void
    {
        $progress = new ProgressBar(100, 20, '', 'Initial');
        ob_clean();

        $progress->setSuffix('Updated');

        $progress->advance();
        $output = ob_get_clean();

        $this->assertStringContainsString('Updated', $output);

        ob_start();
    }

    public function test_create_styled(): void
    {
        $progress = ProgressBar::createStyled(100, 'download');
        ob_clean();

        $progress->advance();
        $output = ob_get_clean();

        $this->assertStringContainsString('⬇️  Downloading', $output);

        ob_start();
    }

    public function test_without_percentage(): void
    {
        $progress = new ProgressBar(100, 20, '', '', false);
        ob_clean();

        $progress->advance(50);
        $output = ob_get_clean();

        $this->assertStringNotContainsString('%', $output);

        ob_start();
    }

    public function test_is_finished(): void
    {
        $progress = new ProgressBar(10);
        ob_clean();

        $this->assertFalse($progress->isFinished());

        for ($i = 0; $i < 10; $i++) {
            $progress->advance();
        }

        $this->assertTrue($progress->isFinished());
    }

    public function test_advance_beyond_total(): void
    {
        $progress = new ProgressBar(10);
        ob_clean();

        $progress->advance(15);

        $this->assertSame(10, $progress->getCurrent());
        $this->assertTrue($progress->isFinished());
    }

    public function test_constructor_output(): void
    {
        new ProgressBar(100);
        $output = ob_get_clean();

        $this->assertNotEmpty($output);
        $this->assertStringContainsString('[', $output);

        ob_start();
    }

    public function test_display_at_50_percent(): void
    {
        $progress = new ProgressBar(100, 20);
        ob_clean();

        $progress->setProgress(50);
        $output = ob_get_clean();

        $this->assertStringContainsString('50%', $output);
        $this->assertStringContainsString('█', $output);
        $this->assertStringContainsString('░', $output);

        ob_start();
    }

    public function test_display_at_100_percent(): void
    {
        $progress = new ProgressBar(100, 20);
        ob_clean();

        $progress->finish();
        $output = ob_get_clean();

        $this->assertStringContainsString('100%', $output);
        $this->assertStringContainsString('█', $output);
        $this->assertStringNotContainsString('░', $output);

        ob_start();
    }

    public function test_display_at_0_percent(): void
    {
        $progress = new ProgressBar(100, 20);
        ob_clean();

        $progress->setProgress(0);
        $output = ob_get_clean();

        $this->assertStringContainsString('0%', $output);
        $this->assertStringNotContainsString('█', $output);
        $this->assertStringContainsString('░', $output);

        ob_start();
    }

    public function test_create_styled_with_invalid_style(): void
    {
        $progress = ProgressBar::createStyled(100, 'invalid_style');
        ob_clean();

        $progress->advance();
        $output = ob_get_clean();

        $this->assertStringNotContainsString('Downloading', $output);

        ob_start();
    }

    public function test_add_style(): void
    {
        // ✅ Ajouter le style
        ProgressBar::addStyle('custom', '🚀 Custom', 'done');

        // ✅ Créer la barre avec le style
        $progress = ProgressBar::createStyled(100, 'custom');
        ob_clean();

        // ✅ Avancer et capturer
        $progress->advance();
        $output = ob_get_clean();

        // ✅ Vérifier
        $this->assertStringContainsString('🚀 Custom', $output);

        ob_start();
    }
}
