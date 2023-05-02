<?php

namespace Base\Wikidoc\Entity;

use Base\Annotations\Annotation\Hierarchify;
use Base\Database\Annotation\Cache;
use Base\Database\Annotation\DiscriminatorEntry;
use Base\Wikidoc\Repository\TagRepository;
use Base\Service\Model\IconizeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TagRepository::class)
 *
 * @Cache(usage="NONSTRICT_READ_WRITE", associations="ALL")
 *
 * @DiscriminatorEntry( value = "wikidoc_tag" )
 *
 * @Hierarchify(hierarchy = {"wikidoc", "tags"}, separator = "/" );
 */
class Tag extends \Base\Entity\Thread\Tag implements IconizeInterface
{
    public static function __iconizeStatic(): ?array
    {
        return ['fa-solid fa-tag'];
    }
}
