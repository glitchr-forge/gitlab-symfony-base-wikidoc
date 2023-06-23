<?php

namespace Base\Wikidoc\Repository;

use Base\Wikidoc\Repository\Abstract\AbstractSectionRepository;

/**
 * @method AdminSection|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdminSection|null findOneBy(array $criteria, ?array $orderBy = null)
 * @method AdminSection[]    findAll()
 * @method AdminSection[]    findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 */
class AdminSectionRepository extends AbstractSectionRepository
{
}
