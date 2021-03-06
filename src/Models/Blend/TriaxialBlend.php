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
 * Class TriaxialBlend
 * @package DerekPhilipAu\Ceramicscalc\Models\Blend
 *
 */
class TriaxialBlend
{
    /**
     * @param AbstractMaterial $topMaterial
     * @param AbstractMaterial $bottomLeftMaterial
     * @param AbstractMaterial $bottomRightMaterial
     * @param $dimension
     * @return array
	 *
	 * Return an array containing each blended CompositeMaterial in the triaxial blend (plus some empty entries).
   *
   * The array will look something like
   * X_30
   * X_20 X_21
   * X_10 X_11 X_12
   * X_00 X_01 X_02 X_03
   * where X_00 is the bottomLeftMaterial, X_03 is the bottomRightMaterial, and X_30 is the topMaterial
     */
	public static function createTriaxialBlend(
		AbstractMaterial $topMaterial,
        AbstractMaterial $bottomLeftMaterial,
        AbstractMaterial $bottomRightMaterial,
		$dimension) : array
	{
		if (empty($topMaterial)) { throw new Exception('First Glaze empty.'); }
        if (empty($bottomLeftMaterial)) { throw new Exception('Second Glaze empty.'); }
        if (empty($bottomRightMaterial)) { throw new Exception('Third Glaze empty.'); }
        if ($dimension < 2) { throw new Exception('Dimension must be greater than one.'); }
        $blends = array();
        for ($currentRow = 0; $currentRow < $dimension; $currentRow++) {
            $topMaterialPercentage =
                $currentRow / ($dimension - 1) * 100;
            for ($currentColumn = 0; $currentColumn < $dimension - $currentRow; $currentColumn++) {
                $bottomRightMaterialPercentage =
                    $currentColumn / ($dimension - 1) * 100;
                $bottomLeftMaterialPercentage =
					          ($dimension - 1 - $currentRow - $currentColumn) / ($dimension - 1) * 100;
                $blend = new CompositeMaterial();
                $blend->addMaterial($topMaterial, $topMaterialPercentage);
                $blend->addMaterial($bottomLeftMaterial, $bottomLeftMaterialPercentage);
                $blend->addMaterial($bottomRightMaterial, $bottomRightMaterialPercentage);
                $blends[$currentRow][$currentColumn] = $blend;
            }
        }
        return $blends;
    }
}
?>
