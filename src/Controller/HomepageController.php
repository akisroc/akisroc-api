<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Place;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomepageController
 * @package App\Controller
 */
class HomepageController extends AbstractController
{
    /**
     * @Route("/")
     *
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function index(EntityManagerInterface $em): Response
    {
        $categoryRepository = $em->getRepository(Category::class);
        $placeRepository = $em->getRepository(Place::class);

        return $this->render('homepage.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'places' => $placeRepository->findAll()
        ]);
    }
}
