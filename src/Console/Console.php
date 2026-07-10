<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console;

use AndyDefer\ConsoleWriter\Console\Components\AdaptiveTable;
use AndyDefer\ConsoleWriter\Console\Components\Alert;
use AndyDefer\ConsoleWriter\Console\Components\Badge;
use AndyDefer\ConsoleWriter\Console\Components\Columns;
use AndyDefer\ConsoleWriter\Console\Components\Error;
use AndyDefer\ConsoleWriter\Console\Components\Form;
use AndyDefer\ConsoleWriter\Console\Components\Info;
use AndyDefer\ConsoleWriter\Console\Components\Input;
use AndyDefer\ConsoleWriter\Console\Components\JsonViewer;
use AndyDefer\ConsoleWriter\Console\Components\KeyValue;
use AndyDefer\ConsoleWriter\Console\Components\Link;
use AndyDefer\ConsoleWriter\Console\Components\ListComponent;
use AndyDefer\ConsoleWriter\Console\Components\Logger;
use AndyDefer\ConsoleWriter\Console\Components\Metric;
use AndyDefer\ConsoleWriter\Console\Components\Notification;
use AndyDefer\ConsoleWriter\Console\Components\ProgressBar;
use AndyDefer\ConsoleWriter\Console\Components\Separator;
use AndyDefer\ConsoleWriter\Console\Components\Sound;
use AndyDefer\ConsoleWriter\Console\Components\Spinner;
use AndyDefer\ConsoleWriter\Console\Components\Success;
use AndyDefer\ConsoleWriter\Console\Components\Table;
use AndyDefer\ConsoleWriter\Console\Components\Timeline;
use AndyDefer\ConsoleWriter\Console\Components\Title;
use AndyDefer\ConsoleWriter\Console\Components\Tree;
use AndyDefer\ConsoleWriter\Console\Contracts\ConsoleInterface;
use AndyDefer\ConsoleWriter\Console\Contracts\InputReaderInterface;
use AndyDefer\ConsoleWriter\Console\Enums\ListStyle;
use AndyDefer\ConsoleWriter\Console\Enums\SoundType;
use AndyDefer\ConsoleWriter\Console\Services\AnsiConverterService;
use AndyDefer\ConsoleWriter\Console\Services\StandardInputReaderService;
use AndyDefer\ConsoleWriter\Contracts\Services\AnsiConverterInterface;
use AndyDefer\DomainStructures\Utils\ListCollection;
use AndyDefer\DomainStructures\Utils\MapCollection;
use AndyDefer\DomainStructures\Utils\SetCollection;
use AndyDefer\PhpVo\ValueObjects\Types\BoolVO;

final class Console implements ConsoleInterface
{
    private ListCollection $lines;

    private BoolVO $buffered;

    private AnsiConverterInterface $ansiConverter;

    private ?ProgressBar $progressBar = null;

    private ?Spinner $spinner = null;

    private ?Input $input = null;

    public function __construct(?AnsiConverterInterface $ansiConverter = null)
    {
        $this->lines = ListCollection::from([]);
        $this->buffered = new BoolVO(false);
        $this->ansiConverter = $ansiConverter ?? new AnsiConverterService;
    }

    private function getInput(?InputReaderInterface $reader = null): Input
    {
        if ($this->input === null) {
            $this->input = new Input($this->ansiConverter, $reader ?? new StandardInputReaderService);
        }

        return $this->input;
    }

    // ========== RENDERABLE INTERFACE ==========

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

    public function title(string $message): self
    {
        $this->addLine(Title::render($message));

        return $this;
    }

    public function alert(string $message): self
    {
        $this->addLine(Alert::render($message));

        return $this;
    }

    public function alertWithIcon(string $message, string $icon, int $padding = 4): self
    {
        $this->addLine(Alert::renderWithIcon($message, $icon, $padding));

        return $this;
    }

    public function alertWithColor(string $message, string $color, int $padding = 4): self
    {
        $this->addLine(Alert::renderWithColor($message, $color, $padding));

        return $this;
    }

    public function alertWithIconAndColor(string $message, string $icon, string $color, int $padding = 4): self
    {
        $this->addLine(Alert::renderWithIconAndColor($message, $icon, $color, $padding));

        return $this;
    }

