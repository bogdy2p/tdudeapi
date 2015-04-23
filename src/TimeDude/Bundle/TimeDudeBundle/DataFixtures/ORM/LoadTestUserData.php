<?php

namespace TimeDude\Bundle\TimeDudeBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Config\Definition\Exception\Exception;
use TimeDude\Bundle\TimeDudeBundle\Entity\TimeDudeUser;

class LoadTestUserData extends AbstractFixture implements OrderedFixtureInterface {

    public function getOrder() {
        return 1;
    }

    public function load(ObjectManager $manager) {

        $user = new TimeDudeUser();

        $user->setGoogleUid('110701299456394365841');
        $user->setFirstname('fIrStNaMe');
        $user->setLastname('LaStNaMe');

        $manager->persist($user);
        $manager->flush();

        echo 'Default User loaded.';
    }

}
