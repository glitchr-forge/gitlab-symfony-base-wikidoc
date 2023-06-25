<?php

namespace Base\Wikidoc\Entity;

use Base\Wikidoc\Entity\Abstract\AbstractSection;
use Base\Wikidoc\Repository\AdminSectionRepository;
use Base\Annotations\Annotation\Hierarchify;
use Base\Database\Annotation\Cache;
use Doctrine\ORM\Mapping as ORM;
use Base\Database\Annotation\DiscriminatorEntry;

/**
 * @ORM\Entity(repositoryClass=AdminSectionRepository::class)
 *
 * @Cache(usage="NONSTRICT_READ_WRITE", associations="ALL")
 *
 * @DiscriminatorEntry()
 * @Hierarchify(hierarchy = {"wikidoc", "section"}, separator = "/" );
 */
class AdminSection extends AbstractSection
{
}
