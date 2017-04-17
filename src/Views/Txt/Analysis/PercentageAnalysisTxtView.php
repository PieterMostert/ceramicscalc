<?php
/**
 * This file is part of the ceramicscalc PHP library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DerekPhilipAu\Ceramicscalc\Views\Txt\Analysis;

use DerekPhilipAu\Ceramicscalc\Models\Analysis\Analysis;

class PercentageAnalysisTxtView extends AnalysisTxtView
{
    public static function toString($analysis, $decimals = 3, $includeEmpty = false)
    {
        $str = parent::toString($analysis, $decimals = 3, $includeEmpty = false);
        $str .= 'LOI: ' . number_format($analysis->getLOI(), $decimals) . "\n";
        return $str;
    }


} // end class AnalysisTxtView

?>
