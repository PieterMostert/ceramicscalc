<?php
/**
 * This file is part of the ceramicscalc PHP library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DerekPhilipAu\Ceramicscalc\Models\Material;

/**
 * Class PrimitiveMaterial
 * @package DerekPhilipAu\Ceramicscalc\Models\Material
 *
 * A class that represents single material, e.g. "Kaolin", "EPK Kaolin", "Feldspar", "Custer Feldspar".
 * "PrimitiveMaterial" is a misleading name, as often these materials are quite complex and variable.
 * However, for our purposes we can think of any material that is purchased "in a bag" as a
 * "primitive" material.  Therefore a bag of 200-mesh Silica is a different primitive material than
 * 400-mesh Silica.
 *
 * However, "primitive" materials may also include theoretical materials such as "Kaolin".  "Kaolin" is
 * an idealized material with an analysis based on its chemical formula.
 */
class PrimitiveMaterial extends AbstractMaterial {

    /**
     * PrimitiveMaterial constructor.
     * @param $uniqueId
	 *
	 * TODO:  Think about how better to associate materials with unique ID's (usually from a database).
     */
	public function __construct($uniqueId)
	{
		$this->uniqueId = $uniqueId;
	}

    /**
     * @return bool
	 *
	 * Part of the Composite Pattern.  A PrimitiveMaterial cannot be broken down into constituent parts.
	 * Therefore, isComposite() always returns false.
     */
	public function isComposite()
	{
		return false;
	}

    /**
     * @return PrimitiveMaterial
	 *
	 * A "simplified" material is one that is not composed of multiple PrimitiveMaterials.  For example,
	 * a CompositeMaterial (or blend) of two recipes each containing proportions of Potash Feldspar will
	 * contain two leaf nodes with Potash Feldspar.  A "simplified" material is a material that has been
	 * flattened with identical materials combined.
	 *
	 * "Primitive" materials are already in their simplest form, so we simply return a copy of this object.
     */
	public function getSimplifiedMaterial()
	{
		return clone $this;
	}

    /**
     * @return int
	 *
	 * Part of the Composite pattern, getMaterialCount() for a PrimitiveMaterial will always return 1.
     */
	function getMaterialCount()
	{
		return 1;
	}

    /**
     * @param AbstractMaterial $material
	 *
	 * Part of the Composite pattern, by definition PrimitiveMaterials only represent one material,
	 * thus addMaterial() should throw an exception.
	 *
	 * TODO:  Consider an Exception hierarchy for this package.
     */
	function addMaterial(AbstractMaterial $material)
	{
		throw new Exception('Cannot add material to primitive material.');
	}

    /**
     * @param AbstractMaterial $material
     *
     * Part of the Composite pattern, by definition PrimitiveMaterials only represent one material,
     * thus no materials can be removed from them.
     *
     * TODO:  Consider an Exception hierarchy for this package.
     */
	function removeMaterial(AbstractMaterial $material)
	{
		throw new Exception('Cannot remove material from primitive material.');
	}

}

?>
