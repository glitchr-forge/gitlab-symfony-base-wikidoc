<?php

namespace Base\Wikidoc\Entity;

use Base\Annotations\Annotation\Hierarchify;
use Base\Database\Annotation\Cache;
use Base\Database\Annotation\DiscriminatorEntry;
use Base\Wikidoc\Repository\SectionRepository;
use Base\Service\Model\IconizeInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Base\Database\Annotation\ColumnAlias;

/**
 * @ORM\Entity(repositoryClass=SectionRepository::class)
 *
 * @Cache(usage="NONSTRICT_READ_WRITE", associations="ALL")
 *
 * @DiscriminatorEntry( value = "wikidoc_section" )
 *
 * @Hierarchify(hierarchy = {"wikidoc", "sections"}, separator = "/" );
 */
class Section extends \Base\Entity\Thread\Tag implements IconizeInterface
{
    /**
     * @ColumnAlias(column = "threads")
     */
    protected $documents;

    public function getDocuments(): Collection
    {
        return $this->getThreads();
    }

    /**
     * @param Document $document
     * @return Section
     */
    public function addDocument(Document $document)
    {
        return $this->addThread($document);
    }

    /**
     * @param Document $document
     * @return Section
     */
    public function removeDocument(Document $document)
    {
        return $this->removeThread($document);
    }
}
