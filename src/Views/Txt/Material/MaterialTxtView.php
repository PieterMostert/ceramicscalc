<?php
/**
 * This file is part of the ceramicscalc PHP library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DerekPhilipAu\Ceramicscalc\Views\Txt\Material;

use DerekPhilipAu\Ceramicscalc\Models\Analysis\FormulaAnalysis;
use DerekPhilipAu\Ceramicscalc\Models\Analysis\PercentageAnalysis;
use DerekPhilipAu\Ceramicscalc\Models\Material\AbstractMaterial;
use DerekPhilipAu\Ceramicscalc\Models\Material\CompositeMaterial;
use DerekPhilipAu\Ceramicscalc\Models\Material\CompositeMaterialComponent;
use DerekPhilipAu\Ceramicscalc\Views\Txt\Analysis\AnalysisTxtView;
use DerekPhilipAu\Ceramicscalc\Views\Txt\Analysis\PercentageAnalysisTxtView;

class MaterialTxtView
{
	public static function toString(AbstractMaterial $material, $decimals = 3, $includeEmpty = false)
	{
        $str = '';

        $str .= 'Name: ' . $material->getName() . PHP_EOL;
        $str .= 'Description: ' . $material->getDescription() . PHP_EOL;

        $str .= MaterialTxtView::toStringMaterialComponents($material, $decimals);

        $str .= "SIMPLIFIED VIEW: " . PHP_EOL;
        $simplifiedMaterial = $material->getSimplifiedMaterial();
        $str .= MaterialTxtView::toStringMaterialComponents($simplifiedMaterial, $decimals);

        $str .= "PERCENTAGE ANALYSIS: " . PHP_EOL;
        $str .= PercentageAnalysisTxtView::toString($material->getPercentageAnalysis(), $decimals, $includeEmpty);

        $str .= "100% PERCENTAGE ANALYSIS: " . PHP_EOL;
        $analysis = PercentageAnalysis::create100PercentPercentageAnalysis($material->getPercentageAnalysis());
        $str .= PercentageAnalysisTxtView::toString($analysis, $decimals, $includeEmpty);

        //$analysis = $material->getFormulaAnalysis();
        $analysis = FormulaAnalysis::createROR2OUnityFormulaAnalysis($material->getPercentageAnalysis());
        $str .= "UMF FORMULA: " . PHP_EOL;
        $str .= 'Formula Weight: ' . number_format($analysis->getFormulaWeight(), $decimals) . PHP_EOL;
        $str .= AnalysisTxtView::toString($analysis, $decimals, $includeEmpty);

        return $str;
	}

    public static function toStringMaterialComponents($material, $decimals, $indent = 0)
    {
        $str = '';

        if ($material->isComposite() && $material->getMaterialCount() > 0)
        {
            $materialComponents = $material->getMaterialComponents();
            foreach ($materialComponents as $materialComponent)
            {
                $subMaterial = $materialComponent->getMaterial();
                for ($i = 0; $i < $indent; $i++)
                {
                    $str .= "----";
                }
                $str .= " " . number_format($materialComponent->getAmount(), $decimals) . "% ";
                $str .= $subMaterial->getName() .' (' . $subMaterial->getUniqueId() . ')' . PHP_EOL;
                $str .= MaterialTxtView::toStringMaterialComponents($subMaterial, $decimals, $indent + 1);
            }
        }

        return $str;
    }

    public static function toStringFlattenedMaterialComponents($material, $decimals)
    {
        $str = '';
        
        $materialComponents = $material->getFlattenedMaterialComponents();

        foreach ($materialComponents as $materialComponent)
        {
            $subMaterial = $materialComponent->getMaterial();
            $str .= number_format($materialComponent->getAmount(), $decimals) . "% ";
            $str .= $subMaterial->getName() . PHP_EOL;
        }

        return $str;
    }


    public static function print($material)
    {
		echo MaterialTxtView::toString($material);
    }



} // end class AnalysisTxtView

?>
