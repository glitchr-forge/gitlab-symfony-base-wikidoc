<?php

namespace Base\Wikidoc\Documentation;

use Symfony\Component\Finder\Finder;

/**
 * Builds the documentation tree from markdown on disk, across several roots.
 *
 * Roots are declared lowest-priority first (base-bundle's own manual, then
 * the application's), and a page's PATH is what identifies it: when two
 * roots provide "install/requirements.md", the higher-priority one wins and
 * records what it superseded. Same override model Symfony already uses for
 * bundle templates, so "put a file at the same path to replace it" is a rule
 * the reader already knows.
 *
 * Hierarchy is the directory tree - no separate section concept, no ordering
 * table to maintain. A directory becomes a section; its index.md, if there
 * is one, becomes the section's own page rather than a child of it.
 */
class DocumentationRegistry
{
    /** @var array<string, array{path: string, label: string}> */
    protected array $roots;

    protected ?DocPage $tree = null;

    /** @var array<string, DocPage>|null flattened by path */
    protected ?array $index = null;

    /**
     * @param array<string, array{path: string, label: string}> $roots
     *        keyed by root name, LOWEST priority first
     */
    public function __construct(array $roots = [])
    {
        $this->roots = $roots;
    }

    /** @return array<string, array{path: string, label: string}> */
    public function getRoots(): array
    {
        return $this->roots;
    }

    /** @return DocPage[] top-level pages and sections */
    public function getTree(): array
    {
        return $this->build()->children;
    }

    public function get(string $path): ?DocPage
    {
        $this->build();

        return $this->index[trim($path, '/')] ?? null;
    }

    /**
     * The first page a reader lands on when no path is given: an explicit
     * top-level index.md if one exists, else the first real page in tree
     * order. Deliberately never a section - landing on a heading with no
     * content reads as a broken link.
     */
    public function getDefault(): ?DocPage
    {
        // The root index.md resolves to the EMPTY path, not "index" -
        // pathFor() strips the filename so a section's index page is the
        // section itself. Looking it up as "index" silently missed it and
        // dropped the reader on whatever page happened to sort first.
        if (null !== $home = $this->get('')) {
            return $home;
        }

        $firstPage = function (array $pages) use (&$firstPage): ?DocPage {
            foreach ($pages as $page) {
                if (!$page->isSection()) {
                    return $page;
                }
                if (null !== $found = $firstPage($page->children)) {
                    return $found;
                }
            }

            return null;
        };

        return $firstPage($this->getTree());
    }

    /** @return DocPage[] every page with a file, flattened */
    public function getAllPages(): array
    {
        $this->build();

        return array_values(array_filter($this->index, static fn (DocPage $p): bool => !$p->isSection()));
    }

    /**
     * Cheap fingerprint of every source file, so a caller can cache derived
     * work (the search index) and drop it the moment any file changes.
     */
    public function getFingerprint(): string
    {
        $parts = [];
        foreach ($this->collectFiles() as $path => $found) {
            $parts[] = $path . ':' . $found['mtime'];
        }

        return md5(implode('|', $parts));
    }

    protected function build(): DocPage
    {
        if (null !== $this->tree) {
            return $this->tree;
        }

        $files = $this->collectFiles();

        // Group by directory so a tree can be assembled without recursing
        // over the filesystem a second time.
        $nodes = [];
        foreach ($files as $path => $found) {
            $nodes[$path] = $found;
        }

        $this->index = [];
        $children = $this->assemble('', $nodes);

        // The root index.md is the manual's home page. It is deliberately
        // NOT one of the tree's children - it would show up as a sibling of
        // the sections it introduces - but it still has to be addressable,
        // so it is registered in the lookup by hand.
        if (isset($nodes[''])) {
            $meta = $this->readMeta($nodes['']['file']);
            $this->index[''] = new DocPage(
                path: '',
                title: $meta['title'] ?? 'Documentation',
                file: $nodes['']['file'],
                root: $nodes['']['root'],
                order: $meta['order'] ?? 0,
                supersedes: $nodes['']['supersedes'],
            );
        }

        $this->tree = new DocPage('', '', null, '', 0, $children);

        return $this->tree;
    }

    /**
     * Every markdown file across every root, keyed by its PATH, with the
     * highest-priority root winning. Roots are iterated in declaration
     * order, so a later root simply overwrites an earlier entry - and keeps
     * a note of the file it replaced.
     *
     * @return array<string, array{file: string, root: string, mtime: int, supersedes: ?string}>
     */
    protected function collectFiles(): array
    {
        $files = [];

        foreach ($this->roots as $name => $root) {
            $dir = rtrim($root['path'], '/');
            if (!is_dir($dir)) {
                continue;
            }

            $finder = (new Finder())->files()->in($dir)->name('*.md')->sortByName();
            foreach ($finder as $file) {
                $relative = str_replace('\\', '/', $file->getRelativePathname());
                $path = $this->pathFor($relative);

                $files[$path] = [
                    'file' => $file->getRealPath(),
                    'root' => $name,
                    'mtime' => $file->getMTime(),
                    // Only set when this really replaces something already
                    // collected - not merely because a lower root exists.
                    'supersedes' => isset($files[$path]) ? $files[$path]['file'] : null,
                ];
            }
        }

        ksort($files);

        return $files;
    }

