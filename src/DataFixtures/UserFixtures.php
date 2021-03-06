<?php

namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    const COUNT = 25;

    private $userPasswordEncorder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncorder = $userPasswordEncoder;
    }

    public function load(ObjectManager $manager)
    {

        $faker = Factory::create("fr_FR");

        // personnal user
        $user = new User();

        $user->setFirstname('admin')
            ->setLastname('admin')
            ->setEmail('admin@admin.com')
            ->addRole('ROLE_ADMIN')
            ->removeRole('ROLE_USER')
            ->setAddress($faker->address)
            ->setZipcode($faker->postcode)
            ->setCountry($faker->country)
            ->setPassword($this->userPasswordEncorder->encodePassword(
                $user,

                'password'
            ));

        $manager->persist($user);

        // others users
        for ($i = 0; $i < self::COUNT; $i++) {
            $user = new User();

            $user->setFirstname($faker->firstName)
                ->setLastname($faker->lastName)
                ->setEmail($faker->email)
                ->setAddress($faker->address)
                ->setZipcode($faker->postcode)
                ->setCountry($faker->country)
                ->setPassword($this->userPasswordEncorder->encodePassword(
                    $user,

                    'password'
                ));

            $manager->persist($user);
            $this->addReference('user' .$i, $user);
        }

        $manager->flush();
    }
}
