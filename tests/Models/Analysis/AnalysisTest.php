<?php
/**
 * This file is part of the ceramicscalc PHP library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DerekPhilipAu\Ceramicscalc\Test\Models\Analysis;

use DerekPhilipAu\Ceramicscalc\Models\Analysis\Analysis;

class AnalysisTest extends \PHPUnit\Framework\TestCase
{

    public function testConstructor() {
        // Constructor should initialize oxide value array to 0 for each oxide.
        $analysis = new Analysis();
        $this->assertEquals(0, $analysis->getOxide(Analysis::SiO2));
        $this->assertEquals(0, $analysis->getOxide(Analysis::ZrO2));
        $this->assertEquals(0, $analysis->getOxide(Analysis::SnO2));
    }

    public function testGetOxideNames() {
        $oxide_names = Analysis::getOxideNames();
        $this->assertEquals(60, count($oxide_names));
        $this->assertEquals(Analysis::SiO2, $oxide_names[0]);
    }

    public function testGetMolarMasses() {
        $masses = Analysis::getMolarMasses();
        $this->assertEquals(60, count($masses));
        $this->assertEquals(60.085, $masses[Analysis::SiO2]);
        $this->assertEquals(325.809, $masses[Analysis::La2O3]);
    }

    public function testSetOxide() {
        $analysis = new Analysis();
        $analysis->setOxide(Analysis::SiO2, 50);
        $analysis->setOxide(Analysis::ZrO2, 22.2222);
        $analysis->setOxide(Analysis::HfO2, 1.11);
        $this->assertEquals(50, $analysis->getOxide(Analysis::SiO2));
        $this->assertEquals(22.2222, $analysis->getOxide(Analysis::ZrO2));
        $this->assertEquals(1.11, $analysis->getOxide(Analysis::HfO2));
        $this->assertEquals(0, $analysis->getOxide(Analysis::Al2O3));
    }

    public function testGetKNaO() {
        $analysis = new Analysis();
        $analysis->setOxide(Analysis::K2O, 10);
        $analysis->setOxide(Analysis::Na2O, 10);
        $this->assertEquals(20, $analysis->getKNaO());
        $analysis->setOxide(Analysis::K2O, 1.11);
        $analysis->setOxide(Analysis::Na2O, 2.22);
        $this->assertEquals(3.33, $analysis->getKNaO());
    }

    public function testGetOxides() {
        $analysis = new Analysis();
        $analysis->setOxide(Analysis::K2O, 10);
        $analysis->setOxide(Analysis::Na2O, 10);

        $oxides = $analysis->getOxides();
        $this->assertEquals(10, $oxides[Analysis::K2O]);
        $this->assertEquals(10, $oxides[Analysis::Na2O]);
        $this->assertEquals(0, $oxides[Analysis::Al2O3]);
    }


}
