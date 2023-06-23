<?php

namespace Base\Wikidoc\Repository;

use Base\Wikidoc\Entity\Section;
use Base\Annotations\Traits\HierarchifyTrait;

use Base\Database\Repository\ServiceEntityRepository;
use Base\Repository\Thread\TagRepository;

/**
 * @method Section|null find($id, $lockMode = null, $lockVersion = null)
 * @method Section|null findOneBy(array $criteria, ?array $orderBy = null)
 * @method Section[]    findAll()
 * @method Section[]    findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 */
class SectionRepository extends TagRepository
{
    use HierarchifyTrait;
}
