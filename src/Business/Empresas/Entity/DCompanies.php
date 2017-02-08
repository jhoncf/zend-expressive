<?php

namespace Business\Empresas\Entity;

use Business\Planos\Entity\DPlans;
use Business\Usuarios\Entity\DUsers;
use Doctrine\ORM\Mapping as ORM;

/**
 * DCompanies
 *
 * @ORM\Table(name="d_companies")
 * @ORM\Entity(repositoryClass="Business\Empresas\Entity\DCompaniesRepository")
 */
class DCompanies {
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	public $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="name", type="string", length=255, nullable=true)
	 */
	private $name;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="short_name", type="string", length=255, nullable=true)
	 */
	private $shortName;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="image_url", type="string", nullable=true)
	 */
	private $imageUrl;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="created", type="datetime", nullable=true)
	 */
	private $created;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="modified", type="datetime", nullable=true)
	 */
	private $modified;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="domain", type="string", length=150, nullable=true)
	 */
	private $domain;


    /**
     * @var string
     *
     * @ORM\Column(name="telefone", type="string", length=150, nullable=true)
     */
    private $telefone;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="deleted", type="boolean", nullable=true)
	 */
	private $deleted = 0;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="deleted_date",  type="date", nullable=true)
	 */
	private $deletedDate;

	/**
	 * @var \Doctrine\Common\Collections\Collection
	 *
	 * @ORM\ManyToMany(targetEntity="Business\Empresas\Entity\DCompanies", inversedBy="dCompany")
	 * @ORM\JoinTable(name="d_companies_d_companies",
	 *   joinColumns={
	 *     @ORM\JoinColumn(name="d_company_id", referencedColumnName="id")
	 *   },
	 *   inverseJoinColumns={
	 *     @ORM\JoinColumn(name="d_company_id1", referencedColumnName="id")
	 *   }
	 * )
	 */
	private $dCompany1;

	/**
	 * @var \Doctrine\Common\Collections\Collection
	 *
	 * @ORM\ManyToMany(targetEntity="Business\Planos\Entity\DPlans", mappedBy="dCompany")
	 */
	private $dPlan;

	/**
	 * @var \Doctrine\Common\Collections\Collection
	 *
	 * @ORM\ManyToMany(targetEntity="\Business\Usuarios\Entity\DUsers", mappedBy="dCompanies")
	 */
	private $dUsers;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->dCompany1 = new \Doctrine\Common\Collections\ArrayCollection();
		$this->dPlan = new \Doctrine\Common\Collections\ArrayCollection();
		$this->dUsers = new \Doctrine\Common\Collections\ArrayCollection();
	}

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
	 *
	 * @return DCompanies
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
	 * Set shortName
	 *
	 * @param string $shortName
	 *
	 * @return DCompanies
	 */
	public function setShortName($shortName) {
		$this->shortName = $shortName;

		return $this;
	}

	/**
	 * Get shortName
	 *
	 * @return string
	 */
	public function getShortName() {
		return $this->shortName;
	}

	/**
	 * Set imageUrl
	 *
	 * @param string $imageUrl
	 *
	 * @return DCompanies
	 */
	public function setImageUrl($imageUrl) {
		$this->imageUrl = $imageUrl;

		return $this;
	}

	/**
	 * Get imageUrl
	 *
	 * @return string
	 */
	public function getImageUrl() {
		return $this->imageUrl;
	}

	/**
	 * Set created
	 *
	 * @param \DateTime $created
	 *
	 * @return DCompanies
	 */
	public function setCreated($created) {
		$this->created = $created;

		return $this;
	}

	/**
	 * Get created
	 *
	 * @return \DateTime
	 */
	public function getCreated() {
		return $this->created;
	}

	/**
	 * Set modified
	 *
	 * @param \DateTime $modified
	 *
	 * @return DCompanies
	 */
	public function setModified($modified) {
		$this->modified = $modified;

		return $this;
	}

	/**
	 * Get modified
	 *
	 * @return \DateTime
	 */
	public function getModified() {
		return $this->modified;
	}

	/**
	 * Set domain
	 *
	 * @param string $domain
	 *
	 * @return DCompanies
	 */
	public function setDomain($domain) {
		$this->domain = $domain;

		return $this;
	}

	/**
	 * Get domain
	 *
	 * @return string
	 */
	public function getDomain() {
		return $this->domain;
	}

    /**
     * @return string
     */
    public function getTelefone() {
        return $this->telefone;
    }

    /**
     * @param string $telefone
     */
    public function setTelefone($telefone) {
        $this->telefone = $telefone;
    }

	/**
	 * @return boolean
	 */
	public function isDeleted() {
		return $this->deleted;
	}

	/**
	 * @param boolean $deleted
	 */
	public function setDeleted($deleted) {
		$this->deleted = $deleted;
	}

	/**
	 * @return \DateTime
	 */
	public function getDeletedDate() {
		return $this->deletedDate;
	}

	/**
	 * @param \DateTime $deletedDate
	 */
	public function setDeletedDate($deletedDate) {
		$this->deletedDate = $deletedDate;
	}

	/**
	 * Add dCompany1
	 *
	 * @param DCompanies $dCompany1
	 *
	 * @return DCompanies
	 */
	public function addDCompany1(DCompanies $dCompany1) {
		$this->dCompany1[] = $dCompany1;

		return $this;
	}

	/**
	 * Remove dCompany1
	 *
	 * @param DCompanies $dCompany1
	 */
	public function removeDCompany1(DCompanies $dCompany1) {
		$this->dCompany1->removeElement($dCompany1);
	}

	/**
	 * Get dCompany1
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getDCompany1() {
		return $this->dCompany1;
	}

	/**
	 * Add dPlan
	 *
	 * @param \DPlans $dPlan
	 *
	 * @return DCompanies
	 */
	public function addDPlan(DPlans $dPlan) {
		$this->dPlan[] = $dPlan;

		return $this;
	}

	/**
	 * Remove dPlan
	 *
	 * @param \DPlans $dPlan
	 */
	public function removeDPlan(DPlans $dPlan) {
		$this->dPlan->removeElement($dPlan);
	}

	/**
	 * Get dPlan
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getDPlan() {
		return $this->dPlan;
	}

	/**
	 * Add dUser
	 *
	 * @param DUsers $dUser
	 *
	 * @return DCompanies
	 */
	public function addDUser(DUsers $dUser)
	{
		$this->dUsers[] = $dUser;

		return $this;
	}

	/**
	 * Remove dUser
	 *
	 * @param DUsers $dUser
	 */
	public function removeDUser(DUsers $dUser)
	{
		$this->dUsers->removeElement($dUser);
	}

	/**
	 * Get dUsers
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getDUsers()
	{
		return $this->dUsers;
	}
}

