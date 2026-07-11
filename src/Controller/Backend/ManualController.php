<?php

namespace Base\Wikidoc\Controller\Backend;

use Base\Admin\Context\AdminContext;
use Base\Admin\Menu\MenuBuilder;
use Base\Wikidoc\Entity\UserDocument;
use Base\Wikidoc\Repository\UserDocumentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * The customer-facing help panel: the whole reason wikidoc exists. A flat
 * priority-ordered, self-nesting list of UserDocument entries with the
 * selected one's content rendered inline - deliberately simple, no
 * separate "section" concept (the entity model is just parent/child).
 */
class ManualController extends AbstractController
{
    public function __construct(
        protected readonly AdminContext $adminContext,
        protected readonly MenuBuilder $menuBuilder,
    ) {
    }

    #[Route(['fr' => '/manuel/{slug}', 'en' => '/manual/{slug}'], name: 'backoffice_manual', defaults: ['slug' => null])]
    public function index(?string $slug, UserDocumentRepository $repository): Response
    {
        $documents = $repository->findBy([], ['priority' => 'ASC']);
        $selected = $slug ? $repository->findOneBy(['slug' => $slug]) : null;

        if ([] === $this->adminContext->getMainMenu()) {
            $this->adminContext->setMainMenu($this->menuBuilder->buildDefault());
        }

        return $this->render('@Wikidoc/backoffice/manual.html.twig', [
            'admin_context' => $this->adminContext,
            'documents' => $this->buildTree($documents),
            'selected_document' => $selected,
        ]);
    }

    /**
     * @param UserDocument[] $documents
     * @return array<int, array{document: UserDocument, children: array}>
     */
    protected function buildTree(array $documents): array
    {
        $byParent = [];
        foreach ($documents as $document) {
            $parentId = $document->getParent()?->getId() ?? 0;
            $byParent[$parentId][] = $document;
        }

        $build = function (int $parentId) use (&$build, $byParent): array {
            $nodes = [];
            foreach ($byParent[$parentId] ?? [] as $document) {
                $nodes[] = ['document' => $document, 'children' => $build($document->getId())];
            }

            return $nodes;
        };

        return $build(0);
    }
}
