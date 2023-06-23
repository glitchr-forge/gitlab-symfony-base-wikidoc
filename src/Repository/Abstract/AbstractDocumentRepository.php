<?php

namespace Base\Wikidoc\Repository\Abstract;

use Base\Wikidoc\Entity\Abstract\AbstractDocument;
use Base\Annotations\Traits\HierarchifyTrait;

use Base\Database\Repository\ServiceEntityRepository;

/**
 * @method AbstractDocument|null find($id, $lockMode = null, $lockVersion = null)
 * @method AbstractDocument|null findOneBy(array $criteria, ?array $orderBy = null)
 * @method AbstractDocument[]    findAll()
 * @method AbstractDocument[]    findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 */
class AbstractDocumentRepository extends ServiceEntityRepository
{
    use HierarchifyTrait;
}
