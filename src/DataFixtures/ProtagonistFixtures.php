<?php

namespace App\DataFixtures;

use App\Entity\Protagonist;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

/**
 * Class ProtagonistFixtures
 * @package App\DataFixtures
 */
class ProtagonistFixtures extends Fixture implements DependentFixtureInterface
{
    public const PROTAGONIST_COUNT = 800;

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        for ($i = 0; $i < self::PROTAGONIST_COUNT; ++$i) {
            /** @var User $user */
            $user = $this->getReference(
                'user_' . $faker->numberBetween(0, UserFixtures::USER_COUNT - 1)
            );

            $protagonist = new Protagonist();
            $protagonist->user = $user;
            $protagonist->name =
                $faker->firstName . ' ' . $faker->lastName . ' ' . $faker->randomNumber(6)
            ;
            $protagonist->avatar = $faker->imageUrl();
            $protagonist->anonymous = $faker->boolean;

            $this->setReference("protagonist_$i", $protagonist);
            $manager->persist($protagonist);
            $manager->flush();
        }
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            UserFixtures::class
        ];
    }
}
