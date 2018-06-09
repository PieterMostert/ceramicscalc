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

class PercentageAnalysisTest extends \PHPUnit\Framework\TestCase
{

    public function testConstructor() {
        // Constructor should initialize oxides and loi to 0.
        $percentageAnalysis = new PercentageAnalysis();
        $this->assertEquals(0, $percentageAnalysis->getOxide(Analysis::SiO2));
        $this->assertEquals(0, $percentageAnalysis->getOxide(Analysis::ZrO2));
        $this->assertEquals(0, $percentageAnalysis->getLOI());
    }

    public function testSetLOI() {
        $percentageAnalysis = new PercentageAnalysis();
        $this->assertEquals(0, $percentageAnalysis->getLOI());
        $percentageAnalysis->setLOI(2.22);
        $this->assertEquals(2.22, $percentageAnalysis->getLOI());
    }

    public function testCreate100PercentPercentageAnalysis_PotashFeldspar_NoLOI() {
        // Potash Feldspar
        $percent = new PercentageAnalysis();
        $percent->setOxide(Analysis::K2O, 16.92);
        $percent->setOxide(Analysis::Al2O3, 18.32);
        $percent->setOxide(Analysis::SiO2, 64.76);
        $percent->setLOI(0);

        // Because Potash Feldspar has an LOI of 0, 
        // the 100% percentage analysis should be the same
        // as the original analysis:
        $percent100 = PercentageAnalysis::create100PercentPercentageAnalysis($percent);
        $this->assertEquals(0, $percent100->getLOI());
        $this->assertEquals(16.92, $percent100->getOxide(Analysis::K2O));
        $this->assertEquals(18.32, $percent100->getOxide(Analysis::Al2O3));
        $this->assertEquals(64.76, $percent100->getOxide(Analysis::SiO2));
    }
    
    public function testCreate100PercentPercentageAnalysis_Whiting_LOI() {
        // Whiting
        $percent = new PercentageAnalysis();
        $percent->setOxide(Analysis::CaO, 56.10);
        $percent->setLOI(43.90);

        $percent100 = PercentageAnalysis::create100PercentPercentageAnalysis($percent);
        // A 100% percentage analysis always has LOI of 0:
        $this->assertEquals(0, $percent100->getLOI());
        // Whiting only contains the oxide CaO, so CaO will be 100%:
        $this->assertEquals(100, $percent100->getOxide(Analysis::CaO));
    }

    public function testCreate100PercentPercentageAnalysis_Kaolin_LOI() {
        // Kaolin
        $percent = new PercentageAnalysis();
        $percent->setOxide(Analysis::Al2O3, 40.21);
        $percent->setOxide(Analysis::SiO2, 47.29);
        $percent->setLOI(12.50);

        $percent100 = PercentageAnalysis::create100PercentPercentageAnalysis($percent);
        // A 100% percentage analysis always has LOI of 0:
        $this->assertEquals(0, $percent100->getLOI());
        $this->assertEquals(45.954, $percent100->getOxide(Analysis::Al2O3), '', 0.001);
        $this->assertEquals(54.046, $percent100->getOxide(Analysis::SiO2), '', 0.001);
    }

    public function testCreatePercentageAnalysis_PotashFeldspar_DefaultLOI()
    {
        // Potash Feldspar formula:
        $formulaAnalysis = new FormulaAnalysis();
        $formulaAnalysis->setOxide(Analysis::SiO2, 5.999);
        $formulaAnalysis->setOxide(Analysis::Al2O3, 1);
        $formulaAnalysis->setOxide(Analysis::K2O, 1);

        // Test default LOI value:
        $percentageAnalysis = PercentageAnalysis::createPercentageAnalysis($formulaAnalysis);

        $this->assertEquals(64.76, $percentageAnalysis->getOxide(Analysis::SiO2), '', 0.01);
        $this->assertEquals(18.32, $percentageAnalysis->getOxide(Analysis::Al2O3), '', 0.01);
        $this->assertEquals(16.92, $percentageAnalysis->getOxide(Analysis::K2O), '', 0.01);
    }

    public function testCreatePercentageAnalysis_PotashFeldspar_ExplicitLOI()
    {
        // Potash Feldspar formula:
        $formulaAnalysis = new FormulaAnalysis();
        $formulaAnalysis->setOxide(Analysis::SiO2, 5.999);
        $formulaAnalysis->setOxide(Analysis::Al2O3, 1);
        $formulaAnalysis->setOxide(Analysis::K2O, 1);
    
        // Test explicit LOI value:
        $percentageAnalysis = PercentageAnalysis::createPercentageAnalysis($formulaAnalysis, 0);

        $this->assertEquals(64.76, $percentageAnalysis->getOxide(Analysis::SiO2), '', 0.01);
        $this->assertEquals(18.32, $percentageAnalysis->getOxide(Analysis::Al2O3), '', 0.01);
        $this->assertEquals(16.92, $percentageAnalysis->getOxide(Analysis::K2O), '', 0.01);
    }

    public function testCreatePercentageAnalysis_Whiting_LOI()
    {
        // Whiting formula:
        $formulaAnalysis = new FormulaAnalysis();
        $formulaAnalysis->setOxide(Analysis::CaO, 1);
        $loi = 43.9;
        $percentageAnalysis = PercentageAnalysis::createPercentageAnalysis($formulaAnalysis, $loi);

        $this->assertEquals(56.1, $percentageAnalysis->getOxide(Analysis::CaO), '', 0.01);
        $this->assertEquals(43.9, $percentageAnalysis->getLOI());
    }

}
