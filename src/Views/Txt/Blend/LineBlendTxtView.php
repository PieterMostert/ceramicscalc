<?php
/**
 * This file is part of the ceramicscalc PHP library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DerekPhilipAu\Ceramicscalc\Views\Txt\Blend;

use DerekPhilipAu\Ceramicscalc\Models\Analysis\Analysis;
use DerekPhilipAu\Ceramicscalc\Views\Txt\Material\MaterialTxtView;

class LineBlendTxtView
{
	public static function toString($lineBlend, $decimals = 2)
	{
        $str = '';
        $i = 0;
		foreach ($lineBlend as $blendedMaterial)
        {
            $str .= 'BLEND ' . $i . ":" . PHP_EOL;
            $str .= MaterialTxtView::toString($blendedMaterial);
            $i++;
		}
        return $str;
	}

    public static function printTxt($analysis)
    {
		echo LineBlendTxtView::toString($analysis);
    }



} // end class AnalysisTxtView

?>
