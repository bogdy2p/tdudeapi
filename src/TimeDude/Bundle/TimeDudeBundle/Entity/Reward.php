<?php

namespace TimeDude\Bundle\TimeDudeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TimeDude\Bundle\TimeDudeBundle\Entity\TimeDudeUser;

/**
 * Reward
 *
 * @ORM\Table(name="Rewards")
 * @ORM\Entity(repositoryClass="TimeDude\Bundle\TimeDudeBundle\Entity\RewardRepository")
 */
class Reward {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="game_id", type="integer")
     */
    private $gameId;

    /**
     * @var integer
     *
     * @ORM\Column(name="ammount", type="integer")
     */
    private $ammount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var User
     * 
     * @ORM\ManyToOne(targetEntity="TimeDude\Bundle\TimeDudeBundle\Entity\TimeDudeUser", inversedBy="rewards")
     * @ORM\JoinColumn(name="user_id",referencedColumnName="id")
     * 
     */
    protected $user;

    /**
     * @var RewardType 
     * 
     * @ORM\ManyToOne(targetEntity="TimeDude\Bundle\TimeDudeBundle\Entity\RewardType", inversedBy="rewards")
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
     * Set gameId
     *
     * @param integer $gameId
     * @return Reward
     */
    public function setGameId($gameId)
    {
        $this->gameId = $gameId;

        return $this;
    }

    /**
     * Get gameId
     *
     * @return integer 
     */
    public function getGameId()
    {
        return $this->gameId;
    }

    /**
     * Set ammount
     *
     * @param integer $ammount
     * @return Reward
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
     * Set date
     *
     * @param \DateTime $date
     * @return Reward
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set user
     *
     * @param \TimeDude\Bundle\TimeDudeBundle\Entity\TimeDudeUser $user
     * @return Reward
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
     * @return Reward
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
