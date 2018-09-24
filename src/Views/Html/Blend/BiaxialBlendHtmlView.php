<?php
/**
 * This file is part of the ceramicscalc PHP library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DerekPhilipAu\Ceramicscalc\Views\Html\Blend;

use DerekPhilipAu\Ceramicscalc\Views\Html\Material\MaterialHtmlView;

class BiaxialBlendHtmlView
{
	public static function toString($biaxialBlend, $decimals = 2)
	{
        $str = '';
        $numberOfRows = count($biaxialBlend);
        $numberOfColumns = count($biaxialBlend[0]);

        $str .= "\n\n<table border='1'>\n<tbody>\n";
        for ($currentRow = 0; $currentRow < $numberOfRows; $currentRow++) {
            $str .= '<tr>';
            for ($currentColumn = 0; $currentColumn < $numberOfColumns; $currentColumn++) {
                $str .= '<td>';
                $str .= PHP_EOL . 'BIAXIAL BLEND Row: ' . $currentRow . " Column: " . $currentColumn . PHP_EOL;
                $str .= MaterialHtmlView::toString($biaxialBlend[$currentRow][$currentColumn]);
                $str .= '</td>';
            }
            $str .= '</tr>';
        }
        $str .= "\n</tbody>\n</table>\n\n";

        return $str;
	}

    public static function print($analysis)
    {
		echo BiaxialBlendHtmlView::toString($analysis);
    }



} // end class AnalysisHtmlView

?>
