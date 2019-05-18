<?php

namespace App\Controller;

use App\Entity\User;
use App\Handler\RequestHandler;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Rest\Route("/users")
 */
class UserControler extends AbstractFOSRestController
{
    /**
     * @Rest\Route("/", name="users.index", methods={"GET"})
     *
     * @param Request        $request
     * @param RequestHandler $handler
     *
     * @return  array|int  Integer if "count" query param is true
     */
    public function index(Request $request, RequestHandler $handler)
    {
        return $handler->handleIndexRequest($request, User::class);
    }

    /**
     * @Rest\Route("/{slug}", name="users.one", methods={"GET"})
     *
     * @param string $slug
     * @param RequestHandler $handler
     *
     * @return User|null
     */
    public function one(string $slug, RequestHandler $handler): ?User
    {
        return $handler->handleOneRequest(User::class, 'slug', $slug);
    }

    /**
     * @Rest\Route("/me", name="users.me", methods={"GET"})
     *
     * @return User|null
     */
    public function me(): ?User
    {
        return $this->getUser();
    }
}
