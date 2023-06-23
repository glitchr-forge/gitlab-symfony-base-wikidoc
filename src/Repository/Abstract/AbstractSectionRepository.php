<?php

namespace Base\Wikidoc\Repository\Abstract;

use Base\Wikidoc\Entity\Abstract\AbstractSection;
use Base\Annotations\Traits\HierarchifyTrait;

use Base\Repository\Thread\TagRepository;

/**
 * @method AbstractSection|null find($id, $lockMode = null, $lockVersion = null)
 * @method AbstractSection|null findOneBy(array $criteria, ?array $orderBy = null)
 * @method AbstractSection[]    findAll()
 * @method AbstractSection[]    findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 */
class AbstractSectionRepository extends TagRepository
{
    use HierarchifyTrait;
}
