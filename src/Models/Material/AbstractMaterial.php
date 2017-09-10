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

    /**
     * AbstractMaterial constructor.
     *
     * All materials always have an analysis
     *
     */
    public function __construct()
    {
        $this->percentageAnalysis = new PercentageAnalysis();
        $this->formulaAnalysis = new FormulaAnalysis();
    }

    /**
     * TODO: See if necessary in PHP 7
     */
    public function __clone() {
        $this->percentageAnalysis = clone $this->percentageAnalysis;
        $this->formulaAnalysis = clone $this->formulaAnalysis;
    }

    /**
     * @return bool
     *
     * Part of Composite pattern.  Return True if this is a CompositeMaterial,
     * return false if this is a PrimitiveMaterial.
     */
    public abstract function isComposite() : bool;

    /**
     * @return mixed
     *
     * Returns a simplified material containing only a PrimitiveMaterial or
     * CompositeMaterial that contains only PrimitiveMaterials.
     *
     */
    public abstract function getSimplifiedMaterial();

    /**
     * @return mixed
     * TODO:  Think about how to implement this.
     */
    function getUniqueId()
    {
        return $this->uniqueId;
    }

    /**
     * @param string $name
     *
     * Set the name of this material
     *
     */
    function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     *
     * Return the name of this material
     *
     */
    function getName()
    {
        return $this->name;
    }

    /**
     * @param string $description
     */
    function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    function getDescription()
    {
        return $this->description;
    }

    /**
     * @param PercentageAnalysis $percentageAnalysis
     */
    function setPercentageAnalysis(PercentageAnalysis $percentageAnalysis)
    {
        $this->percentageAnalysis = $percentageAnalysis;
        $this->formulaAnalysis = FormulaAnalysis::createNoUnityFormula($percentageAnalysis);
    }

    /**
     * @return PercentageAnalysis
     */
    function getPercentageAnalysis() : PercentageAnalysis
    {
        return $this->percentageAnalysis;
    }

    /**
     * @return PercentageAnalysis
     */
    function get100PercentPercentageAnalysis() : PercentageAnalysis
    {
        return PercentageAnalysis::create100PercentPercentageAnalysis($this->percentageAnalysis);
    }

    /**
     * @param FormulaAnalysis $formulaAnalysis
     */
    function setFormulaAnalysis(FormulaAnalysis $formulaAnalysis)
    {
        $this->formulaAnalysis = $formulaAnalysis;
        $this->percentageAnalysis = PercentageAnalysis::createPercentageAnalysis($formulaAnalysis);
    }

    /**
     * @return FormulaAnalysis
     */
	function getFormulaAnalysis() : FormulaAnalysis
    {
        return $this->formulaAnalysis;
    }

    /**
     * @return FormulaAnalysis
     */
    function getUmfAnalysis() : FormulaAnalysis
    {
        return FormulaAnalysis::createROR2OUnityFormulaAnalysis($this->percentageAnalysis);
    }
}

?>
