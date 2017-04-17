<?php
/**
 * This file is part of the ceramicscalc PHP library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DerekPhilipAu\Ceramicscalc\Models\Analysis;

class Analysis
{
    const EPSILON = 0.00001;

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

    const MOLAR_MASS = [
        self::SiO2  => 60.08439,

        self::Al2O3 => 101.96137,
        self::B2O3  => 69.6217,

        self::Li2O  => 29.8818,
        self::Na2O  => 61.97897,
        self::K2O   => 94.19605,

        self::BeO   => 25.01158,
        self::MgO   => 40.30449,
        self::CaO   => 56.0778,
        self::SrO   => 103.6204,
        self::BaO   => 153.3271,

        self::P2O5  => 141.94467,

        self::TiO2  => 79.8660,
        self::ZrO   => 107.2234,
        self::ZrO2  => 123.2231,

        self::V2O5  => 181.8800,
        self::Cr2O3 => 151.99061,
        self::MnO   => 70.93748,
        self::MnO2  => 86.93691,
        self::FeO   => 71.8446,
        self::Fe2O3 => 159.6887,
        self::CoO   => 74.93263,
        self::NiO   => 74.69287,
        self::CuO   => 79.5454,
        self::Cu2O  => 143.0914,
        self::CdO   => 128.4104,

        self::ZnO   => 81.3814,

        self::F     => 18.998403,
        self::PbO   => 223.2094,
        self::SnO2  => 150.7096,
    ];

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

	protected $oxides = array();

    public function __construct() {
        $this->initOxides();
    }
    
    protected function initOxides()
    {
        foreach (self::OXIDE_NAMES as $index=> $name) {
            $this->oxides[$name] = 0.0;
        }
    }

    public static function getOxideNames() {
        return self::OXIDE_NAMES;
	}

    public static function getMolarMasses() {
        return self::MOLAR_MASS;
    }

    public function setOxide($name, $value) {
		// Verify that the oxide is supported
		if (array_search($name, self::OXIDE_NAMES) === false) {
			throw new Exception('Oxide '.$name.' is not supported.');
		}

		// Insert oxide value into our list
		$this->oxides[$name] = $value;
	}

	public function getOxide($name) {
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

	public function getKNaO() {
		return $this->oxides[self::K2O] + $this->oxides[self::Na2O];
	}

    protected function setOxides($oxides) {
        if (!is_array($oxides)) {
            throw new Exception('Argument must be an array of oxides.');
        }

        // Verify that all the oxides are supported
        foreach ($oxides as $name => $value) {
            if (array_search($name, self::OXIDE_NAMES) === false) {
                throw new Exception('Oxide '.$name.' is not supported.');
            }
        }

        $this->initOxides();

        // Insert oxide values into our list
        foreach ($oxides as $name=>$value) {
            $this->oxides[$name] = $value;
        }
    }

    public function getOxides() {
        return $this->oxides;
    }

} 

?>
