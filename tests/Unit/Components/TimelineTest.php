<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Tests\Unit\Components;

use AndyDefer\ConsoleWriter\Console\Components\Timeline;
use AndyDefer\DomainStructures\Utils\ListCollection;

final class TimelineTest extends ComponentTestCase
{
    public function test_render_timeline(): void
    {
        $events = [
            ['12:00', 'Application démarrée'],
            ['12:01', 'Connexion DB'],
            ['12:02', 'Serveur prêt'],
        ];

        $result = Timeline::render($events);
        $plainResult = $this->stripAnsi($result);

        $this->assertStringContainsString('●', $plainResult);
        $this->assertStringContainsString('12:00', $plainResult);
        $this->assertStringContainsString('Application démarrée', $plainResult);
        $this->assertStringContainsString('12:01', $plainResult);
        $this->assertStringContainsString('Connexion DB', $plainResult);
        $this->assertStringContainsString('12:02', $plainResult);
        $this->assertStringContainsString('Serveur prêt', $plainResult);
        $this->assertStringContainsString('│', $plainResult);
    }

    public function test_render_timeline_with_description(): void
    {
        $events = [
            ['12:00', 'Application démarrée', 'Service web initialisé sur le port 8080'],
            ['12:01', 'Connexion DB', 'Connexion établie en 45ms'],
            ['12:02', 'Serveur prêt', 'En attente des requêtes'],
        ];

        $result = Timeline::render($events);
        $plainResult = $this->stripAnsi($result);

        $this->assertStringContainsString('Service web initialisé sur le port 8080', $plainResult);
        $this->assertStringContainsString('Connexion établie en 45ms', $plainResult);
        $this->assertStringContainsString('En attente des requêtes', $plainResult);
    }

    public function test_render_timeline_with_list_collection(): void
    {
        $events = ListCollection::from([
            ListCollection::from(['12:00', 'Application démarrée']),
            ListCollection::from(['12:01', 'Connexion DB']),
            ListCollection::from(['12:02', 'Serveur prêt']),
        ]);

        $result = Timeline::render($events);
        $plainResult = $this->stripAnsi($result);

        $this->assertStringContainsString('12:00', $plainResult);
        $this->assertStringContainsString('Application démarrée', $plainResult);
        $this->assertStringContainsString('12:01', $plainResult);
        $this->assertStringContainsString('Connexion DB', $plainResult);
        $this->assertStringContainsString('12:02', $plainResult);
        $this->assertStringContainsString('Serveur prêt', $plainResult);
    }

    public function test_render_timeline_with_mixed_events(): void
    {
        $events = [
            ['12:00', 'Event 1'],
            ListCollection::from(['12:01', 'Event 2']),
            ['12:02', 'Event 3', 'Description 3'],
        ];

        $result = Timeline::render($events);
        $plainResult = $this->stripAnsi($result);

        $this->assertStringContainsString('12:00', $plainResult);
        $this->assertStringContainsString('Event 1', $plainResult);
        $this->assertStringContainsString('12:01', $plainResult);
        $this->assertStringContainsString('Event 2', $plainResult);
        $this->assertStringContainsString('12:02', $plainResult);
        $this->assertStringContainsString('Event 3', $plainResult);
        $this->assertStringContainsString('Description 3', $plainResult);
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
        $plainResult = $this->stripAnsi($result);

        $this->assertStringContainsString('●', $plainResult);
        $this->assertStringContainsString('12:00', $plainResult);
        $this->assertStringContainsString('Event 1', $plainResult);
        $this->assertStringContainsString('12:01', $plainResult);
        $this->assertStringContainsString('Event 2', $plainResult);
    }

    public function test_render_empty(): void
    {
        $result = Timeline::render([]);
        $plainResult = $this->stripAnsi($result);

        $this->assertStringContainsString('No events to display', $plainResult);
        $this->assertStringContainsString('⚠️', $plainResult);
    }

