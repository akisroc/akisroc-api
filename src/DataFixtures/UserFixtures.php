<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\Argon2iPasswordEncoder;

/**
 * Class UserFixtures
 * @package App\DataFixtures
 */
class UserFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $encoder = new Argon2iPasswordEncoder();

        $users = [
            ['username' => 'admin', 'email' => 'admin@akisroc-jdr.fr', 'password' => 'admin', 'roles' => ['ROLE_USER', 'ROLE_ADMIN']],
            ['username' => 'user', 'email' => 'user@akisroc-jdr.fr', 'password' => 'admin', 'roles' => ['ROLE_USER']],
            ['username' => 'Franck Saucisse', 'email' => 'franck@saucisse.net', 'password' => 'franck', 'roles' => ['ROLE_USER']],
            ['username' => 'pandawarrior666', 'email' => 'panda@warrir.com', 'password' => 'panda', 'roles' => ['ROLE_USER']],
            ['username' => 'Jean Deau', 'email' => 'jean@deau.dev', 'password' => 'jean', 'roles' => ['ROLE_USER']],
            ['username' => 'Johnny', 'email' => 'johnny@johnny.fr', 'password' => 'johnny', 'roles' => ['ROLE_USER']]
        ];

        for ($i = 0, $max = count($users); $i < $max; ++$i) {
            $user = new User();
            $user->setUsername($users[$i]['username']);
            $user->setEmail($users[$i]['email']);
            $user->setPassword(
                $encoder->encodePassword($users[$i]['password'], $user->getSalt())
            );
            $user->setRoles($users[$i]['roles']);
            $this->setReference("user_$i", $user);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
