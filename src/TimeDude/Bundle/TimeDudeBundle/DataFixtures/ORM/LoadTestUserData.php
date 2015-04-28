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

//        $user->setGoogleUid('110701299456394365841');
//        $registrationId = 'APA91bG_-Dkxjfh-6IxOiw6bJPs1KNs3Brw_Yh_lZ4b2TDBsurhe_fkWO6sj3LX-6QU0T77BOB1SJiUKuSLgy4GGKp_U0hKVMQ4v7z_mTPepN8dEMs3WXi9-j2m8BUAcmMa9LsiGfPpiqy40kOxXJ0FFjmX3ZX8XWA';
//        $user->setRegistrationId($registrationId);
//        $user->setEmail('testmail@test.com');
//        $user->setName('thename');
//
//        $manager->persist($user);
//        $manager->flush();
//
//        echo 'Default User loaded.';
    }

}
