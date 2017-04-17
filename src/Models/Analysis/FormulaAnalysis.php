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
        $formula_weight = 0.0;

        foreach (Analysis::OXIDE_NAMES as $name) {
            if ($this->getOxide($name) > Analysis::EPSILON)
            {
                $formula_weight += $this->getOxide($name) * Analysis::MOLAR_MASS[$name];
            }
        }

        return $formula_weight;
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

    public static function createMolePercentageFormula(FormulaAnalysis $raw_formula)
    {
        $formula_total = 0.0;
        $formula = new FormulaAnalysis();

        foreach (self::OXIDE_NAMES as $name)
        {
            $formula_total += $raw_formula->getOxide($name);
        }

        if ($formula_total > 0)
        {
            foreach (self::OXIDE_NAMES as $name)
            {
                $formula->setOxide(
                    $name,
                    $raw_formula->getOxide($name) / $formula_total * 100
                );
            }
        }

        return $formula;
    }

    public static function createAutomaticUnityFormula($percentageAnalysis)
    {
        $ror2o_total = 0.0;

        foreach (self::RO_R2O_OXIDES as $name)
        {
            $ror2o_total += $percentageAnalysis->getOxide($name);
        }

        // TODO: 10 is arbitrary
        if ($ror2o_total > 10)
        {
            // create a ROR2O unity
            return self::createROR2OUnityFormulaAnalysis($percentageAnalysis);
        }

        $r2o3_total = 0.0;

        foreach (self::R2O3_OXIDES as $name)
        {
            $r2o3_total += $percentageAnalysis->getOxide($name);
        }

        if ($r2o3_total > self::EPSILON)
        {
            return self::createR2O3UnityFormulaAnalysis($percentageAnalysis);
        }
        else
        {
            $largest_oxide = 0.0;

            foreach (self::OXIDE_NAMES as $name)
            {
                $mass = $percentageAnalysis->getOxide($name) / self::MOLAR_MASS[$name];

                if ($mass > $largest_oxide)
                {
                    $largest_oxide = $mass;
                }
            }

            return self::createUnityFormula($percentageAnalysis, $largest_oxide);
        }
    }


    public static function createUnityFormula($percentageAnalysis, $unity_amount)
    {
        $formula = new FormulaAnalysis;

        if ($unity_amount < self::EPSILON)
        {
            return $formula;
        }

        foreach (self::OXIDE_NAMES as $name)
        {
            $formula->setOxide(
                $name,
                $percentageAnalysis->getOxide($name) / self::MOLAR_MASS[$name] / $unity_amount
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
