<?php

declare(strict_types=1);

namespace App\ViewDataGatherer;

use App\Entity\Episode;
use App\Entity\Story;
use App\Utils\PaginationHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class StoryIndexDataGatherer
 * @package App\ViewDataGatherer
 */
final class StoryIndexDataGatherer extends AbstractViewDataGatherer
{
    /** @var PaginationHelper $paginationHelper */
    private PaginationHelper $paginationHelper;

    /**
     * StoryIndexDataGatherer constructor.
     * @param EntityManagerInterface $em
     * @param PaginationHelper $paginationHelper
     */
    public function __construct(
        EntityManagerInterface $em,
        PaginationHelper $paginationHelper
    ) {
        parent::__construct($em);

        $this->paginationHelper = $paginationHelper;
    }

    public function gatherData(string $slug, int $page, ?int $maxResults): array
    {
        $story = $this->em
            ->getRepository(Story::class)
            ->findOneBy(['slug' => $slug]);

        if (!$story) {
            throw new NotFoundHttpException();
        }

        $episodesQb = $this->em->createQueryBuilder();

        $episodesQb->select('episode', 'protagonist', 'user');

        $episodesQb->from(Episode::class, 'episode');

        $episodesQb->where('episode.story = :story');
        $episodesQb->setParameter('story', $story);

        $episodesQb->innerJoin('episode.protagonist', 'protagonist');
        $episodesQb->innerJoin('protagonist.user', 'user');

        $episodesQb->orderBy('episode.createdAt', 'ASC');

        $this->paginationHelper->paginateQb($episodesQb, $page, $maxResults);

        $episodesPaginator = $this->paginationHelper->getPaginatorFromQb(
            $episodesQb
        );

        return [
            'story' => $story,
            'episodes' => $episodesPaginator,
            'pagination' => [
                'page' => $page,
                'nbPages' => $this->paginationHelper->calculateNumberOfPages(
                    $episodesPaginator, $maxResults
                )
            ]
        ];
    }
}