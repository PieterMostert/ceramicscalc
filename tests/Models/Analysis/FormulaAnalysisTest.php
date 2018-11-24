<?php
/**
 * This file is part of the ceramicscalc PHP library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DerekPhilipAu\Ceramicscalc\Test\Models\Analysis;

use DerekPhilipAu\Ceramicscalc\Models\Analysis\Analysis;
use DerekPhilipAu\Ceramicscalc\Models\Analysis\PercentageAnalysis;
use DerekPhilipAu\Ceramicscalc\Models\Analysis\FormulaAnalysis;

class FormulaAnalysisTest extends \PHPUnit\Framework\TestCase
{

    public function testGetFormulaWeight_SolargilBallClay() {
        // Solargil Ball Clay (HSM):    
        $formulaAnalysis = new FormulaAnalysis();
        $formulaAnalysis->setOxide(Analysis::SiO2, 4.45);
        $formulaAnalysis->setOxide(Analysis::Al2O3, 1);
        $formulaAnalysis->setOxide(Analysis::Na2O, 0.02);
        $formulaAnalysis->setOxide(Analysis::K2O, 0.12);
        $formulaAnalysis->setOxide(Analysis::MgO, 0.04);
        $formulaAnalysis->setOxide(Analysis::CaO, 0.02);
        $formulaAnalysis->setOxide(Analysis::TiO2, 0.07);
        $formulaAnalysis->setOxide(Analysis::Fe2O3, 0.03);
        //echo('FORMULA WEIGHT: '.$formulaAnalysis->getFormulaWeight());
        $this->assertEquals(395.0, $formulaAnalysis->getFormulaWeight(), '', 0.1);
    }

    public function testGetFormulaWeight_SolargilBentonite() {
        // Solargil Bentonite:    
        $formulaAnalysis = new FormulaAnalysis();
        $formulaAnalysis->setOxide(Analysis::SiO2, 8.17);
        $formulaAnalysis->setOxide(Analysis::Al2O3, 0.97);
        $formulaAnalysis->setOxide(Analysis::Na2O, 0.2);
        $formulaAnalysis->setOxide(Analysis::K2O, 0.02);
        $formulaAnalysis->setOxide(Analysis::MgO, 0.25);
        $formulaAnalysis->setOxide(Analysis::CaO, 0.07);
        $formulaAnalysis->setOxide(Analysis::Fe2O3, 0.03);
        //echo('FORMULA WEIGHT: '.$formulaAnalysis->getFormulaWeight());
        $this->assertEquals(622.9, $formulaAnalysis->getFormulaWeight(), '', 0.1);
    }

    public function testGetFormulaWeight_SolargilWhiting() {
        // Solargil Bentonite:    
        $formulaAnalysis = new FormulaAnalysis();
        $formulaAnalysis->setOxide(Analysis::CaO, 1);
        //echo('FORMULA WEIGHT: '.$formulaAnalysis->getFormulaWeight());
        $this->assertEquals(56.08, $formulaAnalysis->getFormulaWeight(), '', 0.01);
    }

    public function testGetCalculatedLoiFromWeight() {
        // Kaolin:    
        $formulaAnalysis = new FormulaAnalysis();
        $formulaAnalysis->setOxide(Analysis::SiO2, 1.996);
        $formulaAnalysis->setOxide(Analysis::Al2O3, 1);
        $loi = $formulaAnalysis->getCalculatedLoiFromWeight(253.588);
        $this->assertEquals(12.5, $loi, '', 0.1);
    }

    public function testGetCalculatedWeightFromLoi() {
        // Kaolin:    
        $formulaAnalysis = new FormulaAnalysis();
        $formulaAnalysis->setOxide(Analysis::SiO2, 1.996);
        $formulaAnalysis->setOxide(Analysis::Al2O3, 1);
        $loi = $formulaAnalysis->getCalculatedWeightFromLoi(12.5);
        $this->assertEquals(253.589, $loi, '', 0.001);
    }

}
