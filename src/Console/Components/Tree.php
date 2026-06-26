<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components;

use AndyDefer\DomainStructures\Utils\ListCollection;
use AndyDefer\DomainStructures\Utils\MapCollection;
use AndyDefer\DomainStructures\Utils\SetCollection;
use AndyDefer\PhpVo\ValueObjects\Types\BoolVO;

final class Tree
{
    private const INDENT = '  ';

    private const PREFIX_LAST = '└─ ';

    private const PREFIX_MIDDLE = '├─ ';

    private const PREFIX_VERTICAL = '│  ';

    public static function render(MapCollection $tree, string $rootLabel = ''): string
    {
        $lines = ListCollection::from([]);

        $hasRootLabel = BoolVO::from($rootLabel !== '');
        if ($hasRootLabel->isTrue()->getValue()) {
            $lines = $lines->add('<fg=cyan><options=bold>'.$rootLabel.'</options=bold></fg=cyan>');
        }

        $lines = $lines->merge(self::renderTree($tree));

        return $lines->reduce(
            fn ($carry, $line) => $carry === '' ? $line : $carry.PHP_EOL.$line,
            ''
        );
    }

    public static function renderWithColors(
        MapCollection $tree,
        string $rootLabel = '',
        string $nodeColor = 'cyan',
        string $leafColor = 'white'
    ): string {
        $lines = ListCollection::from([]);

        $hasRootLabel = BoolVO::from($rootLabel !== '');
        if ($hasRootLabel->isTrue()->getValue()) {
            $lines = $lines->add('<fg='.$nodeColor.'><options=bold>'.$rootLabel.'</options=bold></fg='.$nodeColor.'>');
        }

        $lines = $lines->merge(self::renderTreeWithColors($tree, $nodeColor, $leafColor));

        return $lines->reduce(
            fn ($carry, $line) => $carry === '' ? $line : $carry.PHP_EOL.$line,
            ''
        );
    }

    public static function renderFromPaths(SetCollection $paths, string $rootLabel = '📁 Project'): string
    {
        // Construire l'arbre avec un tableau PHP (plus facile pour les références)
        $tree = [];

        foreach ($paths as $path) {
            $parts = explode('/', $path);
            $current = &$tree;
            foreach ($parts as $part) {
                if (! isset($current[$part])) {
                    $current[$part] = [];
                }
                $current = &$current[$part];
            }
        }

        // Convertir le tableau PHP en MapCollection
        $mapTree = self::arrayToMapCollection($tree);

        return self::render($mapTree, $rootLabel);
    }

    public static function renderWithIcons(
        MapCollection $tree,
        string $rootLabel = '',
        string $folderIcon = '📁',
        string $fileIcon = '📄'
    ): string {
        $lines = ListCollection::from([]);

        $hasRootLabel = BoolVO::from($rootLabel !== '');
        if ($hasRootLabel->isTrue()->getValue()) {
            $lines = $lines->add('<fg=cyan><options=bold>'.$folderIcon.' '.$rootLabel.'</options=bold></fg=cyan>');
        }

        $lines = $lines->merge(self::renderTreeWithIcons($tree, $folderIcon, $fileIcon));

        return $lines->reduce(
            fn ($carry, $line) => $carry === '' ? $line : $carry.PHP_EOL.$line,
            ''
        );
    }

    // ========== MÉTHODES PRIVÉES ==========

    private static function arrayToMapCollection(array $array): MapCollection
    {
        $result = [];
        foreach ($array as $key => $value) {
            if (is_array($value) && ! empty($value)) {
                $result[$key] = self::arrayToMapCollection($value);
            } else {
                $result[$key] = MapCollection::from([]);
            }
        }

        return MapCollection::from($result);
    }

