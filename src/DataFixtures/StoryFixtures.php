<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use App\Entity\Place;
use App\Entity\Story;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

/**
 * Class StoryFixtures
 * @package App\DataFixtures
 */
class StoryFixtures extends Fixture implements DependentFixtureInterface
{
    public const EPISODE_COUNT = 500;

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $remainingEpisodes = self::EPISODE_COUNT;

        $i = 0;
        $date = \DateTime::createFromFormat('Ymd', '20180101');
        $interval = \DateInterval::createFromDateString('1 hour + 3 minutes');
        do {
            /** @var Place $place */
            $place = $this->getReference(
                'place_' . $faker->numberBetween(0, PlaceFixtures::PLACE_COUNT - 1)
            );

            $story = new Story();
            $story->title =
                $faker->colorName . ' ' . $faker->words(2, true) . ' ' . $faker->numberBetween(1, 999)
            ;
            $story->place = $place;
            for ($j = 0; $j < $faker->numberBetween(1, 33); ++$j) {
                $date = clone $date->add($interval);
                $protagonist = $this->getReference(
                    'protagonist_' . $faker->numberBetween(0, ProtagonistFixtures::PROTAGONIST_COUNT - 1)
                );
                $episode = new Episode();
                $episode->story = $story;
                $episode->protagonist = $protagonist;
                $episode->content = $faker->text(2000);
                $episode->createdAt = $date;
                $episode->updatedAt = $date;
                $story->addEpisode($episode);
                --$remainingEpisodes;
                $this->setReference("episode_$j", $episode);
                $manager->persist($episode);
            }
            ++$i;
            $this->setReference("story_$i", $story);
            $manager->persist($story);
            $manager->flush();
        } while ($remainingEpisodes > 0);
    }

    public function getDependencies(): array
    {
        return [
            ProtagonistFixtures::class, PlaceFixtures::class
        ];
    }
}
