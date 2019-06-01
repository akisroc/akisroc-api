<?php

namespace App\Controller;

use App\Entity\Place;
use App\Handler\RequestHandler;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Rest\Route("/places")
 */
class PlaceController extends AbstractFOSRestController
{
    /**
     * @Rest\Get("/", name="places.index")
     *
     * @param Request $request
     * @param RequestHandler $handler
     *
     * @return  array|int  Integer if "count" query param is true
     */
    public function index(Request $request, RequestHandler $handler)
    {
        return $handler->handleIndexRequest($request, Place::class);
    }

    /**
     * @Rest\Get("/{slug}", name="places.one")
     *
     * @param string $slug
     * @param RequestHandler $handler
     *
     * @return Place
     */
    public function one(string $slug, RequestHandler $handler): Place
    {
        $place = $handler->handleOneRequest(Place::class, 'slug', $slug);
        if (!$place) {
            throw $this->createNotFoundException();
        }
        return $place;
    }
}
