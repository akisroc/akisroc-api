<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Post;
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
    public const POST_COUNT = 500;

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $remainingPosts = self::POST_COUNT;

        $i = 0;
        $date = \DateTime::createFromFormat('Ymd', '20180101');
        $interval = \DateInterval::createFromDateString('1 hour + 3 minutes');
        do {
            /** @var Category $category */
            $category = $this->getReference(
                'category_' . $faker->numberBetween(0, CategoryFixtures::CATEGORY_COUNT - 1)
            );

            $thread = new Thread();
            $thread->title =
                $faker->colorName . ' ' . $faker->words(2, true) . ' ' .$faker->numberBetween(1, 999)
            ;
            $thread->category = $category;
            for ($j = 0; $j < $faker->numberBetween(1, 33); ++$j) {
                $date = clone $date->add($interval);
                /** @var User $author */
                $author = $this->getReference(
                    'user_' . $faker->numberBetween(0, UserFixtures::USER_COUNT)
                );
                $post = new Post();
                $post->thread = $thread;
                $post->author = $author;
                $post->content = $faker->text(2000);
                $post->createdAt = $date;
                $post->updatedAt = $date;
                $thread->posts->add($post);
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
