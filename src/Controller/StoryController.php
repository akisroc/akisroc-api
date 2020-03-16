<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Story;
use App\Form\EpisodeType;
use App\ViewDataGatherer\StoryIndexDataGatherer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class StoryController
 * @package App\Controller
 */
class StoryController extends AbstractController
{
    /**
     * @Route(
     *     "/story/{slug}/{page}",
     *     name="story.index",
     *     requirements={"page"="[1-9]\d*"},
     *     defaults={"page"=1}
     * )
     *
     * @param StoryIndexDataGatherer $dataGatherer
     * @param string $slug
     * @param int $page
     *
     * @return Response
     */
    public function index(
        StoryIndexDataGatherer $dataGatherer,
        string $slug,
        int $page
    ): Response {

        return $this->render(
            'story/index.html.twig',
            $dataGatherer->gatherData($slug, $page, 10)
        );
    }

    /**
     * @Route(
     *     "/story/{slug}/add-episode",
     *     name="story.add_episode"
     * )
     *
     * @param Story $story
     * @param Request $request
     *
     * @return Response
     */
    public function addEpisode(Story $story, Request $request): Response
    {
        $episode = new Episode($story);
        $form = $this->createForm(EpisodeType::class, $episode);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($episode);
            $em->flush();

            return $this->redirectToRoute(
                'story.index',
                ['slug' => $story->slug]
            );
        }

        return $this->render('story/add_episode.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
