<?php

namespace Base\Wikidoc\Entity\Abstract;

use Base\Annotations\Annotation\Hierarchify;
use Base\Database\Annotation\Cache;
use Base\Wikidoc\Repository\Abstract\AbstractSectionRepository;
use Base\Service\Model\IconizeInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Base\Database\Annotation\ColumnAlias;
use Base\Database\Annotation\DiscriminatorEntry;

/**
 * @ORM\Entity(repositoryClass=AbstractSectionRepository::class)
 *
 * @Cache(usage="NONSTRICT_READ_WRITE", associations="ALL")
 *
 * @DiscriminatorEntry()
 * @Hierarchify(hierarchy = {"wikidoc", "sections"}, separator = "/" );
 */
abstract class AbstractSection extends \Base\Entity\Thread\Tag implements IconizeInterface
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
     * @param AbstractDocument $document
     * @return Section
     */
    public function addDocument(AbstractDocument $document)
    {
        return $this->addThread($document);
    }

    /**
     * @param AbstractDocument $document
     * @return Section
     */
    public function removeDocument(AbstractDocument $document)
    {
        return $this->removeThread($document);
    }
}
