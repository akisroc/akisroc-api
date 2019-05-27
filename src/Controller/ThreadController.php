<?php

namespace App\Controller;

use App\Entity\Thread;
use App\Handler\RequestHandler;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Rest\Route("/threads")
 */
class ThreadController extends AbstractFOSRestController
{
    /**
     * @Rest\Get("/", name="threads.index")
     *
     * @param Request $request
     * @param RequestHandler $handler
     *
     * @return  array|int  Integer if "count" query param is true
     */
    public function index(Request $request, RequestHandler $handler)
    {
        return $handler->handleIndexRequest($request, Thread::class);
    }

    /**
     * @Rest\Get("/{slug}", name="threads.one")
     *
     * @param string $slug
     * @param RequestHandler $handler
     *
     * @return Thread
     */
    public function one(string $slug, RequestHandler $handler): Thread
    {
        $thread = $handler->handleOneRequest(Thread::class, 'slug', $slug);
        if (!$thread) {
            throw $this->createNotFoundException();
        }
        return $thread;
    }
}
