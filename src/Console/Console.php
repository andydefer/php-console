<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console;

use AndyDefer\ConsoleWriter\Console\Components\AdaptiveTable;
use AndyDefer\ConsoleWriter\Console\Components\Alert;
use AndyDefer\ConsoleWriter\Console\Components\Error;
use AndyDefer\ConsoleWriter\Console\Components\Info;
use AndyDefer\ConsoleWriter\Console\Components\KeyValue;
use AndyDefer\ConsoleWriter\Console\Components\Link;
use AndyDefer\ConsoleWriter\Console\Components\ListComponent;
use AndyDefer\ConsoleWriter\Console\Components\Success;
use AndyDefer\ConsoleWriter\Console\Components\Table;
use AndyDefer\ConsoleWriter\Console\Components\Title;
use AndyDefer\ConsoleWriter\Console\Enums\ListStyle;
use AndyDefer\ConsoleWriter\Console\Services\AnsiConverterService;
use AndyDefer\ConsoleWriter\Contracts\Services\AnsiConverterInterface;
use AndyDefer\DomainStructures\Utils\ListCollection;
use AndyDefer\DomainStructures\Utils\MapCollection;
use AndyDefer\DomainStructures\Utils\SetCollection;
use AndyDefer\PhpVo\ValueObjects\Types\BoolVO;

final class Console
{
    private ListCollection $lines;

    private BoolVO $buffered;

    private AnsiConverterInterface $ansiConverter;

    public function __construct(?AnsiConverterInterface $ansiConverter = null)
    {
        $this->lines = ListCollection::from([]);
        $this->buffered = new BoolVO(false);
        $this->ansiConverter = $ansiConverter ?? new AnsiConverterService;
    }

    // ========== COMPOSANTS EXISTANTS ==========

    public function info(string $message): self
    {
        $this->addLine(Info::render($message));

        return $this;
    }

    public function success(string $message): self
    {
        $this->addLine(Success::render($message));

        return $this;
    }

    public function error(string $message): self
    {
        $this->addLine(Error::render($message));

        return $this;
    }

    public function alert(string $message): self
    {
        $this->addLine(Alert::render($message));

        return $this;
    }

    public function title(string $message): self
    {
        $this->addLine(Title::render($message));

        return $this;
    }

    public function table(ListCollection|array $headers, ListCollection|array $rows): self
    {
        $headersCollection = $headers instanceof ListCollection
            ? $headers
            : ListCollection::from($headers);

        $rowsCollection = $rows instanceof ListCollection
            ? $rows
            : ListCollection::from($rows);

        $this->addLine(Table::render($headersCollection, $rowsCollection));

        return $this;
    }

    public function adaptiveTable(ListCollection|array $headers, ListCollection|array $rows): self
    {
        $headersCollection = $headers instanceof ListCollection
            ? $headers
            : ListCollection::from($headers);

        $rowsCollection = $rows instanceof ListCollection
            ? $rows
            : ListCollection::from($rows);

        $this->addLine(AdaptiveTable::render($headersCollection, $rowsCollection));

        return $this;
    }

    // ========== NOUVEAUX COMPOSANTS ==========

    public function link(string $url, ?string $text = null): self
    {
        if ($text === null) {
            $this->addLine(Link::render($url));
        } else {
            $this->addLine(Link::renderWithText($url, $text));
        }

        return $this;
    }

    public function list(SetCollection|array $items, ListStyle $style = ListStyle::BULLET, int $indent = 0): self
    {
        $itemsCollection = $items instanceof SetCollection
            ? $items
            : SetCollection::from($items);

        $this->addLine(ListComponent::render($itemsCollection, $style, $indent));

        return $this;
    }

    public function listColored(SetCollection|array $items, ListStyle $style = ListStyle::BULLET, string $color = 'green'): self
    {
        $itemsCollection = $items instanceof SetCollection
            ? $items
            : SetCollection::from($items);

        $this->addLine(ListComponent::renderColored($itemsCollection, $style, $color));

        return $this;
    }

    public function keyValue(MapCollection|array $data, int $indent = 0): self
    {
        $dataCollection = $data instanceof MapCollection
            ? $data
            : MapCollection::from($data);

        $this->addLine(KeyValue::render($dataCollection, $indent));

        return $this;
    }

    public function keyValueWithColor(MapCollection|array $data, string $keyColor = 'cyan', int $indent = 0): self
    {
        $dataCollection = $data instanceof MapCollection
            ? $data
            : MapCollection::from($data);

        $this->addLine(KeyValue::renderWithColor($dataCollection, $keyColor, $indent));

        return $this;
    }

    public function keyValueWithValueColor(MapCollection|array $data, string $valueColor = 'green', int $indent = 0): self
    {
        $dataCollection = $data instanceof MapCollection
            ? $data
            : MapCollection::from($data);

        $this->addLine(KeyValue::renderWithValueColor($dataCollection, $valueColor, $indent));

        return $this;
    }

    public function keyValueWithSeparator(MapCollection|array $data, string $separator = ' → ', int $indent = 0): self
    {
        $dataCollection = $data instanceof MapCollection
            ? $data
            : MapCollection::from($data);

        $this->addLine(KeyValue::renderWithSeparator($dataCollection, $separator, $indent));

        return $this;
    }

    // ========== MÉTHODES D'ACCÈS AU SERVICE ANSI ==========

    public function getAnsiConverter(): AnsiConverterInterface
    {
        return $this->ansiConverter;
    }

    /**
     * Affiche directement avec conversion ANSI
     */
    public function ansi(string $text): self
    {
        $this->addLine($this->ansiConverter->convert($text));

        return $this;
    }

    // ========== MÉTHODES UTILITAIRES ==========

    public function line(string $message = ''): self
    {
        $this->addLine($message);

        return $this;
    }

    public function newLine(int $count = 1): self
    {
        $this->addLine(str_repeat(PHP_EOL, $count));

        return $this;
    }

    private function addLine(string $line): void
    {
        if ($this->buffered->getValue()) {
            $this->lines = $this->lines->add($line);
        } else {
            echo $this->ansiConverter->convert($line).PHP_EOL;
        }
    }

    public function startBuffer(): self
    {
        $this->buffered = new BoolVO(true);
        $this->lines = ListCollection::from([]);

        return $this;
    }

    public function render(): self
    {
        if ($this->lines->isNotEmpty()) {
            $output = $this->lines->reduce(
                fn ($carry, $line) => $carry === '' ? $line : $carry.PHP_EOL.$line,
                ''
            );
            echo $this->ansiConverter->convert($output).PHP_EOL;
            $this->lines = ListCollection::from([]);
        }
        $this->buffered = new BoolVO(false);

        return $this;
    }

    public function clear(): self
    {
        $this->lines = ListCollection::from([]);

        return $this;
    }

    public function getLines(): array
    {
        return $this->lines->toArray();
    }

    public function isBuffered(): bool
    {
        return $this->buffered->getValue();
    }
}
