<?php

namespace Business\Permissoes\Entity;

use Business\Perfis\Entity\DProfiles;
use Business\Produtos\Entity\DProducts;
use Doctrine\ORM\Mapping as ORM;

/**
 * DPermissions
 *
 * @ORM\Table(name="d_permissions", indexes={@ORM\Index(name="fk_d_perm_d_prod1_idx", columns={"d_product_id"})})
 * @ORM\Entity(repositoryClass="Business\Permissoes\Entity\DPermissionsRepository")
 */
class DPermissions {
	const PREFIX = 'perm';
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
	 * @var \Business\Produtos\Entity\DProducts
	 *
	 * @ORM\ManyToOne(targetEntity="\Business\Produtos\Entity\DProducts")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="d_product_id", referencedColumnName="id")
	 * })
	 */
	private $dProduct;

	/**
	 * @var \Doctrine\Common\Collections\Collection
	 *
	 * @ORM\ManyToMany(targetEntity="\Business\Perfis\Entity\DProfiles", mappedBy="dPermission")
	 * @ORM\JoinTable(name="d_permissions_d_profiles",
	 *   joinColumns={
	 *     @ORM\JoinColumn(name="d_permission_id", referencedColumnName="id")
	 *   },
	 *   inverseJoinColumns={
	 *     @ORM\JoinColumn(name="d_profile_id", referencedColumnName="id")
	 *   }
	 * )
	 */
	private $dProfile;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->dProfile = new \Doctrine\Common\Collections\ArrayCollection();
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
	 * @return DPermissions
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
	 * @return DPermissions
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
	 * @return DPermissions
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
	 * Set dProduct
	 *
	 * @param DProducts $dProduct
	 *
	 * @return DPermissions
	 */
	public function setDProduct(DProducts $dProduct = null) {
		$this->dProduct = $dProduct;

		return $this;
	}

	/**
	 * Get dProduct
	 *
	 * @return DProducts
	 */
	public function getDProduct() {
		return $this->dProduct;
	}

	/**
	 * Add dProfile
	 *
	 * @param DProfiles $dProfile
	 *
	 * @return DPermissions
	 */
	public function addDProfile(DProfiles $dProfile) {
		$this->dProfile[] = $dProfile;

		return $this;
	}

	/**
	 * Remove dProfile
	 *
	 * @param DProfiles $dProfile
	 */
	public function removeDProfile(DProfiles $dProfile) {
		$this->dProfile->removeElement($dProfile);
	}

	/**
	 * Get dProfile
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getDProfile() {
		return $this->dProfile;
	}

	/**
	 * Get clearDProfile
	 */
	public function clearDProfile() {
		$this->dProfile->clear();
	}
}

