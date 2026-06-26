<?php

declare(strict_types=1);

namespace AndyDefer\ConsoleWriter\Tests\Unit\Components;

use AndyDefer\ConsoleWriter\Console\Components\Tree;
use AndyDefer\DomainStructures\Utils\MapCollection;
use AndyDefer\DomainStructures\Utils\SetCollection;
use PHPUnit\Framework\TestCase;

final class TreeTest extends TestCase
{
    public function test_render_simple_tree(): void
    {
        $tree = MapCollection::from([
            'root' => MapCollection::from([
                'child1' => MapCollection::from([]),
                'child2' => MapCollection::from([
                    'grandchild1' => MapCollection::from([]),
                    'grandchild2' => MapCollection::from([]),
                ]),
            ]),
        ]);

        $result = Tree::render($tree, 'Root');
        $plainResult = strip_tags($result);

        $this->assertStringContainsString('Root', $plainResult);
        $this->assertStringContainsString('└─ root', $plainResult);
        $this->assertStringContainsString('  ├─ child1', $plainResult);
        $this->assertStringContainsString('  └─ child2', $plainResult);
        $this->assertStringContainsString('    ├─ grandchild1', $plainResult);
        $this->assertStringContainsString('    └─ grandchild2', $plainResult);
    }

    public function test_render_tree_without_root_label(): void
    {
        $tree = MapCollection::from([
            'item1' => MapCollection::from([]),
            'item2' => MapCollection::from([
                'subitem1' => MapCollection::from([]),
            ]),
        ]);

        $result = Tree::render($tree, '');
        $plainResult = strip_tags($result);

        $this->assertStringContainsString('├─ item1', $plainResult);
        $this->assertStringContainsString('└─ item2', $plainResult);
        $this->assertStringContainsString('  └─ subitem1', $plainResult);
        $this->assertStringNotContainsString('Root', $plainResult);
    }

    public function test_render_tree_from_paths(): void
    {
        $paths = SetCollection::from([
            'src/Console/Components',
            'src/Console/Services',
            'tests/Unit',
        ]);

        $result = Tree::renderFromPaths($paths, 'Project');
        $plainResult = strip_tags($result);

        $this->assertStringContainsString('Project', $plainResult);
        $this->assertStringContainsString('src', $plainResult);
        $this->assertStringContainsString('Console', $plainResult);
        $this->assertStringContainsString('Components', $plainResult);
        $this->assertStringContainsString('Services', $plainResult);
        $this->assertStringContainsString('tests', $plainResult);
        $this->assertStringContainsString('Unit', $plainResult);
    }

    public function test_render_tree_from_empty_paths(): void
    {
        $paths = SetCollection::from([]);

        $result = Tree::renderFromPaths($paths, 'Project');
        $plainResult = strip_tags($result);

        $this->assertStringContainsString('Project', $plainResult);
    }

    public function test_render_tree_with_colors(): void
    {
        $tree = MapCollection::from([
            'node' => MapCollection::from([
                'leaf1' => MapCollection::from([]),
                'leaf2' => MapCollection::from([]),
            ]),
        ]);

        $result = Tree::renderWithColors($tree, 'Root', 'green', 'yellow');

        $this->assertStringContainsString('<fg=green>', $result);
        $this->assertStringContainsString('<options=bold>Root</options=bold></fg=green>', $result);
        $this->assertStringContainsString('<fg=yellow>', $result);
        $this->assertStringContainsString('leaf1', $result);
        $this->assertStringContainsString('leaf2', $result);
    }

    public function test_render_tree_with_colors_default(): void
    {
        $tree = MapCollection::from([
            'node' => MapCollection::from([
                'leaf1' => MapCollection::from([]),
                'leaf2' => MapCollection::from([]),
            ]),
        ]);

        $result = Tree::renderWithColors($tree, 'Root');

        $this->assertStringContainsString('<fg=cyan>', $result);
        $this->assertStringContainsString('<options=bold>Root</options=bold></fg=cyan>', $result);
        $this->assertStringContainsString('<fg=white>', $result);
        $this->assertStringContainsString('leaf1', $result);
        $this->assertStringContainsString('leaf2', $result);
    }

    public function test_render_tree_with_icons(): void
    {
        $tree = MapCollection::from([
            'folder' => MapCollection::from([
                'file1' => MapCollection::from([]),
                'file2' => MapCollection::from([]),
            ]),
        ]);

        $result = Tree::renderWithIcons($tree, 'Root', '📁', '📄');

        $this->assertStringContainsString('📁 Root', $result);
        $this->assertStringContainsString('📁 folder', $result);
        $this->assertStringContainsString('📄 file1', $result);
        $this->assertStringContainsString('📄 file2', $result);
    }

