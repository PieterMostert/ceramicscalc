<?php
/**
 * This file is part of the ceramicscalc PHP library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DerekPhilipAu\Ceramicscalc\Models\Analysis;

/**
 * Class Analysis
 * @package DerekPhilipAu\Ceramicscalc\Models\Analysis
 *
 * This is the parent class for FormulaAnalysis & PercentageAnalysis.
 *
 * This class contains methods for storing and retrieving a list of oxide values.
 *
 * This class also defines ALL oxides & molar masses supported by the software.
 */
class Analysis
{
    const EPSILON = 0.00001;

    /**
     * The following constants define ALL oxides supported by this software.
     */

	const SiO2 = 'SiO2';

	const Al2O3 = 'Al2O3';
	const B2O3 = 'B2O3';

	// Alkalis
	const Li2O = 'Li2O';
	const Na2O = 'Na2O';
	const K2O = 'K2O';

	// Alkaline Earths
	const BeO = 'BeO';
	const MgO = 'MgO';
	const CaO = 'CaO';
	const SrO = 'SrO';
	const BaO = 'BaO';

	const P2O5 = 'P2O5';

	// Opacifiers
	const TiO2 = 'TiO2';
	const ZrO = 'ZrO';
	const ZrO2 = 'ZrO2';

	// Colors
	const V2O5 = 'V2O5';
	const Cr2O3 = 'Cr2O3';
	const MnO = 'MnO';
	const MnO2 = 'MnO2';
	const FeO = 'FeO';
	const Fe2O3 = 'Fe2O3';
	const CoO = 'CoO';
	const NiO = 'NiO';
	const CuO = 'CuO';
	const Cu2O = 'Cu2O';
	const CdO = 'CdO';

	const ZnO = 'ZnO';

	const F = 'F';
	const PbO = 'PbO';
	const SnO2 = 'SnO2';

    /**
     * Helper array to store names of all oxides.
	 *
	 * These oxides are listed in the order in which they are usually displayed in interfaces.
     */
	const OXIDE_NAMES = [
		self::SiO2,

		self::Al2O3,
		self::B2O3,

		self::Li2O,
		self::Na2O,
		self::K2O,

		self::BeO,
		self::MgO,
		self::CaO,
		self::SrO,
		self::BaO,

		self::P2O5,

		self::TiO2,
		self::ZrO,
		self::ZrO2,

		self::V2O5,
		self::Cr2O3,
		self::MnO,
		self::MnO2,
		self::FeO,
		self::Fe2O3,
		self::CoO,
		self::NiO,
		self::CuO,
		self::Cu2O,
		self::CdO,

		self::ZnO,

		self::F,
		self::PbO,
		self::SnO2,
    ];

    /**
	 * All molar masses come from the CRC Handbook of Chemistry and Physics.
	 *
	 * It is hoped that all glaze software will standardize on a specific set of molar masses
	 * to make testing and verification between systems easier.
	 *
	 * https://books.google.com/books?id=VVezDAAAQBAJ
     */
    const MOLAR_MASS = [
        self::SiO2  => 60.085,

        self::Al2O3 => 101.961,
        self::B2O3  => 69.620,

        self::Li2O  => 29.881,
        self::Na2O  => 61.979,
        self::K2O   => 94.196,

        self::BeO   => 25.011,
        self::MgO   => 40.304,
        self::CaO   => 56.077,
        self::SrO   => 103.62,
        self::BaO   => 153.326,

        self::P2O5  => 141.945,

        self::TiO2  => 79.866,
        self::ZrO   => 107.2234,
        self::ZrO2  => 123.223,

        self::V2O5  => 181.880,
        self::Cr2O3 => 151.990,
        self::MnO   => 70.937,
        self::MnO2  => 86.937,
        self::FeO   => 71.844,
        self::Fe2O3 => 159.688,
        self::CoO   => 74.932,
        self::NiO   => 74.692,
        self::CuO   => 79.545,
        self::Cu2O  => 143.091,
        self::CdO   => 128.410,

        self::ZnO   => 81.39,

        self::F     => 18.998,
        self::PbO   => 223.2,
        self::SnO2  => 150.709,
    ];

