<?php
/**
 * This file is part of the ceramicscalc PHP library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DerekPhilipAu\Ceramicscalc\Models\Blend;

use DerekPhilipAu\Ceramicscalc\Models\Material\AbstractMaterial;
use DerekPhilipAu\Ceramicscalc\Models\Material\CompositeMaterial;

/**
 * Class BiaxialBlend
 * @package DerekPhilipAu\Ceramicscalc\Models\Blend
 */
class BiaxialBlend {

    /**
     * @param AbstractMaterial $topLeftMaterial,
	 * @param AbstractMaterial $topRightMaterial,
     * @param AbstractMaterial $bottomLeftMaterial,
     * @param AbstractMaterial $bottomRightMaterial,
     * @param $numberOfRows,
     * @param $numberOfColumns,
     * @return array
	 *
	 * Return an array containing each blended CompositeMaterial in the biaxial blend.
     */
	public static function createBiaxialBlend(
		AbstractMaterial $topLeftMaterial,
		AbstractMaterial $topRightMaterial,
        AbstractMaterial $bottomLeftMaterial,
        AbstractMaterial $bottomRightMaterial,
		$numberOfRows,
		$numberOfColumns) : array
	{
		if (empty($topLeftMaterial)) { throw new Exception('First Glaze empty.'); }
        if (empty($topRightMaterial)) { throw new Exception('First Glaze empty.'); }
        if (empty($bottomLeftMaterial)) { throw new Exception('First Glaze empty.'); }
        if (empty($bottomRightMaterial)) { throw new Exception('First Glaze empty.'); }
        if (($numberOfRows < 2) || ($numberOfColumns < 2)) { throw new Exception('Rows and columns must be greater than one.'); }

        $blends = array();

        for ($currentRow = 0; $currentRow < $numberOfRows; $currentRow++) {
            for ($currentColumn = 0; $currentColumn < $numberOfColumns; $currentColumn++) {

                $topLeftMaterialPercentage =
					((($numberOfColumns - 1) - $currentColumn) / ($numberOfColumns - 1))
					*
					((($numberOfRows - 1) - $currentRow) / ($numberOfRows - 1))
					*
					100;

                $topRightMaterialPercentage =
					($currentColumn / ($numberOfColumns - 1))
					*
                    ((($numberOfRows - 1) - $currentRow) / ($numberOfRows - 1))
					*
					100;

                $bottomLeftMaterialPercentage =
                    ((($numberOfColumns - 1) - $currentColumn) / ($numberOfColumns - 1))
                    *
                    ($currentRow / ($numberOfRows - 1))
                    *
                    100;

                $bottomRightMaterialPercentage =
                    ($currentColumn / ($numberOfColumns - 1))
                    *
                    ($currentRow / ($numberOfRows - 1))
                    *
                    100;

                $blend = new CompositeMaterial();
                $blend->addMaterial($topLeftMaterial, $topLeftMaterialPercentage);
                $blend->addMaterial($topRightMaterial, $topRightMaterialPercentage);
                $blend->addMaterial($bottomLeftMaterial, $bottomLeftMaterialPercentage);
                $blend->addMaterial($bottomRightMaterial, $bottomRightMaterialPercentage);

                $blends[$currentRow][$currentColumn] = $blend;
            }
		}

		return $blends;
	}


}

?>
