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

        $i = 0;
        do {
            /** @var Category $category */
            $category = $this->getReference(
                'category_' . $faker->numberBetween(0, CategoryFixtures::CATEGORY_COUNT - 1)
            );

            $thread = new Thread();
            $thread->setTitle(
                $faker->colorName . ' ' . $faker->words(2, true) . ' ' .$faker->month
            );
            $thread->setCategory($category);
            for ($j = 0; $j < $faker->numberBetween(1, 33); ++$j) {
                /** @var User $author */
                $author = $this->getReference(
                    'user_' . $faker->numberBetween(0, UserFixtures::USER_COUNT)
                );
                /** @var Protagonist $protagonist */
                $protagonist = $category->isRolePlay()
                    ? $this->getReference(
                        'protagonist_' . $faker->numberBetween(0, ProtagonistFixtures::PROTAGONIST_COUNT - 1)
                    )
                    : null
                ;
                $post = new Post();
                $post->setThread($thread);
                $post->setAuthor($author);
                $post->setProtagonist($protagonist);
                $post->setContent($faker->text(2000));
                $thread->addPost($post);
                --$remainingPosts;
                $this->setReference("post_$j", $post);
                $manager->persist($post);
            }
            ++$i;
            $this->setReference("thread_$i", $thread);
            $manager->persist($thread);
            $manager->flush();
        } while ($remainingPosts > 0);
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            UserFixtures::class, CategoryFixtures::class
        ];
    }
}
