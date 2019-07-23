<?php

namespace App\DataFixtures;

use App\Entity\Job;
use App\Entity\Team;
use App\Entity\User;
use App\Entity\VolunteerAvailability;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class JobFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {

        $nbTeam = count(TeamFixtures::TEAMSNAME) - 1;
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < $nbTeam; $i++) {

            /** @var VolunteerAvailability $volunteerAvailability */
            $volunteerAvailability = $this->getReference('volunteerAvailability' . rand(0, VolunteerAvailabilityFixtures::COUNT - 1));
            /** @var User $user */
            $user = $volunteerAvailability->getUser();
            /** @var Team $team */
            $team = $this->getReference('team' . $nbTeam);

            $startDate = $faker->dateTimeBetween($volunteerAvailability->getStartDate(), $volunteerAvailability->getEndDate())->setTime(rand(0, 10), 0, 0);
            $endDate = $faker->dateTimeBetween($startDate, $volunteerAvailability->getEndDate())->setTime(rand(11, 23), 0, 0);

            $job = new Job();
            $job->setTitle(strtoupper($team->getName()) . ': ' . $user->getLastname() . ' ' . $user->getFirstname())
                ->setUser($user)
                ->setTeam($team)
                ->setStartDate($startDate)
                ->setEndDate($endDate)
                ->setBackgroundColor($faker->hexColor);


            $manager->persist($job);
            $this->addReference('job' . $i, $job);
        }

        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return [
            VolunteerAvailabilityFixtures::class,
            UserFixtures::class,
            TeamFixtures::class,
        ];
    }
}