    public function alertWithBorder(string $message, string $borderChar, string $color = 'yellow', int $padding = 4): self
    {
        $this->addLine(Alert::renderWithBorder($message, $borderChar, $color, $padding));

        return $this;
    }

    public function alertFull(string $message, string $icon, string $color, string $borderChar, int $padding): self
    {
        $this->addLine(Alert::renderFull($message, $icon, $color, $borderChar, $padding));

        return $this;
    }

    public function alertSuccess(string $message): self
    {
        $this->addLine(Alert::renderSuccess($message));

        return $this;
    }

    public function alertError(string $message): self
    {
        $this->addLine(Alert::renderError($message));

        return $this;
    }

    public function alertWarning(string $message): self
    {
        $this->addLine(Alert::renderWarning($message));

        return $this;
    }

    public function alertInfo(string $message): self
    {
        $this->addLine(Alert::renderInfo($message));

        return $this;
    }

    /**
     * Ajoute une ligne brute (déjà formatée) sans conversion ANSI supplémentaire
     * Utile pour insérer des composants statiques dans le chaînage
     */
    public function raw(string $line): self
    {
        $this->addLine($line);

        return $this;
    }

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

    // ========== STYLED COMPONENTS INTERFACE ==========

    // --- LINK ---

    public function link(string $url, ?string $text = null): self
    {
        if ($text === null) {
            $this->addLine(Link::render($url));
        } else {
            $this->addLine(Link::renderWithText($url, $text));
        }

        return $this;
    }

    // --- LIST ---

    public function list(SetCollection|array $items, ListStyle $style = ListStyle::BULLET, int $indent = 0): self
    {
        $itemsCollection = $items instanceof SetCollection ? $items : SetCollection::from($items);
        $this->addLine(ListComponent::render($itemsCollection, $style, $indent));

        return $this;
    }

    public function listColored(SetCollection|array $items, ListStyle $style = ListStyle::BULLET, string $color = 'green'): self
    {
        $itemsCollection = $items instanceof SetCollection ? $items : SetCollection::from($items);
        $this->addLine(ListComponent::renderColored($itemsCollection, $style, $color));

        return $this;
    }

    // --- KEY VALUE ---

    public function keyValue(MapCollection|array $data, int $indent = 0): self
    {
        $dataCollection = $data instanceof MapCollection ? $data : MapCollection::from($data);
        $this->addLine(KeyValue::render($dataCollection, $indent));

        return $this;
    }

    public function keyValueWithColor(MapCollection|array $data, string $keyColor = 'cyan', int $indent = 0): self
    {
        $dataCollection = $data instanceof MapCollection ? $data : MapCollection::from($data);
        $this->addLine(KeyValue::renderWithColor($dataCollection, $keyColor, $indent));

        return $this;
    }

    public function keyValueWithValueColor(MapCollection|array $data, string $valueColor = 'green', int $indent = 0): self
    {
        $dataCollection = $data instanceof MapCollection ? $data : MapCollection::from($data);
        $this->addLine(KeyValue::renderWithValueColor($dataCollection, $valueColor, $indent));

        return $this;
    }

    public function keyValueWithSeparator(MapCollection|array $data, string $separator = ' → ', int $indent = 0): self
    {
        $dataCollection = $data instanceof MapCollection ? $data : MapCollection::from($data);
        $this->addLine(KeyValue::renderWithSeparator($dataCollection, $separator, $indent));

        return $this;
    }

    // --- TABLE ---

    public function table(ListCollection|array $headers, ListCollection|array $rows): self
    {
        $headersCollection = $headers instanceof ListCollection ? $headers : ListCollection::from($headers);
        $rowsCollection = $rows instanceof ListCollection ? $rows : ListCollection::from($rows);
        $this->addLine(Table::render($headersCollection, $rowsCollection));

        return $this;
    }

    public function adaptiveTable(ListCollection|array $headers, ListCollection|array $rows): self
    {
        $headersCollection = $headers instanceof ListCollection ? $headers : ListCollection::from($headers);
        $rowsCollection = $rows instanceof ListCollection ? $rows : ListCollection::from($rows);
        $this->addLine(AdaptiveTable::render($headersCollection, $rowsCollection));

        return $this;
    }

    // --- TREE ---

