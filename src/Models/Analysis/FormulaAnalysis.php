<?php
/**
 * This file is part of the ceramicscalc PHP library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DerekPhilipAu\Ceramicscalc\Models\Analysis;

class FormulaAnalysis extends Analysis
{
    const UNITY_TYPE_AUTO = 'auto';
    const UNITY_TYPE_RO_R2O = 'ror2o';
    const UNITY_TYPE_R2O3 = 'r2o3';
    const UNITY_TYPE_NONE = 'none';

    public function getSiO2Al2O3Ratio()
    {
        if ($this->getOxide($this::Al2O3) == 0)
        {
            return 0;
        }
        return $this->getOxide($this::SiO2) / $this->getOxide($this::Al2O3);
    }

    public function getR2OTotal() {

        return $this->getOxide($this::Na2O)
            + $this->getOxide($this::K2O)
            + $this->getOxide($this::Li2O);

    }

    public function getROTotal() {

        return $this->getOxide($this::PbO)
            + $this->getOxide($this::SrO)
            + $this->getOxide($this::BaO)
            + $this->getOxide($this::ZnO)
            + $this->getOxide($this::CaO)
            + $this->getOxide($this::MgO);

    }

    public function getFormulaWeight()
    {
        $formulaWeight = 0.0;

        foreach (Analysis::OXIDE_NAMES as $name) {
            if ($this->getOxide($name) > Analysis::EPSILON)
            {
                $formulaWeight += $this->getOxide($name) * Analysis::MOLAR_MASS[$name];
            }
        }

        return $formulaWeight;
    }

    public function getCalculatedLoiFromWeight($weight) {
        $formulaWeight = $this->getFormulaWeight();
        if ($weight <= $formulaWeight) {
            return 0;
        }
        return ($weight - $formulaWeight) /  $weight * 100;
    }

    public function getCalculatedWeightFromLoi($loi) {
        $formulaWeight = $this->getFormulaWeight();
        if ($loi >= 100) {
            return 0;
        }
        return $formulaWeight / (100 - $loi) * 100;
    }

    public static function createNoUnityFormula($percentageAnalysis)
    {
        $formula = new FormulaAnalysis;

        foreach (self::OXIDE_NAMES as $name)
        {
            $formula->setOxide(
                $name,
                $percentageAnalysis->getOxide($name) / self::MOLAR_MASS[$name]
            );
        }

        return $formula;
    }

    public static function createMolePercentageFormula(FormulaAnalysis $rawFormula)
    {
        $formulaTotal = 0.0;
        $formula = new FormulaAnalysis();

        foreach (self::OXIDE_NAMES as $name)
        {
            $formulaTotal += $rawFormula->getOxide($name);
        }

        if ($formulaTotal > 0)
        {
            foreach (self::OXIDE_NAMES as $name)
            {
                $formula->setOxide(
                    $name,
                    $rawFormula->getOxide($name) / $formulaTotal * 100
                );
            }
        }

        return $formula;
    }

    /**
     * @param $percentageAnalysis
     * @return FormulaAnalysis
     *
     * This is an old attempt to automatically guess the correct group to unify on based on
     * prevalence of oxides in the analysis.
     *
     * TODO: Refine
     */
    public static function createAutomaticUnityFormula($percentageAnalysis)
    {
        $ror2oTotal = 0.0;

        foreach (self::RO_R2O_OXIDES as $name)
        {
            $ror2oTotal += $percentageAnalysis->getOxide($name);
        }

        // TODO: 10 is arbitrary
        if ($ror2oTotal > 10)
        {
            // create a ROR2O unity
            return self::createROR2OUnityFormulaAnalysis($percentageAnalysis);
        }

        $r2o3Total = 0.0;

        foreach (self::R2O3_OXIDES as $name)
        {
            $r2o3Total += $percentageAnalysis->getOxide($name);
        }

        if ($r2o3Total > self::EPSILON)
        {
            return self::createR2O3UnityFormulaAnalysis($percentageAnalysis);
        }
        else
        {
            $largestOxide = 0.0;

            foreach (self::OXIDE_NAMES as $name)
            {
                $mass = $percentageAnalysis->getOxide($name) / self::MOLAR_MASS[$name];

                if ($mass > $largestOxide)
                {
                    $largestOxide = $mass;
                }
            }

            return self::createUnityFormula($percentageAnalysis, $largestOxide);
        }
    }


    public static function createUnityFormula($percentageAnalysis, $unityAmount)
    {
        $formula = new FormulaAnalysis;

        if ($unityAmount < self::EPSILON)
        {
            return $formula;
        }

        foreach (self::OXIDE_NAMES as $name)
        {
            $formula->setOxide(
                $name,
                $percentageAnalysis->getOxide($name) / self::MOLAR_MASS[$name] / $unityAmount
            );
        }

        return $formula;
    }

    public static function createROR2OUnityFormulaAnalysis($percentageAnalysis)
    {
        $ror2oUnityFormula = new FormulaAnalysis();

        foreach (self::OXIDE_NAMES as $name)
        {
            $ror2oUnityFormula->setOxide(
                $name,
                $percentageAnalysis->getOxide($name) / self::MOLAR_MASS[$name]
            );
        }

        $ratio = 0;

        foreach (self::RO_R2O_OXIDES as $name)
        {
            $ratio += $ror2oUnityFormula->getOxide($name);
        }

        if ($ratio < self::EPSILON)
        {
            return new FormulaAnalysis();
        }

        foreach (self::OXIDE_NAMES as $name)
        {
            $ror2oUnityFormula->setOxide($name, $ror2oUnityFormula->getOxide($name) / $ratio);
        }

        return $ror2oUnityFormula;
    }

    public static function createR2O3UnityFormulaAnalysis($percentageAnalysis)
    {
        $r2o3UnityFormula = new FormulaAnalysis();

        foreach (self::OXIDE_NAMES as $name)
        {
            $r2o3UnityFormula->setOxide(
                $name,
                $percentageAnalysis->getOxide($name) / self::MOLAR_MASS[$name]
            );
        }

        $ratio = 0;

        foreach (self::R2O3_OXIDES as $name)
        {
            $ratio += $r2o3UnityFormula->getOxide($name);
        }

        if ($ratio < self::EPSILON)
        {
            return new FormulaAnalysis();
        }

        foreach (self::OXIDE_NAMES as $name)
        {
            $r2o3UnityFormula->setOxide($name, $r2o3UnityFormula->getOxide($name) / $ratio);
        }

        return $r2o3UnityFormula;
    }

} // end class Analysis

?>