    /**
     * "install/requirements.md" -> "install/requirements"
     * "install/index.md"        -> "install"   (the section's own page)
     */
    protected function pathFor(string $relative): string
    {
        $path = preg_replace('/\.md$/i', '', $relative);
        $path = preg_replace('#(^|/)index$#i', '', $path);

        return trim((string) $path, '/');
    }

    /**
     * @param array<string, array{file: string, root: string, mtime: int, supersedes: ?string}> $nodes
     * @return DocPage[]
     */
    protected function assemble(string $prefix, array $nodes): array
    {
        $depth = '' === $prefix ? 0 : substr_count($prefix, '/') + 1;
        $children = [];

        foreach ($nodes as $path => $found) {
            if ('' === $path) {
                continue; // the root index page is not a child of itself
            }
            if ('' !== $prefix && !str_starts_with($path, $prefix . '/')) {
                continue;
            }

            $segments = explode('/', $path);
            if (\count($segments) !== $depth + 1) {
                continue; // deeper - handled by the recursion below
            }

            $children[$path] = $found;
        }

        // Directories that hold pages but have no page of their own still
        // need to appear, as sections.
        foreach ($nodes as $path => $found) {
            if ('' !== $prefix && !str_starts_with($path, $prefix . '/')) {
                continue;
            }
            $segments = explode('/', $path);
            if (\count($segments) <= $depth + 1) {
                continue;
            }

            $sectionPath = implode('/', \array_slice($segments, 0, $depth + 1));
            if (!isset($children[$sectionPath])) {
                $children[$sectionPath] = null;
            }
        }

        $pages = [];
        foreach ($children as $path => $found) {
            $meta = null !== $found ? $this->readMeta($found['file']) : ['title' => null, 'order' => null];

            $page = new DocPage(
                path: $path,
                title: $meta['title'] ?? $this->humanize(basename($path)),
                file: $found['file'] ?? null,
                root: $found['root'] ?? '',
                order: $meta['order'] ?? $this->orderFor(basename($path)),
                children: $this->assemble($path, $nodes),
                supersedes: $found['supersedes'] ?? null,
            );

            $this->index[$path] = $page;
            $pages[] = $page;
        }

        usort($pages, static fn (DocPage $a, DocPage $b): int => [$a->order, $a->title] <=> [$b->order, $b->title]);

        return $pages;
    }

    /**
     * Title and ordering, read from the file itself rather than a manifest:
     * an optional YAML-ish front-matter block, else the first H1, else the
     * filename. Ordering falls back to a numeric filename prefix ("10-"),
     * which is how people already order files they want ordered.
     *
     * @return array{title: ?string, order: ?int}
     */
    protected function readMeta(string $file): array
    {
        $handle = @fopen($file, 'r');
        if (false === $handle) {
            return ['title' => null, 'order' => null];
        }

        $title = null;
        $order = null;
        $inFrontMatter = false;
        $line = 0;

        while (false !== $buffer = fgets($handle)) {
            $buffer = rtrim($buffer, "\r\n");
            ++$line;

            if (1 === $line && '---' === trim($buffer)) {
                $inFrontMatter = true;
                continue;
            }

            if ($inFrontMatter) {
                if ('---' === trim($buffer)) {
                    $inFrontMatter = false;
                    continue;
                }
                if (preg_match('/^title\s*:\s*(.+)$/i', $buffer, $m)) {
                    $title = trim($m[1], " \t\"'");
                }
                if (preg_match('/^order\s*:\s*(-?\d+)/i', $buffer, $m)) {
                    $order = (int) $m[1];
                }
                continue;
            }

            if (null === $title && preg_match('/^#\s+(.+)$/', $buffer, $m)) {
                $title = trim($m[1]);
            }

            // Stop early: everything that matters is at the top of the file
            // and these are read for EVERY page on EVERY request.
            if (null !== $title && $line > 40) {
                break;
            }
            if ($line > 200) {
                break;
            }
        }

        fclose($handle);

        return ['title' => $title, 'order' => $order];
    }

    protected function orderFor(string $basename): int
    {
        return preg_match('/^(\d+)[-_]/', $basename, $m) ? (int) $m[1] : 500;
    }

    protected function humanize(string $basename): string
    {
        $name = preg_replace('/^\d+[-_]/', '', $basename);
        $name = str_replace(['-', '_'], ' ', (string) $name);

        return ucfirst(trim($name));
    }
}
