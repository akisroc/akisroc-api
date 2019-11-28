<?php

namespace App\Controller;

use App\Entity\Place;
use App\Entity\Story;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PlaceController
 * @package App\Controller
 */
class PlaceController extends AbstractController
{
    /**
     * @Route(
     *     "/place/{slug}/{page}",
     *     name="place.index",
     *     requirements={"page"="[1-9]\d*"},
     *     defaults={"page"=1}
     * )
     *
     * @param EntityManagerInterface $em
     * @param string $slug
     * @param int $page
     *
     * @return Response
     */
    public function index(EntityManagerInterface $em, string $slug, int $page): Response
    {
        $nbItemsPerPage = 20;

        $placeRepository = $em->getRepository(Place::class);
        $place = $placeRepository->findOneBy(['slug' => $slug]);

        if (!$place) {
            throw $this->createNotFoundException();
        }

        $storyRepository = $em->getRepository(Story::class);
        $stories = $storyRepository->getPaginatedList(
            $page,
            $nbItemsPerPage,
            ['place' => $place],
            ['createdAt' => 'DESC'],
            true
        );

        return $this->render('place/index.html.twig', [
            'place' => $place,
            'stories' => $stories,
            'pagination' => [
                'page' => $page,
                'nbPages' => ceil($stories->count() / $nbItemsPerPage)
            ]
        ]);
    }
}