    public function tree(MapCollection $tree, string $rootLabel = ''): self
    {
        $this->addLine(Tree::render($tree, $rootLabel));

        return $this;
    }

    public function treeWithColors(
        MapCollection $tree,
        string $rootLabel = '',
        string $nodeColor = 'cyan',
        string $leafColor = 'white'
    ): self {
        $this->addLine(Tree::renderWithColors($tree, $rootLabel, $nodeColor, $leafColor));

        return $this;
    }

    public function treeFromPaths(SetCollection $paths, string $rootLabel = '📁 Project'): self
    {
        $this->addLine(Tree::renderFromPaths($paths, $rootLabel));

        return $this;
    }

    public function treeWithIcons(
        MapCollection $tree,
        string $rootLabel = '',
        string $folderIcon = '📁',
        string $fileIcon = '📄'
    ): self {
        $this->addLine(Tree::renderWithIcons($tree, $rootLabel, $folderIcon, $fileIcon));

        return $this;
    }

    // --- BADGE ---

    public function badge(string $text, string $style = 'default'): self
    {
        $this->addLine(Badge::render($text, $style));

        return $this;
    }

    public function badgeWithIcon(string $text, string $icon, string $style = 'default'): self
    {
        $this->addLine(Badge::renderWithIcon($text, $icon, $style));

        return $this;
    }

    public function badgeSuccess(string $text = 'SUCCESS'): self
    {
        $this->addLine(Badge::success($text));

        return $this;
    }

    public function badgeDanger(string $text = 'FAILED'): self
    {
        $this->addLine(Badge::danger($text));

        return $this;
    }

    public function badgeWarning(string $text = 'PENDING'): self
    {
        $this->addLine(Badge::warning($text));

        return $this;
    }

    public function badgeInfo(string $text = 'INFO'): self
    {
        $this->addLine(Badge::info($text));

        return $this;
    }

    public function badgePrimary(string $text = 'PRIMARY'): self
    {
        $this->addLine(Badge::primary($text));

        return $this;
    }

    public function badgeDark(string $text = 'DARK'): self
    {
        $this->addLine(Badge::dark($text));

        return $this;
    }

    public function badgeLight(string $text = 'LIGHT'): self
    {
        $this->addLine(Badge::light($text));

        return $this;
    }

    // --- METRIC ---

    public function metric(string $label, string $value, string $color = 'white'): self
    {
        $this->addLine(Metric::render($label, $value, $color));

        return $this;
    }

    public function metricWithIcon(string $label, string $value, string $icon, string $color = 'white'): self
    {
        $this->addLine(Metric::renderWithIcon($label, $value, $icon, $color));

        return $this;
    }

    public function metricWithTrend(
        string $label,
        string $value,
        string $trend,
        string $trendColor = 'green',
        string $valueColor = 'white'
    ): self {
        $this->addLine(Metric::renderWithTrend($label, $value, $trend, $trendColor, $valueColor));

        return $this;
    }

    public function metricInline(string $label, string $value, string $color = 'white'): self
    {
        $this->addLine(Metric::renderInline($label, $value, $color));

        return $this;
    }

    // --- COLUMNS ---

    public function columns(ListCollection|array $columns, int $width = 10, string $separator = '   '): self
    {
        $this->addLine(Columns::render($columns, $width, $separator));

        return $this;
    }

    public function columnsWithIcons(array $columns, int $width = 10, string $separator = '   '): self
    {
        $this->addLine(Columns::renderWithIcons($columns, $width, $separator));

        return $this;
    }

    public function columnsWithColors(ListCollection|array $columns, array $colors = [], int $width = 10, string $separator = '   '): self
    {
        $this->addLine(Columns::renderWithColors($columns, $colors, $width, $separator));

        return $this;
    }

    public function columnsWithHeaders(ListCollection|array $columns, int $width = 20, string $separator = '   '): self
    {
        $this->addLine(Columns::renderWithHeaders($columns, $width, $separator));

        return $this;
    }

    public function columnsCompact(ListCollection|array $columns, string $separator = '   '): self
    {
        $this->addLine(Columns::renderCompact($columns, $separator));

        return $this;
    }

    // ========== SEPARATOR METHODS ==========

