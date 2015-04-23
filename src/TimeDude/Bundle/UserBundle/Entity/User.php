<?php

namespace TimeDude\Bundle\UserBundle\Entity;

// For extending FOSUserBundle:
use FOS\UserBundle\Entity\User as BaseUser;
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
 * @ORM\Entity(repositoryClass="TimeDude\Bundle\UserBundle\Entity\UserRepository")
 * @ORM\Table(name="users")
 * @ExclusionPolicy("all");
 */
class User extends BaseUser {

    public function __construct() {
        parent::__construct();
    }

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", name="api_key", nullable=true)
     */
    protected $apiKey;

    
    /**
     * @ORM\Column(type="string", name="honey_id", nullable=true)
     */
    protected $honeyid;

    
    /**
     * @ORM\Column(type="string", name="honey_uuid", nullable=true)
     */
    protected $honeyuuid;

    /**
     * @ORM\Column(type="string", name="honey_refresh_token", nullable=true)
     */
    protected $honeyRefreshToken;
    
    
    /**
     * @ORM\Column(type="string", name="firstname", nullable=true)
     */
    protected $firstname;

    /**
     * @ORM\Column(type="string", name="lastname", nullable=true)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", name="title", nullable=true)
     */
    protected $title;

    /**
     * @ORM\Column(type="string", name="office", nullable=true)
     */
    protected $office;

    /**
     * @ORM\Column(type="string", name="phone", nullable=true)
     */
    protected $phone;

    /**
     * @ORM\Column(type="string", name="profilepicture", nullable=true)
     */
    protected $profilepicture;

    /**
     * @ORM\Column(type="datetime" , name="created_at")
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime" , name="updated_at", nullable=false)
     */
    protected $updatedAt;

  
}
