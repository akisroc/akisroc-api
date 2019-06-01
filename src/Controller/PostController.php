<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Thread;
use App\Handler\RequestHandler;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Rest\Route("/posts")
 */
class PostController extends AbstractFOSRestController
{
    /**
     * @Rest\Get("/", name="posts.index")
     */
    public function index(Request $request, RequestHandler $handler)
    {
        return $handler->handleIndexRequest($request, Post::class);
    }

    /**
     * @Rest\Get("/last/{slug}", name="posts.last")
     *
     * @param string $slug
     * @param RequestHandler $handler
     *
     * @return Post
     */
    public function last(string $slug, RequestHandler $handler): Post
    {
        $thread = $handler->handleOneRequest(Thread::class, 'slug', $slug);
        if (!$thread) {
            throw $this->createNotFoundException("Uknown thread \"$slug\".");
        }

        $post = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findLastBy(['thread' => $thread]);

        if (!$post) {
            throw new \UnexpectedValueException(
                'No post found for given thread. This should really not happen. :-o'
            );
        }

        return $post;
    }
}
