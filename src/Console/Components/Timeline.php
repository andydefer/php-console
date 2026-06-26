<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components;

use AndyDefer\ConsoleWriter\Console\Abstracts\Component;
use AndyDefer\DomainStructures\Utils\ListCollection;

final class Timeline extends Component
{
    private const BULLET = '●';

    private const VERTICAL_LINE = '│';

    private const BULLET_WIDTH = 3;

    private const TIME_WIDTH = 10;

    private const INDENT = '  ';

    private const DEFAULT_COLOR = 'cyan';

    public static function render(ListCollection|array $events, string $color = self::DEFAULT_COLOR): string
    {
        $eventsCollection = self::normalizeEvents($events);

        if ($eventsCollection->isEmpty()) {
            return self::fg('⚠️  No events to display', 'yellow');
        }

        $fg = self::getFgColor($color);
        $lines = [];
        $total = $eventsCollection->count();

        foreach ($eventsCollection as $index => $event) {
            $isLast = ($index === $total - 1);

            $time = (string) ($event->get(0) ?? '');
            $title = (string) ($event->get(1) ?? '');
            $description = (string) ($event->get(2) ?? '');

            $bullet = self::fg(
                str_pad(self::BULLET, self::BULLET_WIDTH, ' ', STR_PAD_BOTH).' ',
                $fg
            );

            $timeFormatted = ' '.self::bold(
                str_pad($time, self::TIME_WIDTH, ' ', STR_PAD_RIGHT)
            );

            $titleWithDesc = $title;
            if ($description !== '') {
                $titleWithDesc .= ' '.self::fg('('.$description.')', 'gray');
            }

            $line = self::INDENT.$bullet.$timeFormatted.' '.$titleWithDesc;
            $lines[] = $line;

            if (! $isLast) {
                $lineIndent = self::INDENT.str_repeat(' ', (int) ((self::BULLET_WIDTH - 1) / 2));
                $lines[] = $lineIndent.self::bold(self::fg(self::VERTICAL_LINE, 'gray'));
            }
        }

        return implode(PHP_EOL, $lines);
    }

    public static function renderWithColors(ListCollection|array $events, array $colors = []): string
    {
        $eventsCollection = self::normalizeEvents($events);

        if ($eventsCollection->isEmpty()) {
            return self::fg('⚠️  No events to display', 'yellow');
        }

        $lines = [];
        $total = $eventsCollection->count();

        foreach ($eventsCollection as $index => $event) {
            $isLast = ($index === $total - 1);
            $color = $colors[$index] ?? self::DEFAULT_COLOR;
            $fg = self::getFgColor($color);

            $time = (string) ($event->get(0) ?? '');
            $title = (string) ($event->get(1) ?? '');
            $description = (string) ($event->get(2) ?? '');

            $bullet = self::fg(
                str_pad(self::BULLET, self::BULLET_WIDTH, ' ', STR_PAD_BOTH).' ',
                $fg
            );

            $timeFormatted = ' '.self::bold(
                str_pad($time, self::TIME_WIDTH, ' ', STR_PAD_RIGHT)
            );

            $titleWithDesc = $title;
            if ($description !== '') {
                $titleWithDesc .= ' '.self::fg('('.$description.')', 'gray');
            }

            $line = self::INDENT.$bullet.$timeFormatted.' '.$titleWithDesc;
            $lines[] = $line;

            if (! $isLast) {
                $lineIndent = self::INDENT.str_repeat(' ', (int) ((self::BULLET_WIDTH - 1) / 2));
                $lines[] = $lineIndent.self::bold(self::fg(self::VERTICAL_LINE, 'gray'));
            }
        }

        return implode(PHP_EOL, $lines);
    }

