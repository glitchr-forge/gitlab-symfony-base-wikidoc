<?php

namespace Base\Wikidoc\Entity;

use Base\Wikidoc\Repository\DocumentRepository;
use Base\Annotations\Annotation\Hierarchify;
use Base\Database\Annotation\Cache;
use Base\Database\Annotation\ColumnAlias;
use Base\Database\Annotation\DiscriminatorEntry;
use Base\Database\TranslatableInterface;
use Base\Entity\Thread;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
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
class Document extends Thread
{
    public static function __iconizeStatic(): ?array
    {
        return ['fa-solid fa-book'];
    }
}
