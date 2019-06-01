<?php

namespace App\DataFixtures;

use App\Entity\Place;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

/**
 * Class PlaceFixtures
 * @package App\DataFixtures
 */
class PlaceFixtures extends Fixture
{
    public const PLACE_COUNT = 6;

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < self::PLACE_COUNT; ++$i) {
            $place = new Place();
            $place->setTitle($faker->unique()->colorName . ' ' . $faker->word);
            $place->setDescription($faker->sentence(9));
            $place->setImage($faker->imageUrl());

            $this->setReference("place_$i", $place);

            $manager->persist($place);
        }

        $manager->flush();
    }
}
