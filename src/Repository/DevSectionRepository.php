<?php

namespace Base\Wikidoc\Repository;

use Base\Wikidoc\Repository\Abstract\AbstractSectionRepository;

/**
 * @method DevSection|null find($id, $lockMode = null, $lockVersion = null)
 * @method DevSection|null findOneBy(array $criteria, ?array $orderBy = null)
 * @method DevSection[]    findAll()
 * @method DevSection[]    findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 */
class DevSectionRepository extends AbstractSectionRepository
{
}
