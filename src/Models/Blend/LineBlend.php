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
 * Class LineBlend
 * @package DerekPhilipAu\Ceramicscalc\Models\Blend
 *
 * This is just a basic implementation of a class to help make line blends.
 *
 * In the future, triaxial and biaxial blends should be added.
 */
class LineBlend {

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
	public static function createLineBlend(
		AbstractMaterial $firstMaterial,
		AbstractMaterial $secondMaterial,
		$firstMinimumPercent,
		$firstMaximumPercent,
		$secondMinimumPercent,
		$secondMaximumPercent,
		$stepPercentage) : array
	{
		$blends = array();

		if (empty($firstMaterial))
		{
			throw new Exception('First Glaze empty.');
		}
		if (empty($secondMaterial))
		{
			throw new Exception('Second Glaze empty.');
		}
		if ($firstMinimumPercent > $firstMaximumPercent)
		{
			throw new Exception('Line Blend first material minimum percentage must be less than maximum percentage.');
		}
		if ($secondMinimumPercent > $secondMaximumPercent)
		{
			throw new Exception('Line Blend second material minimum percentage must be less than maximum percentage.');
		}

		$currentFirstPercentage = $firstMaximumPercent;
		$currentSecondPercentage = 100 - $currentFirstPercentage;

		while (
			$currentFirstPercentage <= $firstMaximumPercent &&
			$currentFirstPercentage >= $firstMinimumPercent &&
			$currentSecondPercentage <= $secondMaximumPercent &&
			$currentSecondPercentage >= $secondMinimumPercent &&
			$currentFirstPercentage <= 100 &&
			$currentFirstPercentage >= 0 &&
			$currentSecondPercentage <= 100 &&
			$currentSecondPercentage >= 0
		)
		{
			$blend = new CompositeMaterial();
			$blend->addMaterial($firstMaterial, $currentFirstPercentage);
			$blend->addMaterial($secondMaterial, $currentSecondPercentage);

			$blends[] = $blend;

			$currentFirstPercentage -= $stepPercentage;
			$currentSecondPercentage = 100 - $currentFirstPercentage;
		}

		return $blends;
	}


}

?>
