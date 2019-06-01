<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

/**
 * Class CategoryFixtures
 * @package App\DataFixtures
 */
class CategoryFixtures extends Fixture
{
    public const CATEGORY_COUNT = 6;

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < self::CATEGORY_COUNT; ++$i) {
            $category = new Category();
            $category->setTitle($faker->unique()->colorName . ' ' . $faker->word);
            $category->setDescription($faker->sentence(9));
            $category->setImage($faker->imageUrl());

            $this->setReference("category_$i", $category);

            $manager->persist($category);
        }

        $manager->flush();
    }
}
