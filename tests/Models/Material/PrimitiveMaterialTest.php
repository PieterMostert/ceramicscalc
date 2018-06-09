<?php
/**
 * This file is part of the ceramicscalc PHP library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DerekPhilipAu\Ceramicscalc\Test\Models\Material;

use DerekPhilipAu\Ceramicscalc\Models\Analysis\Analysis;
use DerekPhilipAu\Ceramicscalc\Models\Analysis\FormulaAnalysis;
use DerekPhilipAu\Ceramicscalc\Models\Analysis\PercentageAnalysis;
use DerekPhilipAu\Ceramicscalc\Models\Material\PrimitiveMaterial;

class PrimitiveMaterialTest extends \PHPUnit\Framework\TestCase
{

    public function testConstructor()
    {
        $uniqueId = 22938;
        $material = new PrimitiveMaterial($uniqueId);
        $this->assertNotNull($material->getPercentageAnalysis());
        $this->assertNotNull($material->getFormulaAnalysis());
        $this->assertEquals($uniqueId, $material->getUniqueId());
    }

    public function testSetName()
    {
        $uniqueId = 22938;
        $material = new PrimitiveMaterial($uniqueId);
        $material->setName('testme');
        $this->assertEquals('testme', $material->getName());
    }

    public function testSetDescription()
    {
        $uniqueId = 22938;
        $material = new PrimitiveMaterial($uniqueId);
        $material->setDescription('testme');
        $this->assertEquals('testme', $material->getDescription());
    }

    public function testSetPercentageAnalysis()
    {
        // Kaolin
        $percent = new PercentageAnalysis();
        $percent->setOxide(Analysis::Al2O3, 40.21);
        $percent->setOxide(Analysis::SiO2, 47.29);
        $percent->setLOI(12.50);

        $uniqueId = 22938;
        $material = new PrimitiveMaterial($uniqueId);
        $material->setPercentageAnalysis($percent);
        
        $formula = $material->getFormulaAnalysis();

        $this->assertNotNull($material->getPercentageAnalysis());
        $this->assertNotNull($material->getFormulaAnalysis());
        $this->assertNotEquals(0, $formula->getOxide(Analysis::Al2O3));
    }

    public function testSetFormulaAnalysis()
    {
        // Potash Feldspar formula:
        $formula = new FormulaAnalysis();
        $formula->setOxide(Analysis::SiO2, 5.999);
        $formula->setOxide(Analysis::Al2O3, 1);
        $formula->setOxide(Analysis::K2O, 1);

        $uniqueId = 22938;
        $material = new PrimitiveMaterial($uniqueId);
        $material->setFormulaAnalysis($formula);

        $percent = $material->getPercentageAnalysis();
        $this->assertEquals(64.76, $percent->getOxide(Analysis::SiO2), '', 0.01);
    }

    public function testSetFormulaAnalysis_withLOI()
    {
        // Kaolin formula:
        $formula = new FormulaAnalysis();
        $formula->setOxide(Analysis::SiO2, 1.996);
        $formula->setOxide(Analysis::Al2O3, 1);

        $uniqueId = 22938;
        $material = new PrimitiveMaterial($uniqueId);
        $material->setFormulaAnalysis($formula, 12.5);

        $percent = $material->getPercentageAnalysis();
        $this->assertEquals(47.29, $percent->getOxide(Analysis::SiO2), '', 0.01);
        $this->assertEquals(40.20, $percent->getOxide(Analysis::Al2O3), '', 0.01);
    }

}

?>