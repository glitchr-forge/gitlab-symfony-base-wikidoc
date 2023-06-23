<?php

namespace Base\Wikidoc\Repository;

use Base\Wikidoc\Entity\Abstract\DevDocument;
use Base\Wikidoc\Repository\Abstract\AbstractDocumentRepository;

/**
 * @method DevDocument|null find($id, $lockMode = null, $lockVersion = null)
 * @method DevDocument|null findOneBy(array $criteria, ?array $orderBy = null)
 * @method DevDocument[]    findAll()
 * @method DevDocument[]    findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 */
class DevDocumentRepository extends AbstractDocumentRepository
{
}
