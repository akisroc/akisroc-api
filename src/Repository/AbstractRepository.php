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
     * @param string $order
     * @param int|null $limit
     * @param int|null $offset
     * @param array $criteria
     *
     * @return array
     */
    public function getList(string $order = 'DESC',
                            int $limit = null,
                            int $offset = null,
                            array $criteria = []
    ): array {
        $qb = $this->createQueryBuilder('entity');

        $qb->select('entity');

        $qb->setMaxResults($limit);
        $qb->setFirstResult($offset);

        $qb->orderBy('entity.createdAt', $order);
        $qb->addOrderBy('entity.id', $order);

        if (!empty($criteria)) {
            $i = 0;
            foreach ($criteria as $field => $value) {
                $alias = 'c_' . $i++;
                $qb->andWhere("entity.$field = :$alias");
                $qb->setParameter($alias, $value);
            }
        }


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
