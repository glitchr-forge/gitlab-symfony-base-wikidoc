<?php

namespace Base\Wikidoc\Documentation;

use Psr\Cache\CacheItemPoolInterface;

/**
 * The payload behind the ⌘K palette: one flat list of searchable records,
 * built once per set of source files and matched entirely in the browser.
 *
 * Flat rather than nested, and one record per HEADING rather than per page,
 * so a hit can jump straight to the section it matched instead of dropping
 * the reader at the top of a long document.
 *
 * Kept deliberately small - this is downloaded whole. Page bodies are
 * truncated, because a search index exists to locate a page, not to
 * reproduce it.
 */
class SearchIndexBuilder
{
    protected const MAX_TEXT = 1200;

    public function __construct(
        protected readonly DocumentationRegistry $registry,
        protected readonly MarkdownRenderer $renderer,
        protected readonly ?CacheItemPoolInterface $cache = null,
    ) {
    }

    /** @return array<int, array<string, mixed>> */
    public function build(): array
    {
        // Keyed on the fingerprint, so an edited file invalidates the index
        // by simply not matching the old key - no explicit busting anywhere.
        $key = 'wikidoc.search.' . $this->registry->getFingerprint();

        if (null === $this->cache) {
            return $this->compute();
        }

        $item = $this->cache->getItem($key);
        if ($item->isHit()) {
            return $item->get();
        }

        $records = $this->compute();
        $item->set($records);
        $this->cache->save($item);

        return $records;
    }

    /** @return array<int, array<string, mixed>> */
    protected function compute(): array
    {
        $records = [];

        foreach ($this->registry->getAllPages() as $page) {
            if (null === $page->file) {
                continue;
            }

            $markdown = (string) @file_get_contents($page->file);
            $text = $this->renderer->extractText($markdown);

            $records[] = [
                'path' => $page->path,
                'title' => $page->title,
                'section' => null,
                'anchor' => null,
                'text' => mb_substr($text, 0, self::MAX_TEXT),
                'root' => $page->root,
            ];

            foreach ($this->renderer->extractHeadings($markdown) as $heading) {
                $records[] = [
                    'path' => $page->path,
                    'title' => $page->title,
                    'section' => $heading['text'],
                    'anchor' => $heading['id'],
                    'text' => mb_substr($this->textAfter($text, $heading['text']), 0, 400),
                    'root' => $page->root,
                ];
            }
        }

        return $records;
    }

    /**
     * A snippet of what follows a heading, so a section hit can show
     * something more useful than its own title repeated back.
     */
    protected function textAfter(string $text, string $heading): string
    {
        $position = mb_strpos($text, $heading);

        return false === $position ? '' : trim(mb_substr($text, $position + mb_strlen($heading)));
    }
}