    /**
     * Oxide groups used for unity calculations.
	 *
	 * There is still debate as to which oxides should be listed in the RO/R2O oxide group.
     */
    const RO_R2O_OXIDES = [
		self::PbO,
		self::Na2O,
		self::K2O,
		self::Li2O,
		self::SrO,
		self::BaO,
		self::ZnO,
		self::CaO,
		self::MgO,
        self::FeO,
        self::MnO
    ];

	const R2O3_OXIDES = [
		self::Al2O3,
		self::B2O3
	];

	const RO2_OXIDES = [
		self::SiO2,
		self::ZrO2,
		self::SnO2,
		self::TiO2
    ];

	const OTHER_OXIDES = [
		self::Fe2O3,
		self::MnO2,
		self::P2O5,
		self::F,
		self::CoO,
		self::Cr2O3,
		self::Cu2O,
		self::CuO,
		self::NiO,
		self::V2O5,
		self::ZrO,
    ];

    /**
     * @var array
	 *
	 * The oxide list for this analysis.
     */
	protected $oxides = array();

    public function __construct() {
        $this->initOxides();
    }

    /**
     * Sets all oxides in the private oxide list to 0.0
     */
    protected function initOxides()
    {
        foreach (self::OXIDE_NAMES as $index=> $name) {
            $this->oxides[$name] = 0.0;
        }
    }

    /**
     * @return array
	 *
	 * Return the list of supported oxide names.
     */
    public static function getOxideNames() {
        return self::OXIDE_NAMES;
	}

    /**
     * @return array
	 *
	 * Return the list of molar masses used by this software.
     */
    public static function getMolarMasses() {
        return self::MOLAR_MASS;
    }

    /**
     * @param $name
     * @param $value
	 *
	 * Set the value of an oxide.
     */
    public function setOxide(string $name, float $value) {
		// Verify that the oxide is supported
		// 20180611 This is a nice precaution but is just slowing things down.  Remove:
		//if (array_search($name, self::OXIDE_NAMES) === false) {
		//	throw new Exception('Oxide '.$name.' is not supported.');
		//}

		// Insert oxide value into our list
		$this->oxides[$name] = $value;
	}

    /**
     * @param string $name
     * @return mixed|null
	 *
	 * Return the oxide value specified by $name
     */
	public function getOxide(string $name) {
		// Verify that the oxide is supported

		if (array_search($name, self::OXIDE_NAMES) === false) {
			throw new Exception('Oxide '.$name.' is not supported.');
		}
		if (isset($this->oxides[$name]))
		{
			return $this->oxides[$name];
		}
		return null;
	}

    /**
     * @return mixed
	 *
	 * KNaO is the combined value of K2O and Na2O.
     */
	public function getKNaO() {
		return $this->oxides[self::K2O] + $this->oxides[self::Na2O];
	}

    /**
     * @param array $oxides
	 *
	 * Automatically set oxides in this analysis using an array of name/value pairs.
     */
    public function setOxides(array $oxides) {
        if (!is_array($oxides)) {
            throw new Exception('Argument must be an array of oxides.');
        }

        // Verify that all the oxides are supported
        foreach ($oxides as $name => $value) {
            if (array_search($name, self::OXIDE_NAMES) === false) {
                throw new Exception('Oxide '.$name.' is not supported.');
            }
        }

        // By calling this method we are overwriting ALL oxide values,
		// even if some oxides are not listed in the passed array argument.
        $this->initOxides();

        // Insert oxide values into our list
        foreach ($oxides as $name=>$value) {
            if (is_numeric($value)) {
                $this->oxides[$name] = $value;
            }
        }
    }

    /**
     * @return array
	 *
	 * Return this analysis' list of oxide values.
     */
    public function getOxides() : array {
        return $this->oxides;
    }

} 

?>
