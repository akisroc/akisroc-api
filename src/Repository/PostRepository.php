<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Post;
use Doctrine\ORM\EntityRepository;

/**
 * Class PostRepository
 * @package App\Repository
 */
class PostRepository extends EntityRepository
{
    /**
     * @param Post $post
     *
     * @return Category
     */
    public function getCategory(Post $post): Category
    {
        $qb = $this->createQueryBuilder('post');

        $qb->select('category');

        $qb->innerJoin('post.thread', 'thread');
        $qb->innerJoin('thread.category', 'category');

        $qb->where('post = :post');
        $qb->setParameter('post', $post);

        $query = $qb->getQuery();

        return $query->getSingleResult();
    }

    /**
     * @param Post $post
     *
     * @return bool
     */
    public function isRolePlay(Post $post): bool
    {
        $qb = $this->createQueryBuilder('post');

        $qb->select('category.rolePlay');

        $qb->innerJoin('post.thread', 'thread');
        $qb->innerJoin('thread.category', 'category');

        $qb->where('post = :post');
        $qb->setParameter('post', $post);

        $query = $qb->getQuery();

        return $query->getSingleScalarResult();
    }
}
