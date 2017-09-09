<?php
/**
 * This file is part of the ceramicscalc PHP library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DerekPhilipAu\Ceramicscalc\Test\Models\Material;

use DerekPhilipAu\Ceramicscalc\Models\Analysis\Analysis;
use DerekPhilipAu\Ceramicscalc\Test\BaseCompositeMaterialTest;

class CompositeMaterialTest extends BaseCompositeMaterialTest
{

    public function testGetPercentageAnalysisLeach4321()
    {
        $leach4321 = $this->providerLeach4321();

        $percent = $leach4321->getPercentageAnalysis();

        $this->assertEquals(60.63, round($percent->getOxide(Analysis::SiO2), 2));
        $this->assertEquals(11.35, round($percent->getOxide(Analysis::Al2O3), 2));
        $this->assertEquals(6.77, round($percent->getOxide(Analysis::K2O), 2));
        $this->assertEquals(6.77, round($percent->getKNaO(), 2));
        $this->assertEquals(11.22, round($percent->getOxide(Analysis::CaO), 2));
        $this->assertEquals(10.03, round($percent->getLOI(), 2));

    }

    public function testGet100PercentageAnalysisLeach4321()
    {
        $leach4321 = $this->providerLeach4321();

        $percent100 = $leach4321->get100PercentPercentageAnalysis();

        $this->assertEquals(67.39, round($percent100->getOxide(Analysis::SiO2), 2));
        $this->assertEquals(12.61, round($percent100->getOxide(Analysis::Al2O3), 2));
        $this->assertEquals(7.52, round($percent100->getOxide(Analysis::K2O), 2));
        $this->assertEquals(7.52, round($percent100->getKNaO(), 2));
        $this->assertEquals(12.47, round($percent100->getOxide(Analysis::CaO), 2));
        $this->assertEquals(0, $percent100->getLOI());
    }

    public function testGetUmfAnalysisLeach4321()
    {
        $leach4321 = $this->providerLeach4321();

        $formula = $leach4321->getUmfAnalysis();

        $this->assertEquals(3.711, round($formula->getOxide(Analysis::SiO2), 3));
        $this->assertEquals(0.409, round($formula->getOxide(Analysis::Al2O3), 3));
        $this->assertEquals(0.264, round($formula->getOxide(Analysis::K2O), 3));
        $this->assertEquals(0.264, round($formula->getKNaO(), 3));
        $this->assertEquals(0.736, round($formula->getOxide(Analysis::CaO), 3));
        $this->assertEquals(330.855, round($formula->getFormulaWeight(), 3));
    }

    /**
     * Verify that the same recipes with different total amounts still have the
     * same percentage analysis.
     */
    public function testGetPercentageAnalysisDoubled()
    {
        $pinnell = $this->providerPinnellClear();
        $pinnell2x = $this->providerPinnellClearDoubledAmounts();

        $percent = $pinnell->getPercentageAnalysis();
        $percent2x = $pinnell2x->getPercentageAnalysis();

        $this->assertEquals($percent->getOxide(Analysis::SiO2), $percent2x->getOxide(Analysis::SiO2));
        $this->assertEquals($percent->getOxide(Analysis::Al2O3), $percent2x->getOxide(Analysis::Al2O3));
        $this->assertEquals($percent->getOxide(Analysis::K2O), $percent2x->getOxide(Analysis::K2O));
        $this->assertEquals($percent->getKNaO(), $percent2x->getKNaO());
        $this->assertEquals($percent->getOxide(Analysis::CaO), $percent2x->getOxide(Analysis::CaO));
        $this->assertEquals($percent->getLOI(), $percent2x->getLOI());
    }

    /**
     * Verify that the same recipes with different total amounts still have the
     * same 100% percentage analysis.
     */
    public function testGet100PercentageAnalysisDoubled()
    {
        $pinnell = $this->providerPinnellClear();
        $pinnell2x = $this->providerPinnellClearDoubledAmounts();

        $percent = $pinnell->get100PercentPercentageAnalysis();
        $percent2x = $pinnell2x->get100PercentPercentageAnalysis();

        $this->assertEquals($percent->getOxide(Analysis::SiO2), $percent2x->getOxide(Analysis::SiO2));
        $this->assertEquals($percent->getOxide(Analysis::Al2O3), $percent2x->getOxide(Analysis::Al2O3));
        $this->assertEquals($percent->getOxide(Analysis::K2O), $percent2x->getOxide(Analysis::K2O));
        $this->assertEquals($percent->getKNaO(), $percent2x->getKNaO());
        $this->assertEquals($percent->getOxide(Analysis::CaO), $percent2x->getOxide(Analysis::CaO));
        $this->assertEquals($percent->getLOI(), $percent2x->getLOI());
    }

    /**
     * Verify that the same recipes with different total amounts still have the
     * same UMF analysis.
     */
    public function testGetUmfAnalysisDoubled()
    {
        $pinnell = $this->providerPinnellClear();
        $pinnell2x = $this->providerPinnellClearDoubledAmounts();

        $percent = $pinnell->getUmfAnalysis();
        $percent2x = $pinnell2x->getUmfAnalysis();

        $this->assertEquals($percent->getOxide(Analysis::SiO2), $percent2x->getOxide(Analysis::SiO2));
        $this->assertEquals($percent->getOxide(Analysis::Al2O3), $percent2x->getOxide(Analysis::Al2O3));
        $this->assertEquals($percent->getOxide(Analysis::K2O), $percent2x->getOxide(Analysis::K2O));
        $this->assertEquals($percent->getKNaO(), $percent2x->getKNaO());
        $this->assertEquals($percent->getOxide(Analysis::CaO), $percent2x->getOxide(Analysis::CaO));
    }

    /**
     * Check the Analyses of a Composite Material composed of Composite Materials
     */
    public function testGetPercentageAnalysisBlend()
    {
        $leachPinnell = $this->providerLeachPinnell();

        $percent = $leachPinnell->getPercentageAnalysis();

        $this->assertEquals(60.64, round($percent->getOxide(Analysis::SiO2), 2));
        $this->assertEquals(11.99, round($percent->getOxide(Analysis::Al2O3), 2));
        $this->assertEquals(5.50, round($percent->getOxide(Analysis::K2O), 2));
        $this->assertEquals(5.50, round($percent->getKNaO(), 2));
        $this->assertEquals(11.22, round($percent->getOxide(Analysis::CaO), 2));
        $this->assertEquals(10.66, round($percent->getLOI(), 2));
    }

    /**
     * Check the Analyses of a Composite Material composed of Composite Materials
     */
    public function testGet100PercentageAnalysisBlend()
    {
        $leachPinnell = $this->providerLeachPinnell();

        $percent100 = $leachPinnell->get100PercentPercentageAnalysis();

        $this->assertEquals(67.87, round($percent100->getOxide(Analysis::SiO2), 2));
        $this->assertEquals(13.41, round($percent100->getOxide(Analysis::Al2O3), 2));
        $this->assertEquals(6.15, round($percent100->getOxide(Analysis::K2O), 2));
        $this->assertEquals(6.15, round($percent100->getKNaO(), 2));
        $this->assertEquals(12.56, round($percent100->getOxide(Analysis::CaO), 2));
        $this->assertEquals(0, $percent100->getLOI());
    }

    /**
     * Check the Analyses of a Composite Material composed of Composite Materials
     */
    public function testGetUmfAnalysisBlend()
    {
        $leachPinnell = $this->providerLeachPinnell();

        $formula = $leachPinnell->getUmfAnalysis();

        $this->assertEquals(3.905, round($formula->getOxide(Analysis::SiO2), 3));
        $this->assertEquals(0.455, round($formula->getOxide(Analysis::Al2O3), 3));
        $this->assertEquals(0.226, round($formula->getOxide(Analysis::K2O), 3));
        $this->assertEquals(0.226, round($formula->getKNaO(), 3));
        $this->assertEquals(0.774, round($formula->getOxide(Analysis::CaO), 3));
        $this->assertEquals(345.682, round($formula->getFormulaWeight(), 3));
    }
}

?>