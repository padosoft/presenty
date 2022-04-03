# Presentation and Formatting helper with nice fluent interface.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/padosoft/presenty.svg?style=flat-square)](https://packagist.org/packages/padosoft/presenty)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/padosoft/presenty/master.svg?style=flat-square)](https://travis-ci.org/padosoft/presenty)
[![Quality Score](https://img.shields.io/scrutinizer/g/padosoft/presenty.svg?style=flat-square)](https://scrutinizer-ci.com/g/padosoft/presenty)
[![Total Downloads](https://img.shields.io/packagist/dt/padosoft/presenty.svg?style=flat-square)](https://packagist.org/packages/padosoft/presenty)

This package provides a Presentation and Formatting helper with nice fluent interface. 

Table of Contents
=================

   * [Presentation and Formatting helper](#presentation-and-formatting-helper)
      * [Requires](#requires)
      * [Installation](#installation)
      * [USAGE](#usage)
      * [Change log](#change-log)
      * [Testing](#testing)
      * [Contributing](#contributing)
      * [Security](#security)
      * [Credits](#credits)
      * [About Padosoft](#about-padosoft)
      * [License](#license)

##Requires
  
- "php" : ">=7.1.0",
- padosoft/support": "^3.0.4"
  
## Installation

You can install the package via composer:
``` bash
$ composer require padosoft/presenty
```

## USAGE

``` php
//number
echo presenty('2')->number(2);//print '2.00'
echo presenty('2.00')->number(2);//print '2.00'
echo presenty(2)->number(2);//print '2.00'
echo presenty(1000, '.' , ',')->number(2);//print '1.000,00'

//anchor
echo presenty('https://www.padosoft.com')->anchor();//print '<a href="https://www.padosoft.com">https://www.padosoft.com</a>'
echo presenty('https://www.padosoft.com')->anchor(['target' => '_blank']);//print '<a href="https://www.padosoft.com" target="_blank">https://www.padosoft.com</a>'

```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email instead of using the issue tracker.

## Credits
- [Lorenzo Padovani](https://github.com/lopadova)
- [All Contributors](../../contributors)

## About Padosoft
Padosoft (https://www.padosoft.com) is a software house based in Florence, Italy. Specialized in E-commerce and web sites.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
