<?php
/**
 * This file is part of the ceramicscalc PHP library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DerekPhilipAu\Ceramicscalc\Test\Models\Material;

use DerekPhilipAu\Ceramicscalc\Models\Analysis\Analysis;
use DerekPhilipAu\Ceramicscalc\Models\Blend\LineBlend;
use DerekPhilipAu\Ceramicscalc\Models\Blend\TriaxialBlend;
use DerekPhilipAu\Ceramicscalc\Models\Material\CompositeMaterial;
use DerekPhilipAu\Ceramicscalc\Test\BaseCompositeMaterialTest;
use DerekPhilipAu\Ceramicscalc\Views\Html\Blend\TriaxialBlendHtmlView;

class TriaxialBlendTest extends BaseCompositeMaterialTest
{

    protected $potashFeldspar = null;
    protected $silica = null;
    protected $kaolin = null;
    protected $whiting = null;

    function __construct() 
    {
        parent::__construct();

        $this->potashFeldspar = $this->primitiveMaterials->getPrimitiveMaterialByName('Potash Feldspar');
        $this->silica = $this->primitiveMaterials->getPrimitiveMaterialByName('Silica');
        $this->kaolin = $this->primitiveMaterials->getPrimitiveMaterialByName('Kaolin');
        $this->whiting = $this->primitiveMaterials->getPrimitiveMaterialByName('Whiting');
    }

    public function providerTriaxTop()
    {
        $corner = new CompositeMaterial();
        $corner->setName("Top");
        $corner->addMaterial($this->potashFeldspar, 40);
        $corner->addMaterial($this->silica, 30);
        $corner->addMaterial($this->whiting, 20);
        $corner->addMaterial($this->kaolin, 10);
        return $corner;
    }
 
    public function providerTriaxBottomLeft()
    {
        $corner = new CompositeMaterial();
        $corner->setName("Bottom Left");
        $corner->addMaterial($this->potashFeldspar, 45);
        $corner->addMaterial($this->silica, 35);
        $corner->addMaterial($this->whiting, 15);
        $corner->addMaterial($this->kaolin, 5);
        return $corner;
    }

    public function providerTriaxBottomRight()
    {
        $corner = new CompositeMaterial();
        $corner->setName("Bottom Right");
        $corner->addMaterial($this->potashFeldspar, 50);
        $corner->addMaterial($this->silica, 25);
        $corner->addMaterial($this->whiting, 25);
        return $corner;
    }

    public function testTriaxialBlend()
    {
        $dimension = 11;

        $t = $this->providerTriaxTop();
        $bl = $this->providerTriaxBottomLeft();
        $br = $this->providerTriaxBottomRight();
        
        $triaxialBlend = TriaxialBlend::createTriaxialBlend($t, $bl, $br, $dimension);

        $this->assertEquals($dimension, count($triaxialBlend));
        $this->assertEquals($dimension, count($triaxialBlend[0]));

        /*
        We'll test that TriaxialBlend gives the same results as performing line blends from bottom left
        to top, and bottom left to bottom right, followed by a line blend parallel to the third side of the
        triangle. We'll do this by comparing the UMF of the blend in position (2,3).
        */

        $blend50 = LineBlend::createLineBlend($bl, $t, 0, 100, 0, 100, 100/($dimension-1))[5];
        $blend05 = LineBlend::createLineBlend($bl, $br, 0, 100, 0, 100, 100/($dimension-1))[5];
        $blend23 = LineBlend::createLineBlend($blend50, $blend05, 0, 100, 0, 100, 100/5)[3];

        $blend23Umf = $blend23->getUmfAnalysis();
        $triaxialBlend23Umf = $triaxialBlend[2][3]->getUmfAnalysis();

        $oxideArray = array('SiO2', 'Al2O3', 'CaO', 'K2O');
        foreach ($oxideArray as $oxide) {
            $this->assertEquals($blend23Umf->getOxide($oxide), $triaxialBlend23Umf->getOxide($oxide));
        }

        // Now we'll do a check that doesn't depend on LineBlend.

        $triBlend23Materials = $triaxialBlend[2][3]->getSimplifiedMaterial();

        $this->assertEquals(45.5, $triBlend23Materials->getMaterialComponent(self::MATERIAL_POTASH_ID)->getAmount());
        $this->assertEquals(31.0, $triBlend23Materials->getMaterialComponent(self::MATERIAL_SILICA_ID)->getAmount());
        $this->assertEquals(19.0, $triBlend23Materials->getMaterialComponent(self::MATERIAL_WHITING_ID)->getAmount());
        $this->assertEquals(4.5, $triBlend23Materials->getMaterialComponent(self::MATERIAL_KAOLIN_ID)->getAmount());

        //TriaxialBlendHtmlView::print($triaxialBlend);
    }
}

?>