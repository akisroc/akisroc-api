<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Protagonist;
use App\Entity\Thread;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

/**
 * Class ThreadFixtures
 * @package App\DataFixtures
 */
class ThreadFixtures extends Fixture implements DependentFixtureInterface
{
    public const POST_COUNT = 10000;

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $remainingPosts = self::POST_COUNT;

        do {
            /** @var User $author */
            $author = $this->getReference(
                'user_' . $faker->numberBetween(0, UserFixtures::USER_COUNT)
            );
            /** @var Protagonist $protagonist */
            $protagonist = null; // Todo if isRolePlay
            /** @var Category $category */
            $category = $this->getReference(
                'category_' . $faker->numberBetween(0, CategoryFixtures::CATEGORY_COUNT)
            );

            $thread = new Thread();
            for ($j = 0; $j < $faker->numberBetween(1, 33); ++$j) {
                $post = new Post();
                $post->setAuthor($author);
                $post->setProtagonist($protagonist);
            }
        } while ($remainingPosts > 0);
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            UserFixtures::class, Category::class
        ];
    }
}
