<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Tests\Unit\Components;

use AndyDefer\ConsoleWriter\Console\Components\Timeline;
use AndyDefer\DomainStructures\Utils\ListCollection;
use PHPUnit\Framework\TestCase;

final class TimelineTest extends TestCase
{
    public function test_render_timeline(): void
    {
        $events = [
            ['12:00', 'Application démarrée'],
            ['12:01', 'Connexion DB'],
            ['12:02', 'Serveur prêt'],
        ];

        $result = Timeline::render($events);

        $this->assertStringContainsString('●', $result);
        $this->assertStringContainsString('12:00', $result);
        $this->assertStringContainsString('Application démarrée', $result);
        $this->assertStringContainsString('12:01', $result);
        $this->assertStringContainsString('Connexion DB', $result);
        $this->assertStringContainsString('12:02', $result);
        $this->assertStringContainsString('Serveur prêt', $result);
        $this->assertStringContainsString('│', $result);
        $this->assertStringContainsString("\033[1m", $result);
        $this->assertStringContainsString("\033[22m", $result);
    }

    public function test_render_timeline_with_description(): void
    {
        $events = [
            ['12:00', 'Application démarrée', 'Service web initialisé sur le port 8080'],
            ['12:01', 'Connexion DB', 'Connexion établie en 45ms'],
            ['12:02', 'Serveur prêt', 'En attente des requêtes'],
        ];

        $result = Timeline::render($events);

        $this->assertStringContainsString('Service web initialisé sur le port 8080', $result);
        $this->assertStringContainsString('Connexion établie en 45ms', $result);
        $this->assertStringContainsString('En attente des requêtes', $result);
        $this->assertStringContainsString("\033[90m", $result);
    }

    public function test_render_timeline_with_list_collection(): void
    {
        $events = ListCollection::from([
            ListCollection::from(['12:00', 'Application démarrée']),
            ListCollection::from(['12:01', 'Connexion DB']),
            ListCollection::from(['12:02', 'Serveur prêt']),
        ]);

        $result = Timeline::render($events);

        $this->assertStringContainsString('12:00', $result);
        $this->assertStringContainsString('Application démarrée', $result);
        $this->assertStringContainsString('12:01', $result);
        $this->assertStringContainsString('Connexion DB', $result);
        $this->assertStringContainsString('12:02', $result);
        $this->assertStringContainsString('Serveur prêt', $result);
    }

    public function test_render_timeline_with_mixed_events(): void
    {
        $events = [
            ['12:00', 'Event 1'],
            ListCollection::from(['12:01', 'Event 2']),
            ['12:02', 'Event 3', 'Description 3'],
        ];

        $result = Timeline::render($events);

        $this->assertStringContainsString('12:00', $result);
        $this->assertStringContainsString('Event 1', $result);
        $this->assertStringContainsString('12:01', $result);
        $this->assertStringContainsString('Event 2', $result);
        $this->assertStringContainsString('12:02', $result);
        $this->assertStringContainsString('Event 3', $result);
        $this->assertStringContainsString('Description 3', $result);
    }

    public function test_render_with_colors(): void
    {
        $events = [
            ['12:00', 'Event 1', 'Description 1'],
            ['12:01', 'Event 2', 'Description 2'],
        ];

        $colors = ['green', 'yellow'];

        $result = Timeline::renderWithColors($events, $colors);

        $this->assertStringContainsString("\033[32m", $result);
        $this->assertStringContainsString("\033[33m", $result);
        $this->assertStringContainsString('Event 1', $result);
        $this->assertStringContainsString('Event 2', $result);
        $this->assertStringContainsString('Description 1', $result);
        $this->assertStringContainsString('Description 2', $result);
    }

    public function test_render_with_icons(): void
    {
        $events = [
            ['12:00', 'Event 1'],
            ['12:01', 'Event 2'],
        ];

        $result = Timeline::renderWithIcons($events, '▸', 'green');

        $this->assertStringContainsString('▸', $result);
        $this->assertStringContainsString('12:00', $result);
        $this->assertStringContainsString('Event 1', $result);
        $this->assertStringContainsString('12:01', $result);
        $this->assertStringContainsString('Event 2', $result);
        $this->assertStringContainsString("\033[32m", $result);
    }

