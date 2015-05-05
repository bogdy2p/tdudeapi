<?php

namespace TimeDude\Bundle\TimeDudeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * Game
 *
 * @ORM\Table(name="Games")
 * @ORM\Entity
 */
class Game {

    public function __construct() {
        $this->ammounts = new ArrayCollection();
        $this->rewardlogs = new ArrayCollection();
        $this->registrations = new ArrayCollection();
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="appId", type="string", length=255, nullable=false)
     */
    private $appId;

    /**
     * @var string
     *
     * @ORM\Column(name="gcmApiKey", type="string", length=255, nullable=false)
     */
    private $gcmapikey;

    /**
     * @var string
     *
     * @ORM\Column(name="developer", type="string", length=255)
     */
    private $developer;

    /**
     * @var int
     *
     * @ORM\Column(name="version", type="integer")
     */
    private $version;

    /**
     * @ORM\OneToMany(targetEntity="TimeDude\Bundle\TimeDudeBundle\Entity\Registration", mappedBy="game")
     */
    private $registrations;


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
     * Set name
     *
     * @param string $name
     *
     * @return Game
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set appId
     *
     * @param string $appId
     *
     * @return Game
     */
    public function setAppId($appId)
    {
        $this->appId = $appId;

        return $this;
    }

    /**
     * Get appId
     *
     * @return string
     */
    public function getAppId()
    {
        return $this->appId;
    }

    /**
     * Set gcmapikey
     *
     * @param string $gcmapikey
     *
     * @return Game
     */
    public function setGcmapikey($gcmapikey)
    {
        $this->gcmapikey = $gcmapikey;

        return $this;
    }

    /**
     * Get gcmapikey
     *
     * @return string
     */
    public function getGcmapikey()
    {
        return $this->gcmapikey;
    }

    /**
     * Set developer
     *
     * @param string $developer
     *
     * @return Game
     */
    public function setDeveloper($developer)
    {
        $this->developer = $developer;

        return $this;
    }

    /**
     * Get developer
     *
     * @return string
     */
    public function getDeveloper()
    {
        return $this->developer;
    }

    /**
     * Set version
     *
     * @param integer $version
     *
     * @return Game
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get version
     *
     * @return integer
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Add registration
     *
     * @param \TimeDude\Bundle\TimeDudeBundle\Entity\Registration $registration
     *
     * @return Game
     */
    public function addRegistration(\TimeDude\Bundle\TimeDudeBundle\Entity\Registration $registration)
    {
        $this->registrations[] = $registration;

        return $this;
    }

    /**
     * Remove registration
     *
     * @param \TimeDude\Bundle\TimeDudeBundle\Entity\Registration $registration
     */
    public function removeRegistration(\TimeDude\Bundle\TimeDudeBundle\Entity\Registration $registration)
    {
        $this->registrations->removeElement($registration);
    }

    /**
     * Get registrations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRegistrations()
    {
        return $this->registrations;
    }
}
