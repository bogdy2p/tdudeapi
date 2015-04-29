<?php

namespace TimeDude\Bundle\TimeDudeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Registration
 *
 * @ORM\Table(name="Registrations")
 * @ORM\Entity
 */
class Registration {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var User
     * 
     * @ORM\ManyToOne(targetEntity="TimeDude\Bundle\TimeDudeBundle\Entity\TimeDudeUser", inversedBy="rewards")
     * @ORM\JoinColumn(name="user",referencedColumnName="id")
     * 
     */
    protected $googleuser;

    /**
     * @var Game
     * 
     * @ORM\ManyToOne(targetEntity="TimeDude\Bundle\TimeDudeBundle\Entity\Game", inversedBy="games")
     * @ORM\JoinColumn(name="game_id",referencedColumnName="id")
     * 
     */
    protected $game;

    /**
     * @var string
     *
     * @ORM\Column(name="registration_key", type="string", length=255)
     */
    private $registrationId;

    /**
     * @var integer
     *
     * @ORM\Column(name="game_version", type="integer")
     */
    private $game_version;

   

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
     * Set registrationId
     *
     * @param string $registrationId
     *
     * @return Registration
     */
    public function setRegistrationId($registrationId)
    {
        $this->registrationId = $registrationId;

        return $this;
    }

    /**
     * Get registrationId
     *
     * @return string
     */
    public function getRegistrationId()
    {
        return $this->registrationId;
    }

    /**
     * Set gameVersion
     *
     * @param integer $gameVersion
     *
     * @return Registration
     */
    public function setGameVersion($gameVersion)
    {
        $this->game_version = $gameVersion;

        return $this;
    }

    /**
     * Get gameVersion
     *
     * @return integer
     */
    public function getGameVersion()
    {
        return $this->game_version;
    }

    /**
     * Set googleuser
     *
     * @param \TimeDude\Bundle\TimeDudeBundle\Entity\TimeDudeUser $googleuser
     *
     * @return Registration
     */
    public function setGoogleuser(\TimeDude\Bundle\TimeDudeBundle\Entity\TimeDudeUser $googleuser = null)
    {
        $this->googleuser = $googleuser;

        return $this;
    }

    /**
     * Get googleuser
     *
     * @return \TimeDude\Bundle\TimeDudeBundle\Entity\TimeDudeUser
     */
    public function getGoogleuser()
    {
        return $this->googleuser;
    }

    /**
     * Set game
     *
     * @param \TimeDude\Bundle\TimeDudeBundle\Entity\Game $game
     *
     * @return Registration
     */
    public function setGame(\TimeDude\Bundle\TimeDudeBundle\Entity\Game $game = null)
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
}
