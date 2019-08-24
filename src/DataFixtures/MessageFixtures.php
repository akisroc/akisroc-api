<?php

namespace App\DataFixtures;

use App\Entity\Message;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

/**
 * Class MessageFixtures
 * @package App\DataFixtures
 */
class MessageFixtures extends Fixture implements DependentFixtureInterface
{
    public const MESSAGE_COUNT = 4000;

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Todo: Cannot send message to self
        for ($i = 0; $i < self::MESSAGE_COUNT; ++$i) {
            /** @var User $from */
            $from = $this->getReference(
                'user_' . $faker->numberBetween(0, UserFixtures::USER_COUNT - 1)
            );
            /** @var User $to */
            $to = $this->getReference(
                'user_' . $faker->numberBetween(0, UserFixtures::USER_COUNT - 1)
            );
            $message = new Message();
            $message->from = $from;
            $message->to = $to;
            $message->content = $faker->text(400);

            $this->setReference("message_$i", $message);
            $manager->persist($message);
        }

        $manager->flush();
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
