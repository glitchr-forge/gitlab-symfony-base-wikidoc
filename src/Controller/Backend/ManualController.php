<?php

namespace Base\Wikidoc\Controller\Backend;

use Base\Admin\Context\AdminContext;
use Base\Admin\Menu\MenuBuilder;
use Base\Wikidoc\Documentation\DocumentationRegistry;
use Base\Wikidoc\Documentation\MarkdownRenderer;
use Base\Wikidoc\Documentation\SearchIndexBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * The manual, rendered from markdown on disk.
 *
 * There is no database behind this any more: the pages are files in the
 * documentation roots (base-bundle's own manual, then the application's,
 * which overrides it), so they are versioned with the code they describe
 * and reviewed in the same diff.
 */
class ManualController extends AbstractController
{
    public function __construct(
        protected readonly AdminContext $adminContext,
        protected readonly MenuBuilder $menuBuilder,
        protected readonly DocumentationRegistry $registry,
        protected readonly MarkdownRenderer $renderer,
        protected readonly SearchIndexBuilder $searchIndex,
    ) {
    }

    /**
     * Declared BEFORE index() below: "/manuel/{path}" has a `.+` requirement
     * so it can match nested paths, which means it would happily swallow
     * "_search" as a page path if it were registered first.
     */
    #[Route(['fr' => '/manuel/_search', 'en' => '/manual/_search'], name: 'backoffice_manual_search', methods: ['GET'], priority: 10)]
    public function search(): JsonResponse
    {
        // Same firewall as the manual itself - the index is a flattened copy
        // of the documentation, so it must not be reachable by anyone who
        // could not already read the pages.
        return new JsonResponse($this->searchIndex->build());
    }

    #[Route(['fr' => '/manuel/{path}', 'en' => '/manual/{path}'], name: 'backoffice_manual', defaults: ['path' => null], requirements: ['path' => '.+'])]
    public function index(?string $path): Response
    {
        $page = null !== $path ? $this->registry->get($path) : $this->registry->getDefault();

        if (null !== $path && null === $page) {
            throw $this->createNotFoundException(sprintf('No documentation page at "%s".', $path));
        }

        if ([] === $this->adminContext->getMainMenu()) {
            $this->adminContext->setMainMenu($this->menuBuilder->buildDefault());
        }

        $markdown = null !== $page?->file ? (string) @file_get_contents($page->file) : null;

        return $this->render('@Wikidoc/backoffice/manual.html.twig', [
            'admin_context' => $this->adminContext,
            'tree' => $this->registry->getTree(),
            'page' => $page,
            'content' => null !== $markdown ? $this->renderer->render($markdown) : null,
            'headings' => null !== $markdown ? $this->renderer->extractHeadings($markdown) : [],
            'roots' => $this->registry->getRoots(),
        ]);
    }
}
