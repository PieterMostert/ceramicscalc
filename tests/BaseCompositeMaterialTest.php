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

abstract class BaseCompositeMaterialTest extends \PHPUnit\Framework\TestCase
{
    const MATERIAL_POTASH_ID = 1;
    const MATERIAL_SILICA_ID = 2;
    const MATERIAL_WHITING_ID = 3;
    const MATERIAL_KAOLIN_ID = 4;

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
        $potash = new PrimitiveMaterial(self::MATERIAL_POTASH_ID);
        $potash->setName("Potash Feldspar");
        $potash->setPercentageAnalysis($percent);

        return $potash;
    }

    public function providerSilica()
    {
        $percent = new PercentageAnalysis();
        $percent->setOxide(Analysis::SiO2, 100);
        $silica = new PrimitiveMaterial(self::MATERIAL_SILICA_ID);
        $silica->setName("Silica");
        $silica->setPercentageAnalysis($percent);

        return $silica;
    }

    public function providerWhiting()
    {
        $percent = new PercentageAnalysis();
        $percent->setOxide(Analysis::CaO, 56.10);
        $percent->setLOI(43.90);
        $whiting = new PrimitiveMaterial(self::MATERIAL_WHITING_ID);
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
        $kaolin = new PrimitiveMaterial(self::MATERIAL_KAOLIN_ID);
        $kaolin->setName("Kaolin");
        $kaolin->setPercentageAnalysis($percent);

        return $kaolin;
    }
}
?>