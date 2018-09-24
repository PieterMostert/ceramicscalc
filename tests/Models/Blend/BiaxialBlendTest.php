<?php
/**
 * This file is part of the ceramicscalc PHP library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DerekPhilipAu\Ceramicscalc\Test\Models\Material;

use DerekPhilipAu\Ceramicscalc\Models\Blend\BiaxialBlend;
use DerekPhilipAu\Ceramicscalc\Models\Material\CompositeMaterial;
use DerekPhilipAu\Ceramicscalc\Test\BaseCompositeMaterialTest;
use DerekPhilipAu\Ceramicscalc\Views\Html\Blend\BiaxialBlendHtmlView;

class BiaxialBlendTest extends BaseCompositeMaterialTest
{

    protected $mahavir = null;
    protected $silica = null;
    protected $epk = null;
    protected $talc = null;
    protected $wollastonite = null;
    protected $whiting = null;
    protected $boneAsh = null;
    protected $rio = null;

    function __construct() {
        parent::__construct();

        $this->mahavir = $this->primitiveMaterials->getPrimitiveMaterialByName('Mahavir Potash Feldspar');
        $this->silica = $this->primitiveMaterials->getPrimitiveMaterialByName('Silica');
        $this->epk = $this->primitiveMaterials->getPrimitiveMaterialByName('EPK');
        $this->talc = $this->primitiveMaterials->getPrimitiveMaterialByName('Talc');
        $this->wollastonite = $this->primitiveMaterials->getPrimitiveMaterialByName('Wollastonite');
        $this->whiting = $this->primitiveMaterials->getPrimitiveMaterialByName('Whiting');
        $this->boneAsh = $this->primitiveMaterials->getPrimitiveMaterialByName('Bone Ash');
        $this->rio = $this->primitiveMaterials->getPrimitiveMaterialByName('Red iron oxide');
    }

    public function providerBiaxBottomRight()
    {
        $corner = new CompositeMaterial();
        $corner->setName("Bottom Right");
        $corner->addMaterial($this->mahavir, 40.5);
        $corner->addMaterial($this->silica, 35);
        $corner->addMaterial($this->epk, 8);
        $corner->addMaterial($this->talc, 8.5);
        $corner->addMaterial($this->wollastonite, 8);
        $corner->addMaterial($this->boneAsh, 13.6);
        $corner->addMaterial($this->rio, 12.6);
        return $corner;
    }

    public function providerBiaxBottomLeft()
    {
        $corner = new CompositeMaterial();
        $corner->setName("Bottom Left");
        $corner->addMaterial($this->mahavir, 47);
        $corner->addMaterial($this->epk, 35);
        $corner->addMaterial($this->talc, 10);
        $corner->addMaterial($this->whiting, 8);
        $corner->addMaterial($this->boneAsh, 16);
        $corner->addMaterial($this->rio, 15);
        return $corner;
    }

    public function providerBiaxTopRight()
    {
        $corner = new CompositeMaterial();
        $corner->setName("Top Right");
        $corner->addMaterial($this->mahavir, 29.4);
        $corner->addMaterial($this->silica, 45.3);
        $corner->addMaterial($this->epk, 13.7);
        $corner->addMaterial($this->talc, 6.3);
        $corner->addMaterial($this->wollastonite, 5.3);
        $corner->addMaterial($this->boneAsh, 10.5);
        $corner->addMaterial($this->rio, 9.4);
        return $corner;
    }

    public function providerBiaxTopLeft()
    {
        $corner = new CompositeMaterial();
        $corner->setName("Top Left");
        $corner->addMaterial($this->mahavir, 35);
        $corner->addMaterial($this->silica, 5);
        $corner->addMaterial($this->epk, 45);
        $corner->addMaterial($this->talc, 7.5);
        $corner->addMaterial($this->wollastonite, 7.5);
        $corner->addMaterial($this->boneAsh, 12);
        $corner->addMaterial($this->rio, 11);
        return $corner;
    }

    public function testCompositeMaterialBiaxialBlend()
    {
        $numRows = 5;
        $numColumns = 5;

        $br = $this->providerBiaxBottomRight();
        $bl = $this->providerBiaxBottomLeft();
        $tr = $this->providerBiaxTopRight();
        $tl = $this->providerBiaxTopLeft();

        $biaxialBlend = BiaxialBlend::createBiaxialBlend($tl, $tr, $bl, $br, $numRows, $numColumns);

        $this->assertEquals($numRows, count($biaxialBlend));
        foreach ($biaxialBlend as $lineBlend) {
            $this->assertEquals($numColumns, count($lineBlend));
        }

        //        $leach90Pinnell10 = $biaxialBlend[0][0]->getSimplifiedMaterial();
/*
        $this->assertEquals(38.5, $leach90Pinnell10->getMaterialComponent(self::MATERIAL_POTASH_ID)->getAmount());
        $this->assertEquals(30.5, $leach90Pinnell10->getMaterialComponent(self::MATERIAL_SILICA_ID)->getAmount());
        $this->assertEquals(20, $leach90Pinnell10->getMaterialComponent(self::MATERIAL_WHITING_ID)->getAmount());
        $this->assertEquals(11, $leach90Pinnell10->getMaterialComponent(self::MATERIAL_KAOLIN_ID)->getAmount());
*/
//        $leach40Pinnell60 = $biaxialBlend[2][2]->getSimplifiedMaterial();
/*
        $this->assertEquals(31, $leach40Pinnell60->getMaterialComponent(self::MATERIAL_POTASH_ID)->getAmount());
        $this->assertEquals(33, $leach40Pinnell60->getMaterialComponent(self::MATERIAL_SILICA_ID)->getAmount());
        $this->assertEquals(20, $leach40Pinnell60->getMaterialComponent(self::MATERIAL_WHITING_ID)->getAmount());
        $this->assertEquals(16, $leach40Pinnell60->getMaterialComponent(self::MATERIAL_KAOLIN_ID)->getAmount());
*/
        BiaxialBlendHtmlView::print($biaxialBlend);
    }

}

?>