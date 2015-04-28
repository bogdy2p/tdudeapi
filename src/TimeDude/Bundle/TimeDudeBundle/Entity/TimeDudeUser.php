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
 * @ORM\Table(name="GoogleUsers")
 * @ExclusionPolicy("all");
 */
class TimeDudeUser {

    public function __construct() {

        $this->rewards = new ArrayCollection();
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
     * @ORM\Column(type="string", name="registrationId", nullable=true)
     */
    protected $registrationId;

    /**
     * @ORM\Column(type="string", name="email", nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", name="name", nullable=true)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="TimeDude\Bundle\TimeDudeBundle\Entity\Reward", mappedBy="user")
     */
    private $rewards;

    /**
     * @ORM\OneToMany(targetEntity="TimeDude\Bundle\TimeDudeBundle\Entity\Registration", mappedBy="user")
     */
    private $registrations;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set googleUid
     *
     * @param string $googleUid
     *
     * @return TimeDudeUser
     */
    public function setGoogleUid($googleUid) {
        $this->googleUid = $googleUid;

        return $this;
    }

    /**
     * Get googleUid
     *
     * @return string
     */
    public function getGoogleUid() {
        return $this->googleUid;
    }

    /**
     * Set registrationId
     *
     * @param string $registrationId
     *
     * @return TimeDudeUser
     */
    public function setRegistrationId($registrationId) {
        $this->registrationId = $registrationId;

        return $this;
    }

    /**
     * Get registrationId
     *
     * @return string
     */
    public function getRegistrationId() {
        return $this->registrationId;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return TimeDudeUser
     */
    public function setLastname($lastname) {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname() {
        return $this->lastname;
    }

    /**
     * Add reward
     *
     * @param \TimeDude\Bundle\TimeDudeBundle\Entity\Reward $reward
     *
     * @return TimeDudeUser
     */
    public function addReward(\TimeDude\Bundle\TimeDudeBundle\Entity\Reward $reward) {
        $this->rewards[] = $reward;

        return $this;
    }

    /**
     * Remove reward
     *
     * @param \TimeDude\Bundle\TimeDudeBundle\Entity\Reward $reward
     */
    public function removeReward(\TimeDude\Bundle\TimeDudeBundle\Entity\Reward $reward) {
        $this->rewards->removeElement($reward);
    }

    /**
     * Get rewards
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRewards() {
        return $this->rewards;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return TimeDudeUser
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return TimeDudeUser
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
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
