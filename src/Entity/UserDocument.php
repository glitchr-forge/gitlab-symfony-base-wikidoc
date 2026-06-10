<?php

namespace Base\Wikidoc\Entity;

use Base\Wikidoc\Repository\UserDocumentRepository;
use Base\Attributes\Attribute\Hierarchify;
use Base\Database\Attribute\Cache;
use Base\Wikidoc\Entity\Abstract\AbstractDocument;
use Doctrine\ORM\Mapping as ORM;
use Base\Database\Attribute\DiscriminatorEntry;

/**
 * @ORM\Entity(repositoryClass=UserDocumentRepository::class)
 *
 * @Cache(usage="NONSTRICT_READ_WRITE", associations="ALL")
 *
 * @DiscriminatorEntry()
 * @Hierarchify(hierarchy = {"wikidoc"}, separator = "/" );
 */
class UserDocument extends AbstractDocument
{
}
