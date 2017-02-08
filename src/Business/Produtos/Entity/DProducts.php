<?php

namespace Business\Produtos\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * DProducts
 *
 * @ORM\Table(name="d_products")
 * @ORM\Entity(repositoryClass="Business\Produtos\Entity\DProductsRepository")
 */
class DProducts
{
	const PREFIX = 'prod';
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    var $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=45, nullable=true)
     */
    private $slug;

    /**
     * @var integer
     *
     * @ORM\Column(name="image_url", type="string", nullable=true)
     */
    private $imageUrl;

    /**
     * @var boolean
     *
     * @ORM\Column(name="can_access_user_list", type="boolean", nullable=true)
     */
    private $canAccessUserList;

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
     * @ORM\Column(name="is_temp", type="boolean", nullable=true)
     */
    private $isTemp = '1';


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
     * @return DProducts
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
     * Set url
     *
     * @param string $url
     *
     * @return DProducts
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return DProducts
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
     * Set imageUrl
     *
     * @param string $imageUrl
     *
     * @return DProducts
     */
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    /**
     * Get imageUrl
     *
     * @return string
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * Set canAccessUserList
     *
     * @param boolean $canAccessUserList
     *
     * @return DProducts
     */
    public function setCanAccessUserList($canAccessUserList)
    {
        $this->canAccessUserList = $canAccessUserList;

        return $this;
    }

    /**
     * Get canAccessUserList
     *
     * @return boolean
     */
    public function getCanAccessUserList()
    {
        return $this->canAccessUserList;
    }

    /**
     * Set order
     *
     * @param integer $order
     *
     * @return DProducts
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
     * Set created
     *
     * @param \DateTime $created
     *
     * @return DProducts
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set modified
     *
     * @param \DateTime $modified
     *
     * @return DProducts
     */
    public function setModified($modified)
    {
        $this->modified = $modified;

        return $this;
    }

    /**
     * Get modified
     *
     * @return \DateTime
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * Set isTemp
     *
     * @param boolean $isTemp
     *
     * @return DProducts
     */
    public function setIsTemp($isTemp)
    {
        $this->isTemp = $isTemp;

        return $this;
    }

    /**
     * Get isTemp
     *
     * @return boolean
     */
    public function getIsTemp()
    {
        return $this->isTemp;
    }
}

