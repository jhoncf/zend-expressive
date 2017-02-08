<?php

namespace Business\UsuariosGrupos\Entity;

use Business\Usuarios\Entity\DUsers;
use Doctrine\ORM\Mapping as ORM;

/**
 * DUserGroups
 *
 * @ORM\Table(name="d_user_groups")
 * @ORM\Entity(repositoryClass="Business\UsuariosGrupos\Entity\DUserGroupsRepository")
 */
class DUserGroups
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="`order`", type="integer", nullable=true)
     */
    private $order;

    /**
     * @var integer
     *
     * @ORM\Column(name="d_company_id", type="integer", nullable=true)
     */
    private $dCompanyId;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Business\Usuarios\Entity\DUsers", mappedBy="dUserGroup")
     */
    private $dUser;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dUser = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * @return DUserGroups
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
     * Set order
     *
     * @param integer $order
     *
     * @return DUserGroups
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return integer
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set dCompanyId
     *
     * @param integer $dCompanyId
     *
     * @return DUserGroups
     */
    public function setDCompanyId($dCompanyId)
    {
        $this->dCompanyId = $dCompanyId;

        return $this;
    }

    /**
     * Get dCompanyId
     *
     * @return integer
     */
    public function getDCompanyId()
    {
        return $this->dCompanyId;
    }

    /**
     * Add dUser
     *
     * @param DUsers $dUser
     *
     * @return DUserGroups
     */
    public function addDUser(DUsers $dUser)
    {
        $this->dUser[] = $dUser;

        return $this;
    }

    /**
     * Remove dUser
     *
     * @param DUsers $dUser
     */
    public function removeDUser(DUsers $dUser)
    {
        $this->dUser->removeElement($dUser);
    }

    /**
     * Get dUser
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDUser()
    {
        return $this->dUser;
    }
}

