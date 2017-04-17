<?php
/**
 * This file is part of the ceramicscalc PHP library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DerekPhilipAu\Ceramicscalc\Views\Txt\Analysis;

use DerekPhilipAu\Ceramicscalc\Models\Analysis\Analysis;

class AnalysisTxtView
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
                    $str .= $name . ': ' . number_format($analysis->getOxide($name), $decimals) . "\n";
                }
            }
            else
            {
                $str .= $name . ': ' . number_format($analysis->getOxide($name), $decimals) . "\n";
            }
		}
        return $str;
	}

    public static function printTxt($analysis)
    {
		echo AnalysisTxtView::toString($analysis);
    }



} // end class AnalysisTxtView

?>
