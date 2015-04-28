<?php

namespace TimeDude\Bundle\TimeDudeBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Config\Definition\Exception\Exception;
use TimeDude\Bundle\TimeDudeBundle\Entity\Game;

class LoadDefaultGameData extends AbstractFixture implements OrderedFixtureInterface {

    public function getOrder() {
        return 1;
    }

    public function load(ObjectManager $manager) {

        $game = new Game();

        $game->setName('Time Dude');
        $game->setDeveloper("REEA");
        $game->setAppId('net.reea.TimeDude');
        $game->setGcmapikey('AIzaSyD0SWi_s_gdWIgfWLZOVxoXYiAGOudTKQE');
        $manager->persist($game);
        $manager->flush();

        echo 'TimeDude Game loaded.';
    }

}
