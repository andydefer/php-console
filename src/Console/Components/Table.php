<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components;

use AndyDefer\ConsoleWriter\Console\Abstracts\Component;
use AndyDefer\ConsoleWriter\Console\Services\VirtualTerminalService;
use AndyDefer\ConsoleWriter\Console\ValueObjects\CleanedTextVO;
use AndyDefer\DomainStructures\Utils\ListCollection;
use AndyDefer\PhpVo\ValueObjects\Types\BoolVO;
use AndyDefer\PhpVo\ValueObjects\Types\FloatVO;
use AndyDefer\PhpVo\ValueObjects\Types\StringVO;

/**
 * Tableau avec rendu cellule par cellule
 * Toutes les cellules ont la MÊME largeur (la plus grande cellule + padding)
 */
final class Table extends Component
{
    private const PADDING = 2;

    private const BORDER_LEFT = '│';

    private const BORDER_RIGHT = '│';

    private const SEPARATOR = ' │ ';

    public static function render(ListCollection $headers, ListCollection $rows): string
    {
        if ($rows->isEmpty()) {
            return self::fg('⚠️  No data to display', 'yellow');
        }

        // ✅ 1. NETTOYER LES ÉMOJIS AVANT TOUTE OPÉRATION
        $cleanHeaders = self::cleanEmojisFromHeaders($headers);
        $cleanRows = self::cleanEmojisFromRows($rows);

        $vt = self::getVT();
        $vt->clear();

        $columnCount = $cleanHeaders->count();

        // 1. Trouver la cellule la plus large
        $maxCellWidth = self::findMaxCellWidth($cleanHeaders, $cleanRows);

        // 2. Largeur de cellule = max + padding
        $cellWidthInt = self::getCellWidth($maxCellWidth);

        // 3. Toutes les cellules ont la MÊME largeur
        $columnCount = $cleanHeaders->count();
        $totalWidth = FloatVO::from($cellWidthInt)
            ->multiply(FloatVO::from($columnCount))
            ->add(FloatVO::from($columnCount + 1));
        $paddingAdjustment = ($columnCount - 2) * 2;

        $totalWidthInt = $totalWidth->toInt() + $paddingAdjustment;

        $lineIndex = 0;

        // ✅ Ligne 0: Bordure supérieure
        $vt->add('line_'.$lineIndex++, self::topBorder($totalWidthInt));

        // Ligne 1: En-têtes
        $headerLine = self::BORDER_LEFT;
        foreach ($cleanHeaders as $index => $header) {
            $padded = self::padCenter(StringVO::from($header), $cellWidthInt);
            $headerLine .= $padded;
            if ($index < $cleanHeaders->count() - 1) {
                $headerLine .= self::SEPARATOR;
            }
        }
        $headerLine .= self::BORDER_RIGHT;
        $vt->add('line_'.$lineIndex++, self::fg(self::bold($headerLine), 'cyan'));

        // ✅ Ligne 2: Séparateur
        $vt->add('line_'.$lineIndex++, self::separator($totalWidthInt));

        // Lignes de données
        foreach ($cleanRows as $rowIndex => $row) {
            $rowCollection = $row instanceof ListCollection ? $row : ListCollection::from((array) $row);
            $dataLine = self::BORDER_LEFT;

            foreach ($rowCollection as $colIndex => $cell) {
                $padded = self::padCenter(StringVO::from($cell), $cellWidthInt);
                $dataLine .= $padded;
                if ($colIndex < $rowCollection->count() - 1) {
                    $dataLine .= self::SEPARATOR;
                }
            }
            $dataLine .= self::BORDER_RIGHT;
            $vt->add('line_'.$lineIndex++, $dataLine);
        }

        // ✅ Dernière ligne: Bordure inférieure
        $vt->add('line_'.$lineIndex++, self::bottomBorder($totalWidthInt));

        // Rendu
        $vt->render();

        return $vt->getLines()->reduce(
            fn ($carry, $line) => $carry === '' ? $line : $carry.PHP_EOL.$line,
            ''
        );
    }

    /**
     * ✅ Nettoie les émojis des en-têtes
     */
    private static function cleanEmojisFromHeaders(ListCollection $headers): ListCollection
    {
        $clean = [];
        foreach ($headers as $header) {
            $clean[] = (new CleanedTextVO(StringVO::from($header)->getValue()))->withoutEmojis()->getValue();
        }

        return ListCollection::from($clean);
    }

    /**
     * ✅ Nettoie les émojis des lignes
     */
    private static function cleanEmojisFromRows(ListCollection $rows): ListCollection
    {
        $cleanRows = [];
        foreach ($rows as $row) {
            $rowArray = $row instanceof ListCollection ? $row->toArray() : (array) $row;
            $cleanRow = [];
            foreach ($rowArray as $cell) {
                $cleanRow[] = (new CleanedTextVO(StringVO::from($cell)->getValue()))->withoutEmojis()->getValue();
            }
            $cleanRows[] = ListCollection::from($cleanRow);
        }

        return ListCollection::from($cleanRows);
    }

