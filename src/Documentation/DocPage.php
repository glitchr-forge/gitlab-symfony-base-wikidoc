<?php

namespace Base\Wikidoc\Documentation;

/**
 * One markdown file, resolved to the page the admin actually renders.
 *
 * A page knows which ROOT it came from and, when it shadows a page of the
 * same path in a lower-priority root, which file it superseded. That is the
 * whole point of the layered model: base-bundle ships a manual, the
 * application overrides the parts that differ, and a reader can still tell
 * that an override is in play.
 */
final class DocPage
{
    /** @param DocPage[] $children */
    public function __construct(
        public readonly string $path,
        public readonly string $title,
        public readonly ?string $file,
        public readonly string $root,
        public readonly int $order,
        public array $children = [],
        public readonly ?string $supersedes = null,
    ) {
    }

    /**
     * True when this page replaces one that a lower-priority root also
     * provides.
     */
    public function isOverride(): bool
    {
        return null !== $this->supersedes;
    }

    /**
     * A section with no markdown file of its own - a directory that groups
     * pages but has no index.md. It is a heading, not a link.
     */
    public function isSection(): bool
    {
        return null === $this->file;
    }

    /**
     * Whether this page, or anything nested under it, is the current one -
     * what the sidebar uses to decide which branches to keep expanded.
     */
    public function contains(?string $path): bool
    {
        if (null === $path) {
            return false;
        }

        if ($this->path === $path) {
            return true;
        }

        foreach ($this->children as $child) {
            if ($child->contains($path)) {
                return true;
            }
        }

        return false;
    }
}
