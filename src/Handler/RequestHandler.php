<?php

namespace App\Handler;

use App\Entity\EntityInterface;
use App\Entity\User;
use App\Repository\AbstractRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RequestHandler
 * @package App\Handler
 */
class RequestHandler
{
    /** @var EntityManagerInterface $em */
    protected $em;

    /**
     * RequestHandler constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param Request $request
     * @param string  $class
     *
     * @return  array|int  Integer if "count" query param is true
     */
    public function handleIndexRequest(Request $request, string $class)
    {
        /** @var AbstractRepository $repository */
        $repository = $this->em->getRepository($class);

        if ((bool) $request->query->get('count') === true) {
            return $repository->getCount();
        }

        return $repository->getList(
            (string) $request->query->get('order'),
            (int) $request->query->get('limit'),
            (int) $request->query->get('offset'),
            []
        );
    }

    /**
     * @param string $class
     * @param string $field
     * @param string $value
     *
     * @return EntityInterface|null
     */
    public function handleOneRequest(string $class, string $field, string $value): ?EntityInterface
    {
        $repository = $this->em->getRepository($class);

        return $repository->findOneBy([$field => $value]);
    }

    /**
     * @param string $class
     * @return array
     */
    protected function getSelects(string $class): array
    {
        $selects = ['id', 'createdAt', 'updatedAt'];
        switch ($class) {
            case User::class:
                $selects = array_merge($selects, ['avatar', 'username', 'email', 'slug', 'enabled']);
                break;
        }

        return $selects;
    }
}
