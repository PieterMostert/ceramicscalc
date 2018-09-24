<?php
/**
 * This file is part of the ceramicscalc PHP library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DerekPhilipAu\Ceramicscalc\Views\Html\Analysis;

use DerekPhilipAu\Ceramicscalc\Models\Analysis\Analysis;

class AnalysisHtmlView
{
	public static function toString($analysis, $decimals = 3, $includeEmpty = false)
	{
        $str = '';
		foreach (Analysis::OXIDE_NAMES as $name)
        {
            if ($analysis->getOxide($name) < Analysis::EPSILON)
            {
                if ($includeEmpty)
                {
                    $str .= $name . ': ' . number_format($analysis->getOxide($name), $decimals) . "<br/>";
                }
            }
            else
            {
                $str .= $name . ': ' . number_format($analysis->getOxide($name), $decimals) . "<br/>";
            }
		}

		$str .= 'SiO2:Al2O3: ' . number_format($analysis->getSiAlRatio(), $decimals) . "<br/>";
        return $str;
	}

    public static function print($analysis)
    {
		echo AnalysisHtmlView::toString($analysis);
    }



} // end class AnalysisHtmlView

?>