    public static function renderWithIcons(ListCollection|array $events, string $icon = '●', string $color = self::DEFAULT_COLOR): string
    {
        $eventsCollection = self::normalizeEvents($events);

        if ($eventsCollection->isEmpty()) {
            return self::fg('⚠️  No events to display', 'yellow');
        }

        $fg = self::getFgColor($color);
        $lines = [];
        $total = $eventsCollection->count();

        foreach ($eventsCollection as $index => $event) {
            $isLast = ($index === $total - 1);

            $time = (string) ($event->get(0) ?? '');
            $title = (string) ($event->get(1) ?? '');
            $description = (string) ($event->get(2) ?? '');

            $bullet = self::fg(
                str_pad($icon, self::BULLET_WIDTH, ' ', STR_PAD_BOTH).' ',
                $fg
            );

            $timeFormatted = ' '.self::bold(
                str_pad($time, self::TIME_WIDTH, ' ', STR_PAD_RIGHT)
            );

            $titleWithDesc = $title;
            if ($description !== '') {
                $titleWithDesc .= ' '.self::fg('('.$description.')', 'gray');
            }

            $line = self::INDENT.$bullet.$timeFormatted.' '.$titleWithDesc;
            $lines[] = $line;

            if (! $isLast) {
                $lineIndent = self::INDENT.str_repeat(' ', (int) ((self::BULLET_WIDTH - 1) / 2));
                $lines[] = $lineIndent.self::bold(self::fg(self::VERTICAL_LINE, 'gray'));
            }
        }

        return implode(PHP_EOL, $lines);
    }

    public static function renderWithStatus(ListCollection|array $events, array $statuses = []): string
    {
        $eventsCollection = self::normalizeEvents($events);

        if ($eventsCollection->isEmpty()) {
            return self::fg('⚠️  No events to display', 'yellow');
        }

        $lines = [];
        $total = $eventsCollection->count();

        foreach ($eventsCollection as $index => $event) {
            $isLast = ($index === $total - 1);
            $status = $statuses[$index] ?? 'default';

            [$icon, $color] = match ($status) {
                'success' => ['✅', 'green'],
                'error' => ['❌', 'red'],
                'warning' => ['⚠️', 'yellow'],
                'info' => ['ℹ️', 'blue'],
                'pending' => ['⏳', 'yellow'],
                'done' => ['✔️', 'green'],
                'failed' => ['❌', 'red'],
                default => ['●', self::DEFAULT_COLOR],
            };

            $fg = self::getFgColor($color);

            $time = (string) ($event->get(0) ?? '');
            $title = (string) ($event->get(1) ?? '');
            $description = (string) ($event->get(2) ?? '');

            $bullet = self::fg(
                str_pad($icon, self::BULLET_WIDTH, ' ', STR_PAD_BOTH).' ',
                $fg
            );

            $timeFormatted = ' '.self::bold(
                str_pad($time, self::TIME_WIDTH, ' ', STR_PAD_RIGHT)
            );

            $titleWithDesc = $title;
            if ($description !== '') {
                $titleWithDesc .= ' '.self::fg('('.$description.')', 'gray');
            }

            $line = self::INDENT.$bullet.$timeFormatted.' '.$titleWithDesc;
            $lines[] = $line;

            if (! $isLast) {
                $lineIndent = self::INDENT.str_repeat(' ', (int) ((self::BULLET_WIDTH - 1) / 2));
                $lines[] = $lineIndent.self::bold(self::fg(self::VERTICAL_LINE, 'gray'));
            }
        }

        return implode(PHP_EOL, $lines);
    }

    private static function normalizeEvents(ListCollection|array $events): ListCollection
    {
        if ($events instanceof ListCollection) {
            $normalized = [];
            foreach ($events as $event) {
                if ($event instanceof ListCollection) {
                    $normalized[] = $event;
                } elseif (is_array($event)) {
                    $normalized[] = ListCollection::from($event);
                } else {
                    $normalized[] = ListCollection::from([$event]);
                }
            }

            return ListCollection::from($normalized);
        }

        $result = [];
        foreach ($events as $event) {
            if (is_array($event)) {
                $result[] = ListCollection::from($event);
            } elseif ($event instanceof ListCollection) {
                $result[] = $event;
            } else {
                $result[] = ListCollection::from([$event]);
            }
        }

        return ListCollection::from($result);
    }
}
