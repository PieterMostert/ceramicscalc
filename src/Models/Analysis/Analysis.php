<?php
/**
 * This file is part of the ceramicscalc PHP library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DerekPhilipAu\Ceramicscalc\Models\Analysis;

use Exception;

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
    const SiO2 = 'SiO2';   // Silicon dioxide

	const Al2O3 = 'Al2O3'; // Aluminum Oxide
	const B2O3 = 'B2O3';   // Boric Oxide

	// Alkalis
	const Li2O = 'Li2O';   // Lithium Oxide
	const Na2O = 'Na2O';   // Sodium Oxide
	const K2O = 'K2O';     // Potassium Oxide

	// Alkaline Earths
	const BeO = 'BeO';     // Beryllium Oxide
	const MgO = 'MgO';     // Magnesium Oxide
	const CaO = 'CaO';     // Calcium Oxide
	const SrO = 'SrO';     // Strontium Oxide, Strontia
	const BaO = 'BaO';     // Barium Oxide

	const P2O5 = 'P2O5';   // Phosphorus Pentoxide

	// Opacifiers
	const TiO2 = 'TiO2';   // Titania, Titanium Dioxide
	const ZrO = 'ZrO';     // Zirconium Oxide, Zirconia
	const ZrO2 = 'ZrO2';   // Zirconium Dioxide

	// Colors
	const V2O5 = 'V2O5';   // Vanadium Pentoxide
	const Cr2O3 = 'Cr2O3'; // Chrome Oxide
	const MnO = 'MnO';     // Manganese Oxide
	const MnO2 = 'MnO2';   // Manganese Dioxide
	const FeO = 'FeO';     // Ferrous Oxide
	const Fe2O3 = 'Fe2O3'; // Iron Oxide, Ferric Oxide
	const CoO = 'CoO';     // Cobalt Oxide
	const NiO = 'NiO';     // Nickel Oxide
	const CuO = 'CuO';     // Cupric Oxide
	const Cu2O = 'Cu2O';   // Cuprous Oxide
	const CdO = 'CdO';     // Cadmium Oxide

	const ZnO = 'ZnO';     // Zinc Oxide

	const F = 'F';         // Fluorine
	const PbO = 'PbO';     // Lead Oxide
	const SnO2 = 'SnO2';   // Tin Oxide, Stannic Oxide

    // Extended (10/2018):
    const HfO2  = 'HfO2';   // Hafnium dioxide
    const Nb2O5  = 'Nb2O5'; // Niobium(V) oxide
    const Ta2O5  = 'Ta2O5'; // Tantalum(V) oxide
    const MoO3  = 'MoO3';   // Molybdenum(VI) oxide
    const WO3  = 'WO3';     // Tungsten(VI) oxide
    const OsO2  = 'OsO2';   // Osmium(IV) oxide
    const IrO2  = 'IrO2';   // Iridium(IV) oxide
    const PtO2  = 'PtO2';   // Platinum(IV) oxide
    const Ag2O  = 'Ag2O';   // Silver Oxide
    const Au2O3  = 'Au2O3'; // Gold Oxide
    const GeO2  = 'GeO2';   // Germanium(IV) oxide
    const As2O3  = 'As2O3'; // Arsenic Oxide
    const Sb2O3  = 'Sb2O3'; // Antimony(III) oxide
    const Bi2O3  = 'Bi2O3'; // Bismuth Oxide
    const SeO2  = 'SeO2';   // Selenium dioxide
    // Lanthanides:
    const La2O3  = 'La2O3'; // Lanthanum oxide
    const CeO2  = 'CeO2';   // Cerium Oxide
    const PrO2  = 'PrO2';   // Praseodymium Oxide
    const Pr2O3  = 'Pr2O3'; // Praseodymium oxide
    const Nd2O3  = 'Nd2O3'; // Neodymium oxide
    const U3O8  = 'U3O8';   // Uranium(V,VI) oxide
    const Sm2O3  = 'Sm2O3'; // Samarium(III) oxide
    const Eu2O3  = 'Eu2O3'; // Europium(III) oxide
    const Tb2O3  = 'Tb2O3'; // Terbium oxide
    const Dy2O3  = 'Dy2O3'; // Dysprosium oxide
    const Ho2O3  = 'Ho2O3'; // Holmia; Holmium(III) oxide
    const Er2O3  = 'Er2O3'; // Erbium oxide
    const Tm2O3  = 'Tm2O3'; // Thulium oxide
    const Yb2O3  = 'Yb2O3'; // Ytterbium(III) oxide
    const Lu2O3  = 'Lu2O3'; // Lutetium oxide

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

        // Extended (10/2018):
        self::HfO2,
        self::Nb2O5,
        self::Ta2O5,
        self::MoO3,
        self::WO3,
        self::OsO2,
        self::IrO2,
        self::PtO2,
        self::Ag2O,
        self::Au2O3,
        self::GeO2,
        self::As2O3,
        self::Sb2O3,
        self::Bi2O3,
        self::SeO2,
        self::La2O3,
        self::CeO2,
        self::PrO2,
        self::Pr2O3,
        self::Nd2O3,
        self::U3O8,
        self::Sm2O3,
        self::Eu2O3,
        self::Tb2O3,
        self::Dy2O3,
        self::Ho2O3,
        self::Er2O3,
        self::Tm2O3,
        self::Yb2O3,
        self::Lu2O3,
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

        // Extended (10/2018):
        self::HfO2  => 210.49,
        self::Nb2O5  => 265.810,
        self::Ta2O5  => 441.893,
        self::MoO3  => 143.94,
        self::WO3  => 231.84,
        self::OsO2  => 222.23,
        self::IrO2  => 224.216,
        self::PtO2  => 227.08,
        self::Ag2O  => 231.735,
        self::Au2O3  => 441.93,
        self::GeO2  => 104.61,
        self::As2O3  => 197.841,
        self::Sb2O3  => 291.518,
        self::Bi2O3  => 465.959,
        self::SeO2  => 110.96,
        self::La2O3  => 325.809,
        self::CeO2  => 172.115,
        self::PrO2  => 172.906,
        self::Pr2O3  => 329.813,
        self::Nd2O3  => 336.48,
        self::U3O8  => 842.082,
        self::Sm2O3  => 348.72,
        self::Eu2O3  => 351.926,
        self::Tb2O3  => 365.849,
        self::Dy2O3  => 373.00,
        self::Ho2O3  => 377.858,
        self::Er2O3  => 382.52,
        self::Tm2O3  => 385.866,
        self::Yb2O3  => 394.08,
        self::Lu2O3  => 397.932,
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

        // Extended (10/2018):
        self::HfO2,
        self::Nb2O5,
        self::Ta2O5,
        self::MoO3,
        self::WO3,
        self::OsO2,
        self::IrO2,
        self::PtO2,
        self::Ag2O,
        self::Au2O3,
        self::GeO2,
        self::As2O3,
        self::Sb2O3,
        self::Bi2O3,
        self::SeO2,
        self::La2O3,
        self::CeO2,
        self::PrO2,
        self::Pr2O3,
        self::Nd2O3,
        self::U3O8,
        self::Sm2O3,
        self::Eu2O3,
        self::Tb2O3,
        self::Dy2O3,
        self::Ho2O3,
        self::Er2O3,
        self::Tm2O3,
        self::Yb2O3,
        self::Lu2O3,
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
     * @return mixed
     *
     * The Si:Al ratio.
     */
    public function getSiAlRatio() {
    	if (empty($this->oxides[self::Al2O3])) {
    		return 0;
		}
        return $this->oxides[self::SiO2] / $this->oxides[self::Al2O3];
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

    public function setFromJson($jsonAnalysis)
    {
        $this->initOxides();

        foreach (self::OXIDE_NAMES as $index => $name) {
            if (!empty($jsonAnalysis[$name])) {
                $this->setOxide($name, (float) $jsonAnalysis[$name]);
            }
        }
    }
} 

?>
