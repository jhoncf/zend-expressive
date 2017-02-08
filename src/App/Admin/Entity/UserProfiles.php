<?php

namespace App\Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserProfiles
 *
 * @ORM\Table(name="user_profiles")
 * @ORM\Entity(repositoryClass="App\Admin\Entity\UserProfilesRepository")
 */
class UserProfiles
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
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=60, nullable=true)
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
     * @ORM\ManyToMany(targetEntity="UserPermissions", inversedBy="userProfile")
     * @ORM\JoinTable(name="user_profiles_user_permissions",
     *   joinColumns={
     *     @ORM\JoinColumn(name="user_profile_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="user_permission_id", referencedColumnName="id")
     *   }
     * )
     */
    private $userPermission;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="UserUsers", mappedBy="userProfile")
     */
    private $userUser;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->userPermission = new \Doctrine\Common\Collections\ArrayCollection();
        $this->userUser = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return UserProfiles
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
     * Set slug
     *
     * @param string $slug
     *
     * @return UserProfiles
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return UserProfiles
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set created
     *
     * @param string $created
     *
     * @return UserProfiles
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return string
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set modified
     *
     * @param string $modified
     *
     * @return UserProfiles
     */
    public function setModified($modified)
    {
        $this->modified = $modified;

        return $this;
    }

    /**
     * Get modified
     *
     * @return string
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * Add userPermission
     *
     * @param UserPermissions $userPermission
     *
     * @return UserProfiles
     */
    public function addUserPermission(UserPermissions $userPermission)
    {
        $this->userPermission[] = $userPermission;

        return $this;
    }

    /**
     * Remove userPermission
     *
     * @param UserPermissions $userPermission
     */
    public function removeUserPermission(UserPermissions $userPermission)
    {
        $this->userPermission->removeElement($userPermission);
    }

    /**
     * Get userPermission
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserPermission()
    {
        return $this->userPermission;
    }

    /**
     * Add userUser
     *
     * @param UserUsers $userUser
     *
     * @return UserProfiles
     */
    public function addUserUser(UserUsers $userUser)
    {
        $this->userUser[] = $userUser;

        return $this;
    }

    /**
     * Remove userUser
     *
     * @param UserUsers $userUser
     */
    public function removeUserUser(UserUsers $userUser)
    {
        $this->userUser->removeElement($userUser);
    }

    /**
     * Get userUser
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserUser()
    {
        return $this->userUser;
    }

	/**
	 * Get clearUserProfile
	 */
	public function clearUserPermission() {
		$this->userPermission->clear();
	}

}

