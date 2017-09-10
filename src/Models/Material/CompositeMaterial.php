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

/**
 * Class CompositeMaterial
 * @package DerekPhilipAu\Ceramicscalc\Models\Material
 *
 * A class that represents a "composite" material, most often thought of as a "recipe".
 *
 * Recipes are usually composed of "primitive" materials, e.g. "Leach 4321":
 *      40 Feldspar
 *      30 Silica
 *      20 Whiting
 *      10 Kaolin
 *
 * Recipes can also be composed of other recipes, for example a mix between Leach 4321 & Pinnell Clear:
 *
 * 40/60 Leach/Pinnell Blend:
 *
 * 40.000% Leach 4321
 *      40.000% Potash Feldspar
 *      30.000% Silica
 *      20.000% Whiting
 *      10.000% Kaolin
 * 60.000% Pinnell Clear
 *      25.000% Potash Feldspar
 *      35.000% Silica
 *      20.000% Whiting
 *      20.000% Kaolin
 *
 * In the above example, the constituent materials are themselves "composite" materials (recipes).
 *
 * For convenience, we can view this "composite" material that contains other composite materials in a simplified form:
 *
 * Simplified Composite Material representing 40/60 Leach/Pinnell Blend:
 *      31.000% Potash Feldspar
 *      33.000% Silica
 *      20.000% Whiting
 *      16.000% Kaolin
 *
 * In this "simplified" form, the original composite material has been flattened with leaves containing duplicate
 * materials (such as Kaolin) combined into a single "ingredient".
 *
 * Chemically, this "simplified" material is equivalent to the original composite material.
 *
 */
class CompositeMaterial extends AbstractMaterial {

    /**
     * @var float
     *
     * "Recipes" do not always add up to 100%.  Therefore a CompositeMaterial containing 10% Kaolin & 30% Feldspar (40%)
     * is just as valid as one containing 50% Kaolin & 50% Feldspar (100%).
     *
     * $components_total_percentage simply keeps track of the total percentage contained by this material.
     */
    private $components_total_percentage = 0.0;

    /**
     * @var array
     *
     * Part of the Composite patern, this array is used to store component materials, whether they be "primitive"
     * or "composite" (i.e. consisting of other materials).
     */
	private $materialComponents = array();

    /**
     * @return bool
     *
     * Part of the Composite pattern, by definition isComposite() always returns true for CompositeMaterials.
     */
    public function isComposite() : bool
    {
        return true;
    }

    /**
     * TODO: Helper function to sort materials by amount, need better way to traverse materials based on
     * different sorting variables.
     */
    public function sortByAmount(){}

    /**
     * @return array
     *
     * Simply return the materials that this composite material contains.
     */
    public function getMaterialComponents() : array
    {
        return $this->materialComponents;
    }

    /**
     * @return CompositeMaterial
     *
     * A "simplified" material is one that is not composed of multiple PrimitiveMaterials.  For example,
     * a CompositeMaterial (or blend) of two recipes each containing proportions of Potash Feldspar will
     * contain two leaf nodes with Potash Feldspar.  A "simplified" material is a material that has been
     * flattened with identical materials combined.
     */
    public function getSimplifiedMaterial() : CompositeMaterial
    {
        $flattenedComponents = CompositeMaterial::getFlattenedMaterialComponents();
        $simplifiedMaterial = clone $this;
        $simplifiedMaterial->replaceMaterialComponents($flattenedComponents);
        return $simplifiedMaterial;
    }

    /**
     * @return array
     *
     * Helper function to flatten our composite material, combining materials that have the same unique ID.
     */
    protected function getFlattenedMaterialComponents() : array
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

    /**
     * @param array $materialComponents
     * @param array $flattenedComponents
     * @param $percentage
     *
     * Helper function for getFlattenedMaterialComponents().  Recursively traverse the composite material.
     */
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

    /**
     * @return int
     *
     * Currently only returns the count at the top-level of the tree.
     *
     * TODO:  The definition of the material count is not clear.
     */
    public function getMaterialCount() : int
	{
		return count($this->materialComponents);
	}

    /**
     * @param $uniqueId
     * @return AbstractMaterial
     *
     * Find a material in the composite tree with the same unique ID.  Return null if no material is found.
     *
     * TODO:  This does not take into account the occurrence of multiple component materials with the same
     * unique ID.
     *
     */
	public function getMaterial($uniqueId) : AbstractMaterial
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

    /**
     * @param $uniqueId
     * @return mixed|null
     *
     * Find a material in the composite tree with the same unique ID, returning the entire
     * CompositeMaterialComponent.  A CompositeMaterialComponent contains information about
     * how much of a given material occurs in the parent material, and specifies whether or not the
     * component material is "additional".
     */
    public function getMaterialComponent($uniqueId) : CompositeMaterialComponent
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

    /**
     * @param AbstractMaterial $material
     * @param $amount
     * @param bool $is_additional
     * @return int the updated $this->getMaterialCount()
     *
     * Add a material (either primitive or composite) to this composite material.
     * Update chemical analysis given the new ingredient.
     *
     */
	public function addMaterial(AbstractMaterial $material, $amount, $is_additional = false) : int
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

    /**
     * @param AbstractMaterial $material
     * @param $amount
     * @return int the updated $this->getMaterialCount()
     *
     * Helper function to add an "additional" material, e.g. Red Iron Oxide
     */
    public function addAdditionalMaterial(AbstractMaterial $material, $amount) : int
    {
        return $this->addMaterial($material, $amount, true);
    }

    /**
     * @param $materialComponents
     *
     * Swap out the material components and re-calculate.
     */
    protected function replaceMaterialComponents($materialComponents)
    {
        $this->materialComponents = $materialComponents;
        $this->update();
    }

    /**
     * @param AbstractMaterial $remove
     *
     * Remove a material by unique ID.
     *
     * TODO:  Unsure of how I decided to handle duplicates.
     */
	public function removeMaterial(AbstractMaterial $remove)
    {
        $this->removeMaterialByUniqueId($remove->getUniqueId());
    }

    /**
     * @param $uniqueId
     *
     * Helper function for removeMaterial(AbstractMaterial $remove)
     *
     * TODO:  Really need to decide if we're traversing entire tree or not!
     */
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

    /**
     * Typically called when a composite material's components have been modified,
     * update() re-calculates the total percentages as well as the chemical analysis.
     */
    protected function update()
    {
        $this->recalculateTotalPercentageAmount();
        $this->recalculateAnalysis();
    }

    /**
     * Helper function for update()
     */
    protected function recalculateTotalPercentageAmount()
    {
        $this->components_total_percentage = 0.0;
        foreach ($this->materialComponents as $materialComponent)
        {
            $this->components_total_percentage += $materialComponent->getAmount();
        }
    }

    /**
     * Helper function for update()
     */
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

}

?>
