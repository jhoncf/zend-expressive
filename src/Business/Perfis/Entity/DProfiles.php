<?php

namespace Business\Perfis\Entity;

use Business\Permissoes\Entity\DPermissions;
use Business\Planos\Entity\DPlans;
use Business\Usuarios\Entity\DUsers;
use Doctrine\ORM\Mapping as ORM;

/**
 * DProfiles
 *
 * @ORM\Table(name="d_profiles")
 * @ORM\Entity(repositoryClass="Business\Perfis\Entity\DProfilesRepository")
 */
class DProfiles {
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
	 * @ORM\Column(name="`name`", type="string", length=255, nullable=true)
	 */
	private $name;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="description", type="string", length=255, nullable=true)
	 */
	private $description;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="slug", type="string", length=255, nullable=true)
	 */
	private $slug;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="`order`", type="integer", nullable=true)
	 */
	private $order;

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
	 * @var boolean
	 *
	 * @ORM\Column(name="hidden", type="boolean", nullable=true)
	 */
	private $hidden = '0';

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="product_order", type="integer", nullable=true)
	 */
	private $productOrder;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="is_temp", type="boolean", nullable=true)
	 */
	private $isTemp = '1';

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="deleted", type="boolean", nullable=false)
	 */
	private $deleted = 0;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="deleted_date", type="datetime", nullable=true)
	 */
	private $deletedDate;

	/**
	 * @var \Doctrine\Common\Collections\Collection
	 *
	 * @ORM\ManyToMany(targetEntity="\Business\Permissoes\Entity\DPermissions", inversedBy="dProfile")
	 * @ORM\JoinTable(name="d_permissions_d_profiles",
	 *   joinColumns={
	 *     @ORM\JoinColumn(name="d_profile_id", referencedColumnName="id")
	 *   },
	 *   inverseJoinColumns={
	 *     @ORM\JoinColumn(name="d_permission_id", referencedColumnName="id")
	 *   }
	 * )
	 */
	private $dPermission;

	/**
	 * @var \Doctrine\Common\Collections\Collection
	 *
	 * @ORM\ManyToMany(targetEntity="Business\Planos\Entity\DPlans", mappedBy="dProfile")
	 */
	private $dPlan;

	/**
	 * @var \Doctrine\Common\Collections\Collection
	 *
	 * @ORM\ManyToMany(targetEntity="\Business\Usuarios\Entity\DUsers", mappedBy="dProfile")
	 * @ORM\JoinTable(name="d_profiles_d_users",
	 *   joinColumns={
	 *     @ORM\JoinColumn(name="d_profile_id", referencedColumnName="id")
	 *   },
	 *   inverseJoinColumns={
	 *     @ORM\JoinColumn(name="d_user_id", referencedColumnName="id")
	 *   }
	 * )
	 */
	private $dUser;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->dPermission = new \Doctrine\Common\Collections\ArrayCollection();
		$this->dPlan = new \Doctrine\Common\Collections\ArrayCollection();
		$this->dUser = new \Doctrine\Common\Collections\ArrayCollection();
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
	 * @return DProfiles
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
	 * Set description
	 *
	 * @param string $description
	 *
	 * @return DProfiles
	 */
	public function setDescription($description) {
		$this->description = $description;

		return $this;
	}

	/**
	 * Get description
	 *
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * Set slug
	 *
	 * @param string $slug
	 *
	 * @return DProfiles
	 */
	public function setSlug($slug) {
		$this->slug = $slug;

		return $this;
	}

	/**
	 * Get slug
	 *
	 * @return string
	 */
	public function getSlug() {
		return $this->slug;
	}

	/**
	 * Set order
	 *
	 * @param integer $order
	 *
	 * @return DProfiles
	 */
	public function setOrder($order) {
		$this->order = $order;

		return $this;
	}

	/**
	 * Get order
	 *
	 * @return integer
	 */
	public function getOrder() {
		return $this->order;
	}

	/**
	 * Set created
	 *
	 * @param \DateTime $created
	 *
	 * @return DProfiles
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
	 * @return DProfiles
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
	 * Set hidden
	 *
	 * @param boolean $hidden
	 *
	 * @return DProfiles
	 */
	public function setHidden($hidden) {
		$this->hidden = $hidden;

		return $this;
	}

	/**
	 * Get hidden
	 *
	 * @return boolean
	 */
	public function getHidden() {
		return $this->hidden;
	}

	/**
	 * Set productOrder
	 *
	 * @param integer $productOrder
	 *
	 * @return DProfiles
	 */
	public function setProductOrder($productOrder) {
		$this->productOrder = $productOrder;

		return $this;
	}

	/**
	 * Get productOrder
	 *
	 * @return integer
	 */
	public function getProductOrder() {
		return $this->productOrder;
	}

	/**
	 * Set isTemp
	 *
	 * @param boolean $isTemp
	 *
	 * @return DProfiles
	 */
	public function setIsTemp($isTemp) {
		$this->isTemp = $isTemp;

		return $this;
	}

	/**
	 * Get isTemp
	 *
	 * @return boolean
	 */
	public function getIsTemp() {
		return $this->isTemp;
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
	 * Add dPermission
	 *
	 * @param DPermissions $dPermission
	 *
	 * @return DProfiles
	 */
	public function addDPermission(DPermissions $dPermission) {
		$this->dPermission[] = $dPermission;

		return $this;
	}

	/**
	 * Remove dPermission
	 *
	 * @param DPermissions $dPermission
	 */
	public function removeDPermission(DPermissions $dPermission) {
		$this->dPermission->removeElement($dPermission);
	}

	/**
	 * Get dPermission
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getDPermission() {
		return $this->dPermission;
	}

	/**
	 * Add dPlan
	 *
	 * @param DPlans $dPlan
	 *
	 * @return DProfiles
	 */
	public function addDPlan(DPlans $dPlan) {
		$this->dPlan[] = $dPlan;

		return $this;
	}

	/**
	 * Remove dPlan
	 *
	 * @param DPlans $dPlan
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
	 * @return DProfiles
	 */
	public function addDUser(DUsers $dUser) {
		$this->dUser[] = $dUser;

		return $this;
	}

	/**
	 * Remove dUser
	 *
	 * @param DUsers $dUser
	 */
	public function removeDUser(DUsers $dUser) {
		$this->dUser->removeElement($dUser);
	}

	/**
	 * Get dUser
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getDUser() {
		return $this->dUser;
	}

	/**
	 * Get clearDPermission
	 */
	public function clearDPermission() {
		$this->getDPermission()->clear();
	}
}

