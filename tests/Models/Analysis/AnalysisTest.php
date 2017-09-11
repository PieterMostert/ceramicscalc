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

    public function testGetOxideNames() {
        $oxide_names = Analysis::getOxideNames();
        $this->assertEquals(30, count($oxide_names));
    }

    public function testGetMolarMasses() {
        $masses = Analysis::getMolarMasses();
        $this->assertEquals(30, count($masses));
    }

    public function testSetOxide() {
        $analysis = new Analysis();
        $analysis->setOxide(Analysis::SiO2, 50);
        $sio2 = $analysis->getOxide(Analysis::SiO2);
        $this->assertEquals(50, $sio2);

        $this->assertEquals(0, $analysis->getOxide(Analysis::Al2O3));
    }

    public function testGetKNaO() {
        $analysis = new Analysis();
        $analysis->setOxide(Analysis::K2O, 10);
        $analysis->setOxide(Analysis::Na2O, 10);

        $this->assertEquals(20, $analysis->getKNaO());
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
