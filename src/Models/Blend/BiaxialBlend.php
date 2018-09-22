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
 *
 * This is just a basic implementation of a class to help make line blends.
 *
 * In the future, triaxial and biaxial blends should be added.
 */
class BiaxialBlend {

    /**
     * @param AbstractMaterial $firstMaterial
     * @param AbstractMaterial $secondMaterial
     * @param $firstMinimumPercent
     * @param $firstMaximumPercent
     * @param $secondMinimumPercent
     * @param $secondMaximumPercent
     * @param $stepPercentage
     * @return array
	 *
	 * Return an array containing each blended CompositeMaterial in the line blend.
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
        if (!$numberOfRows || !$numberOfColumns) { throw new Exception('Rows and columns must be greater than zero.'); }

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
