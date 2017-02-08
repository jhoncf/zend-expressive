<?php

namespace Business\Entities;

use Business\Usuarios\Entity\DUsers;
use Doctrine\ORM\Mapping as ORM;

/**
 * DLoginStatuses
 *
 * @ORM\Table(name="d_login_statuses", indexes={@ORM\Index(name="fk_d_log_stat_d_u1_idx", columns={"d_user_id"})})
 * @ORM\Entity(repositoryClass="Business\Entities\DLoginStatusesRepository")
 */
class DLoginStatuses
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean", nullable=true)
     */
    private $active;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="started", type="datetime", nullable=true)
     */
    private $started;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_access", type="datetime", nullable=true)
     */
    private $lastAccess;

    /**
     * @var string
     *
     * @ORM\Column(name="session_key", type="string", length=255, nullable=true)
     */
    private $sessionKey;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=20, nullable=true)
     */
    private $ip;

    /**
     * @var string
     *
     * @ORM\Column(name="client_ip", type="string", length=20, nullable=true)
     */
    private $clientIp;

    /**
     * @var DUsers
     *
     * @ORM\ManyToOne(targetEntity="Business\Usuarios\Entity\DUsers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="d_user_id", referencedColumnName="id")
     * })
     */
    private $dUser;


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
     * Set active
     *
     * @param boolean $active
     *
     * @return DLoginStatuses
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set started
     *
     * @param \DateTime $started
     *
     * @return DLoginStatuses
     */
    public function setStarted($started)
    {
        $this->started = $started;

        return $this;
    }

    /**
     * Get started
     *
     * @return \DateTime
     */
    public function getStarted()
    {
        return $this->started;
    }

    /**
     * Set lastAccess
     *
     * @param \DateTime $lastAccess
     *
     * @return DLoginStatuses
     */
    public function setLastAccess($lastAccess)
    {
        $this->lastAccess = $lastAccess;

        return $this;
    }

    /**
     * Get lastAccess
     *
     * @return \DateTime
     */
    public function getLastAccess()
    {
        return $this->lastAccess;
    }

    /**
     * Set sessionKey
     *
     * @param string $sessionKey
     *
     * @return DLoginStatuses
     */
    public function setSessionKey($sessionKey)
    {
        $this->sessionKey = $sessionKey;

        return $this;
    }

    /**
     * Get sessionKey
     *
     * @return string
     */
    public function getSessionKey()
    {
        return $this->sessionKey;
    }

    /**
     * Set ip
     *
     * @param string $ip
     *
     * @return DLoginStatuses
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set clientIp
     *
     * @param string $clientIp
     *
     * @return DLoginStatuses
     */
    public function setClientIp($clientIp)
    {
        $this->clientIp = $clientIp;

        return $this;
    }

    /**
     * Get clientIp
     *
     * @return string
     */
    public function getClientIp()
    {
        return $this->clientIp;
    }

    /**
     * Set dUser
     *
     * @param DUsers $dUser
     *
     * @return DLoginStatuses
     */
    public function setDUser(DUsers $dUser = null)
    {
        $this->dUser = $dUser;

        return $this;
    }

    /**
     * Get dUser
     *
     * @return \DUsers
     */
    public function getDUser()
    {
        return $this->dUser;
    }
}

