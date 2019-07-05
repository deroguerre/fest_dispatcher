<?php

namespace App\DataFixtures;

use App\Entity\Festival;
use App\Entity\User;
use App\Entity\VolunteerAvailability;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class VolunteerAvailabilityFixtures extends Fixture implements DependentFixtureInterface
{
    const COUNT = 15;

    public function load(ObjectManager $manager)
    {

        $faker = Factory::create('fr_FR');
        $nbUsers = UserFixtures::COUNT;

        for ($i = 0; $i < $nbUsers - 10; $i++) {
            $availability = new VolunteerAvailability();

            /** @var Festival $festival */
            $festival = $this->getReference('festival' . rand(0, FestivalFixtures::COUNT - 1));
            /** @var User $user */
            $user = $this->getReference('user' . $i);

            $nbRand = rand(0,23);

            $startDate = $faker->dateTimeBetween($festival->getStartDate(), $festival->getEndDate())->setTime($nbRand,0,0);
            $endDate = $faker->dateTimeBetween($startDate, $festival->getEndDate())->setTime(rand($nbRand,23),0,0);

            $availability
                ->setFestival($festival)
                ->setUser($user)
                ->setStartDate($startDate)
                ->setEndDate($endDate);


            $manager->persist($availability);
            $this->addReference('volunteerAvailability' . $i, $availability);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            FestivalFixtures::class
        ];
    }
}
