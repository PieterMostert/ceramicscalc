<?php
/**
 * This file is part of the ceramicscalc PHP library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DerekPhilipAu\Ceramicscalc\Models\Material;

class CompositeMaterialComponent {

    private $amount;
	private $material;
    private $is_additional;

    public function __clone() {
        $this->material = clone $this->material;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setMaterial(AbstractMaterial $material)
    {
        $this->material = $material;
    }

	public function getMaterial()
	{
        return $this->material;
	}

    public function setIsAdditional($is_additional)
    {
        $this->is_additional = $is_additional;
    }

    public function getIsAdditional()
    {
        return $this->is_additional;
    }

    public function hasMaterial(AbstractMaterial $material)
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

    public function hasMaterialUniqueId($uniqueId)
    {
        if ($this->material->getUniqueId() == $uniqueId)
        {
            return true;
        }

        return false;
    }


}

?>
