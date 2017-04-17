<?php
/**
 * This file is part of the ceramicscalc PHP library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DerekPhilipAu\Ceramicscalc\Models\Material;

class PrimitiveMaterial extends AbstractMaterial {

	public function __construct($uniqueId)
	{
		$this->uniqueId = $uniqueId;
	}

	public function isComposite()
	{
		return false;
	}

	public function getSimplifiedMaterial()
	{
		return clone $this;
	}

	function getMaterialCount()
	{
		return 1;
	}
	
	function addMaterial(AbstractMaterial $material)
	{
		throw new Exception('Cannot add material to primitive material.');
	}

	function removeMaterial(AbstractMaterial $material)
	{
		throw new Exception('Cannot remove material from primitive material.');
	}

}

?>
