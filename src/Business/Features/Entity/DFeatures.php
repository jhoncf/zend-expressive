<?php

namespace Business\Features\Entity;

use Business\Produtos\Entity\DProducts;
use Doctrine\ORM\Mapping as ORM;

/**
 * DFeatures
 *
 * @ORM\Table(name="d_features", indexes={@ORM\Index(name="fk_d_features_d_products1_idx", columns={"d_product_id"})})
 * @ORM\Entity(repositoryClass="Business\Features\Entity\DFeaturesRepository")
 */
class DFeatures {
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
	 * @ORM\Column(name="type", type="string", nullable=true)
	 */
	private $type;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="`order`", type="integer", nullable=true)
	 */
	private $order;

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
	 * @return DFeatures
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
	 * Set type
	 *
	 * @param string $type
	 *
	 * @return DFeatures
	 */
	public function setType($type) {
		$this->type = $type;

		return $this;
	}

	/**
	 * Get type
	 *
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Set order
	 *
	 * @param integer $order
	 *
	 * @return DFeatures
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
	 * Set dProduct
	 *
	 * @param DProducts $dProduct
	 *
	 * @return DFeatures
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
}