    /**
     * Displays a separator line.
     *
     * @param  string  $character  The character to repeat (default: '-')
     * @param  int  $length  The length of the separator (default: 80)
     * @param  string  $color  The color of the separator (default: 'gray')
     */
    public function separator(string $character = '-', int $length = 80, string $color = 'gray'): self
    {
        $this->addLine(Separator::renderWithChar($character, $length, $color));

        return $this;
    }

    /**
     * Displays a double separator line (using '=').
     *
     * @param  int  $length  The length of the separator (default: 80)
     * @param  string  $color  The color of the separator (default: 'gray')
     */
    public function separatorDouble(int $length = 80, string $color = 'gray'): self
    {
        $this->addLine(Separator::renderDouble($length, $color));

        return $this;
    }

    /**
     * Displays a separator with a centered title.
     *
     * @param  string  $title  The title to display in the center
     * @param  string  $character  The character to repeat (default: '-')
     * @param  int  $length  The length of the separator (default: 80)
     * @param  string  $color  The color of the separator (default: 'gray')
     */
    public function separatorWithTitle(string $title, string $character = '-', int $length = 80, string $color = 'gray'): self
    {
        $this->addLine(Separator::renderWithTitle($title, $character, $length, $color));

        return $this;
    }

    // --- TIMELINE ---

    public function timeline(ListCollection|array $events, string $color = 'cyan'): self
    {
        $this->addLine(Timeline::render($events, $color));

        return $this;
    }

    public function timelineWithColors(ListCollection|array $events, array $colors = []): self
    {
        $this->addLine(Timeline::renderWithColors($events, $colors));

        return $this;
    }

    public function timelineWithIcons(ListCollection|array $events, string $icon = '●', string $color = 'cyan'): self
    {
        $this->addLine(Timeline::renderWithIcons($events, $icon, $color));

        return $this;
    }

    public function timelineWithStatus(ListCollection|array $events, array $statuses = []): self
    {
        $this->addLine(Timeline::renderWithStatus($events, $statuses));

        return $this;
    }

    // --- JSON VIEWER ---

    public function json(array|string $data): self
    {
        $this->addLine(JsonViewer::render($data));

        return $this;
    }

    public function jsonRaw(array|string $data): self
    {
        ($this->addLine(JsonViewer::renderRaw($data)));

        return $this;
    }

    public function jsonCompact(array|string $data): self
    {
        $this->addLine(JsonViewer::renderCompact($data));

        return $this;
    }

    public function jsonWithDepth(array|string $data, int $maxDepth = 3): self
    {
        $this->addLine(JsonViewer::renderWithDepth($data, $maxDepth));

        return $this;
    }

    // --- SPACE ---

    public function space(int $count = 1): self
    {
        $this->addLine(str_repeat(' ', $count));

        return $this;
    }

    // ========== SYSTEM INTERFACE ==========

    // --- ANSI ---

    public function getAnsiConverter(): AnsiConverterInterface
    {
        return $this->ansiConverter;
    }

    public function ansi(string $text): self
    {
        $this->addLine($this->ansiConverter->convert($text));

        return $this;
    }

    // --- NOTIFICATION ---

    public function notify(string $message, string $type = 'info', string $icon = '🔔'): self
    {
        $this->addLine(Notification::render($message, $type, $icon));

        return $this;
    }

    public function notifySuccess(string $message): self
    {
        $this->addLine(Notification::success($message));

        return $this;
    }

    public function notifyError(string $message): self
    {
        $this->addLine(Notification::error($message));

        return $this;
    }

    public function notifyWarning(string $message): self
    {
        $this->addLine(Notification::warning($message));

        return $this;
    }

    public function notifyInfo(string $message): self
    {
        $this->addLine(Notification::info($message));

        return $this;
    }

    // --- SOUND ---

    public function soundSuccess(): self
    {
        Sound::success();

        return $this;
    }

    public function soundError(): self
    {
        Sound::error();

        return $this;
    }

    public function soundInfo(): self
    {
        Sound::info();

        return $this;
    }

    public function sound(SoundType $type): self
    {
        Sound::play($type);

        return $this;
    }

    public function soundAsync(SoundType $type): self
    {
        Sound::playAsync($type);

        return $this;
    }

    // --- LOGGER ---

    public function logInfo(string $message): self
    {
        $this->addLine(Logger::info($message));

        return $this;
    }

    public function logSuccess(string $message): self
    {
        $this->addLine(Logger::success($message));

        return $this;
    }

