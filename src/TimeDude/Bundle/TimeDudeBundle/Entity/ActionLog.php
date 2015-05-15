<?php

namespace TimeDude\Bundle\TimeDudeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ActionLog
 *
 * @ORM\Table(name="Action_Logs")
 * @ORM\Entity
 */
class ActionLog {

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
     * @ORM\Column(name="call_type", type="string", length=255)
     */
    private $callType;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_adress", type="string", length=255)
     */
    private $ipAdress;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="full_url", type="string", length=255)
     */
    private $full_url;

    /**
     * @var string
     *
     * @ORM\Column(name="redirect_status", type="string", length=255)
     */
    private $redirect_status;

    /**
     * @var string
     *
     * @ORM\Column(name="response_text", type="text")
     */
    private $response_text;

     /**
     * @var boolean
     *
     * @ORM\Column(name="call_success", type="boolean")
     */
    private $call_success;
    
    
    

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
     * Set callType
     *
     * @param string $callType
     *
     * @return ActionLog
     */
    public function setCallType($callType)
    {
        $this->callType = $callType;

        return $this;
    }

    /**
     * Get callType
     *
     * @return string
     */
    public function getCallType()
    {
        return $this->callType;
    }

    /**
     * Set ipAdress
     *
     * @param string $ipAdress
     *
     * @return ActionLog
     */
    public function setIpAdress($ipAdress)
    {
        $this->ipAdress = $ipAdress;

        return $this;
    }

    /**
     * Get ipAdress
     *
     * @return string
     */
    public function getIpAdress()
    {
        return $this->ipAdress;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return ActionLog
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
     * Set fullUrl
     *
     * @param string $fullUrl
     *
     * @return ActionLog
     */
    public function setFullUrl($fullUrl)
    {
        $this->full_url = $fullUrl;

        return $this;
    }

    /**
     * Get fullUrl
     *
     * @return string
     */
    public function getFullUrl()
    {
        return $this->full_url;
    }

    /**
     * Set redirectStatus
     *
     * @param string $redirectStatus
     *
     * @return ActionLog
     */
    public function setRedirectStatus($redirectStatus)
    {
        $this->redirect_status = $redirectStatus;

        return $this;
    }

    /**
     * Get redirectStatus
     *
     * @return string
     */
    public function getRedirectStatus()
    {
        return $this->redirect_status;
    }

    /**
     * Set responseText
     *
     * @param string $responseText
     *
     * @return ActionLog
     */
    public function setResponseText($responseText)
    {
        $this->response_text = $responseText;

        return $this;
    }

    /**
     * Get responseText
     *
     * @return string
     */
    public function getResponseText()
    {
        return $this->response_text;
    }

    /**
     * Set callSuccess
     *
     * @param boolean $callSuccess
     *
     * @return ActionLog
     */
    public function setCallSuccess($callSuccess)
    {
        $this->call_success = $callSuccess;

        return $this;
    }

    /**
     * Get callSuccess
     *
     * @return boolean
     */
    public function getCallSuccess()
    {
        return $this->call_success;
    }
}
