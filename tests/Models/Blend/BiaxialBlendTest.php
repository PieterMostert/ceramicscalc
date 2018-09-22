<?php
/**
 * This file is part of the ceramicscalc PHP library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DerekPhilipAu\Ceramicscalc\Test\Models\Material;

use DerekPhilipAu\Ceramicscalc\Models\Blend\BiaxialBlend;
use DerekPhilipAu\Ceramicscalc\Test\BaseCompositeMaterialTest;
use DerekPhilipAu\Ceramicscalc\Views\Html\Blend\BiaxialBlendHtmlView;

class BiaxialBlendTest extends BaseCompositeMaterialTest
{

    public function testCompositeMaterialBiaxialBlend()
    {
        $numRows = 5;
        $numColumns = 5;

        $tl = $this->providerLeach4321();
        $tl->setName('Top Left');

        $tr = $this->providerPinnellClear();
        $tr->setName('Top Right');

        $bl = $this->providerLeach4321();
        $bl->setName("Bottom Left");

        $br = $this->providerLeach4321();
        $br->setName("Bottom Right");

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