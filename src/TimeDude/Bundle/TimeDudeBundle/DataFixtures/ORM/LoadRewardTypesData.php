<?php

namespace TimeDude\Bundle\TimeDudeBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Config\Definition\Exception\Exception;
use TimeDude\Bundle\TimeDudeBundle\Entity\RewardType;

class LoadRewardTypesData extends AbstractFixture implements OrderedFixtureInterface {

    public function getOrder() {
        return 1;
    }

    public function load(ObjectManager $manager) {

        $reward_type_names = array(
            'coin', //'type2', 'type3'
        );

        foreach ($reward_type_names as $reward_name) {

            $rewardType = new RewardType();

            $rewardType->setName($reward_name);

            $manager->persist($rewardType);
        }
        $manager->flush();

        echo 'Reward type names loaded.';
    }

}
