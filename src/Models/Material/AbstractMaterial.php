<?php
/**
 * This file is part of the ceramicscalc PHP library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DerekPhilipAu\Ceramicscalc\Models\Material;

use DerekPhilipAu\Ceramicscalc\Models\Analysis\PercentageAnalysis;
use DerekPhilipAu\Ceramicscalc\Models\Analysis\FormulaAnalysis;

abstract class AbstractMaterial {

	protected $uniqueId;
	protected $name;
	protected $description;

	protected $loi = 0.0;

	protected $percentageAnalysis; // PercentageAnalysis
    protected $formulaAnalysis; // Analysis

    public function __construct()
    {
        $this->percentageAnalysis = new PercentageAnalysis();
        $this->formulaAnalysis = new FormulaAnalysis();
    }

    public function __clone() {
        $this->percentageAnalysis = clone $this->percentageAnalysis;
        $this->formulaAnalysis = clone $this->formulaAnalysis;
    }

    public abstract function isComposite();
    public abstract function getSimplifiedMaterial();

    function getUniqueId()
    {
        return $this->uniqueId;
    }

    function setName($name)
    {
        $this->name = $name;
    }

    function getName()
    {
        return $this->name;
    }

    function setDescription($description)
    {
        $this->description = $description;
    }

    function getDescription()
    {
        return $this->description;
    }

    function setPercentageAnalysis(PercentageAnalysis $percentageAnalysis)
    {
        $this->percentageAnalysis = $percentageAnalysis;
        $this->formulaAnalysis = FormulaAnalysis::createNoUnityFormula($percentageAnalysis);
    }

    function getPercentageAnalysis()
    {
        return $this->percentageAnalysis;
    }

    function get100PercentPercentageAnalysis()
    {
        return PercentageAnalysis::create100PercentPercentageAnalysis($this->percentageAnalysis);
    }

    function setFormulaAnalysis(FormulaAnalysis $formulaAnalysis)
    {
        $this->formulaAnalysis = $formulaAnalysis;
        $this->percentageAnalysis = PercentageAnalysis::createPercentageAnalysis($formulaAnalysis);
    }

	function getFormulaAnalysis() // : Analysis
    {
        return $this->formulaAnalysis;
    }

    function getUmfAnalysis()
    {
        return FormulaAnalysis::createROR2OUnityFormulaAnalysis($this->percentageAnalysis);
    }
}

?>
