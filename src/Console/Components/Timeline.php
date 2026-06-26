<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components;

use AndyDefer\ConsoleWriter\Console\Enums\FgColor;
use AndyDefer\ConsoleWriter\Console\Enums\Options;
use AndyDefer\ConsoleWriter\Console\Services\AnsiConverterService;
use AndyDefer\ConsoleWriter\Contracts\Services\AnsiConverterInterface;
use AndyDefer\DomainStructures\Utils\ListCollection;

final class Timeline
{
    private const BULLET = '●';

    private const VERTICAL_LINE = '│';

    private const BULLET_WIDTH = 3;

    private const TIME_WIDTH = 10;

    private const INDENT = '  ';

    private const DEFAULT_COLOR = 'cyan';

    private static ?AnsiConverterInterface $ansi = null;

    private static function getAnsi(): AnsiConverterInterface
    {
        if (self::$ansi === null) {
            self::$ansi = new AnsiConverterService;
        }

        return self::$ansi;
    }

    public static function render(ListCollection|array $events, string $color = self::DEFAULT_COLOR): string
    {
        $eventsCollection = self::normalizeEvents($events);

        if ($eventsCollection->isEmpty()) {
            return '<fg=yellow>⚠️  No events to display</fg=yellow>';
        }

        $ansi = self::getAnsi();
        $fg = self::getFgColor($color);
        $lines = [];

        $total = $eventsCollection->count();

        foreach ($eventsCollection as $index => $event) {
            $isLast = ($index === $total - 1);

            $time = $event->get(0) ?? '';
            $title = $event->get(1) ?? '';
            $description = $event->get(2) ?? '';

            // ✅ S'assurer que time et title sont des strings
            $time = is_string($time) ? $time : (string) $time;
            $title = is_string($title) ? $title : (string) $title;
            $description = is_string($description) ? $description : (string) $description;

            // Puces centrées
            $bullet = $ansi->colorEnum(
                str_pad(self::BULLET, self::BULLET_WIDTH, ' ', STR_PAD_BOTH),
                $fg
            );

            // Heure en bold blanc
            $timeFormatted = $ansi->option(
                str_pad($time, self::TIME_WIDTH, ' ', STR_PAD_RIGHT),
                Options::BOLD
            );

            $titleFormatted = $title;

            $line = self::INDENT.$bullet.' '.$timeFormatted.' '.$titleFormatted;
            $lines[] = $line;

            // Description en gris
            if ($description !== '') {
                $descIndent = self::INDENT.str_repeat(' ', self::BULLET_WIDTH + 1 + self::TIME_WIDTH + 1);
                $lines[] = $descIndent.$ansi->colorEnum($description, FgColor::GRAY);
            }

            // Ligne verticale centrée
            if (! $isLast) {
                $lineIndent = self::INDENT.str_repeat(' ', (int) ((self::BULLET_WIDTH - 1) / 2));
                $lines[] = $lineIndent.$ansi->colorEnum(self::VERTICAL_LINE, FgColor::GRAY);
            }
        }

        return implode(PHP_EOL, $lines);
    }

    public static function renderWithColors(ListCollection|array $events, array $colors = []): string
    {
        $eventsCollection = self::normalizeEvents($events);

        if ($eventsCollection->isEmpty()) {
            return '<fg=yellow>⚠️  No events to display</fg=yellow>';
        }

        $ansi = self::getAnsi();
        $lines = [];

        $total = $eventsCollection->count();

        foreach ($eventsCollection as $index => $event) {
            $isLast = ($index === $total - 1);
            $color = $colors[$index] ?? self::DEFAULT_COLOR;
            $fg = self::getFgColor($color);

            $time = $event->get(0) ?? '';
            $title = $event->get(1) ?? '';
            $description = $event->get(2) ?? '';

            $time = is_string($time) ? $time : (string) $time;
            $title = is_string($title) ? $title : (string) $title;
            $description = is_string($description) ? $description : (string) $description;

            $bullet = $ansi->colorEnum(
                str_pad(self::BULLET, self::BULLET_WIDTH, ' ', STR_PAD_BOTH),
                $fg
            );
            $timeFormatted = $ansi->option(
                str_pad($time, self::TIME_WIDTH, ' ', STR_PAD_RIGHT),
                Options::BOLD
            );
            $titleFormatted = $title;

            $line = self::INDENT.$bullet.' '.$timeFormatted.' '.$titleFormatted;
            $lines[] = $line;

            if ($description !== '') {
                $descIndent = self::INDENT.str_repeat(' ', self::BULLET_WIDTH + 1 + self::TIME_WIDTH + 1);
                $lines[] = $descIndent.$ansi->colorEnum($description, FgColor::GRAY);
            }

            if (! $isLast) {
                $lineIndent = self::INDENT.str_repeat(' ', (int) ((self::BULLET_WIDTH - 1) / 2));
                $lines[] = $lineIndent.$ansi->colorEnum(self::VERTICAL_LINE, FgColor::GRAY);
            }
        }

        return implode(PHP_EOL, $lines);
    }

