<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class AbstractRepository
 * @package App\Repository
 */
abstract class AbstractRepository extends EntityRepository
{
    /**
     * @param string   $order
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return array
     */
    public function getList(string $order = 'DESC',
                            int $limit = null,
                            int $offset = null
    ): array {
        $qb = $this->createQueryBuilder('entity');

        $qb->select('entity');

        $qb->setMaxResults($limit);
        $qb->setFirstResult($offset);

        $qb->orderBy('entity.createdAt', $order);
        $qb->addOrderBy('entity.id', $order);

        return $qb->getQuery()->getResult();
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        $qb = $this->createQueryBuilder('entity');
        $qb->select($qb->expr()->count('entity'));

        return $qb->getQuery()->getSingleScalarResult();
    }
}
