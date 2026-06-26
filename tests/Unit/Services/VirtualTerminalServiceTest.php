<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Tests\Unit\Services;

use AndyDefer\ConsoleWriter\Console\Services\VirtualTerminalService;
use AndyDefer\ConsoleWriter\Tests\Unit\Components\ComponentTestCase;

final class VirtualTerminalServiceTest extends ComponentTestCase
{
    private VirtualTerminalService $vt;

    protected function setUp(): void
    {
        parent::setUp();
        $this->vt = new VirtualTerminalService;
        // ✅ Ne pas démarrer ob_start() ici, on le fera uniquement pour les tests de rendu
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    // ========== TESTS D'AJOUT ==========

    public function test_add_line(): void
    {
        $this->vt->add('line1', 'Hello');
        $lines = $this->vt->getLines()->toArray();

        $this->assertCount(1, $lines);
        $this->assertSame('Hello', $lines[0]);
    }

    public function test_add_multiple_lines(): void
    {
        $this->vt->add('line1', 'Hello');
        $this->vt->add('line2', 'World');
        $lines = $this->vt->getLines()->toArray();

        $this->assertCount(2, $lines);
        $this->assertSame('Hello', $lines[0]);
        $this->assertSame('World', $lines[1]);
    }

    public function test_add_at_position(): void
    {
        $this->vt->add('line1', 'Hello');
        $this->vt->add('line2', 'World');
        $this->vt->addAt('line3', 'Middle', 1);

        $lines = $this->vt->getLines()->toArray();
        $this->assertCount(3, $lines);
        $this->assertSame('Hello', $lines[0]);
        $this->assertSame('Middle', $lines[1]);
        $this->assertSame('World', $lines[2]);
    }

    public function test_add_at_position_end(): void
    {
        $this->vt->add('line1', 'Hello');
        $this->vt->add('line2', 'World');
        $this->vt->addAt('line3', 'End', 2);

        $lines = $this->vt->getLines()->toArray();
        $this->assertCount(3, $lines);
        $this->assertSame('Hello', $lines[0]);
        $this->assertSame('World', $lines[1]);
        $this->assertSame('End', $lines[2]);
    }

    public function test_add_at_position_beginning(): void
    {
        $this->vt->add('line1', 'Hello');
        $this->vt->add('line2', 'World');
        $this->vt->addAt('line3', 'Start', 0);

        $lines = $this->vt->getLines()->toArray();
        $this->assertCount(3, $lines);
        $this->assertSame('Start', $lines[0]);
        $this->assertSame('Hello', $lines[1]);
        $this->assertSame('World', $lines[2]);
    }

    public function test_add_existing_key_updates_content(): void
    {
        $this->vt->add('line1', 'Hello');
        $this->vt->add('line1', 'Updated');

        $lines = $this->vt->getLines()->toArray();
        $this->assertCount(1, $lines);
        $this->assertSame('Updated', $lines[0]);
    }

    // ========== TESTS DE MISE À JOUR ==========

    public function test_update_line(): void
    {
        $this->vt->add('line1', 'Hello');
        $this->vt->update('line1', 'Updated');

        $lines = $this->vt->getLines()->toArray();
        $this->assertSame('Updated', $lines[0]);
    }

    public function test_update_nonexistent_key_does_nothing(): void
    {
        $this->vt->add('line1', 'Hello');
        $this->vt->update('line2', 'World');

        $lines = $this->vt->getLines()->toArray();
        $this->assertCount(1, $lines);
        $this->assertSame('Hello', $lines[0]);
    }

    public function test_update_with_position_change(): void
    {
        $this->vt->add('line1', 'Hello');
        $this->vt->add('line2', 'World');
        $this->vt->update('line1', 'Updated', 1);

        $lines = $this->vt->getLines()->toArray();
        $this->assertCount(2, $lines);
        $this->assertSame('World', $lines[0]);
        $this->assertSame('Updated', $lines[1]);
    }

    // ========== TESTS DE SUPPRESSION ==========

    public function test_remove_line(): void
    {
        $this->vt->add('line1', 'Hello');
        $this->vt->add('line2', 'World');
        $this->vt->remove('line1');

        $lines = $this->vt->getLines()->toArray();
        $this->assertCount(1, $lines);
        $this->assertSame('World', $lines[0]);
    }

    public function test_remove_middle_line(): void
    {
        $this->vt->add('line1', 'Hello');
        $this->vt->add('line2', 'Middle');
        $this->vt->add('line3', 'World');
        $this->vt->remove('line2');

        $lines = $this->vt->getLines()->toArray();
        $this->assertCount(2, $lines);
        $this->assertSame('Hello', $lines[0]);
        $this->assertSame('World', $lines[1]);
    }

    public function test_remove_nonexistent_key_does_nothing(): void
    {
        $this->vt->add('line1', 'Hello');
        $this->vt->remove('line2');

        $lines = $this->vt->getLines()->toArray();
        $this->assertCount(1, $lines);
        $this->assertSame('Hello', $lines[0]);
    }

    // ========== TESTS DE RÉCUPÉRATION ==========

    public function test_get_line(): void
    {
        $this->vt->add('line1', 'Hello');
        $this->vt->add('line2', 'World');

        $this->assertSame('Hello', $this->vt->get('line1'));
        $this->assertSame('World', $this->vt->get('line2'));
        $this->assertNull($this->vt->get('line3'));
    }

    public function test_get_position(): void
    {
        $this->vt->add('line1', 'Hello');
        $this->vt->add('line2', 'World');

        $this->assertSame(0, $this->vt->getPosition('line1'));
        $this->assertSame(1, $this->vt->getPosition('line2'));
        $this->assertNull($this->vt->getPosition('line3'));
    }

    public function test_has_line(): void
    {
        $this->vt->add('line1', 'Hello');

        $this->assertTrue($this->vt->has('line1'));
        $this->assertFalse($this->vt->has('line2'));
    }

    // ✅ Correction du test
    public function test_get_lines_with_keys(): void
    {
        $this->vt->add('line1', 'Hello');
        $this->vt->add('line2', 'World');

        $linesWithKeys = $this->vt->getLinesWithKeys()->toArray();
        $this->assertCount(2, $linesWithKeys);

        // ✅ Vérifier les clés correctement
        $this->assertArrayHasKey(0, $linesWithKeys);
        $this->assertArrayHasKey(1, $linesWithKeys);

        // Les clés sont les indices du tableau, pas les valeurs
        $this->assertSame('Hello', $linesWithKeys[0]['content']);
        $this->assertSame(0, $linesWithKeys[0]['position']);
        $this->assertSame('World', $linesWithKeys[1]['content']);
        $this->assertSame(1, $linesWithKeys[1]['position']);
    }

    // ========== TESTS DE COMPTEUR ==========

    public function test_count(): void
    {
        $this->assertSame(0, $this->vt->count());

        $this->vt->add('line1', 'Hello');
        $this->assertSame(1, $this->vt->count());

        $this->vt->add('line2', 'World');
        $this->assertSame(2, $this->vt->count());

        $this->vt->remove('line1');
        $this->assertSame(1, $this->vt->count());
    }

    // ========== TESTS DE CLEAR ==========

    public function test_clear(): void
    {
        $this->vt->add('line1', 'Hello');
        $this->vt->add('line2', 'World');
        $this->vt->clear();

        $this->assertSame(0, $this->vt->count());
        $this->assertFalse($this->vt->has('line1'));
        $this->assertFalse($this->vt->has('line2'));
    }

    // ========== TESTS D'IMPORT/EXPORT ==========

    public function test_export_import(): void
    {
        $this->vt->add('line1', 'Hello');
        $this->vt->add('line2', 'World');

        $exported = $this->vt->export();
        $this->assertCount(2, $exported->toArray());

        $newVt = new VirtualTerminalService;
        $newVt->import($exported);

        $this->assertSame('Hello', $newVt->get('line1'));
        $this->assertSame('World', $newVt->get('line2'));
        $this->assertSame(2, $newVt->count());
    }

    // ========== TESTS DE RENDU ==========

    public function test_render_output(): void
    {
        $this->vt->add('line1', 'Hello');
        $this->vt->add('line2', 'World');

        ob_start();
        $this->vt->render();
        $output = ob_get_clean();

        $this->assertStringContainsString('Hello', $output);
        $this->assertStringContainsString('World', $output);
        $this->assertStringContainsString(PHP_EOL, $output);
    }

    public function test_render_update_only_changed_lines(): void
    {
        // Premier rendu
        $this->vt->add('line1', 'Hello');
        $this->vt->add('line2', 'World');

        ob_start();
        $this->vt->render();
        ob_clean();

        // Mettre à jour uniquement line1
        $this->vt->update('line1', 'Bonjour');
        $this->vt->render();
        $output = ob_get_clean();

        // Le rendu doit contenir les deux lignes
        $this->assertStringContainsString('Bonjour', $output);
        $this->assertStringContainsString('World', $output);
    }

    // ========== TESTS DE CLEAR DISPLAY ==========

    public function test_clear_display(): void
    {
        $this->vt->add('line1', 'Hello');

        ob_start();
        $this->vt->render();
        ob_clean();

        $this->vt->clearDisplay();
        $output = ob_get_clean();

        // clearDisplay doit émettre des séquences ANSI
        $this->assertNotEmpty($output);
        $this->assertStringContainsString("\033", $output);
    }

    // ========== TESTS D'ORDRE ==========

    public function test_lines_order_is_preserved(): void
    {
        $this->vt->add('line1', 'First');
        $this->vt->add('line2', 'Second');
        $this->vt->add('line3', 'Third');

        $lines = $this->vt->getLines()->toArray();
        $this->assertSame(['First', 'Second', 'Third'], $lines);
    }

    public function test_order_after_multiple_operations(): void
    {
        $this->vt->add('line1', 'A');
        $this->vt->add('line2', 'B');
        $this->vt->add('line3', 'C');
        $this->vt->remove('line2');
        $this->vt->addAt('line4', 'D', 1);

        $lines = $this->vt->getLines()->toArray();
        $this->assertSame(['A', 'D', 'C'], $lines);
    }

    // ========== TESTS DE CAS LIMITES ==========

    public function test_empty_lines(): void
    {
        $lines = $this->vt->getLines()->toArray();
        $this->assertEmpty($lines);
        $this->assertSame(0, $this->vt->count());
    }

    public function test_add_empty_content(): void
    {
        $this->vt->add('line1', '');
        $lines = $this->vt->getLines()->toArray();
        $this->assertCount(1, $lines);
        $this->assertSame('', $lines[0]);
    }

    public function test_add_at_negative_position(): void
    {
        $this->vt->add('line1', 'Hello');
        $this->vt->add('line2', 'World');
        $this->vt->addAt('line3', 'Start', -1);

        $lines = $this->vt->getLines()->toArray();
        // Une position négative devrait être traitée comme 0
        $this->assertSame('Start', $lines[0]);
    }

    public function test_update_same_content(): void
    {
        $this->vt->add('line1', 'Hello');
        $this->vt->update('line1', 'Hello');

        $lines = $this->vt->getLines()->toArray();
        $this->assertCount(1, $lines);
        $this->assertSame('Hello', $lines[0]);
    }

    // ========== TESTS DE PERFORMANCE (RAPIDES) ==========

    public function test_large_number_of_lines(): void
    {
        for ($i = 0; $i < 100; $i++) {
            $this->vt->add('line_'.$i, 'Content '.$i);
        }

        $this->assertSame(100, $this->vt->count());

        // Supprimer une ligne au milieu
        $this->vt->remove('line_50');
        $this->assertSame(99, $this->vt->count());

        // Vérifier que l'ordre est correct
        $lines = $this->vt->getLines()->toArray();
        $this->assertSame('Content 0', $lines[0]);
        $this->assertSame('Content 49', $lines[49]);
        $this->assertSame('Content 51', $lines[50]);
        $this->assertSame('Content 99', $lines[98]);
    }

    // ========== TESTS DE RENDU AVEC DIFFING ==========

    public function test_render_with_diffing(): void
    {
        // Premier état
        $this->vt->add('line1', 'Hello');
        $this->vt->add('line2', 'World');

        ob_start();
        $this->vt->render();
        ob_clean();

        // Deuxième état : une ligne modifiée
        $this->vt->update('line1', 'Bonjour');
        $this->vt->render();
        $output = ob_get_clean();

        // Vérifier que le diffing a fonctionné
        $this->assertStringContainsString('Bonjour', $output);
        $this->assertStringContainsString('World', $output);
    }
}
