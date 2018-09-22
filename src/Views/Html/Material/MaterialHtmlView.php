<?php
/**
 * This file is part of the ceramicscalc PHP library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DerekPhilipAu\Ceramicscalc\Views\Html\Material;

use DerekPhilipAu\Ceramicscalc\Models\Analysis\FormulaAnalysis;
use DerekPhilipAu\Ceramicscalc\Models\Analysis\PercentageAnalysis;
use DerekPhilipAu\Ceramicscalc\Models\Material\AbstractMaterial;
use DerekPhilipAu\Ceramicscalc\Models\Material\CompositeMaterial;
use DerekPhilipAu\Ceramicscalc\Models\Material\CompositeMaterialComponent;
use DerekPhilipAu\Ceramicscalc\Views\Html\Analysis\AnalysisHtmlView;
use DerekPhilipAu\Ceramicscalc\Views\Html\Analysis\PercentageAnalysisHtmlView;

class MaterialHtmlView
{
	public static function toString(AbstractMaterial $material, $decimals = 3, $includeEmpty = false)
	{
        $str = '';

        $str .= 'Name: ' . $material->getName() . "<br/>";
        $str .= 'Description: ' . $material->getDescription() . "<br/>";

        $str .= MaterialHtmlView::toStringMaterialComponents($material, $decimals);

        $str .= "SIMPLIFIED VIEW: " . "<br/>";
        $simplifiedMaterial = $material->getSimplifiedMaterial();
        $str .= MaterialHtmlView::toStringMaterialComponents($simplifiedMaterial, $decimals);

        $str .= "PERCENTAGE ANALYSIS: " . "<br/>";
        $str .= PercentageAnalysisHtmlView::toString($material->getPercentageAnalysis(), $decimals, $includeEmpty);

        $str .= "100% PERCENTAGE ANALYSIS: " . "<br/>";
        $analysis = PercentageAnalysis::create100PercentPercentageAnalysis($material->getPercentageAnalysis());
        $str .= PercentageAnalysisHtmlView::toString($analysis, $decimals, $includeEmpty);

        //$analysis = $material->getFormulaAnalysis();
        $analysis = FormulaAnalysis::createROR2OUnityFormulaAnalysis($material->getPercentageAnalysis());
        $str .= "UMF FORMULA: " . "<br/>";
        $str .= 'Formula Weight: ' . number_format($analysis->getFormulaWeight(), $decimals) . "<br/>";
        $str .= AnalysisHtmlView::toString($analysis, $decimals, $includeEmpty);

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
                $str .= $subMaterial->getName() . "<br/>";
                $str .= MaterialHtmlView::toStringMaterialComponents($subMaterial, $decimals, $indent + 1);
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
            $str .= $subMaterial->getName() . "<br/>";
        }

        return $str;
    }


    public static function print($material)
    {
		echo MaterialHtmlView::toString($material);
    }



} // end class AnalysisHtmlView

?>
