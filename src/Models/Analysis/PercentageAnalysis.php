<?php
/**
 * This file is part of the ceramicscalc PHP library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DerekPhilipAu\Ceramicscalc\Models\Analysis;

class PercentageAnalysis extends Analysis
{

    protected $loi = 0;

    public function getLOI() {
        return $this->loi;
    }

    public function setLOI($loi) {
        $this->loi = $loi;
    }

    public static function create100PercentPercentageAnalysis(PercentageAnalysis $percentageAnalysis)
    {
        $newPercentageAnalysis = new PercentageAnalysis();

        $total = 0.0;

        foreach (self::OXIDE_NAMES as $name)
        {
            $total += $percentageAnalysis->getOxide($name);
        }

        if ($total > self::EPSILON)
        {
            foreach (self::OXIDE_NAMES as $name)
            {
                $newPercentageAnalysis->setOxide($name, $percentageAnalysis->getOxide($name) / $total * 100);
            }
        }

        return $newPercentageAnalysis;
    }

    /**
     * @return PercentageAnalysis|void
     * @throws Exception
     *
     */
    public static function createPercentageAnalysis(FormulaAnalysis $formulaAnalysis)
    {
        $totalWeight = 0.0;

        $percentageAnalysis = new PercentageAnalysis();

        // Calculate the total formula weight
        foreach (self::OXIDE_NAMES as $name) {
            $totalWeight += self::MOLAR_MASS[$name] * $formulaAnalysis->getOxide($name);
        }

        if ($totalWeight < self::EPSILON)
        {
            return $percentageAnalysis;
        }

        foreach (self::OXIDE_NAMES as $name) {
            $percentageAnalysis->setOxide(
                $name,
                self::MOLAR_MASS[$name] * $formulaAnalysis->getOxide($name) / $totalWeight * 100
            );
        }

        return $percentageAnalysis;
    }

} // end class PercentageAnalysis

?>
