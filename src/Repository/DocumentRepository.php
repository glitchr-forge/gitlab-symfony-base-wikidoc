<?php

namespace Base\Wikidoc\Repository;

use Base\Entity\Wikidoc;
use Base\Annotations\Traits\HierarchifyTrait;

use Base\Database\Repository\ServiceEntityRepository;
use Base\Enum\WorkflowState;

/**
 * @method Document|null find($id, $lockMode = null, $lockVersion = null)
 * @method Document|null findOneBy(array $criteria, ?array $orderBy = null)
 * @method Document[]    findAll()
 * @method Document[]    findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentRepository extends ServiceEntityRepository
{
    use HierarchifyTrait;
}
