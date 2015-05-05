<?php

namespace TimeDude\Bundle\TimeDudeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TimeDude\Bundle\TimeDudeBundle\Entity\TimeDudeUser;

/**
 * Ammount
 *
 * @ORM\Table(name="Ammounts")
 * @ORM\Entity
 */
class Ammount {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Game 
     * 
     * @ORM\ManyToOne(targetEntity="TimeDude\Bundle\TimeDudeBundle\Entity\Game", inversedBy="ammounts")
     * @ORM\JoinColumn(name="game",referencedColumnName="id", nullable=false)
     */
    private $game;

    /**
     * @var integer
     *
     * @ORM\Column(name="ammount", type="integer")
     */
    private $ammount;

    /**
     * @var User
     * 
     * @ORM\ManyToOne(targetEntity="TimeDude\Bundle\TimeDudeBundle\Entity\TimeDudeUser", inversedBy="ammounts")
     * @ORM\JoinColumn(name="user_id",referencedColumnName="id")
     * 
     */
    protected $user;

    /**
     * @var RewardType 
     * 
     * @ORM\ManyToOne(targetEntity="TimeDude\Bundle\TimeDudeBundle\Entity\RewardType", inversedBy="ammounts")
     * @ORM\JoinColumn(name="reward_type",referencedColumnName="id", nullable=false)
     */
    private $rewardtype;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set ammount
     *
     * @param integer $ammount
     *
     * @return Ammount
     */
    public function setAmmount($ammount)
    {
        $this->ammount = $ammount;

        return $this;
    }

    /**
     * Get ammount
     *
     * @return integer
     */
    public function getAmmount()
    {
        return $this->ammount;
    }

    /**
     * Set game
     *
     * @param \TimeDude\Bundle\TimeDudeBundle\Entity\Game $game
     *
     * @return Ammount
     */
    public function setGame(\TimeDude\Bundle\TimeDudeBundle\Entity\Game $game)
    {
        $this->game = $game;

        return $this;
    }

    /**
     * Get game
     *
     * @return \TimeDude\Bundle\TimeDudeBundle\Entity\Game
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * Set user
     *
     * @param \TimeDude\Bundle\TimeDudeBundle\Entity\TimeDudeUser $user
     *
     * @return Ammount
     */
    public function setUser(\TimeDude\Bundle\TimeDudeBundle\Entity\TimeDudeUser $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \TimeDude\Bundle\TimeDudeBundle\Entity\TimeDudeUser
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set rewardtype
     *
     * @param \TimeDude\Bundle\TimeDudeBundle\Entity\RewardType $rewardtype
     *
     * @return Ammount
     */
    public function setRewardtype(\TimeDude\Bundle\TimeDudeBundle\Entity\RewardType $rewardtype)
    {
        $this->rewardtype = $rewardtype;

        return $this;
    }

    /**
     * Get rewardtype
     *
     * @return \TimeDude\Bundle\TimeDudeBundle\Entity\RewardType
     */
    public function getRewardtype()
    {
        return $this->rewardtype;
    }
}
