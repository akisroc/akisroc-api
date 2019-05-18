<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\Argon2iPasswordEncoder;

/**
 * Class UserFixtures
 * @package App\DataFixtures
 */
class UserFixtures extends Fixture
{
    public const USER_COUNT = 200;

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $encoder = new Argon2iPasswordEncoder();

        $faker = Factory::create();

        $users = [
            ['username' => 'admin', 'email' => 'admin@akisroc-jdr.fr', 'password' => 'admin', 'roles' => ['ROLE_USER', 'ROLE_ADMIN']],
            ['username' => 'user', 'email' => 'user@akisroc-jdr.fr', 'password' => 'user', 'roles' => ['ROLE_USER']],
        ];
        for ($i = 0; $i < self::USER_COUNT; ++$i) {
            $users[] = [
                'username' => $faker->unique()->firstName,
                'email' => $faker->unique()->safeEmail,
                'password' => 'password',
                'roles' => ['ROLE_USER']
            ];
        }

        for ($i = 0, $max = count($users); $i < $max; ++$i) {
            $user = new User();
            $user->setUsername($users[$i]['username']);
            $user->setEmail($users[$i]['email']);
            $user->setPassword(
                $encoder->encodePassword($users[$i]['password'], $user->getSalt())
            );
            $user->setRoles($users[$i]['roles']);
            $user->setEnabled($faker->boolean(92));
            $this->setReference("user_$i", $user);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