    public function logError(string $message): self
    {
        $this->addLine(Logger::error($message));

        return $this;
    }

    public function logWarning(string $message): self
    {
        $this->addLine(Logger::warning($message));

        return $this;
    }

    public function logDebug(string $message): self
    {
        $this->addLine(Logger::debug($message));

        return $this;
    }

    public function logNotice(string $message): self
    {
        $this->addLine(Logger::notice($message));

        return $this;
    }

    public function logCritical(string $message): self
    {
        $this->addLine(Logger::critical($message));

        return $this;
    }

    public function log(string $level, string $message, string $color = 'white'): self
    {
        $this->addLine(Logger::log($level, $message, $color));

        return $this;
    }

    // ========== INTERACTIVE INTERFACE ==========

    public function ask(string $question, ?string $default = null, string $color = 'cyan'): string
    {
        return $this->getInput()->ask($question, $default, $color);
    }

    public function secret(string $question, string $color = 'cyan'): string
    {
        return $this->getInput()->secret($question, $color);
    }

    public function confirm(string $question, bool $default = true, string $color = 'cyan'): bool
    {
        return $this->getInput()->confirm($question, $default, $color);
    }

    public function choice(string $question, array $choices, ?int $default = null, string $color = 'cyan'): string
    {
        return $this->getInput()->choice($question, $choices, $default, $color);
    }

    public function suggest(string $question, array $suggestions, string $color = 'cyan'): string
    {
        return $this->getInput()->suggest($question, $suggestions, $color);
    }

    public function number(string $question, ?int $min = null, ?int $max = null, ?int $default = null, string $color = 'cyan'): int
    {
        return $this->getInput()->number($question, $min, $max, $default, $color);
    }

    public function confirmWithTimeout(string $question, int $timeout = 5, bool $default = true, string $color = 'cyan'): bool
    {
        return $this->getInput()->confirmWithTimeout($question, $timeout, $default, $color);
    }

    public function multiChoice(string $question, array $options, array $selected = [], string $color = 'cyan'): array
    {
        return $this->getInput()->multiChoice($question, $options, $selected, $color);
    }

    public function form(): Form
    {
        return new Form($this);
    }

    // ========== PROGRESS INTERFACE ==========

    public function progressBar(int $total, int $width = 50, string $prefix = '', string $suffix = ''): self
    {
        $this->progressBar = new ProgressBar($total, $width, $prefix, $suffix);

        return $this;
    }

    public function progressBarStyled(int $total, string $style = 'default', int $width = 50): self
    {
        $this->progressBar = ProgressBar::createStyled($total, $style, $width);

        return $this;
    }

    public function advance(int $steps = 1): self
    {
        if ($this->progressBar !== null) {
            $this->progressBar->advance($steps);
        }

        return $this;
    }

    public function setProgress(int $current): self
    {
        if ($this->progressBar !== null) {
            $this->progressBar->setProgress($current);
        }

        return $this;
    }

    public function setPrefix(string $prefix): self
    {
        if ($this->progressBar !== null) {
            $this->progressBar->setPrefix($prefix);
        }

        return $this;
    }

    public function setSuffix(string $suffix): self
    {
        if ($this->progressBar !== null) {
            $this->progressBar->setSuffix($suffix);
        }

        return $this;
    }

    public function finish(): self
    {
        if ($this->progressBar !== null) {
            $this->progressBar->finish();
            $this->progressBar = null;
        }

        return $this;
    }

    public function hasProgressBar(): bool
    {
        return $this->progressBar !== null;
    }

    public function getProgressBar(): ?ProgressBar
    {
        return $this->progressBar;
    }

    public function spinner(string $message, callable $task, string $prefix = '', string $suffix = ''): self
    {
        $spinner = new Spinner($message, $prefix, $suffix);
        $this->spinner = $spinner;
        $spinner->start($task);
        $this->spinner = null;

        return $this;
    }

    public function spinnerWait(string $message, callable $isComplete, string $prefix = '', string $suffix = ''): self
    {
        $spinner = new Spinner($message, $prefix, $suffix);
        $this->spinner = $spinner;
        $spinner->wait($isComplete);
        $this->spinner = null;

        return $this;
    }

    // ========== BUFFER INTERFACE ==========

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
