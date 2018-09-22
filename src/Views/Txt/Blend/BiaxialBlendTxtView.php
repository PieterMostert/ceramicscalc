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

class BiaxialBlendTxtView
{
	public static function toString($biaxialBlend, $decimals = 2)
	{
        $str = '';
        $numberOfRows = count($biaxialBlend);
        $numberOfColumns = count($biaxialBlend[0]);

        for ($currentRow = 0; $currentRow < $numberOfRows; $currentRow++) {
            for ($currentColumn = 0; $currentColumn < $numberOfColumns; $currentColumn++) {
                $str .= PHP_EOL . 'BIAXIAL BLEND Row: ' . $currentRow . " Column: " . $currentColumn . PHP_EOL;
                $str .= MaterialTxtView::toString($biaxialBlend[$currentRow][$currentColumn]);
            }
        }
        return $str;
	}

    public static function print($analysis)
    {
		echo BiaxialBlendTxtView::toString($analysis);
    }



} // end class AnalysisTxtView

?>
