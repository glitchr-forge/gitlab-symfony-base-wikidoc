<?php

namespace Base\Wikidoc\Entity;

use Base\Wikidoc\Repository\DocumentRepository;
use Base\Annotations\Annotation\Hierarchify;
use Base\Database\Annotation\Cache;
use Base\Database\Annotation\DiscriminatorEntry;
use Base\Entity\Thread;
use Base\Service\Model\LinkableInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Base\Database\Annotation\ColumnAlias;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @ORM\Entity(repositoryClass=DocumentRepository::class)
 *
 * @Cache(usage="NONSTRICT_READ_WRITE", associations="ALL")
 *
 * @DiscriminatorEntry( value = "wikidoc" )
 *
 * @Hierarchify(hierarchy = {"wikidoc"}, separator = "/" );
 */
class Document extends Thread implements LinkableInterface
{
    public static function __iconizeStatic(): ?array
    {
        return ['fa-solid fa-book'];
    }

    public function __toLink(array $routeParameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): ?string
    {
        $routeName = 'backoffice_manual';
        $routeParameters = array_merge($routeParameters, ['slug' => $this->getSlug()]);

        return $this->getRouter()->generate($routeName, $routeParameters, $referenceType);
    }

    /**
     * @ColumnAlias(column = "tags")
     */
    protected $sections;

    public function getSections(): Collection
    {
        return $this->getTags();
    }

    public function addSection(Section $section): self
    {
        return $this->addTag($section);
    }

    public function removeSection(Section $section): self
    {
        return $this->removeTag($section);
    }

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $icon;

    public function getIcon(): ?string
    {
        return $this->icon ?? $this->__iconize()[0] ?? $this->__iconizeStatic()[0] ?? null;
    }

    /**
     * @param string|null $icon
     * @return $this
     */
    public function setIcon(?string $icon): static
    {
        $this->icon = $icon;
        return $this;
    }
}
