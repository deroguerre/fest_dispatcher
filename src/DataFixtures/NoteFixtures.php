<?php

namespace App\DataFixtures;

use App\Entity\Festival;
use App\Entity\Note;
use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class NoteFixtures extends Fixture implements DependentFixtureInterface
{
    const COUNT = 10;

    public function load(ObjectManager $manager)
    {

        $faker = Factory::create('fr_FR');
        $nbTeam = count(TeamFixtures::TEAMSNAME) - 1;

        for ($i = 0; $i < self::COUNT; $i++) {

            $note = new Note();
            /** @var Festival $festival */
            $festival = $this->getReference('festival'.rand(0, FestivalFixtures::COUNT - 1));
            /** @var Team $team */
            $team = $this->getReference('team'.rand(0, $nbTeam));

            $note->setTeam($team)
                ->setDescription($faker->text)
                ->setUpdateDate($faker->dateTimeBetween($festival->getStartDate(), $festival->getEndDate()));

            $manager->persist($note);
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
            TeamFixtures::class,
            FestivalFixtures::class,
        ];
    }
}
