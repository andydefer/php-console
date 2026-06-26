<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Tests\Unit\Components;

use AndyDefer\ConsoleWriter\Console\Components\Alert;

final class AlertTest extends ComponentTestCase
{
    public function test_render_alert(): void
    {
        $result = Alert::render('Important message');
        $plainResult = strip_tags($result);

        $this->assertStringContainsString('⚠️', $plainResult);
        $this->assertStringContainsString('Important message', $plainResult);
        $this->assertStringContainsString('┌', $plainResult);
        $this->assertStringContainsString('└', $plainResult);

        // ✅ Vérifier que les codes ANSI sont présents (jaune = 33m)
        $this->assertStringContainsString("\033[33m", $result);
        $this->assertStringContainsString("\033[39m", $result); // reset fg
    }
}