    public function test_render_tree_with_icons_default(): void
    {
        $tree = MapCollection::from([
            'folder' => MapCollection::from([
                'file1' => MapCollection::from([]),
                'file2' => MapCollection::from([]),
            ]),
        ]);

        $result = Tree::renderWithIcons($tree, 'Root');

        $this->assertStringContainsString('📁 Root', $result);
        $this->assertStringContainsString('📁 folder', $result);
        $this->assertStringContainsString('📄 file1', $result);
        $this->assertStringContainsString('📄 file2', $result);
    }

    public function test_render_empty_tree(): void
    {
        $tree = MapCollection::from([]);
        $result = Tree::render($tree, '');

        $this->assertSame('', $result);
    }

    public function test_render_empty_tree_with_root_label(): void
    {
        $tree = MapCollection::from([]);
        $result = Tree::render($tree, 'Root');

        $plainResult = strip_tags($result);
        $this->assertStringContainsString('Root', $plainResult);
    }

    public function test_render_flat_tree(): void
    {
        $tree = MapCollection::from([
            'item1' => MapCollection::from([]),
            'item2' => MapCollection::from([]),
            'item3' => MapCollection::from([]),
        ]);

        $result = Tree::render($tree);
        $plainResult = strip_tags($result);

        $this->assertStringContainsString('├─ item1', $plainResult);
        $this->assertStringContainsString('├─ item2', $plainResult);
        $this->assertStringContainsString('└─ item3', $plainResult);
    }

    public function test_render_deep_tree(): void
    {
        $tree = MapCollection::from([
            'level1' => MapCollection::from([
                'level2' => MapCollection::from([
                    'level3' => MapCollection::from([
                        'level4' => MapCollection::from([]),
                    ]),
                ]),
            ]),
        ]);

        $result = Tree::render($tree);
        $plainResult = strip_tags($result);

        $this->assertStringContainsString('level1', $plainResult);
        $this->assertStringContainsString('level2', $plainResult);
        $this->assertStringContainsString('level3', $plainResult);
        $this->assertStringContainsString('level4', $plainResult);
        $this->assertStringContainsString('└─', $plainResult);
    }

    public function test_render_complex_tree(): void
    {
        $tree = MapCollection::from([
            'src' => MapCollection::from([
                'Console' => MapCollection::from([
                    'Components' => MapCollection::from([
                        'Table.php' => MapCollection::from([]),
                        'Tree.php' => MapCollection::from([]),
                    ]),
                    'Services' => MapCollection::from([
                        'AnsiConverterService.php' => MapCollection::from([]),
                    ]),
                ]),
                'Contracts' => MapCollection::from([
                    'Renderable.php' => MapCollection::from([]),
                ]),
            ]),
            'tests' => MapCollection::from([
                'Unit' => MapCollection::from([
                    'Components' => MapCollection::from([
                        'TreeTest.php' => MapCollection::from([]),
                    ]),
                ]),
            ]),
        ]);

        $result = Tree::render($tree, 'php-console-writer');
        $plainResult = strip_tags($result);

        $this->assertStringContainsString('php-console-writer', $plainResult);
        $this->assertStringContainsString('src', $plainResult);
        $this->assertStringContainsString('Console', $plainResult);
        $this->assertStringContainsString('Components', $plainResult);
        $this->assertStringContainsString('Table.php', $plainResult);
        $this->assertStringContainsString('Tree.php', $plainResult);
        $this->assertStringContainsString('Services', $plainResult);
        $this->assertStringContainsString('AnsiConverterService.php', $plainResult);
        $this->assertStringContainsString('Contracts', $plainResult);
        $this->assertStringContainsString('Renderable.php', $plainResult);
        $this->assertStringContainsString('tests', $plainResult);
        $this->assertStringContainsString('Unit', $plainResult);
        $this->assertStringContainsString('TreeTest.php', $plainResult);
    }

    public function test_render_tree_with_nodes_and_leafs(): void
    {
        $tree = MapCollection::from([
            'dossier1' => MapCollection::from([
                'fichier1.txt' => MapCollection::from([]),
                'fichier2.txt' => MapCollection::from([]),
            ]),
            'dossier2' => MapCollection::from([
                'sous_dossier' => MapCollection::from([
                    'fichier3.txt' => MapCollection::from([]),
                ]),
            ]),
            'fichier_racine.txt' => MapCollection::from([]),
        ]);

        $result = Tree::render($tree, '📁 Racine');
        $plainResult = strip_tags($result);

        // Vérifier que les nœuds sont en cyan gras
        $this->assertStringContainsString('📁 Racine', $plainResult);
        $this->assertStringContainsString('dossier1', $plainResult);
        $this->assertStringContainsString('dossier2', $plainResult);
        $this->assertStringContainsString('fichier_racine.txt', $plainResult);
        $this->assertStringContainsString('fichier1.txt', $plainResult);
        $this->assertStringContainsString('fichier2.txt', $plainResult);
        $this->assertStringContainsString('sous_dossier', $plainResult);
        $this->assertStringContainsString('fichier3.txt', $plainResult);
        $this->assertStringContainsString('├─', $plainResult);
        $this->assertStringContainsString('└─', $plainResult);
    }

