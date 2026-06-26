<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Console\Components;

use AndyDefer\ConsoleWriter\Console\Abstracts\Component;
use AndyDefer\ConsoleWriter\Console\Services\VirtualTerminalService;
use AndyDefer\ConsoleWriter\Console\ValueObjects\CleanedTextVO;
use AndyDefer\DomainStructures\Utils\ListCollection;
use AndyDefer\PhpVo\ValueObjects\Types\FloatVO;
use AndyDefer\PhpVo\ValueObjects\Types\StringVO;

/**
 * Transforme un tableau en liste KeyValue lisible avec VirtualTerminalService
 * Utile pour les tableaux avec plus de 5 colonnes
 *
 * @example
 * TableList::render(
 *     ListCollection::from(['ID', 'Name', 'Description', 'Price']),
 *     ListCollection::from([
 *         ListCollection::from(['1', 'Laptop', 'High-performance laptop', '1299.99']),
 *     ])
 * )
 */
final class TableList extends Component
{
    private const INDENT = '  ';

    private const MAX_KEY_WIDTH = 25;

    private const SEPARATOR = ' : ';

    private const EXTRA_SPACES = 3;

    private const BORDER_CHAR = '─';

    public static function render(ListCollection $headers, ListCollection $rows): string
    {
        if ($rows->isEmpty()) {
            return self::fg('⚠️  No data to display', 'yellow');
        }
        // ✅ 1. NETTOYER LES ÉMOJIS AVANT TOUTE OPÉRATION
        $cleanHeaders = self::cleanEmojisFromHeaders($headers);
        $cleanRows = self::cleanEmojisFromRows($rows);

        $vt = new VirtualTerminalService(self::getAnsi());
        $headersArray = $cleanHeaders->toArray();

        // ✅ 2. Calculer la largeur max des clés (après nettoyage)
        $maxKeyLength = self::calculateMaxKeyLength($headersArray);
        $totalKeyWidth = $maxKeyLength->add(FloatVO::from(self::EXTRA_SPACES));
        $keyWidthInt = $totalKeyWidth->toInt();

        $lineIndex = 0;
        $allLines = [];

        // En-tête informatif
        $headerLine = self::infoHeader($headersArray);
        $allLines[] = $headerLine;
        $vt->add('line_'.$lineIndex++, $headerLine);

        $rowNumber = 0;
        foreach ($cleanRows as $row) {
            $rowNumber++;
            $rowArray = $row instanceof ListCollection ? $row->toArray() : (array) $row;

            if ($rowNumber > 1) {
                $allLines[] = '';
                $vt->add('line_'.$lineIndex++, '');
            }

            // ✅ Bordure supérieure (provisoire)
            $topBorder = self::topBorder();
            $allLines[] = $topBorder;
            $vt->add('line_'.$lineIndex++, $topBorder);

            // Lignes de contenu
            foreach ($headersArray as $index => $header) {
                $value = $rowArray[$index] ?? '';
                $line = self::formatLine(
                    StringVO::from($header),
                    StringVO::from($value),
                    $keyWidthInt
                );
                $allLines[] = $line;
                $vt->add('line_'.$lineIndex++, $line);
            }

            // ✅ Bordure inférieure (provisoire)
            $bottomBorder = self::bottomBorder();
            $allLines[] = $bottomBorder;
            $vt->add('line_'.$lineIndex++, $bottomBorder);
        }

        // ✅ 3. Trouver la ligne la plus longue (sans les balises ANSI)
        $maxWidth = self::findMaxLineWidth($allLines);

        // ✅ 4. Reconstruire avec les bonnes largeurs
        $vt->clear();
        $lineIndex = 0;

        $vt->add('line_'.$lineIndex++, self::infoHeader($headersArray));

        $rowNumber = 0;
        foreach ($cleanRows as $row) {
            $rowNumber++;
            $rowArray = $row instanceof ListCollection ? $row->toArray() : (array) $row;

            if ($rowNumber > 1) {
                $vt->add('line_'.$lineIndex++, '');
            }

            // ✅ Bordure supérieure avec la largeur max
            $vt->add('line_'.$lineIndex++, self::topBorderWithWidth($maxWidth));

            foreach ($headersArray as $index => $header) {
                $value = $rowArray[$index] ?? '';
                $vt->add('line_'.$lineIndex++, self::formatLine(
                    StringVO::from($header),
                    StringVO::from($value),
                    $keyWidthInt
                ));
            }

            // ✅ Bordure inférieure avec la largeur max
            $vt->add('line_'.$lineIndex++, self::bottomBorderWithWidth($maxWidth));
        }

        $vt->render();

        return $vt->getLines()->reduce(
            fn ($carry, $line) => $carry === '' ? $line : $carry.PHP_EOL.$line,
            ''
        );
    }

