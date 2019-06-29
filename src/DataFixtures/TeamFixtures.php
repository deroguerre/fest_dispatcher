<?php

namespace App\DataFixtures;

use App\Entity\Team;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class TeamFixtures extends Fixture implements DependentFixtureInterface
{
    const TEAMSNAME = [
        'parking',
        'bar',
        'greenteam',
        'billeterie',
        'securite',
        'acces',
        'montage',
        'demontage'
    ];

    public function load(ObjectManager $manager)
    {

        $faker = Factory::create("fr_FR");

        for ($i = 0; $i < count(self::TEAMSNAME); $i++) {
            $team = new Team();

            /** @var User $teamManager */
            $teamManager = $this->getReference('user' . rand(0, UserFixtures::COUNT - 1));

            $team->setName(self::TEAMSNAME[$i])
                ->setDescription($faker->text)
                ->setBackgroundColor($faker->hexColor)
                ->setNeededVolunteers(rand(2, 19))
                ->addManager($teamManager);

            $manager->persist($team);

            $this->addReference('team' . $i, $team);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}
