<?php
/**
 * This file is part of the ceramicscalc PHP library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Ceramicscalc\Test\Models\Material;

use Ceramicscalc\Models\Blend\BiaxialBlend;
use Ceramicscalc\Models\Material\CompositeMaterial;
use Ceramicscalc\Test\BaseCompositeMaterialTest;
use Ceramicscalc\Views\Html\Blend\BiaxialBlendHtmlView;

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
        $this->nzk = $this->primitiveMaterials->getPrimitiveMaterialByName('New Zealand Halloysite');
        $this->talc = $this->primitiveMaterials->getPrimitiveMaterialByName('Talc');
        $this->wollastonite = $this->primitiveMaterials->getPrimitiveMaterialByName('Wollastonite');
        $this->whiting = $this->primitiveMaterials->getPrimitiveMaterialByName('Whiting');
        $this->boneAsh = $this->primitiveMaterials->getPrimitiveMaterialByName('Bone Ash');
        $this->rio = $this->primitiveMaterials->getPrimitiveMaterialByName('Red iron oxide');
        $this->yio = $this->primitiveMaterials->getPrimitiveMaterialByName('Yellow iron oxide');
        $this->tio = $this->primitiveMaterials->getPrimitiveMaterialByName('Titanium Dioxide');
        $this->mno = $this->primitiveMaterials->getPrimitiveMaterialByName('Manganese Dioxide');


    }

    public function providerBiaxBottomRight()
    {
        $corner = new CompositeMaterial();
        $corner->setName("Bottom Right");
        $corner->addMaterial($this->mahavir, 33.8);
        $corner->addMaterial($this->silica, 21);
        $corner->addMaterial($this->wollastonite, 20.3);
        $corner->addMaterial($this->epk, 17.7);
        $corner->addMaterial($this->talc, 7.2);
        $corner->addMaterial($this->rio, 11.6);
        $corner->addMaterial($this->tio, 0.9);
        $corner->addMaterial($this->mno, 0.3);
        return $corner;
    }

    public function providerBiaxBottomLeft()
    {
        $corner = new CompositeMaterial();
        $corner->setName("Bottom Left");
        $corner->addMaterial($this->mahavir, 35.2);
        $corner->addMaterial($this->epk, 24.1);
        $corner->addMaterial($this->wollastonite, 21);
        $corner->addMaterial($this->silica, 12.1);
        $corner->addMaterial($this->talc, 7.6);
        $corner->addMaterial($this->rio, 12);
        $corner->addMaterial($this->tio, 1);
        $corner->addMaterial($this->mno, 0.3);
        return $corner;
    }

    public function providerBiaxTopRight()
    {
        $corner = new CompositeMaterial();
        $corner->setName("Top Right");
        $corner->addMaterial($this->silica, 28.5);
        $corner->addMaterial($this->mahavir, 27.2);
        $corner->addMaterial($this->epk, 21.9);
        $corner->addMaterial($this->wollastonite, 16.5);
        $corner->addMaterial($this->talc, 5.9);
        $corner->addMaterial($this->rio, 9.5);
        $corner->addMaterial($this->tio, 0.6);
        $corner->addMaterial($this->mno, 0.2);
        return $corner;
    }

    public function providerBiaxTopLeft()
    {
        $corner = new CompositeMaterial();
        $corner->setName("Top Left");
        $corner->addMaterial($this->mahavir, 28.8);
        $corner->addMaterial($this->epk, 28.4);
        $corner->addMaterial($this->silica, 19.6);
        $corner->addMaterial($this->wollastonite, 17.2);
        $corner->addMaterial($this->talc, 6);
        $corner->addMaterial($this->rio, 10);
        $corner->addMaterial($this->tio, 0.6);
        $corner->addMaterial($this->mno, 0.2);
        return $corner;
    }

    public function testCompositeMaterialBiaxialBlend()
    {
        $numRows = 4;
        $numColumns = 4;

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
//        BiaxialBlendHtmlView::print($biaxialBlend);
    }

}


/*
Tenmoku Biaxial
    public function providerBiaxBottomRight()
    {
        $corner = new CompositeMaterial();
        $corner->setName("Bottom Right");
        $corner->addMaterial($this->mahavir, 37);
        $corner->addMaterial($this->wollastonite, 29);
        $corner->addMaterial($this->silica, 26);
        $corner->addMaterial($this->epk, 8);
        $corner->addMaterial($this->rio, 8.5);
        return $corner;
    }

    public function providerBiaxBottomLeft()
    {
        $corner = new CompositeMaterial();
        $corner->setName("Bottom Left");
        $corner->addMaterial($this->mahavir, 41);
        $corner->addMaterial($this->epk, 30);
        $corner->addMaterial($this->whiting, 19);
        $corner->addMaterial($this->wollastonite, 10);
        $corner->addMaterial($this->rio, 8.5);
        return $corner;
    }

    public function providerBiaxTopRight()
    {
        $corner = new CompositeMaterial();
        $corner->setName("Top Right");
        $corner->addMaterial($this->mahavir, 27);
        $corner->addMaterial($this->silica, 37);
        $corner->addMaterial($this->wollastonite, 22);
        $corner->addMaterial($this->epk, 14);
        $corner->addMaterial($this->rio, 8.5);
        return $corner;
    }

    public function providerBiaxTopLeft()
    {
        $corner = new CompositeMaterial();
        $corner->setName("Top Left");
        $corner->addMaterial($this->epk, 42);
        $corner->addMaterial($this->mahavir, 32);
        $corner->addMaterial($this->wollastonite, 26);
        $corner->addMaterial($this->rio, 8.5);
        return $corner;
    }

 */

/*
 Celadon Biax (New Pinnell)

    public function providerBiaxBottomRight()
    {
        $corner = new CompositeMaterial();
        $corner->setName("Bottom Right");
        $corner->addMaterial($this->mahavir, 37);
        $corner->addMaterial($this->wollastonite, 29);
        $corner->addMaterial($this->silica, 26);
        $corner->addMaterial($this->nzk, 8);
        $corner->addMaterial($this->yio, 0.7);
        return $corner;
    }

    public function providerBiaxBottomLeft()
    {
        $corner = new CompositeMaterial();
        $corner->setName("Bottom Left");
        $corner->addMaterial($this->mahavir, 42);
        $corner->addMaterial($this->wollastonite, 33);
        $corner->addMaterial($this->nzk, 20);
        $corner->addMaterial($this->silica, 5);
        $corner->addMaterial($this->yio, 0.7);
        return $corner;
    }

    public function providerBiaxTopRight()
    {
        $corner = new CompositeMaterial();
        $corner->setName("Top Right");
        $corner->addMaterial($this->mahavir, 27);
        $corner->addMaterial($this->silica, 35);
        $corner->addMaterial($this->wollastonite, 21);
        $corner->addMaterial($this->nzk, 17);
        $corner->addMaterial($this->yio, 0.7);
        return $corner;
    }

    public function providerBiaxTopLeft()
    {
        $corner = new CompositeMaterial();
        $corner->setName("Top Left");
        $corner->addMaterial($this->nzk, 30);
        $corner->addMaterial($this->mahavir, 29);
        $corner->addMaterial($this->wollastonite, 23);
        $corner->addMaterial($this->silica, 18);
        $corner->addMaterial($this->yio, 0.7);
        return $corner;
    }

 */
?>