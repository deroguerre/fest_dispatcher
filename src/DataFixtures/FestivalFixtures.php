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

            $startDate = $faker->dateTimeInInterval('-1 days', '+1 days')->setTime(0,0,0);
            $endDate = $faker->dateTimeInInterval('+2 days', '+4 days')->setTime(0,0,0);

            $festival->setName($faker->colorName . '_festival')
                ->setStartDate($startDate)
                ->setEndDate($endDate)
                ->setAddress($faker->address)
                ->setZipcode($faker->postcode)
                ->setCountry($faker->country);

            $manager->persist($festival);

            $this->addReference('festival' . $i, $festival);
        }

        $manager->flush();
    }
}