    /**
     * ✅ Trouve la cellule la plus large parmi toutes (headers + rows)
     */
    private static function findMaxCellWidth(ListCollection $headers, ListCollection $rows): FloatVO
    {
        $max = FloatVO::from(0);

        foreach ($headers as $header) {
            $length = FloatVO::from(mb_strlen(StringVO::from($header)->getValue()));
            $isGreater = BoolVO::from($length->greaterThan($max)->getValue());
            if ($isGreater->isTrue()->getValue()) {
                $max = $length;
            }
        }

        foreach ($rows as $row) {
            $rowArray = $row instanceof ListCollection ? $row : ListCollection::from((array) $row);
            foreach ($rowArray as $cell) {
                $length = FloatVO::from(mb_strlen(StringVO::from($cell)->getValue()));
                $isGreater = BoolVO::from($length->greaterThan($max)->getValue());
                if ($isGreater->isTrue()->getValue()) {
                    $max = $length;
                }
            }
        }

        return $max;
    }

    /**
     * ✅ Calcule la largeur de cellule avec padding, en s'assurant qu'elle est paire
     */
    private static function getCellWidth(FloatVO $maxCellWidth): int
    {
        $cellWidth = $maxCellWidth->add(FloatVO::from(self::PADDING * 2));
        $cellWidthInt = $cellWidth->toInt();

        $isEven = BoolVO::from($cellWidthInt % 2 === 0);
        if ($isEven->isFalse()->getValue()) {
            $cellWidthInt += 1;
        }

        return $cellWidthInt;
    }

    // ========== BORDURES ==========

    /**
     * ✅ Bordure supérieure - largeur exacte
     */
    private static function topBorder(int $totalWidth): string
    {
        $line = str_repeat('─', $totalWidth);

        return self::fg('┌'.$line.'┐', 'cyan');
    }

    /**
     * ✅ Bordure inférieure - largeur exacte
     */
    private static function bottomBorder(int $totalWidth): string
    {
        $line = str_repeat('─', $totalWidth);

        return self::fg('└'.$line.'┘', 'cyan');
    }

    /**
     * ✅ Séparateur - largeur exacte
     */
    private static function separator(int $totalWidth): string
    {
        $line = str_repeat('─', $totalWidth);

        return self::fg('├'.$line.'┤', 'cyan');
    }

    // ========== PADDING ==========

    private static function padCenter(StringVO $text, int $width): StringVO
    {
        $textLength = mb_strlen($text->getValue());
        $padding = $width - $textLength;

        $left = (int) floor($padding / 2);
        $right = $padding - $left;

        return StringVO::from('')
            ->concat(str_repeat(' ', $left))
            ->concat($text)
            ->concat(str_repeat(' ', $right));
    }

    // ========== MÉTHODES DE MISE À JOUR ==========

    /**
     * ✅ Met à jour une cellule spécifique
     */
    public static function updateCell(
        VirtualTerminalService $vt,
        int $rowIndex,
        int $colIndex,
        string $value,
        int $cellWidthInt
    ): void {
        $lineKey = 'line_'.($rowIndex + 3);
        $line = $vt->get($lineKey);

        if ($line === null) {
            return;
        }

        $paddedValue = self::padCenter(StringVO::from($value), $cellWidthInt)->getValue();

        $parts = explode(self::SEPARATOR, $line);
        $newLine = self::BORDER_LEFT;

        foreach ($parts as $index => $part) {
            if ($index === $colIndex) {
                $newLine .= $paddedValue;
            } else {
                $newLine .= $part;
            }
            if ($index < count($parts) - 1) {
                $newLine .= self::SEPARATOR;
            }
        }
        $newLine .= self::BORDER_RIGHT;

        $vt->update($lineKey, $newLine);
        $vt->render();
    }

    /**
     * ✅ Met à jour une ligne entière
     */
    public static function updateRow(
        VirtualTerminalService $vt,
        int $rowIndex,
        ListCollection $row,
        int $cellWidthInt
    ): void {
        $lineKey = 'line_'.($rowIndex + 3);
        $dataLine = self::BORDER_LEFT;

        foreach ($row as $colIndex => $cell) {
            $padded = self::padCenter(StringVO::from($cell), $cellWidthInt);
            $dataLine .= $padded;
            if ($colIndex < $row->count() - 1) {
                $dataLine .= self::SEPARATOR;
            }
        }
        $dataLine .= self::BORDER_RIGHT;

        $vt->update($lineKey, $dataLine);
        $vt->render();
    }

    /**
     * ✅ Ajoute une ligne
     */
    public static function addRow(
        VirtualTerminalService $vt,
        ListCollection $row,
        int $cellWidthInt
    ): void {
        $lineIndex = 3;
        while ($vt->has('line_'.($lineIndex + 1))) {
            $lineIndex++;
        }

        $dataLine = self::BORDER_LEFT;
        foreach ($row as $colIndex => $cell) {
            $padded = self::padCenter(StringVO::from($cell), $cellWidthInt);
            $dataLine .= $padded;
            if ($colIndex < $row->count() - 1) {
                $dataLine .= self::SEPARATOR;
            }
        }
        $dataLine .= self::BORDER_RIGHT;

        $vt->add('line_'.$lineIndex, $dataLine);
        $vt->render();
    }

    /**
     * ✅ Supprime une ligne
     */
    public static function removeRow(VirtualTerminalService $vt, int $rowIndex): void
    {
        $lineKey = 'line_'.($rowIndex + 3);
        $vt->remove($lineKey);

        $i = $rowIndex + 4;
        while ($vt->has('line_'.$i)) {
            $content = $vt->get('line_'.$i);
            $vt->remove('line_'.$i);
            $vt->add('line_'.($i - 1), $content);
            $i++;
        }
        $vt->render();
    }
}
