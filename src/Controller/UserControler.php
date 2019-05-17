<?php

namespace App\Controller;

use App\Entity\User;
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
     * @param Request $request
     *
     * @return array
     */
    public function index(Request $request): array
    {
        // Todo
        return $this->getDoctrine()->getRepository(User::class)->findBy(
            [], [], 100
        );
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

    /**
     * @Rest\Route("/{slug}", name="users.one", methods={"GET"})
     *
     * @param string $slug
     *
     * @return User|null
     */
    public function one(string $slug): ?User
    {
        return $this->getDoctrine()->getRepository(User::class)->findOneBy([
            'slug' => $slug
        ]);
    }
}
