<?php

namespace TimeDude\Bundle\TimeDudeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TimeDude\Bundle\UserBundle\Entity\User;

/**
 * Coin
 *
 * @ORM\Table(name="Coins")
 * @ORM\Entity(repositoryClass="TimeDude\Bundle\TimeDudeBundle\Entity\CoinRepository")
 */
class Coin
{
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
     * @ORM\ManyToOne(targetEntity="TimeDude\Bundle\UserBundle\Entity\User", inversedBy="coins")
     * @ORM\JoinColumn(name="user_id",referencedColumnName="id")
     * 
     */
     protected $user;
    

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
     * @return Coin
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
     * @return Coin
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
     * @return Coin
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
     * @param \TimeDude\Bundle\UserBundle\Entity\User $user
     * @return Coin
     */
    public function setUser(\TimeDude\Bundle\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \TimeDude\Bundle\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
