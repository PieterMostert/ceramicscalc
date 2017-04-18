<?php
/**
 * This file is part of the ceramicscalc PHP library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DerekPhilipAu\Ceramicscalc\Models\Material;

use DerekPhilipAu\Ceramicscalc\Models\Analysis\Analysis;
use DerekPhilipAu\Ceramicscalc\Models\Analysis\PercentageAnalysis;

class CompositeMaterial extends AbstractMaterial {

    private $components_total_percentage = 0.0;
	private $materialComponents = array();

    public function isComposite()
    {
        return true;
    }

    public function sortByAmount(){}

    public function getMaterialComponents()
    {
        return $this->materialComponents;
    }

    public function getSimplifiedMaterial()
    {
        $flattenedComponents = CompositeMaterial::getFlattenedMaterialComponents();
        $simplifiedMaterial = clone $this;
        $simplifiedMaterial->replaceMaterialComponents($flattenedComponents);
        return $simplifiedMaterial;
    }

    protected function getFlattenedMaterialComponents()
    {
        $flattenedComponents = array();
        $this->traverseComponents($this->materialComponents, $flattenedComponents, 100);

        // Combine materials having the same unique ID
        $consolidatedMaterialComponents = array();
        foreach($flattenedComponents as $materialComponent)
        {
            $material = $materialComponent->getMaterial();
            if (isset($consolidatedMaterialComponents[$material->getUniqueId()]))
            {
                $existingMaterialComponent = $consolidatedMaterialComponents[$material->getUniqueId()];
                $existingMaterialComponent->setAmount($existingMaterialComponent->getAmount() + $materialComponent->getAmount());
            }
            else
            {
                $newMaterialComponent = clone $materialComponent;
                $consolidatedMaterialComponents[$material->getUniqueId()] = $newMaterialComponent;
            }
        }
        foreach($consolidatedMaterialComponents as $key => $materialComponent)
        {
            if ($materialComponent->getAmount() < Analysis::EPSILON)
            {
                unset($consolidatedMaterialComponents[$key]);
            }
        }
        return $consolidatedMaterialComponents;
    }

    protected function traverseComponents(array &$materialComponents, array &$flattenedComponents, $percentage)
    {
        foreach($materialComponents as $materialComponent)
        {
            $material = $materialComponent->getMaterial();
            $amount = $materialComponent->getAmount();
            if ($material->isComposite())
            {
                $componentMaterialComponents = $material->getMaterialComponents();
                $this->traverseComponents($componentMaterialComponents, $flattenedComponents, $amount);
            }
            else
            {
                $newComponent = new CompositeMaterialComponent();
                $newComponent->setMaterial($material);
                $newComponent->setAmount($amount * $percentage / 100);
                $flattenedComponents[] = $newComponent;
            }
        }
    }

    public function getMaterialCount()
	{
		return count($this->materialComponents);
	}

	public function getMaterial($uniqueId)
    {
        foreach ($this->materialComponents as $materialRow)
        {
            if ($materialRow->hasMaterialUniqueId($uniqueId))
            {
                return $materialRow->getMaterial();
            }
        }
        return null;
	}

    public function getMaterialComponent($uniqueId)
    {
        foreach ($this->materialComponents as $materialRow)
        {
            if ($materialRow->hasMaterialUniqueId($uniqueId))
            {
                return $materialRow;
            }
        }
        return null;
    }

	public function addMaterial(AbstractMaterial $material, $amount, $is_additional = false)
    {
/*
        if ($amount + $this->components_total_percentage > 100)
        {
            throw new Exception('CompositeMaterial requires component material amounts in percentages, with total not exceeding 100%');
        }
*/
        $materialRow = new CompositeMaterialComponent();
        $materialRow->setMaterial($material);
        $materialRow->setAmount($amount);
        $materialRow->setIsAdditional($is_additional);

		$this->materialComponents[] = $materialRow;

        $materialPercentageAnalysis = $material->getPercentageAnalysis();
        $totalPercentageAnalysis = $this->getPercentageAnalysis();

/*
        $percentage = 100 - $loi;
        // DAU:  Bug- divide by zero
        if ($percentage > 0.00001) {
            $this->analysis->setOxide($oxide_name, ((GlazyMolarMassList::$weights[$oxide_name] * $formula_weight) / $this->formula_weight * $percentage));
        }
        else {
            $this->analysis->setOxide($oxide_name, 0);
        }
*/

        foreach (Analysis::OXIDE_NAMES as $name) {
            $newPercentage = $totalPercentageAnalysis->getOxide($name) +
                ($materialPercentageAnalysis->getOxide($name) * $amount / 100);
            $totalPercentageAnalysis->setOxide($name, $newPercentage);
        }

        // update total LOI
        $newLoi = $totalPercentageAnalysis->getLOI() + ($materialPercentageAnalysis->getLOI() * $amount / 100);

        $totalPercentageAnalysis->setLOI($newLoi);

        $this->update();

		return $this->getMaterialCount();
	}

    public function addAdditionalMaterial(AbstractMaterial $material, $amount)
    {
        return $this->addMaterial($material, $amount, true);
    }


    protected function replaceMaterialComponents($materialComponents)
    {
        $this->materialComponents = $materialComponents;
        $this->update();
    }

	public function removeMaterial(AbstractMaterial $remove)
    {
        $this->removeMaterialByUniqueId($remove->getUniqueId());
    }

    public function removeMaterialByUniqueId($uniqueId) {

        foreach ($this->materialComponents as $key => $materialRow)
        {
            if ($materialRow->hasMaterialUniqueId($uniqueId))
            {
                unset($this->materialComponents[$key]);
                $this->update();
                return;
            }
        }

        // Should we traverse and find the material???
        foreach ($this->materialComponents as $key => $materialRow)
        {
            $material = $materialRow->getMaterial();
            if ($material->isComposite())
            {
                // Remove this material from a child composite material
                $material->removeMaterialByUniqueId($uniqueId);
            }
        }
        $this->update();
    }

    protected function update()
    {
        $this->recalculateTotalPercentageAmount();
        $this->recalculateAnalysis();
    }

    protected function recalculateAnalysis()
    {
        $totalPercentages = new PercentageAnalysis();

        foreach ($this->materialComponents as $materialRow)
        {
            $material = $materialRow->getMaterial();
            $amount = $materialRow->getAmount();

            $materialPercentages = $material->getPercentageAnalysis();

            foreach (Analysis::OXIDE_NAMES as $name)
            {
                $subtotal = $totalPercentages->getOxide($name) +
                    ($materialPercentages->getOxide($name) * $amount / $this->components_total_percentage);
                $totalPercentages->setOxide($name, $subtotal);
            }

            $newLoi = $totalPercentages->getLOI() + ($materialPercentages->getLOI() * $amount / $this->components_total_percentage);

            $totalPercentages->setLOI($newLoi);

        }
        
        $this->setPercentageAnalysis($totalPercentages);
    }

    protected function recalculateTotalPercentageAmount()
    {
        $this->components_total_percentage = 0.0;
        foreach ($this->materialComponents as $materialComponent)
        {
            $this->components_total_percentage += $materialComponent->getAmount();
        }
    }


}

?>
