<?php

namespace TimeDude\Bundle\TimeDudeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GoogleUser
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="TimeDude\Bundle\TimeDudeBundle\Entity\GoogleUserRepository")
 */
class GoogleUser
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
     * @var string
     *
     * @ORM\Column(name="googleUid", type="string", length=255)
     */
    private $googleUid;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;


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
     * @return GoogleUser
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
     * Set name
     *
     * @param string $name
     * @return GoogleUser
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
}
