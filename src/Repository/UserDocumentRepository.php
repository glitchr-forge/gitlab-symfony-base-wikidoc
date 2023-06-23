<?php

namespace Base\Wikidoc\Repository;

use Base\Wikidoc\Entity\Abstract\UserDocument;
use Base\Wikidoc\Repository\Abstract\AbstractDocumentRepository;

/**
 * @method UserDocument|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserDocument|null findOneBy(array $criteria, ?array $orderBy = null)
 * @method UserDocument[]    findAll()
 * @method UserDocument[]    findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 */
class UserDocumentRepository extends AbstractDocumentRepository
{
}
