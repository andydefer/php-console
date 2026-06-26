<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components;

use AndyDefer\ConsoleWriter\Console\Services\VirtualTerminalService;
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

    private VirtualTerminalService $vt;

    private string $key;

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

    /**
     * ✅ Constructeur avec injection optionnelle du VT
     */
    public function __construct(
        int $total,
        int $width = self::DEFAULT_WIDTH,
        string $prefix = '',
        string $suffix = '',
        bool $showPercentage = true,
        ?VirtualTerminalService $vt = null,
        string $key = 'progress_bar'
    ) {
        $this->total = $total;
        $this->current = 0;
        $this->width = $width;
        $this->prefix = $prefix;
        $this->suffix = $suffix;
        $this->showPercentage = $showPercentage;
        $this->vt = $vt ?? new VirtualTerminalService;
        $this->key = $key;

        // Ajouter la ligne initiale dans le VT
        $this->vt->add($this->key, $this->buildBar());
        $this->vt->render();
    }

    public function advance(int $steps = 1): self
    {
        $this->current = min($this->current + $steps, $this->total);
        $this->updateDisplay();

        return $this;
    }

    public function setProgress(int $current): self
    {
        $this->current = min(max($current, 0), $this->total);
        $this->updateDisplay();

        return $this;
    }

    public function finish(): self
    {
        $this->current = $this->total;
        $this->updateDisplay();
        echo PHP_EOL;

        return $this;
    }

    public function setPrefix(string $prefix): self
    {
        $this->prefix = $prefix;
        $this->updateDisplay();

        return $this;
    }

    public function setSuffix(string $suffix): self
    {
        $this->suffix = $suffix;
        $this->updateDisplay();

        return $this;
    }

    private function buildBar(): string
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

        return $output;
    }

    /**
     * ✅ Met à jour uniquement la ligne modifiée (pas de flash)
     */
    private function updateDisplay(): void
    {
        $this->vt
            ->update($this->key, $this->buildBar())
            ->render();
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
        self::$styles = $styles->put($name, [
            'prefix' => $prefix,
            'suffix' => $suffix,
        ]);
    }
}
