<?php

namespace Base\Wikidoc\Entity;

use Base\Wikidoc\Repository\UserDocumentRepository;
use Base\Annotations\Annotation\Hierarchify;
use Base\Database\Annotation\Cache;
use Base\Wikidoc\Entity\Abstract\AbstractDocument;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserDocumentRepository::class)
 *
 * @Cache(usage="NONSTRICT_READ_WRITE", associations="ALL")
 *
 * @Hierarchify(hierarchy = {"wikidoc"}, separator = "/" );
 */
class UserDocument extends AbstractDocument
{
}