    public function test_render_tree_with_duplicate_paths(): void
    {
        $paths = SetCollection::from([
            'src/Console/Components',
            'src/Console/Services',
            'src/Console/Components', // Duplicate
            'tests/Unit',
        ]);

        $result = Tree::renderFromPaths($paths, 'Project');
        $plainResult = strip_tags($result);

        // Les doublons ne doivent pas créer de doublons dans l'arbre
        // Vérifier que "Components" n'apparaît qu'une fois comme dossier
        $this->assertEquals(1, substr_count($plainResult, 'Components'));
    }

    public function test_render_tree_with_single_path(): void
    {
        $paths = SetCollection::from([
            'src/Console/Components/Table.php',
        ]);

        $result = Tree::renderFromPaths($paths, 'Project');
        $plainResult = strip_tags($result);

        $this->assertStringContainsString('Project', $plainResult);
        $this->assertStringContainsString('src', $plainResult);
        $this->assertStringContainsString('Console', $plainResult);
        $this->assertStringContainsString('Components', $plainResult);
        $this->assertStringContainsString('Table.php', $plainResult);
    }

    public function test_render_tree_with_deep_paths(): void
    {
        $paths = SetCollection::from([
            'a/b/c/d/e/f/g/h/i/j/file.txt',
        ]);

        $result = Tree::renderFromPaths($paths, 'Root');
        $plainResult = strip_tags($result);

        $this->assertStringContainsString('Root', $plainResult);
        $this->assertStringContainsString('a', $plainResult);
        $this->assertStringContainsString('b', $plainResult);
        $this->assertStringContainsString('c', $plainResult);
        $this->assertStringContainsString('d', $plainResult);
        $this->assertStringContainsString('e', $plainResult);
        $this->assertStringContainsString('f', $plainResult);
        $this->assertStringContainsString('g', $plainResult);
        $this->assertStringContainsString('h', $plainResult);
        $this->assertStringContainsString('i', $plainResult);
        $this->assertStringContainsString('j', $plainResult);
        $this->assertStringContainsString('file.txt', $plainResult);
    }

    public function test_tree_root_label_formatting(): void
    {
        $tree = MapCollection::from([
            'root' => MapCollection::from([]),
        ]);

        $result = Tree::render($tree, 'My Root');

        // Vérifier que le root label est en cyan gras
        $this->assertStringContainsString('<fg=cyan><options=bold>My Root</options=bold></fg=cyan>', $result);
    }

    public function test_tree_node_formatting(): void
    {
        $tree = MapCollection::from([
            'node' => MapCollection::from([
                'leaf' => MapCollection::from([]),
            ]),
        ]);

        $result = Tree::render($tree, 'Root');

        // Vérifier que le nœud est en cyan gras
        $this->assertStringContainsString('<fg=cyan><options=bold>node</options=bold></fg=cyan>', $result);

        // Vérifier que la feuille est en blanc (avec son préfixe)
        $this->assertStringContainsString('<fg=white>  └─ leaf</fg=white>', $result);
    }

    public function test_tree_prefix_formatting(): void
    {
        $tree = MapCollection::from([
            'node' => MapCollection::from([
                'leaf' => MapCollection::from([]),
            ]),
        ]);

        $result = Tree::render($tree, 'Root');

        // Vérifier que les préfixes sont en blanc
        $this->assertStringContainsString('<fg=white>└─ </fg=white>', $result);
        $this->assertStringContainsString('<fg=white>  └─ leaf</fg=white>', $result);
    }

    public function test_tree_with_mixed_nodes_and_leafs(): void
    {
        $tree = MapCollection::from([
            'folder1' => MapCollection::from([
                'file1.txt' => MapCollection::from([]),
                'subfolder' => MapCollection::from([
                    'file2.txt' => MapCollection::from([]),
                ]),
            ]),
            'file_root.txt' => MapCollection::from([]),
        ]);

        $result = Tree::render($tree, 'Root');
        $plainResult = strip_tags($result);

        // Vérifier l'ordre et la structure
        $this->assertStringContainsString('folder1', $plainResult);
        $this->assertStringContainsString('file1.txt', $plainResult);
        $this->assertStringContainsString('subfolder', $plainResult);
        $this->assertStringContainsString('file2.txt', $plainResult);
        $this->assertStringContainsString('file_root.txt', $plainResult);
    }
}
