<?php

namespace App\DataFixtures;

use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class TeamFixtures extends Fixture implements DependentFixtureInterface

{
    const COUNT  = 10;
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Factory::create("fr_FR");

        $teamname = [
            'parking',
            'bar',
            'green team',
            'billeterie',
            'securite',
            'acces',
            'montage',
            'demontage'
        ];

        for ($i = 0; $i < count ($teamname); $i++) {
            $team = new Team();

            $team->setName($teamname[$i])
                ->setDescription($faker->text)
                ->setBackgroundColor($faker->hexColor)
                ->setNeededVolunteers(rand(2,19))
                ->addManager($this->getReference('user'. rand(0,UserFixtures::COUNT-1)));

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
