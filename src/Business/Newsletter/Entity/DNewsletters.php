<?php

namespace Business\Newsletter\Entity;

use Business\Usuarios\Entity\DUsers;
use Doctrine\ORM\Mapping as ORM;

/**
 * DNewsletters
 *
 * @ORM\Table(name="d_newsletters")
 * @ORM\Entity(repositoryClass="Business\Newsletter\Entity\DNewslettersRepository")
 */
class DNewsletters {
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
	 * @ORM\Column(name="description", type="text", length=65535, nullable=true)
	 */
	private $description;

	/**
	 * @var \Doctrine\Common\Collections\Collection
	 *
	 * @ORM\ManyToMany(targetEntity="\Business\Usuarios\Entity\DUsers", mappedBy="dNewsletter")
	 * @ORM\JoinTable(name="d_users_d_newsletters",
	 *   joinColumns={
	 *     @ORM\JoinColumn(name="d_user_id", referencedColumnName="id")
	 *   },
	 *   inverseJoinColumns={
	 *     @ORM\JoinColumn(name="d_newsletter_id", referencedColumnName="id")
	 *   }
	 * )
	 */
	private $dUser;

	/**
	 * Constructor
	 */
	public function __construct() {
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
	 * @return DNewsletters
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
	 * @return DNewsletters
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
	 * Add dUser
	 *
	 * @param DUsers $dUser
	 *
	 * @return DNewsletters
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
}

