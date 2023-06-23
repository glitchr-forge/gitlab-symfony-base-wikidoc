<?php

namespace Base\Wikidoc\Repository;

use Base\Wikidoc\Entity\Abstract\AdminDocument;
use Base\Wikidoc\Repository\Abstract\AbstractDocumentRepository;

/**
 * @method AdminDocument|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdminDocument|null findOneBy(array $criteria, ?array $orderBy = null)
 * @method AdminDocument[]    findAll()
 * @method AdminDocument[]    findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 */
class AdminDocumentRepository extends AbstractDocumentRepository
{
}
