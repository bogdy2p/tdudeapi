<?php

namespace MissionControl\Bundle\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Role
 *
 * @ORM\Table(name="control_user_roles")
 * @ORM\Entity
 */
class Role {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
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
     * @ORM\Column(name="systemname", type="string", length=255)
     */
    private $systemname;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Role
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
     * Set systemname
     *
     * @param string $systemname
     * @return Role
     */
    public function setSystemname($systemname) {
        $this->systemname = $systemname;

        return $this;
    }

    /**
     * Get systemname
     *
     * @return string 
     */
    public function getSystemname() {
        return $this->systemname;
    }


    /**
     * Set id
     *
     * @param integer $id
     * @return Role
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}
