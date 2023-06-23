<?php

namespace Base\Wikidoc\Entity;

use Base\Wikidoc\Repository\AdminDocumentRepository;
use Base\Annotations\Annotation\Hierarchify;
use Base\Database\Annotation\Cache;
use Base\Wikidoc\Entity\Abstract\AbstractDocument;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdminDocumentRepository::class)
 *
 * @Cache(usage="NONSTRICT_READ_WRITE", associations="ALL")
 *
 * @Hierarchify(hierarchy = {"wikidoc"}, separator = "/" );
 */
class AdminDocument extends AbstractDocument
{
}