    private static function renderTree(MapCollection $tree, string $prefix = '', bool $isLast = true): ListCollection
    {
        $lines = ListCollection::from([]);
        $keys = $tree->keys();
        $count = $keys->count();

        foreach ($keys as $index => $key) {
            $isLastItem = BoolVO::from($index === $count - 1);
            $value = $tree->get($key);
            $isLastItemValue = $isLastItem->getValue();

            $linePrefix = $prefix;
            if ($isLastItemValue) {
                $linePrefix .= self::PREFIX_LAST;
            } else {
                $linePrefix .= self::PREFIX_MIDDLE;
            }

            $isNode = BoolVO::from($value instanceof MapCollection && $value->isNotEmpty());
            $isNodeValue = $isNode->getValue();

            if ($isNodeValue) {
                $lines = $lines->add(
                    '<fg=white>'.$linePrefix.'</fg=white><fg=cyan><options=bold>'.$key.'</options=bold></fg=cyan>'
                );
            } else {
                $lines = $lines->add('<fg=white>'.$linePrefix.$key.'</fg=white>');
            }

            if ($isNodeValue) {
                $newPrefix = $prefix;
                if ($isLastItemValue) {
                    $newPrefix .= self::INDENT;
                } else {
                    $newPrefix .= self::PREFIX_VERTICAL;
                }
                $lines = $lines->merge(self::renderTree($value, $newPrefix, $isLastItemValue));
            }
        }

        return $lines;
    }

    private static function renderTreeWithColors(
        MapCollection $tree,
        string $nodeColor,
        string $leafColor,
        string $prefix = '',
        bool $isLast = true
    ): ListCollection {
        $lines = ListCollection::from([]);
        $keys = $tree->keys();
        $count = $keys->count();

        foreach ($keys as $index => $key) {
            $isLastItem = BoolVO::from($index === $count - 1);
            $value = $tree->get($key);
            $isLastItemValue = $isLastItem->getValue();

            $linePrefix = $prefix;
            if ($isLastItemValue) {
                $linePrefix .= self::PREFIX_LAST;
            } else {
                $linePrefix .= self::PREFIX_MIDDLE;
            }

            $isNode = BoolVO::from($value instanceof MapCollection && $value->isNotEmpty());
            $isNodeValue = $isNode->getValue();

            if ($isNodeValue) {
                $lines = $lines->add(
                    '<fg=white>'.$linePrefix.'</fg=white><fg='.$nodeColor.'><options=bold>'.$key.'</options=bold></fg='.$nodeColor.'>'
                );
            } else {
                $lines = $lines->add('<fg='.$leafColor.'>'.$linePrefix.$key.'</fg='.$leafColor.'>');
            }

            if ($isNodeValue) {
                $newPrefix = $prefix;
                if ($isLastItemValue) {
                    $newPrefix .= self::INDENT;
                } else {
                    $newPrefix .= self::PREFIX_VERTICAL;
                }
                $lines = $lines->merge(self::renderTreeWithColors($value, $nodeColor, $leafColor, $newPrefix, $isLastItemValue));
            }
        }

        return $lines;
    }

    private static function renderTreeWithIcons(
        MapCollection $tree,
        string $folderIcon,
        string $fileIcon,
        string $prefix = '',
        bool $isLast = true
    ): ListCollection {
        $lines = ListCollection::from([]);
        $keys = $tree->keys();
        $count = $keys->count();

        foreach ($keys as $index => $key) {
            $isLastItem = BoolVO::from($index === $count - 1);
            $value = $tree->get($key);
            $isLastItemValue = $isLastItem->getValue();

            $linePrefix = $prefix;
            if ($isLastItemValue) {
                $linePrefix .= self::PREFIX_LAST;
            } else {
                $linePrefix .= self::PREFIX_MIDDLE;
            }

            $isNode = BoolVO::from($value instanceof MapCollection && $value->isNotEmpty());
            $isNodeValue = $isNode->getValue();
            $icon = $isNodeValue ? $folderIcon : $fileIcon;

            if ($isNodeValue) {
                $lines = $lines->add(
                    '<fg=white>'.$linePrefix.'</fg=white><fg=cyan><options=bold>'.$icon.' '.$key.'</options=bold></fg=cyan>'
                );
            } else {
                $lines = $lines->add('<fg=white>'.$linePrefix.$icon.' '.$key.'</fg=white>');
            }

            if ($isNodeValue) {
                $newPrefix = $prefix;
                if ($isLastItemValue) {
                    $newPrefix .= self::INDENT;
                } else {
                    $newPrefix .= self::PREFIX_VERTICAL;
                }
                $lines = $lines->merge(self::renderTreeWithIcons($value, $folderIcon, $fileIcon, $newPrefix, $isLastItemValue));
            }
        }

        return $lines;
    }
}
