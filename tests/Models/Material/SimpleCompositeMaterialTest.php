<?php
/**
 * This file is part of the ceramicscalc PHP library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DerekPhilipAu\Ceramicscalc\Test\Models\Material;

// Import required classes
use DerekPhilipAu\Ceramicscalc\Models\Analysis\Analysis;
use DerekPhilipAu\Ceramicscalc\Models\Analysis\PercentageAnalysis;
use DerekPhilipAu\Ceramicscalc\Models\Material\PrimitiveMaterial;
use DerekPhilipAu\Ceramicscalc\Models\Material\CompositeMaterial;

class SimpleCompositeMaterialTest extends \PHPUnit\Framework\TestCase
{
    public function testGet5050UmfAnalysis()
    {
        // Create Potash Primitive Material:
        $percent = new PercentageAnalysis();
        $percent->setOxide(Analysis::K2O, 16.92);
        $percent->setOxide(Analysis::Al2O3, 18.32);
        $percent->setOxide(Analysis::SiO2, 64.76);
        $potash = new PrimitiveMaterial(1);
        $potash->setName("Potash Feldspar");
        $potash->setPercentageAnalysis($percent);

        // Create Whiting Primitive Material:
        $percent = new PercentageAnalysis();
        $percent->setOxide(Analysis::CaO, 56.10);
        $percent->setLOI(43.90);
        $whiting = new PrimitiveMaterial(2);
        $whiting->setName("Whiting");
        $whiting->setPercentageAnalysis($percent);

        // Create Composite Material (Recipe):
        $test = new CompositeMaterial();
        $test->setName("Test");
        $test->addMaterial($potash, 50);
        $test->addMaterial($whiting, 50);

        // Print out SiO2 UMF value:
        $formula = $test->getUmfAnalysis();
        $this->assertEquals(0.91, round($formula->getOxide(Analysis::SiO2), 2));
    }
}


?>