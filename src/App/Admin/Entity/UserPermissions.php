<?php

namespace App\Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserPermissions
 *
 * @ORM\Table(name="user_permissions")
 * @ORM\Entity(repositoryClass="App\Admin\Entity\UserPermissionsRepository")
 */
class UserPermissions {
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
	 * @ORM\Column(name="slug", type="string", length=255, nullable=true)
	 */
	private $slug;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="description", type="text", length=65535, nullable=true)
	 */
	private $description;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="created", type="string", nullable=true)
	 */
	private $created;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="modified", type="string", nullable=true)
	 */
	private $modified;

	/**
	 * @var \Doctrine\Common\Collections\Collection
	 *
	 * @ORM\ManyToMany(targetEntity="App\Admin\Entity\UserProfiles", mappedBy="userPermission")
	 */
	private $userProfile;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->userProfile = new \Doctrine\Common\Collections\ArrayCollection();
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
	 * @return UserPermissions
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
	 * Set slug
	 *
	 * @param string $slug
	 *
	 * @return UserPermissions
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
	 * Set description
	 *
	 * @param string $description
	 *
	 * @return UserPermissions
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
	 * Set created
	 *
	 * @param \DateTime $created
	 *
	 * @return UserPermissions
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
	 * @return UserPermissions
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
	 * Add userProfile
	 *
	 * @param UserProfiles $userProfile
	 *
	 * @return UserPermissions
	 */
	public function addUserProfile(UserProfiles $userProfile) {
		$this->userProfile[] = $userProfile;

		return $this;
	}

	/**
	 * Remove userProfile
	 *
	 * @param UserProfiles $userProfile
	 */
	public function removeUserProfile(UserProfiles $userProfile) {
		$this->userProfile->removeElement($userProfile);
	}

	/**
	 * Get userProfile
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getUserProfile() {
		return $this->userProfile;
	}
}

