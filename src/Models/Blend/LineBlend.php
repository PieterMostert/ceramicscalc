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

class LineBlend {


	public static function createLineBlend(
		AbstractMaterial $firstMaterial,
		AbstractMaterial $secondMaterial,
		$first_minimum_percent,
		$first_maximum_percent,
		$second_minimum_percent,
		$second_maximum_percent,
		$step_percentage)
	{
		$blends = array();

		if (empty($firstMaterial))
		{
			throw new Exception('First Glazy empty.');
		}
		if (empty($secondMaterial))
		{
			throw new Exception('Second Glazy empty.');
		}
		if ($first_minimum_percent > $first_maximum_percent)
		{
			throw new Exception('Line Blend first material minimum percentage must be less than maximum percentage.');
		}
		if ($second_minimum_percent > $second_maximum_percent)
		{
			throw new Exception('Line Blend second material minimum percentage must be less than maximum percentage.');
		}

		$current_first_percentage = $first_maximum_percent;
		$current_second_percentage = 100 - $current_first_percentage;

		while (
			$current_first_percentage <= $first_maximum_percent &&
			$current_first_percentage >= $first_minimum_percent &&
			$current_second_percentage <= $second_maximum_percent &&
			$current_second_percentage >= $second_minimum_percent &&
			$current_first_percentage <= 100 &&
			$current_first_percentage >= 0 &&
			$current_second_percentage <= 100 &&
			$current_second_percentage >= 0
		)
		{
			$blend = new CompositeMaterial();
			$blend->addMaterial($firstMaterial, $current_first_percentage);
			$blend->addMaterial($secondMaterial, $current_second_percentage);

			$blends[] = $blend;

			$current_first_percentage -= $step_percentage;
			$current_second_percentage = 100 - $current_first_percentage;
		}

		return $blends;
	}


}

?>