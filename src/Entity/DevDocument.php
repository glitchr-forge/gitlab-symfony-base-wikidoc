<?php

namespace Base\Wikidoc\Entity;

use Base\Wikidoc\Repository\DevDocumentRepository;
use Base\Database\Attribute\Hierarchify;
use Base\Database\Attribute\Cache;
use Base\Wikidoc\Entity\Abstract\AbstractDocument;
use Doctrine\ORM\Mapping as ORM;
use Base\Database\Attribute\DiscriminatorEntry;

#[ORM\Entity(repositoryClass: DevDocumentRepository::class)]
#[Cache(usage: 'NONSTRICT_READ_WRITE', associations: 'ALL')]
#[DiscriminatorEntry]
#[Hierarchify(['wikidoc'], separator: '/')]
class DevDocument extends AbstractDocument
{
}
