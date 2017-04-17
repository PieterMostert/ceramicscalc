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
use DerekPhilipAu\Ceramicscalc\Models\Blend\LineBlend;
use DerekPhilipAu\Ceramicscalc\Models\Material\PrimitiveMaterial;
use DerekPhilipAu\Ceramicscalc\Models\Material\CompositeMaterial;
use DerekPhilipAu\Ceramicscalc\Views\Txt\Analysis\AnalysisTxtView;
use DerekPhilipAu\Ceramicscalc\Views\Txt\Analysis\LineBlendTxtView;
use DerekPhilipAu\Ceramicscalc\Views\Txt\Material\MaterialTxtView;

class Test extends \PHPUnit_Framework_TestCase
{
    public function testTrueIsTrue()
    {
        $foo = true;
        $this->assertTrue($foo);
    }

/*
    public function testLeach4321()
    {
        $percent = new PercentageAnalysis();
        $percent->setOxide(Analysis::K2O, 16.92);
        $percent->setOxide(Analysis::Al2O3, 18.32);
        $percent->setOxide(Analysis::SiO2, 64.76);
        $potash = new PrimitiveMaterial(1);
        $potash->setName("Potash Feldspar");
        $potash->setPercentageAnalysis($percent);

        echo "**************************\n";
        MaterialTxtView::printTxt($potash);

        $percent = new PercentageAnalysis();
        $percent->setOxide(Analysis::SiO2, 100);
        $silica = new PrimitiveMaterial(2);
        $silica->setName("Silica");
        $silica->setPercentageAnalysis($percent);

        echo "**************************\n";
        MaterialTxtView::printTxt($silica);

        $percent = new PercentageAnalysis();
        $percent->setOxide(Analysis::CaO, 56.10);
        $percent->setLOI(43.90);
        $whiting = new PrimitiveMaterial(3);
        $whiting->setName("Whiting");
        $whiting->setPercentageAnalysis($percent);

        echo "**************************\n";
        MaterialTxtView::printTxt($whiting);

        $percent = new PercentageAnalysis();
        $percent->setOxide(Analysis::Al2O3, 40.21);
        $percent->setOxide(Analysis::SiO2, 47.29);
        $percent->setLOI(12.50);
        $kaolin = new PrimitiveMaterial(4);
        $kaolin->setName("Kaolin");
        $kaolin->setPercentageAnalysis($percent);

        echo "**************************\n";
        MaterialTxtView::printTxt($kaolin);

        $leach4321 = new CompositeMaterial();
        $leach4321->setName("Leach 4321");
        $leach4321->addMaterial($potash, 40);
        $leach4321->addMaterial($silica, 30);
        $leach4321->addMaterial($whiting, 20);
        $leach4321->addMaterial($kaolin, 10);

        echo "**************************\n";
        MaterialTxtView::printTxt($leach4321);

        $pinnell = new CompositeMaterial();
        $pinnell->setName("Pinnell Clear");
        $pinnell->addMaterial($potash, 25);
        $pinnell->addMaterial($silica, 35);
        $pinnell->addMaterial($whiting, 20);
        $pinnell->addMaterial($kaolin, 20);

        echo "**************************\n";
        MaterialTxtView::printTxt($pinnell);

        $pinnell = new CompositeMaterial();
        $pinnell->setName("Pinnell Clear 2X");
        $pinnell->addMaterial($potash, 50);
        $pinnell->addMaterial($silica, 70);
        $pinnell->addMaterial($whiting, 40);
        $pinnell->addMaterial($kaolin, 40);

        echo "**************************\n";
        MaterialTxtView::printTxt($pinnell);

return;
        $leachPinnell = new CompositeMaterial();
        $leachPinnell->setName("MIX of Leach 4321 and Pinnell Clear");
        $leachPinnell->addMaterial($leach4321, 50);
        $leachPinnell->addMaterial($pinnell, 50);

        echo "**************************\n";
        MaterialTxtView::printTxt($leachPinnell);

        $lineBlend = LineBlend::createLineBlend($leach4321, $pinnell, 10, 90, 10, 90, 10);

        LineBlendTxtView::printTxt($lineBlend);

    }
*/
}

//$test = new Test();
//$test->testLeach4321();

?>