    public static function renderWithIcons(ListCollection|array $events, string $icon = '●', string $color = self::DEFAULT_COLOR): string
    {
        $eventsCollection = self::normalizeEvents($events);

        if ($eventsCollection->isEmpty()) {
            return '<fg=yellow>⚠️  No events to display</fg=yellow>';
        }

        $ansi = self::getAnsi();
        $fg = self::getFgColor($color);
        $lines = [];

        $total = $eventsCollection->count();

        foreach ($eventsCollection as $index => $event) {
            $isLast = ($index === $total - 1);

            $time = $event->get(0) ?? '';
            $title = $event->get(1) ?? '';
            $description = $event->get(2) ?? '';

            $time = is_string($time) ? $time : (string) $time;
            $title = is_string($title) ? $title : (string) $title;
            $description = is_string($description) ? $description : (string) $description;

            $bullet = $ansi->colorEnum(
                str_pad($icon, self::BULLET_WIDTH, ' ', STR_PAD_BOTH),
                $fg
            );
            $timeFormatted = $ansi->option(
                str_pad($time, self::TIME_WIDTH, ' ', STR_PAD_RIGHT),
                Options::BOLD
            );
            $titleFormatted = $title;

            $line = self::INDENT.$bullet.' '.$timeFormatted.' '.$titleFormatted;
            $lines[] = $line;

            if ($description !== '') {
                $descIndent = self::INDENT.str_repeat(' ', self::BULLET_WIDTH + 1 + self::TIME_WIDTH + 1);
                $lines[] = $descIndent.$ansi->colorEnum($description, FgColor::GRAY);
            }

            if (! $isLast) {
                $lineIndent = self::INDENT.str_repeat(' ', (int) ((self::BULLET_WIDTH - 1) / 2));
                $lines[] = $lineIndent.$ansi->colorEnum(self::VERTICAL_LINE, FgColor::GRAY);
            }
        }

        return implode(PHP_EOL, $lines);
    }

    public static function renderWithStatus(ListCollection|array $events, array $statuses = []): string
    {
        $eventsCollection = self::normalizeEvents($events);

        if ($eventsCollection->isEmpty()) {
            return '<fg=yellow>⚠️  No events to display</fg=yellow>';
        }

        $ansi = self::getAnsi();
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

            $time = $event->get(0) ?? '';
            $title = $event->get(1) ?? '';
            $description = $event->get(2) ?? '';

            $time = is_string($time) ? $time : (string) $time;
            $title = is_string($title) ? $title : (string) $title;
            $description = is_string($description) ? $description : (string) $description;

            $bullet = $ansi->colorEnum(
                str_pad($icon, self::BULLET_WIDTH, ' ', STR_PAD_BOTH),
                $fg
            );
            $timeFormatted = $ansi->option(
                str_pad($time, self::TIME_WIDTH, ' ', STR_PAD_RIGHT),
                Options::BOLD
            );
            $titleFormatted = $title;

            $line = self::INDENT.$bullet.' '.$timeFormatted.' '.$titleFormatted;
            $lines[] = $line;

            if ($description !== '') {
                $descIndent = self::INDENT.str_repeat(' ', self::BULLET_WIDTH + 1 + self::TIME_WIDTH + 1);
                $lines[] = $descIndent.$ansi->colorEnum($description, FgColor::GRAY);
            }

            if (! $isLast) {
                $lineIndent = self::INDENT.str_repeat(' ', (int) ((self::BULLET_WIDTH - 1) / 2));
                $lines[] = $lineIndent.$ansi->colorEnum(self::VERTICAL_LINE, FgColor::GRAY);
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

    private static function getFgColor(string $color): FgColor
    {
        return match ($color) {
            'black' => FgColor::BLACK,
            'red' => FgColor::RED,
            'green' => FgColor::GREEN,
            'yellow' => FgColor::YELLOW,
            'blue' => FgColor::BLUE,
            'magenta' => FgColor::MAGENTA,
            'cyan' => FgColor::CYAN,
            'white' => FgColor::WHITE,
            'gray' => FgColor::GRAY,
            default => FgColor::CYAN,
        };
    }
}
