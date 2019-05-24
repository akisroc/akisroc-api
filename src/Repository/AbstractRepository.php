<?php

namespace App\Repository;

use App\Entity\EntityInterface;
use Doctrine\ORM\EntityRepository;

/**
 * Class AbstractRepository
 * @package App\Repository
 */
abstract class AbstractRepository extends EntityRepository
{
    private const EDGE_FIRST = 0;
    private const EDGE_LAST = 1;

    /**
     * @param array $criteria
     *
     * @return EntityInterface|null
     */
    public function findFirstBy(array $criteria = []): ?EntityInterface
    {
        return $this->findEdgeElementBy(self::EDGE_FIRST, $criteria);
    }

    /**
     * @param array $criteria
     *
     * @return EntityInterface|null
     */
    public function findLastBy(array $criteria = []): ?EntityInterface
    {
        return $this->findEdgeElementBy(self::EDGE_LAST, $criteria);
    }

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

    /**
     * @param int $edge
     * @param string[] $criteria
     *
     * @return EntityInterface|null
     */
    private function findEdgeElementBy(int $edge, array $criteria = []): ?EntityInterface
    {
        $qb = $this->createQueryBuilder('entity');

        $qb->select('entity');

        if (!empty($criteria)) {
            $i = 0;
            foreach ($criteria as $field => $value) {
                $alias = 'c_' . $i++;
                $qb->andWhere("entity.$field = :$alias");
                $qb->setParameter($alias, $value);
            }
        }

        $order = (function () use ($edge) {
            switch ($edge) {
                case self::EDGE_FIRST:
                    return 'ASC';
                case self::EDGE_LAST:
                    return 'DESC';
                default: throw new \RuntimeException("Unknown edge $edge");
            }
        })();
        $qb->orderBy('entity.createdAt', $order);
        $qb->addOrderBy('entity.id', $order);

        $qb->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