    public function test_render_with_status(): void
    {
        $events = [
            ['12:00', 'Task 1', 'Description 1'],
            ['12:01', 'Task 2', 'Description 2'],
        ];

        $statuses = ['success', 'error'];

        $result = Timeline::renderWithStatus($events, $statuses);

        $this->assertStringContainsString('✅', $result);
        $this->assertStringContainsString('❌', $result);
        $this->assertStringContainsString('Task 1', $result);
        $this->assertStringContainsString('Task 2', $result);
        $this->assertStringContainsString('Description 1', $result);
        $this->assertStringContainsString('Description 2', $result);
        $this->assertStringContainsString("\033[32m", $result);
        $this->assertStringContainsString("\033[31m", $result);
    }

    public function test_render_with_status_default(): void
    {
        $events = [
            ['12:00', 'Event 1'],
            ['12:01', 'Event 2'],
        ];

        $result = Timeline::renderWithStatus($events);

        $this->assertStringContainsString('●', $result);
        $this->assertStringContainsString('12:00', $result);
        $this->assertStringContainsString('Event 1', $result);
        $this->assertStringContainsString('12:01', $result);
        $this->assertStringContainsString('Event 2', $result);
    }

    public function test_render_empty(): void
    {
        $result = Timeline::render([]);

        $this->assertStringContainsString('No events to display', $result);
        $this->assertStringContainsString('⚠️', $result);
        $this->assertStringContainsString('<fg=yellow>', $result);
        $this->assertStringContainsString('</fg=yellow>', $result);
    }

    public function test_render_single_event(): void
    {
        $events = [
            ['12:00', 'Single event'],
        ];

        $result = Timeline::render($events);

        $this->assertStringContainsString('12:00', $result);
        $this->assertStringContainsString('Single event', $result);
        $this->assertStringNotContainsString('│', $result);
    }

    public function test_render_single_event_with_description(): void
    {
        $events = [
            ['12:00', 'Single event', 'With description'],
        ];

        $result = Timeline::render($events);

        $this->assertStringContainsString('12:00', $result);
        $this->assertStringContainsString('Single event', $result);
        $this->assertStringContainsString('With description', $result);
        $this->assertStringNotContainsString('│', $result);
    }

    public function test_render_with_long_description(): void
    {
        $events = [
            ['12:00', 'Event', 'This is a very long description that should be displayed properly without any truncation'],
        ];

        $result = Timeline::render($events);

        $this->assertStringContainsString('This is a very long description that should be displayed properly without any truncation', $result);
    }

    public function test_render_with_unicode(): void
    {
        $events = [
            ['12:00', 'Événement', 'Description avec accents'],
            ['12:01', '🚀 Déploiement', '✅ Succès du déploiement'],
        ];

        $result = Timeline::render($events);

        $this->assertStringContainsString('Événement', $result);
        $this->assertStringContainsString('Description avec accents', $result);
        $this->assertStringContainsString('🚀', $result);
        $this->assertStringContainsString('✅', $result);
        $this->assertStringContainsString('Déploiement', $result);
        $this->assertStringContainsString('Succès du déploiement', $result);
    }

    public function test_render_with_all_status_types(): void
    {
        $events = [
            ['12:00', 'Success', 'OK'],
            ['12:01', 'Error', 'Failed'],
            ['12:02', 'Warning', 'Warning message'],
            ['12:03', 'Info', 'Info message'],
            ['12:04', 'Pending', 'Pending task'],
        ];

        $statuses = ['success', 'error', 'warning', 'info', 'pending'];

        $result = Timeline::renderWithStatus($events, $statuses);

        $this->assertStringContainsString('✅', $result);
        $this->assertStringContainsString('❌', $result);
        $this->assertStringContainsString('⚠️', $result);
        $this->assertStringContainsString('ℹ️', $result);
        $this->assertStringContainsString('⏳', $result);
        $this->assertStringContainsString('Success', $result);
        $this->assertStringContainsString('Error', $result);
        $this->assertStringContainsString('Warning', $result);
        $this->assertStringContainsString('Info', $result);
        $this->assertStringContainsString('Pending', $result);
    }

