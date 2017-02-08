<?php

namespace App\Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserUsers
 *
 * @ORM\Table(name="user_users")
 * @ORM\Entity(repositoryClass="App\Admin\Entity\UserUsersRepository")
 */
class UserUsers {
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
	 * @ORM\Column(name="username", type="string", length=255, nullable=false)
	 */
	private $username;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="password", type="string", length=255, nullable=false)
	 */
	private $password;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="surname", type="string", length=80, nullable=true)
	 */
	private $surname;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="name", type="string", length=80, nullable=true)
	 */
	private $name;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="email", type="string", length=255, nullable=true)
	 */
	private $email;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="created", type="string", nullable=true)
	 */
	private $created;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="modified", type="string", nullable=true)
	 */
	private $modified;

	/**
	 * @var \Doctrine\Common\Collections\Collection
	 *
	 * @ORM\ManyToMany(targetEntity="App\Admin\Entity\UserProfiles", inversedBy="userUser")
	 * @ORM\JoinTable(name="user_users_user_profiles",
	 *   joinColumns={
	 *     @ORM\JoinColumn(name="user_user_id", referencedColumnName="id")
	 *   },
	 *   inverseJoinColumns={
	 *     @ORM\JoinColumn(name="user_profile_id", referencedColumnName="id")
	 *   }
	 * )
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
	 * Set username
	 *
	 * @param string $username
	 *
	 * @return UserUsers
	 */
	public function setUsername($username) {
		$this->username = $username;

		return $this;
	}

	/**
	 * Get username
	 *
	 * @return string
	 */
	public function getUsername() {
		return $this->username;
	}

	/**
	 * Set password
	 *
	 * @param string $password
	 *
	 * @return UserUsers
	 */
	public function setPassword($password) {
		$this->password = $password;

		return $this;
	}

	/**
	 * Get password
	 *
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * Set surname
	 *
	 * @param string $surname
	 *
	 * @return UserUsers
	 */
	public function setSurname($surname) {
		$this->surname = $surname;

		return $this;
	}

	/**
	 * Get surname
	 *
	 * @return string
	 */
	public function getSurname() {
		return $this->surname;
	}

	/**
	 * Set name
	 *
	 * @param string $name
	 *
	 * @return UserUsers
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
	 * Set email
	 *
	 * @param string $email
	 *
	 * @return UserUsers
	 */
	public function setEmail($email) {
		$this->email = $email;

		return $this;
	}

	/**
	 * Get email
	 *
	 * @return string
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * Set created
	 *
	 * @param \DateTime $created
	 *
	 * @return UserUsers
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
	 * @return UserUsers
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
	 * @return UserUsers
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

	/**
	 * Get clearUserProfile
	 */
	public function clearUserProfile() {
		$this->userProfile->clear();
	}

}

