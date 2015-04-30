<?php

namespace TimeDude\Bundle\TimeDudeBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Config\Definition\Exception\Exception;
use TimeDude\Bundle\UserBundle\Entity\User;

class LoadAPIUsersData extends AbstractFixture implements OrderedFixtureInterface {

    public function getOrder() {
        return 1;
    }

    public function load(ObjectManager $manager) {

        $api_users = array(
            array('support@inappworld.com', 'inappworld', 'In App World', '6123986c-dd7a-4496-819a-17cd1af38e3d', 'passwordzzz'),
            array('timedude@reea.net', 'reea', 'reea', 'bf4bff30-4664-48f9-87d5-fb78520df136', 'passwordzzz'),
        );

        foreach ($api_users as $user) {

            $email = $user[0];
            $username = $user[1];
            $name = $user[2];
            $apiKey = $user[3];
            $password = $user[4];

            $user = new User();
            $user->setEmail($email);
            $user->setUsername($username);
            $user->setName($name);
            $user->setApiKey($apiKey);
            $user->setPassword(md5($password));

            $user->setEnabled(true);

            $manager->persist($user);
        }
        $manager->flush();
    }

}
