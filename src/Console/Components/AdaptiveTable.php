<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components;

use AndyDefer\ConsoleWriter\Console\Abstracts\Component;
use AndyDefer\DomainStructures\Utils\ListCollection;

final class AdaptiveTable extends Component
{
    private const MAX_TABLE_COLUMNS = 5;

    public static function render(ListCollection $headers, ListCollection $rows): string
    {
        if ($rows->isEmpty()) {
            return '<fg=yellow>⚠️  No data to display</fg=yellow>';
        }

        $columnCount = $headers->count();

        // Si <= 5 colonnes → tableau
        if ($columnCount <= self::MAX_TABLE_COLUMNS) {
            return Table::render($headers, $rows);
        }

        // Si > 5 colonnes → liste KeyValue
        return TableList::render($headers, $rows);
    }

    /**
     * Force l'affichage en liste même si <= 5 colonnes
     */
    public static function renderAsList(ListCollection $headers, ListCollection $rows): string
    {
        return TableList::render($headers, $rows);
    }

    /**
     * Force l'affichage en tableau même si > 5 colonnes
     */
    public static function renderAsTable(ListCollection $headers, ListCollection $rows): string
    {
        return Table::render($headers, $rows);
    }
}
