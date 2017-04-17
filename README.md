# ceramicscalc

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

PHP package for representing ceramics analyses, materials, and recipes.

## Structure

If any of the following are applicable to your project, then the directory structure should follow industry best practises by being named the following.

```
bin/        
config/
src/
tests/
vendor/
```


## Install

Via Composer

``` bash
$ composer require derekphilipau/ceramicscalc
```

## Usage

``` php

        $percent = new PercentageAnalysis();
        $percent->setOxide(Analysis::K2O, 16.92);
        $percent->setOxide(Analysis::Al2O3, 18.32);
        $percent->setOxide(Analysis::SiO2, 64.76);
        $potash = new PrimitiveMaterial(1);
        $potash->setName("Potash Feldspar");
        $potash->setPercentageAnalysis($percent);

        $percent = new PercentageAnalysis();
        $percent->setOxide(Analysis::SiO2, 100);
        $silica = new PrimitiveMaterial(2);
        $silica->setName("Silica");
        $silica->setPercentageAnalysis($percent);

        $percent = new PercentageAnalysis();
        $percent->setOxide(Analysis::CaO, 56.10);
        $percent->setLOI(43.90);
        $whiting = new PrimitiveMaterial(3);
        $whiting->setName("Whiting");
        $whiting->setPercentageAnalysis($percent);

        $percent = new PercentageAnalysis();
        $percent->setOxide(Analysis::Al2O3, 40.21);
        $percent->setOxide(Analysis::SiO2, 47.29);
        $percent->setLOI(12.50);
        $kaolin = new PrimitiveMaterial(4);
        $kaolin->setName("Kaolin");
        $kaolin->setPercentageAnalysis($percent);

        $leach4321 = new CompositeMaterial();
        $leach4321->setName("Leach 4321");
        $leach4321->addMaterial($potash, 40);
        $leach4321->addMaterial($silica, 30);
        $leach4321->addMaterial($whiting, 20);
        $leach4321->addMaterial($kaolin, 10);
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Credits

- [Derek Philip Au][link-author]
- [All Contributors][link-contributors]

## License

GNU General Public License v3.0. Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/derekphilipau/ceramicscalc.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/derekphilipau/ceramicscalc/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/derekphilipau/ceramicscalc.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/derekphilipau/ceramicscalc.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/derekphilipau/ceramicscalc.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/derekphilipau/ceramicscalc
[link-travis]: https://travis-ci.org/derekphilipau/ceramicscalc
[link-scrutinizer]: https://scrutinizer-ci.com/g/derekphilipau/ceramicscalc/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/derekphilipau/ceramicscalc
[link-downloads]: https://packagist.org/packages/derekphilipau/ceramicscalc
[link-author]: https://github.com/derekphilipau
[link-contributors]: ../../contributors
