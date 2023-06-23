<?php

namespace Base\Wikidoc\Repository;

use Base\Wikidoc\Repository\Abstract\AbstractSectionRepository;

/**
 * @method UserSection|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserSection|null findOneBy(array $criteria, ?array $orderBy = null)
 * @method UserSection[]    findAll()
 * @method UserSection[]    findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 */
class UserSectionRepository extends AbstractSectionRepository
{
}
