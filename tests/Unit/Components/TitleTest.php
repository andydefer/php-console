<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Tests\Unit\Components;

use AndyDefer\ConsoleWriter\Console\Components\Title;

final class TitleTest extends ComponentTestCase
{
    public function test_render_title(): void
    {
        $result = Title::render('System Status');
        $plainResult = $this->stripAnsi($result);

        $this->assertStringContainsString('System Status', $plainResult);
        $this->assertStringContainsString('╔', $plainResult);
        $this->assertStringContainsString('╚', $plainResult);
        $this->assertStringContainsString('System Status', $plainResult);
    }
}
