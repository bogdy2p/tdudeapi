<?php

namespace TimeDude\Bundle\TimeDudeBundle\Entity;

// For extending FOSUserBundle:
//use FOS\UserBundle\Entity\User as BaseUser;
//use Symfony\Component\Security\Core\User\User as BaseUser;
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
     * @ORM\Column(type="string", name="firstname", nullable=true)
     */
    protected $firstname;

    /**
     * @ORM\Column(type="string", name="lastname", nullable=true)
     */
    private $lastname;

    /**
     * @ORM\OneToMany(targetEntity="TimeDude\Bundle\TimeDudeBundle\Entity\Reward", mappedBy="user")
     */
    private $rewards;


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
     * @return User
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
     * Set firstname
     *
     * @param string $firstname
     * @return User
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
     * @return User
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
     * Add rewards
     *
     * @param \TimeDude\Bundle\TimeDudeBundle\Entity\Reward $rewards
     * @return User
     */
    public function addReward(\TimeDude\Bundle\TimeDudeBundle\Entity\Reward $rewards)
    {
        $this->rewards[] = $rewards;

        return $this;
    }

    /**
     * Remove rewards
     *
     * @param \TimeDude\Bundle\TimeDudeBundle\Entity\Reward $rewards
     */
    public function removeReward(\TimeDude\Bundle\TimeDudeBundle\Entity\Reward $rewards)
    {
        $this->rewards->removeElement($rewards);
    }

    /**
     * Get rewards
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRewards()
    {
        return $this->rewards;
    }
}
