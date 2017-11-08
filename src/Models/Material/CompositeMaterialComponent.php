<?php
/**
 * This file is part of the ceramicscalc PHP library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DerekPhilipAu\Ceramicscalc\Models\Material;

/**
 * Class CompositeMaterialComponent
 * @package DerekPhilipAu\Ceramicscalc\Models\Material
 *
 * Given a typical recipe like "Leach 4321 Celadon":
 *      40    Feldspar
 *      30    Silica
 *      20    Whiting
 *      10    Kaolin
 *      (100)
 *      +1    Red Iron Oxide
 *      (101)
 *
 * The recipe "Leach 4321 Celadon" is represented by a CompositeMaterial.
 *
 * Each component or "row" of this recipe is a CompositeMaterialComponent.
 *
 * A CompositeMaterialComponent contains:
 *      - a material (either Primitive or Composite)
 *      - a percentage amount (e.g. 40% Feldspar)
 *      - whether or not the amount is "additional" (e.g. Red Iron Oxide)
 *
 * "Additional" is simply an attribute set by the user.  It does not in any
 * way affect the chemical composition of the composite material.  Traditionally,
 * coloring oxides and opacifiers are considered "additional" materials.
 */
class CompositeMaterialComponent {

    /**
     * @var The percentage amount of the material
     */
    private $amount;

    /**
     * @var The material
     */
	private $material;

    /**
     * @var Whether or not this material is "additional"
     */
    private $isAdditional;

    /**
     * Make sure we deep-copy the component material.
     *
     * TODO: Check PHP 7 to see how this should be done, if at all.
     */
    public function __clone() {
        $this->material = clone $this->material;
    }

    /**
     * @param $amount
     * Set the percentage amount of the material component
     */
    public function setAmount(float $amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return float
     *
     * Return the component percentage amount
     */
    public function getAmount() : float
    {
        return $this->amount;
    }

    /**
     * @param AbstractMaterial $material
     */
    public function setMaterial(AbstractMaterial $material)
    {
        $this->material = $material;
    }

    /**
     * @return AbstractMaterial
     */
	public function getMaterial() : AbstractMaterial
	{
        return $this->material;
	}

    /**
     * @param bool $isAdditional
     */
    public function setIsAdditional(bool $isAdditional)
    {
        $this->isAdditional = $isAdditional;
    }

    /**
     * @return bool
     */
    public function getIsAdditional() : bool
    {
        return $this->isAdditional;
    }

    /**
     * @param AbstractMaterial $material
     * @return bool
     */
    public function hasMaterial(AbstractMaterial $material) : bool
    {
        if ($this->material->getUniqueId() == $material->getUniqueId())
        {
            return true;
        }
        if ($this->material->isCompositeMaterial())
        {
            $materialComponents = $material->getMaterialComponents();
            foreach($materialComponents as $materialComponent)
            {
                if ($materialComponent->hasMaterial())
                {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param $uniqueId
     * @return bool
     */
    public function hasMaterialUniqueId($uniqueId) : bool
    {
        if ($this->material->getUniqueId() == $uniqueId)
        {
            return true;
        }

        return false;
    }


}

?>
