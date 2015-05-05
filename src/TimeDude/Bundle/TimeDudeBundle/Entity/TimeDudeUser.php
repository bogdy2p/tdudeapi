<?php

namespace TimeDude\Bundle\TimeDudeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
// For setting validation constraints:
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Exclude;

/**
 * @ORM\Entity(repositoryClass="TimeDude\Bundle\TimeDudeBundle\Entity\TimeDudeUserRepository")
 * @ORM\Table(name="Users")
 * @ExclusionPolicy("all");
 */
class TimeDudeUser {

    public function __construct() {

        $this->ammounts = new ArrayCollection();
        $this->rewardlogs = new ArrayCollection();
        $this->registrations = new ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", name="googleUid", nullable=false)
     */
    protected $googleUid;

    /**
     * @ORM\Column(type="string", name="email", nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", name="firstname", nullable=true)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", name="lastname", nullable=true)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", name="location", nullable=true)
     */
    private $location;

    /**
     * @ORM\Column(type="string", name="language", nullable=true)
     */
    private $language;

    /**
     * @ORM\Column(type="string", name="birthday", nullable=true)
     */
    private $birthday;

    /**
     * @ORM\OneToMany(targetEntity="TimeDude\Bundle\TimeDudeBundle\Entity\Ammount", mappedBy="user")
     */
    private $ammounts;
    
    /**
     * @ORM\OneToMany(targetEntity="TimeDude\Bundle\TimeDudeBundle\Entity\RewardLog", mappedBy="user")
     */
    private $rewardlogs;

    /**
     * @ORM\OneToMany(targetEntity="TimeDude\Bundle\TimeDudeBundle\Entity\Registration", mappedBy="user")
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
     * Set googleUid
     *
     * @param string $googleUid
     *
     * @return TimeDudeUser
     */
    public function setGoogleUid($googleUid)
    {
        $this->googleUid = $googleUid;

        return $this;
    }

    /**
     * Get googleUid
     *
     * @return string
     */
    public function getGoogleUid()
    {
        return $this->googleUid;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return TimeDudeUser
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return TimeDudeUser
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return TimeDudeUser
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set location
     *
     * @param string $location
     *
     * @return TimeDudeUser
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set language
     *
     * @param string $language
     *
     * @return TimeDudeUser
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set birthday
     *
     * @param string $birthday
     *
     * @return TimeDudeUser
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return string
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Add ammount
     *
     * @param \TimeDude\Bundle\TimeDudeBundle\Entity\Ammount $ammount
     *
     * @return TimeDudeUser
     */
    public function addAmmount(\TimeDude\Bundle\TimeDudeBundle\Entity\Ammount $ammount)
    {
        $this->ammounts[] = $ammount;

        return $this;
    }

    /**
     * Remove ammount
     *
     * @param \TimeDude\Bundle\TimeDudeBundle\Entity\Ammount $ammount
     */
    public function removeAmmount(\TimeDude\Bundle\TimeDudeBundle\Entity\Ammount $ammount)
    {
        $this->ammounts->removeElement($ammount);
    }

    /**
     * Get ammounts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAmmounts()
    {
        return $this->ammounts;
    }

    /**
     * Add rewardlog
     *
     * @param \TimeDude\Bundle\TimeDudeBundle\Entity\RewardLog $rewardlog
     *
     * @return TimeDudeUser
     */
    public function addRewardlog(\TimeDude\Bundle\TimeDudeBundle\Entity\RewardLog $rewardlog)
    {
        $this->rewardlogs[] = $rewardlog;

        return $this;
    }

    /**
     * Remove rewardlog
     *
     * @param \TimeDude\Bundle\TimeDudeBundle\Entity\RewardLog $rewardlog
     */
    public function removeRewardlog(\TimeDude\Bundle\TimeDudeBundle\Entity\RewardLog $rewardlog)
    {
        $this->rewardlogs->removeElement($rewardlog);
    }

    /**
     * Get rewardlogs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRewardlogs()
    {
        return $this->rewardlogs;
    }

    /**
     * Add registration
     *
     * @param \TimeDude\Bundle\TimeDudeBundle\Entity\Registration $registration
     *
     * @return TimeDudeUser
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