    public function test_render_with_custom_color(): void
    {
        $events = [
            ['12:00', 'Event 1'],
            ['12:01', 'Event 2'],
        ];

        $result = Timeline::render($events, 'yellow');

        $this->assertStringContainsString("\033[33m", $result);
        $this->assertStringContainsString('12:00', $result);
        $this->assertStringContainsString('Event 1', $result);
        $this->assertStringContainsString('12:01', $result);
        $this->assertStringContainsString('Event 2', $result);
    }

    public function test_render_centered_bullets(): void
    {
        $events = [
            ['12:00', 'Event 1'],
            ['12:01', 'Event 2'],
        ];

        $result = Timeline::render($events);

        // ✅ Vérifier les composants séparément
        $this->assertStringContainsString('●', $result);
        $this->assertStringContainsString('12:00', $result);
        $this->assertStringContainsString('12:01', $result);
        $this->assertStringContainsString('Event 1', $result);
        $this->assertStringContainsString('Event 2', $result);

        // ✅ Supprimer les codes ANSI pour vérifier le format
        $plainResult = preg_replace('/\033\[[0-9;]*m/', '', $result);
        $this->assertStringContainsString('● 12:00', $plainResult);
        $this->assertStringContainsString('● 12:01', $plainResult);
    }

    public function test_render_centered_vertical_lines(): void
    {
        $events = [
            ['12:00', 'Event 1'],
            ['12:01', 'Event 2'],
        ];

        $result = Timeline::render($events);

        // ✅ La ligne verticale est présente entre les événements
        $this->assertStringContainsString('│', $result);

        // ✅ Vérifier que la ligne verticale est seule sur sa ligne
        $lines = explode("\n", $result);
        $this->assertCount(3, $lines); // Event1, ligne, Event2

        // ✅ Vérifier que la ligne verticale est sur sa propre ligne
        $this->assertStringContainsString('│', $lines[1]);
        $this->assertStringNotContainsString('Event', $lines[1]);
        $this->assertStringNotContainsString('12:', $lines[1]);
    }

    public function test_render_time_bold_white(): void
    {
        $events = [
            ['12:00', 'Event 1'],
        ];

        $result = Timeline::render($events);

        $this->assertStringContainsString("\033[1m", $result);
        $this->assertStringContainsString("\033[22m", $result);
        $this->assertStringContainsString('12:00', $result);
    }

    public function test_render_multiple_descriptions(): void
    {
        $events = [
            ['12:00', 'Event 1', 'Description 1'],
            ['12:01', 'Event 2', 'Description 2'],
            ['12:02', 'Event 3', 'Description 3'],
        ];

        $result = Timeline::render($events);

        $this->assertStringContainsString('Description 1', $result);
        $this->assertStringContainsString('Description 2', $result);
        $this->assertStringContainsString('Description 3', $result);

        $lines = explode("\n", $result);
        $this->assertGreaterThan(5, count($lines));
    }

    public function test_render_with_status_done_and_failed(): void
    {
        $events = [
            ['12:00', 'Task done', 'Completed'],
            ['12:01', 'Task failed', 'Error occurred'],
        ];

        $statuses = ['done', 'failed'];

        $result = Timeline::renderWithStatus($events, $statuses);

        $this->assertStringContainsString('✔️', $result);
        $this->assertStringContainsString('❌', $result);
        $this->assertStringContainsString('Task done', $result);
        $this->assertStringContainsString('Task failed', $result);
        $this->assertStringContainsString('Completed', $result);
        $this->assertStringContainsString('Error occurred', $result);
    }

    public function test_render_with_status_warning_and_info(): void
    {
        $events = [
            ['12:00', 'Warning task', 'Something is wrong'],
            ['12:01', 'Info task', 'Just a note'],
        ];

        $statuses = ['warning', 'info'];

        $result = Timeline::renderWithStatus($events, $statuses);

        $this->assertStringContainsString('⚠️', $result);
        $this->assertStringContainsString('ℹ️', $result);
        $this->assertStringContainsString('Warning task', $result);
        $this->assertStringContainsString('Info task', $result);
    }
}
