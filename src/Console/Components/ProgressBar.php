<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components;

use AndyDefer\DomainStructures\Utils\MapCollection;
use AndyDefer\PhpVo\ValueObjects\Types\FloatVO;

final class ProgressBar
{
    private const DEFAULT_WIDTH = 50;

    private const COMPLETE_CHAR = '█';

    private const EMPTY_CHAR = '░';

    private const BAR_OPEN = '[';

    private const BAR_CLOSE = ']';

    private int $total;

    private int $current;

    private int $width;

    private string $prefix;

    private string $suffix;

    private bool $showPercentage;

    /**
     * @var MapCollection<string, array{prefix: string, suffix: string}>
     */
    private static MapCollection $styles;

    private static function getStyles(): MapCollection
    {
        if (! isset(self::$styles)) {
            self::$styles = MapCollection::from([
                'default' => [
                    'prefix' => '',
                    'suffix' => '',
                ],
                'download' => [
                    'prefix' => '⬇️  Downloading',
                    'suffix' => '',
                ],
                'upload' => [
                    'prefix' => '⬆️  Uploading',
                    'suffix' => '',
                ],
                'processing' => [
                    'prefix' => '⚙️  Processing',
                    'suffix' => '',
                ],
                'loading' => [
                    'prefix' => '🔄 Loading',
                    'suffix' => '',
                ],
                'install' => [
                    'prefix' => '📦 Installing',
                    'suffix' => '',
                ],
                'cleanup' => [
                    'prefix' => '🧹 Cleaning',
                    'suffix' => '',
                ],
            ]);
        }

        return self::$styles;
    }

    public function __construct(
        int $total,
        int $width = self::DEFAULT_WIDTH,
        string $prefix = '',
        string $suffix = '',
        bool $showPercentage = true
    ) {
        $this->total = $total;
        $this->current = 0;
        $this->width = $width;
        $this->prefix = $prefix;
        $this->suffix = $suffix;
        $this->showPercentage = $showPercentage;

        $this->display();
    }

    public function advance(int $steps = 1): self
    {
        $this->current = min($this->current + $steps, $this->total);
        $this->display();

        return $this;
    }

    public function setProgress(int $current): self
    {
        $this->current = min(max($current, 0), $this->total);
        $this->display();

        return $this;
    }

    public function finish(): self
    {
        $this->current = $this->total;
        $this->display();
        echo PHP_EOL;

        return $this;
    }

    public function setPrefix(string $prefix): self
    {
        $this->prefix = $prefix;
        $this->display();

        return $this;
    }

    public function setSuffix(string $suffix): self
    {
        $this->suffix = $suffix;
        $this->display();

        return $this;
    }

    private function display(): void
    {
        $percentage = FloatVO::from($this->current / $this->total * 100);
        $filled = (int) round($this->width * ($this->current / $this->total));

        $bar = self::BAR_OPEN
            .str_repeat(self::COMPLETE_CHAR, $filled)
            .str_repeat(self::EMPTY_CHAR, $this->width - $filled)
            .self::BAR_CLOSE;

        $output = '';

        if ($this->prefix !== '') {
            $output .= $this->prefix.' ';
        }

        $output .= $bar;

        if ($this->showPercentage) {
            $output .= ' '.number_format($percentage->getValue(), 0).'%';
        }

        if ($this->suffix !== '') {
            $output .= ' '.$this->suffix;
        }

        echo "\r\033[K".$output;
    }

    public function getPercentage(): float
    {
        return ($this->current / $this->total) * 100;
    }

    public function getCurrent(): int
    {
        return $this->current;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function isFinished(): bool
    {
        return $this->current >= $this->total;
    }

    public static function createStyled(
        int $total,
        string $style = 'default',
        int $width = self::DEFAULT_WIDTH
    ): self {
        $styles = self::getStyles();

        $styleConfig = $styles->hasKey($style)
            ? $styles->get($style)
            : $styles->get('default');

        $prefix = $styleConfig['prefix'] ?? '';
        $suffix = $styleConfig['suffix'] ?? '';

        return new self($total, $width, $prefix, $suffix);
    }

    public static function addStyle(string $name, string $prefix, string $suffix = ''): void
    {
        $styles = self::getStyles();
        // ✅ Correction : on met à jour la collection
        self::$styles = $styles->put($name, [
            'prefix' => $prefix,
            'suffix' => $suffix,
        ]);
    }
}
