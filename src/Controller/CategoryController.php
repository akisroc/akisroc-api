<?php

namespace App\Controller;

use App\Entity\Category;
use App\Handler\RequestHandler;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Rest\Route("/categories")
 */
class CategoryController extends AbstractFOSRestController
{
    /**
     * @Rest\Get("/", name="categories.index")
     *
     * @param Request $request
     * @param RequestHandler $handler
     *
     * @return  array|int  Integer if "count" query param is true
     */
    public function index(Request $request, RequestHandler $handler)
    {
        return $handler->handleIndexRequest($request, Category::class);
    }

    /**
     * @Rest\Get("/{slug}", name="categories.one")
     *
     * @param string $slug
     * @param RequestHandler $handler
     *
     * @return Category
     */
    public function one(string $slug, RequestHandler $handler): Category
    {
        $category = $handler->handleOneRequest(Category::class, 'slug', $slug);
        if (!$category) {
            throw $this->createNotFoundException();
        }
        return $category;
    }
}
