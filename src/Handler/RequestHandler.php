<?php

namespace App\Handler;

use App\Entity\Category;
use App\Entity\EntityInterface;
use App\Entity\Message;
use App\Entity\Post;
use App\Entity\Thread;
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
            $this->getFilters($class, $request)
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
     * Todo: Use rights to determine public filters
     * Todo: Static getFilters() in entities (enforced by EntityInterface)
     *
     * @param string $class
     * @param Request $request
     *
     * @return array
     */
    protected function getFilters(string $class, Request $request): array
    {
        $publicFilters = array_merge(
            ['id'],
            (function () use ($class): array {
                switch ($class) {
                    case Category::class:
                        return ['rolePlay', 'slug', 'title', 'description'];
                    case Message::class:
                        return ['from', 'to'];
                    case Post::class:
                        return ['thread', 'author', 'protagonist'];
                    case Thread::class:
                        return ['category', 'title', 'slug'];
                    case User::class:
                        return ['username', 'slug', 'enabled'];
                }
            })()
        );

        return array_filter(
            $request->query->all(), function (string $key) use ($publicFilters): bool {
                return in_array($key, $publicFilters, true);
            },
            ARRAY_FILTER_USE_KEY
        );
    }
}
