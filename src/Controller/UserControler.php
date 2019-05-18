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
     * @return  array|int  Integer if "count" query param is true
     */
    public function index(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(User::class);

        if ((bool) $request->query->get('count') === true) {
            return $repository->getCount();
        }

        return $repository->getList(
            (string) $request->query->get('order'),
            (int) $request->query->get('limit'),
            (int) $request->query->get('offset')
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
