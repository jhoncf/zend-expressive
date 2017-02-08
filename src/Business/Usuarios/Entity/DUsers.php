<?php

namespace Business\Usuarios\Entity;

use Business\Empresas\Entity\DCompanies;
use Business\Newsletter\Entity\DNewsletters;
use Business\Perfis\Entity\DProfiles;
use Business\UsuariosGrupos\Entity\DUserGroups;
use Doctrine\ORM\Mapping as ORM;

/**
 * DUsers
 *
 * @ORM\Table(name="d_users")
 * @ORM\Entity(repositoryClass="Business\Usuarios\Entity\DUsersRepository")
 */
class DUsers {
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
	 * @ORM\Column(name="surname", type="string", length=255, nullable=true)
	 */
	private $surname;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="password", type="string", length=255, nullable=true)
	 */
	private $password;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="email", type="string", length=255, nullable=true)
	 */
	private $email;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="image_id", type="integer", nullable=true)
	 */
	private $imageId;

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
	 * @ORM\Column(name="activation_key", type="string", length=45, nullable=true)
	 */
	private $activationKey;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="blocked", type="boolean", nullable=true)
	 */
	private $blocked = '0';

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="deleted", type="boolean", nullable=true)
	 */
	private $deleted = '0';

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="deleted_date", type="datetime", nullable=true)
	 */
	private $deletedDate;

    /**
     * @var string
     *
     * @ORM\Column(name="telefone", type="string", length=150, nullable=true)
     */
    private $telefone;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="status", type="string", nullable=true)
	 */
	private $status = 'migrated';

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="incorrect_login_attempts", type="smallint", nullable=true)
	 */
	private $incorrectLoginAttempts = '0';

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="is_temp", type="boolean", nullable=true)
	 */
	private $isTemp = '1';

	/**
	 * @var \Doctrine\Common\Collections\Collection
	 *
	 * @ORM\ManyToMany(targetEntity="\Business\Empresas\Entity\DCompanies", inversedBy="dUsers")
	 * @ORM\JoinTable(name="d_users_d_companies",
	 *   joinColumns={
	 *     @ORM\JoinColumn(name="d_users_id", referencedColumnName="id")
	 *   },
	 *   inverseJoinColumns={
	 *     @ORM\JoinColumn(name="d_companies_id", referencedColumnName="id")
	 *   }
	 * )
	 */
	private $dCompanies;

	/**
	 * @var \Doctrine\Common\Collections\Collection
	 *
	 * @ORM\ManyToMany(targetEntity="\Business\Newsletter\Entity\DNewsletters", inversedBy="dUser")
	 * @ORM\JoinTable(name="d_users_d_newsletters",
	 *   joinColumns={
	 *     @ORM\JoinColumn(name="d_user_id", referencedColumnName="id")
	 *   },
	 *   inverseJoinColumns={
	 *     @ORM\JoinColumn(name="d_newsletter_id", referencedColumnName="id")
	 *   }
	 * )
	 */
	private $dNewsletter;

	/**
	 * @var \Doctrine\Common\Collections\Collection
	 *
	 * @ORM\ManyToMany(targetEntity="\Business\Perfis\Entity\DProfiles", inversedBy="dUser")
	 * @ORM\JoinTable(name="d_profiles_d_users",
	 *   joinColumns={
	 *     @ORM\JoinColumn(name="d_user_id", referencedColumnName="id")
	 *   },
	 *   inverseJoinColumns={
	 *     @ORM\JoinColumn(name="d_profile_id", referencedColumnName="id")
	 *   }
	 * )
	 */
	private $dProfile;

	/**
	 * @var \Doctrine\Common\Collections\Collection
	 *
	 * @ORM\ManyToMany(targetEntity="\Business\UsuariosGrupos\Entity\DUserGroups", inversedBy="dUser")
	 * @ORM\JoinTable(name="d_users_d_user_groups",
	 *   joinColumns={
	 *     @ORM\JoinColumn(name="d_user_id", referencedColumnName="id")
	 *   },
	 *   inverseJoinColumns={
	 *     @ORM\JoinColumn(name="d_user_group_id", referencedColumnName="id")
	 *   }
	 * )
	 */
	private $dUserGroup;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->dProfile = new \Doctrine\Common\Collections\ArrayCollection();
		$this->dCompanies = new \Doctrine\Common\Collections\ArrayCollection();
		$this->dUserGroup = new \Doctrine\Common\Collections\ArrayCollection();
		$this->dNewsletter = new \Doctrine\Common\Collections\ArrayCollection();
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
	 * @return DUsers
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
	 * Set surname
	 *
	 * @param string $surname
	 *
	 * @return DUsers
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
	 * Set password
	 *
	 * @param string $password
	 *
	 * @return DUsers
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
	 * Set email
	 *
	 * @param string $email
	 *
	 * @return DUsers
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
	 * Set imageId
	 *
	 * @param integer $imageId
	 *
	 * @return DUsers
	 */
	public function setImageId($imageId) {
		$this->imageId = $imageId;

		return $this;
	}

	/**
	 * Get imageId
	 *
	 * @return integer
	 */
	public function getImageId() {
		return $this->imageId;
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
	 * Set created
	 *
	 * @param \DateTime $created
	 *
	 * @return DUsers
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
	 * @return DUsers
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
	 * Set activationKey
	 *
	 * @param string $activationKey
	 *
	 * @return DUsers
	 */
	public function setActivationKey($activationKey) {
		$this->activationKey = $activationKey;

		return $this;
	}

	/**
	 * Get activationKey
	 *
	 * @return string
	 */
	public function getActivationKey() {
		return $this->activationKey;
	}

	/**
	 * Set blocked
	 *
	 * @param boolean $blocked
	 *
	 * @return DUsers
	 */
	public function setBlocked($blocked) {
		$this->blocked = $blocked;

		return $this;
	}

	/**
	 * Get blocked
	 *
	 * @return boolean
	 */
	public function getBlocked() {
		return $this->blocked;
	}

	/**
	 * Set deleted
	 *
	 * @param boolean $deleted
	 *
	 * @return DUsers
	 */
	public function setDeleted($deleted) {
		$this->deleted = $deleted;

		return $this;
	}

	/**
	 * Get deleted
	 *
	 * @return boolean
	 */
	public function getDeleted() {
		return $this->deleted;
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
	 * Set status
	 *
	 * @param string $status
	 *
	 * @return DUsers
	 */
	public function setStatus($status) {
		$this->status = $status;

		return $this;
	}

	/**
	 * Get status
	 *
	 * @return string
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * Set incorrectLoginAttempts
	 *
	 * @param integer $incorrectLoginAttempts
	 *
	 * @return DUsers
	 */
	public function setIncorrectLoginAttempts($incorrectLoginAttempts) {
		$this->incorrectLoginAttempts = $incorrectLoginAttempts;

		return $this;
	}

	/**
	 * Get incorrectLoginAttempts
	 *
	 * @return integer
	 */
	public function getIncorrectLoginAttempts() {
		return $this->incorrectLoginAttempts;
	}

	/**
	 * Set isTemp
	 *
	 * @param boolean $isTemp
	 *
	 * @return DUsers
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
	 * Add dCompany
	 *
	 * @param DCompanies $dCompany
	 *
	 * @return DUsers
	 */
	public function addDCompany(DCompanies $dCompany) {
		$this->dCompanies[] = $dCompany;

		return $this;
	}

	/**
	 * Remove dCompany
	 *
	 * @param DCompanies $dCompany
	 */
	public function removeDCompany(DCompanies $dCompany) {
		$this->dCompanies->removeElement($dCompany);
	}

	/**
	 * Get dCompanies
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getDCompanies() {
		return $this->dCompanies;
	}

	/**
	 * Add dNewsletter
	 *
	 * @param DNewsletters $dNewsletter
	 *
	 * @return DUsers
	 */
	public function addDNewsletter(DNewsletters $dNewsletter)
	{
		$this->dNewsletter[] = $dNewsletter;

		return $this;
	}

	/**
	 * Remove dNewsletter
	 *
	 * @param DNewsletters $dNewsletter
	 */
	public function removeDNewsletter(DNewsletters $dNewsletter)
	{
		$this->dNewsletter->removeElement($dNewsletter);
	}

	/**
	 * Get dNewsletter
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getDNewsletter()
	{
		return $this->dNewsletter;
	}

	/**
	 * Add dProfile
	 *
	 * @param DProfiles $dProfile
	 *
	 * @return DUsers
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
	 * Add dUserGroup
	 *
	 * @param \DUserGroups $dUserGroup
	 *
	 * @return DUsers
	 */
	public function addDUserGroup(DUserGroups $dUserGroup) {
		$this->dUserGroup[] = $dUserGroup;

		return $this;
	}

	/**
	 * Remove dUserGroup
	 *
	 * @param \DUserGroups $dUserGroup
	 */
	public function removeDUserGroup(DUserGroups $dUserGroup) {
		$this->dUserGroup->removeElement($dUserGroup);
	}

	/**
	 * Get dUserGroup
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getDUserGroup() {
		return $this->dUserGroup;
	}

	/**
	 * Get clearDProfile
	 */
	public function clearDProfile() {
		$this->dProfile->clear();
	}

	/**
	 * Delete clearDCompany
	 */
	public function clearDCompany() {
		$this->dCompanies->clear();
	}

	/**
	 * Delete clearDNewsletter
	 */
	public function clearDNewsletter() {
		$this->dNewsletter->clear();
	}
}

