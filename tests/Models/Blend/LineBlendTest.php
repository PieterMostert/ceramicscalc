<?php
/**
 * This file is part of the ceramicscalc PHP library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DerekPhilipAu\Ceramicscalc\Test\Models\Material;

use DerekPhilipAu\Ceramicscalc\Models\Blend\LineBlend;
use DerekPhilipAu\Ceramicscalc\Test\BaseCompositeMaterialTest;
use DerekPhilipAu\Ceramicscalc\Views\Txt\Blend\LineBlendTxtView;

class LineBlendTest extends BaseCompositeMaterialTest
{

    public function testCompositeMaterialLineBlend()
    {
        $leach4321 = $this->providerLeach4321();
        $pinnell = $this->providerPinnellClear();

        $lineBlend = LineBlend::createLineBlend($leach4321, $pinnell, 10, 90, 10, 90, 10);

        $this->assertEquals(9, count($lineBlend));

        $leach90Pinnell10 = $lineBlend[0]->getSimplifiedMaterial();

        $this->assertEquals(38.5, $leach90Pinnell10->getMaterialComponent(self::MATERIAL_POTASH_ID)->getAmount());
        $this->assertEquals(30.5, $leach90Pinnell10->getMaterialComponent(self::MATERIAL_SILICA_ID)->getAmount());
        $this->assertEquals(20, $leach90Pinnell10->getMaterialComponent(self::MATERIAL_WHITING_ID)->getAmount());
        $this->assertEquals(11, $leach90Pinnell10->getMaterialComponent(self::MATERIAL_KAOLIN_ID)->getAmount());

        $leach40Pinnell60 = $lineBlend[5]->getSimplifiedMaterial();

        $this->assertEquals(31, $leach40Pinnell60->getMaterialComponent(self::MATERIAL_POTASH_ID)->getAmount());
        $this->assertEquals(33, $leach40Pinnell60->getMaterialComponent(self::MATERIAL_SILICA_ID)->getAmount());
        $this->assertEquals(20, $leach40Pinnell60->getMaterialComponent(self::MATERIAL_WHITING_ID)->getAmount());
        $this->assertEquals(16, $leach40Pinnell60->getMaterialComponent(self::MATERIAL_KAOLIN_ID)->getAmount());

        LineBlendTxtView::printTxt($lineBlend);
    }

}

?>