    public static function renderWithTitle(
        ListCollection $headers,
        ListCollection $rows,
        string $title
    ): string {
        if ($rows->isEmpty()) {
            return self::fg('⚠️  No data to display', 'yellow');
        }

        // ✅ Nettoyer les émojis
        $cleanHeaders = self::cleanEmojisFromHeaders($headers);
        $cleanRows = self::cleanEmojisFromRows($rows);

        $vt = new VirtualTerminalService(self::getAnsi());
        $headersArray = $cleanHeaders->toArray();

        $maxKeyLength = self::calculateMaxKeyLength($headersArray);
        $totalKeyWidth = $maxKeyLength->add(FloatVO::from(self::EXTRA_SPACES));
        $keyWidthInt = $totalKeyWidth->toInt();

        $lineIndex = 0;
        $allLines = [];

        $titleLine = self::titleLine($title);
        $allLines[] = $titleLine;
        $vt->add('line_'.$lineIndex++, $titleLine);

        $rowNumber = 0;
        foreach ($cleanRows as $row) {
            $rowNumber++;
            $rowArray = $row instanceof ListCollection ? $row->toArray() : (array) $row;

            if ($rowNumber > 1) {
                $allLines[] = '';
                $vt->add('line_'.$lineIndex++, '');
            }

            $topBorder = self::topBorder();
            $allLines[] = $topBorder;
            $vt->add('line_'.$lineIndex++, $topBorder);

            foreach ($headersArray as $index => $header) {
                $value = $rowArray[$index] ?? '';
                $line = self::formatLine(
                    StringVO::from($header),
                    StringVO::from($value),
                    $keyWidthInt
                );
                $allLines[] = $line;
                $vt->add('line_'.$lineIndex++, $line);
            }

            $bottomBorder = self::bottomBorder();
            $allLines[] = $bottomBorder;
            $vt->add('line_'.$lineIndex++, $bottomBorder);
        }

        $maxWidth = self::findMaxLineWidth($allLines);

        $vt->clear();
        $lineIndex = 0;

        $vt->add('line_'.$lineIndex++, self::titleLine($title));

        $rowNumber = 0;
        foreach ($cleanRows as $row) {
            $rowNumber++;
            $rowArray = $row instanceof ListCollection ? $row->toArray() : (array) $row;

            if ($rowNumber > 1) {
                $vt->add('line_'.$lineIndex++, '');
            }

            $vt->add('line_'.$lineIndex++, self::topBorderWithWidth($maxWidth));

            foreach ($headersArray as $index => $header) {
                $value = $rowArray[$index] ?? '';
                $vt->add('line_'.$lineIndex++, self::formatLine(
                    StringVO::from($header),
                    StringVO::from($value),
                    $keyWidthInt
                ));
            }

            $vt->add('line_'.$lineIndex++, self::bottomBorderWithWidth($maxWidth));
        }

        $vt->render();

        return $vt->getLines()->reduce(
            fn ($carry, $line) => $carry === '' ? $line : $carry.PHP_EOL.$line,
            ''
        );
    }

    // ========== NETTOYAGE DES ÉMOJIS ==========

    private static function cleanEmojisFromHeaders(ListCollection $headers): ListCollection
    {
        $clean = [];
        foreach ($headers as $header) {
            $clean[] = (new CleanedTextVO(StringVO::from((string) $header)->getValue()))->withoutEmojis()->getValue();
        }

        return ListCollection::from($clean);
    }

    private static function cleanEmojisFromRows(ListCollection $rows): ListCollection
    {
        $cleanRows = [];
        foreach ($rows as $row) {
            $rowArray = $row instanceof ListCollection ? $row->toArray() : (array) $row;
            $cleanRow = [];
            foreach ($rowArray as $cell) {
                $cleanRow[] = (new CleanedTextVO(StringVO::from((string) $cell)->getValue()))->withoutEmojis()->getValue();
            }
            $cleanRows[] = ListCollection::from($cleanRow);
        }

        return ListCollection::from($cleanRows);
    }

    // ========== MÉTHODES DE CALCUL ==========

    private static function calculateMaxKeyLength(array $headers): FloatVO
    {
        $max = FloatVO::from(0);
        foreach ($headers as $header) {
            $length = FloatVO::from(mb_strlen(StringVO::from($header)->getValue()));
            $max = $max->max($length);
        }

        return $max;
    }

    /**
     * ✅ Trouve la largeur maximale parmi toutes les lignes (sans les balises ANSI)
     */
    private static function findMaxLineWidth(array $lines): int
    {
        $max = 0;
        foreach ($lines as $line) {
            $clean = strip_tags($line);
            $length = mb_strlen($clean);
            if ($length > $max) {
                $max = $length;
            }
        }

        return $max;
    }

    // ========== BORDURES ==========

    private static function topBorder(): string
    {
        return self::fg(self::bold('┌──────────────────────────────────────────────────┐'), 'cyan');
    }

    private static function bottomBorder(): string
    {
        return self::fg(self::bold('└──────────────────────────────────────────────────┘'), 'cyan');
    }

    /**
     * ✅ Bordure supérieure avec largeur personnalisée
     */
    private static function topBorderWithWidth(int $width): string
    {
        $line = str_repeat(self::BORDER_CHAR, $width);

        return self::fg(self::bold('┌'.$line.'┐'), 'cyan');
    }

    /**
     * ✅ Bordure inférieure avec largeur personnalisée
     */
    private static function bottomBorderWithWidth(int $width): string
    {
        $line = str_repeat(self::BORDER_CHAR, $width);

        return self::fg(self::bold('└'.$line.'┘'), 'cyan');
    }

    // ========== LIGNES DE CONTENU ==========
    private static function formatLine(StringVO $key, StringVO $value, int $keyWidth): string
    {
        $keyText = $key->getValue();
        $valueText = $value->getValue();

        $paddedKey = str_pad($keyText, $keyWidth, ' ', STR_PAD_RIGHT);

        return self::INDENT.self::fg($paddedKey, 'cyan').self::SEPARATOR.$valueText;
    }

    private static function infoHeader(array $headers): string
    {
        $count = count($headers);

        return self::fg('📋 '.$count.' colonnes → affichage en liste', 'yellow');
    }

    private static function titleLine(string $title): string
    {
        return self::fg(self::bold('📋 '.$title), 'cyan');
    }
}
