<?php

namespace Business\Planos\Entity;
use Business\Empresas\Entity\DCompanies;
use Business\Perfis\Entity\DProfiles;
use Business\Produtos\Entity\DProducts;
use Doctrine\ORM\Mapping as ORM;

/**
 * DPlans
 *
 * @ORM\Table(name="d_plans", indexes={@ORM\Index(name="fk_d_plans_d_products1_idx", columns={"d_product_id"})})
 * @ORM\Entity(repositoryClass="Business\Planos\Entity\DPlansRepository")
 */
class DPlans
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
     * @var integer
     *
     * @ORM\Column(name="`order`", type="integer", nullable=true)
     */
    private $order;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

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
     * @var DProducts
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
     * @ORM\ManyToMany(targetEntity="\Business\Empresas\Entity\DCompanies", inversedBy="dPlan")
     * @ORM\JoinTable(name="d_plans_d_companies",
     *   joinColumns={
     *     @ORM\JoinColumn(name="d_plan_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="d_company_id", referencedColumnName="id")
     *   }
     * )
     */
    private $dCompany;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="\Business\Perfis\Entity\DProfiles", inversedBy="dPlan")
     * @ORM\JoinTable(name="d_plans_d_profiles",
     *   joinColumns={
     *     @ORM\JoinColumn(name="d_plan_id", referencedColumnName="id")
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
    public function __construct()
    {
        $this->dCompany = new \Doctrine\Common\Collections\ArrayCollection();
        $this->dProfile = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set order
     *
     * @param integer $order
     *
     * @return DPlans
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
     * Set name
     *
     * @param string $name
     *
     * @return DPlans
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
     * Set created
     *
     * @param \DateTime $created
     *
     * @return DPlans
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
     * @return DPlans
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
     * @return DPlans
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

    /**
     * Set dProduct
     *
     * @param DProducts $dProduct
     *
     * @return DPlans
     */
    public function setDProduct(DProducts $dProduct = null)
    {
        $this->dProduct = $dProduct;

        return $this;
    }

    /**
     * Get dProduct
     *
     * @return DProducts
     */
    public function getDProduct()
    {
        return $this->dProduct;
    }

    /**
     * Add dCompany
     *
     * @param DCompanies $dCompany
     *
     * @return DPlans
     */
    public function addDCompany(DCompanies $dCompany)
    {
        $this->dCompany[] = $dCompany;

        return $this;
    }

    /**
     * Remove dCompany
     *
     * @param DCompanies $dCompany
     */
    public function removeDCompany(DCompanies $dCompany)
    {
        $this->dCompany->removeElement($dCompany);
    }

    /**
     * Get dCompany
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDCompany()
    {
        return $this->dCompany;
    }

    /**
     * Add dProfile
     *
     * @param \DProfiles $dProfile
     *
     * @return DPlans
     */
    public function addDProfile(DProfiles $dProfile)
    {
        $this->dProfile[] = $dProfile;

        return $this;
    }

    /**
     * Remove dProfile
     *
     * @param \DProfiles $dProfile
     */
    public function removeDProfile(DProfiles $dProfile)
    {
        $this->dProfile->removeElement($dProfile);
    }

    /**
     * Get dProfile
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDProfile()
    {
        return $this->dProfile;
    }
}

