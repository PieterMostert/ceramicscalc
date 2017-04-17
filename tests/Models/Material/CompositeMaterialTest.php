<?php
/**
 * This file is part of the ceramicscalc PHP library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DerekPhilipAu\Ceramicscalc\Test;

use DerekPhilipAu\Ceramicscalc\Models\Analysis\Analysis;
use DerekPhilipAu\Ceramicscalc\Models\Analysis\PercentageAnalysis;
use DerekPhilipAu\Ceramicscalc\Models\Material\PrimitiveMaterial;
use DerekPhilipAu\Ceramicscalc\Models\Material\CompositeMaterial;

class CompositeMaterialTest extends \PHPUnit_Framework_TestCase
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
        $this->assertEquals(330.858, round($formula->getFormulaWeight(), 3));
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
        $this->assertEquals(345.686, round($formula->getFormulaWeight(), 3));
    }

    /*************************************************************************
     * Data providers
     *************************************************************************/

    /*
     *  The classic Leach 4321 recipe
     */
    public function providerLeach4321()
    {
        $potash = $this->providerPotash();
        $silica = $this->providerSilica();
        $whiting = $this->providerWhiting();
        $kaolin = $this->providerKaolin();

        $leach4321 = new CompositeMaterial();
        $leach4321->setName("Leach 4321");
        $leach4321->addMaterial($potash, 40);
        $leach4321->addMaterial($silica, 30);
        $leach4321->addMaterial($whiting, 20);
        $leach4321->addMaterial($kaolin, 10);

        return $leach4321;
    }

    public function providerPinnellClear()
    {
        $potash = $this->providerPotash();
        $silica = $this->providerSilica();
        $whiting = $this->providerWhiting();
        $kaolin = $this->providerKaolin();

        $pinnell = new CompositeMaterial();
        $pinnell->setName("Pinnell Clear");
        $pinnell->addMaterial($potash, 25);
        $pinnell->addMaterial($silica, 35);
        $pinnell->addMaterial($whiting, 20);
        $pinnell->addMaterial($kaolin, 20);

        return $pinnell;
    }

    public function providerLeachPinnell()
    {
        $leach4321 = $this->providerLeach4321();
        $pinnell = $this->providerPinnellClear();

        $leachPinnell = new CompositeMaterial();
        $leachPinnell->setName("MIX of Leach 4321 and Pinnell Clear");
        $leachPinnell->addMaterial($leach4321, 50);
        $leachPinnell->addMaterial($pinnell, 50);

        return $leachPinnell;
    }

    public function providerPinnellClearDoubledAmounts()
    {
        $potash = $this->providerPotash();
        $silica = $this->providerSilica();
        $whiting = $this->providerWhiting();
        $kaolin = $this->providerKaolin();

        $pinnell = new CompositeMaterial();
        $pinnell->setName("Pinnell Clear Doubled Amounts");
        $pinnell->addMaterial($potash, 50);
        $pinnell->addMaterial($silica, 70);
        $pinnell->addMaterial($whiting, 40);
        $pinnell->addMaterial($kaolin, 40);

        return $pinnell;
    }

    public function providerPotash()
    {
        $percent = new PercentageAnalysis();
        $percent->setOxide(Analysis::K2O, 16.92);
        $percent->setOxide(Analysis::Al2O3, 18.32);
        $percent->setOxide(Analysis::SiO2, 64.76);
        $potash = new PrimitiveMaterial(1);
        $potash->setName("Potash Feldspar");
        $potash->setPercentageAnalysis($percent);

        return $potash;
    }

    public function providerSilica()
    {
        $percent = new PercentageAnalysis();
        $percent->setOxide(Analysis::SiO2, 100);
        $silica = new PrimitiveMaterial(2);
        $silica->setName("Silica");
        $silica->setPercentageAnalysis($percent);

        return $silica;
    }

    public function providerWhiting()
    {
        $percent = new PercentageAnalysis();
        $percent->setOxide(Analysis::CaO, 56.10);
        $percent->setLOI(43.90);
        $whiting = new PrimitiveMaterial(3);
        $whiting->setName("Whiting");
        $whiting->setPercentageAnalysis($percent);

        return $whiting;
    }

    public function providerKaolin()
    {
        $percent = new PercentageAnalysis();
        $percent->setOxide(Analysis::Al2O3, 40.21);
        $percent->setOxide(Analysis::SiO2, 47.29);
        $percent->setLOI(12.50);
        $kaolin = new PrimitiveMaterial(4);
        $kaolin->setName("Kaolin");
        $kaolin->setPercentageAnalysis($percent);

        return $kaolin;
    }
}

?>