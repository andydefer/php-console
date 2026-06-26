<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Tests\Unit\Components;

use AndyDefer\ConsoleWriter\Console\Components\Spinner;
use PHPUnit\Framework\TestCase;

final class SpinnerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Démarrer le buffer pour capturer les sorties
        ob_start();
    }

    protected function tearDown(): void
    {
        // Vider et fermer le buffer
        ob_end_clean();
        parent::tearDown();
    }

    public function test_create_spinner(): void
    {
        ob_clean();

        $spinner = new Spinner('Test');
        $this->assertFalse($spinner->isRunning());
        $this->assertFalse($spinner->isFinished());
    }

    public function test_start_with_task(): void
    {
        ob_clean();

        $spinner = new Spinner('Test');
        $called = false;

        // Utiliser startSync pour éviter les forks dans les tests
        $spinner->startSync(function ($s) use (&$called) {
            $called = true;
            $s->success('Done');
        });

        $this->assertTrue($called);
        $this->assertFalse($spinner->isRunning());
        $this->assertTrue($spinner->isFinished());
    }

    public function test_start_with_exception(): void
    {
        ob_clean();

        $spinner = new Spinner('Test');
        $exception = null;

        try {
            $spinner->startSync(function ($s) {
                throw new \Exception('Test exception');
            });
        } catch (\Exception $e) {
            $exception = $e;
        }

        $this->assertNotNull($exception);
        $this->assertEquals('Test exception', $exception->getMessage());
    }

    public function test_success(): void
    {
        ob_clean();

        $spinner = new Spinner('Test');
        $spinner->startSync(function ($s) {
            $s->success('Done');
        });

        $output = ob_get_clean();
        $this->assertStringContainsString('✅ Done', $output);
        ob_start();
    }

    public function test_error(): void
    {
        ob_clean();

        $spinner = new Spinner('Test');
        $spinner->startSync(function ($s) {
            $s->error('Failed');
        });

        $output = ob_get_clean();
        $this->assertStringContainsString('❌ Failed', $output);
        ob_start();
    }

    public function test_info(): void
    {
        ob_clean();

        $spinner = new Spinner('Test');
        $spinner->startSync(function ($s) {
            $s->info('Info');
        });

        $output = ob_get_clean();
        $this->assertStringContainsString('ℹ️ Info', $output);
        ob_start();
    }

    public function test_warning(): void
    {
        ob_clean();

        $spinner = new Spinner('Test');
        $spinner->startSync(function ($s) {
            $s->warning('Warning');
        });

        $output = ob_get_clean();
        $this->assertStringContainsString('⚠️ Warning', $output);
        ob_start();
    }

    public function test_stop_with_custom_icon(): void
    {
        ob_clean();

        $spinner = new Spinner('Test');
        $spinner->startSync(function ($s) {
            $s->stop('🚀', 'Custom');
        });

        $output = ob_get_clean();
        $this->assertStringContainsString('🚀 Custom', $output);
        ob_start();
    }

    public function test_set_message(): void
    {
        ob_clean();

        $spinner = new Spinner('Initial');
        $spinner->startSync(function ($s) {
            $s->setMessage('Updated');
            $s->success('Done');
        });

        $output = ob_get_clean();
        $this->assertStringContainsString('Done', $output);
        ob_start();
    }

    public function test_wait_with_condition(): void
    {
        ob_clean();

        $spinner = new Spinner('Test');
        $ready = false;

        $spinner->wait(function () use (&$ready) {
            $ready = true;

            return $ready;
        });

        $this->assertTrue($ready);

        $output = ob_get_clean();
        $this->assertStringContainsString('✅', $output);
        ob_start();
    }

    public function test_prefix_and_suffix(): void
    {
        ob_clean();

        $spinner = new Spinner('Test', '🔃', 'en cours');
        $spinner->startSync(function ($s) {
            $s->success('Terminé');
        });

        $output = ob_get_clean();
        $this->assertStringContainsString('Terminé', $output);
        $this->assertStringContainsString('en cours', $output);
        ob_start();
    }

    public function test_auto_success_when_task_completes(): void
    {
        ob_clean();

        $spinner = new Spinner('Test');
        $spinner->startSync(function ($s) {
            // La tâche se termine sans appeler success()
        });

        $this->assertFalse($spinner->isRunning());
        $this->assertTrue($spinner->isFinished());

        $output = ob_get_clean();
        $this->assertStringContainsString('✅', $output);
        ob_start();
    }

    public function test_is_running_and_finished(): void
    {
        ob_clean();

        $spinner = new Spinner('Test');

        $spinner->startSync(function ($s) use ($spinner) {
            $this->assertTrue($spinner->isRunning());
            $this->assertFalse($spinner->isFinished());
            $s->success('Done');
        });

        $this->assertFalse($spinner->isRunning());
        $this->assertTrue($spinner->isFinished());
    }

    public function test_spinner_renders_frames(): void
    {
        ob_clean();

        $spinner = new Spinner('Test');
        $spinner->startSync(function ($s) {
            usleep(300000);
            $s->success('Done');
        });

        $output = ob_get_clean();
        $this->assertNotEmpty($output);
        $this->assertStringContainsString('✅ Done', $output);
        ob_start();
    }
}
