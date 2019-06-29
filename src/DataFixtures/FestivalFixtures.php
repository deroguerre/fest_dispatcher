<?php

namespace App\DataFixtures;

use App\Entity\Festival;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class FestivalFixtures extends Fixture
{

    const COUNT = 3;

    public function load(ObjectManager $manager)
    {

        $faker = Factory::create("fr_FR");

        for ($i = 0; $i < self::COUNT; $i++) {
            $festival = new Festival();

            $festival->setName($faker->colorName . '_festival')
                ->setStartDate($faker->dateTimeInInterval('now', '+2 days'))
                ->setEndDate($faker->dateTimeInInterval('+3 days', '+5 days'))
                ->setAddress($faker->address)
                ->setZipcode($faker->postcode)
                ->setCountry($faker->country);

            $manager->persist($festival);

            $this->addReference('festival'.$i, $festival);
        }

        $manager->flush();
    }
}