    public function test_render_single_event(): void
    {
        $events = [
            ['12:00', 'Single event'],
        ];

        $result = Timeline::render($events);
        $plainResult = $this->stripAnsi($result);

        $this->assertStringContainsString('12:00', $plainResult);
        $this->assertStringContainsString('Single event', $plainResult);
        $this->assertStringNotContainsString('│', $plainResult);
    }

    public function test_render_single_event_with_description(): void
    {
        $events = [
            ['12:00', 'Single event', 'With description'],
        ];

        $result = Timeline::render($events);
        $plainResult = $this->stripAnsi($result);

        $this->assertStringContainsString('12:00', $plainResult);
        $this->assertStringContainsString('Single event', $plainResult);
        $this->assertStringContainsString('With description', $plainResult);
        $this->assertStringNotContainsString('│', $plainResult);
    }

    public function test_render_with_long_description(): void
    {
        $events = [
            ['12:00', 'Event', 'This is a very long description that should be displayed properly without any truncation'],
        ];

        $result = Timeline::render($events);
        $plainResult = $this->stripAnsi($result);

        $this->assertStringContainsString('This is a very long description that should be displayed properly without any truncation', $plainResult);
    }

    public function test_render_with_unicode(): void
    {
        $events = [
            ['12:00', 'Événement', 'Description avec accents'],
            ['12:01', '🚀 Déploiement', '✅ Succès du déploiement'],
        ];

        $result = Timeline::render($events);
        $plainResult = $this->stripAnsi($result);

        $this->assertStringContainsString('Événement', $plainResult);
        $this->assertStringContainsString('Description avec accents', $plainResult);
        $this->assertStringContainsString('🚀', $plainResult);
        $this->assertStringContainsString('✅', $plainResult);
        $this->assertStringContainsString('Déploiement', $plainResult);
        $this->assertStringContainsString('Succès du déploiement', $plainResult);
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
        $plainResult = $this->stripAnsi($result);

        $this->assertStringContainsString('●', $plainResult);
        $this->assertStringContainsString('12:00', $plainResult);
        $this->assertStringContainsString('Event 1', $plainResult);
        $this->assertStringContainsString('12:01', $plainResult);
        $this->assertStringContainsString('Event 2', $plainResult);

        // Vérifier la couleur jaune
        $this->assertStringContainsString("\033[33m", $result);
    }

    public function test_render_centered_bullets(): void
    {
        $events = [
            ['12:00', 'Event 1'],
            ['12:01', 'Event 2'],
        ];

        $result = Timeline::render($events);
        $plainResult = $this->stripAnsi($result);

        // ✅ Vérifier que les puces et les heures sont présentes (avec les espaces)
        $this->assertStringContainsString('●', $plainResult);
        $this->assertStringContainsString('12:00', $plainResult);
        $this->assertStringContainsString('12:01', $plainResult);
        $this->assertStringContainsString('Event 1', $plainResult);
        $this->assertStringContainsString('Event 2', $plainResult);

        // ✅ Vérifier que les puces sont alignées avec les heures
        $this->assertMatchesRegularExpression('/●\s+12:00/', $plainResult);
        $this->assertMatchesRegularExpression('/●\s+12:01/', $plainResult);
    }

    public function test_render_centered_vertical_lines(): void
    {
        $events = [
            ['12:00', 'Event 1'],
            ['12:01', 'Event 2'],
        ];

        $result = Timeline::render($events);
        $plainResult = $this->stripAnsi($result);

        // La ligne verticale est présente
        $this->assertStringContainsString('│', $plainResult);

        // Vérifier que la ligne verticale est sur sa propre ligne
        $lines = explode("\n", $plainResult);
        $this->assertCount(3, $lines); // Event1, ligne verticale, Event2

        // La ligne du milieu ne contient que '│' (avec indentations)
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

        // Vérifier les codes ANSI pour le bold
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
        $plainResult = $this->stripAnsi($result);

        $this->assertStringContainsString('Description 1', $plainResult);
        $this->assertStringContainsString('Description 2', $plainResult);
        $this->assertStringContainsString('Description 3', $plainResult);

        $lines = explode("\n", $plainResult);
        $this->assertGreaterThanOrEqual(5, count($lines));
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
