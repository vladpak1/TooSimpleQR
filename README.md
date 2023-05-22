<h1 align="center">
    vladpak1\TooSimpleQR
</h1>

**TooSimpleQR** is a wrapper over the wonderful [chillerlan/php-qrcode](https://github.com/chillerlan/php-qrcode), which
simplifies QR code generation, paying more attention to customizations and ease of interface.

This small pet project is far from perfect, as I'm still a beginner developer learning the ropes. Please keep in mind my
limited experience when using it, and proceed at your own risk.

If you want to generate QR codes in a serious project, it's better
to use [chillerlan/php-qrcode](https://github.com/chillerlan/php-qrcode).

Examples of QR codes created with this library:
<p align="center">
<img src="https://i.postimg.cc/HxrNR9tc/presets.png" alt="">
</p>

## Requirements

- PHP 8.0+
- Composer
- GD or Imagick

## Installation

```bash
composer require vladpak1/too-simple-qr
```

## Usage Example

```php

use vladpak1\TooSimpleQR\QRFactory;
use vladpak1\TooSimpleQR\OutputConstants;
use vladpak1\TooSimpleQR\Image\Logo;

// Load composer
require_once __DIR__ . '/vendor/autoload.php';

$qr = \vladpak1\TooSimpleQR\QRFactory::QRCode();

$settings = \vladpak1\TooSimpleQR\QRFactory::QRSettings();

$logo = new Logo();
$logo->setByPath(Logo::TWITTER_LOGO);

$settings
    ->setOutput(OutputConstants::OUTPUT_PNG)
    ->setSize(500)                              // Size in pixels.
    ->setBackgroundColor('#ffffff')             // Background color in HEX format.
    ->setMargin(50)                             // Margin in pixels.
    ->setFinderColor('#1d9bf0')                 // Finder color in HEX format.
    ->setDataColor('#3284bc')                   // Data color in HEX format.
    ->setCorrectionLevel(OutputConstants::CORRECTION_LEVEL_H) // Correction level.
    ->setCircularModules(true)                  // Round modules.
    ->setCircleRadius(0.75)                     // Circle radius.
    ->setLogo($logo)                            // Logo.
    ->setCustomFinderShape(OutputConstants::SHAPE_CIRCLE); // Custom finder shape.

$qrCodeImage = $qr
    ->setData('Hello World!')
    ->setSettings($settings)
    ->render();
    
// Now we can save or directly output (as HTML) the QR code.
$qrCodeImage->echo();

```

As a result, we get:
<p align="center">
<img src="https://i.postimg.cc/MK1pgysF/example-1.png" alt="">
</p>

We can also distort the QR code by adding, for example, a gradient:

```php
->setBackgroundGradient('#FFD8D8', '#FFF9F9', Gradient::DIRECTION_BOTTOM_TO_TOP);
```

<p align="center">
<img src="https://i.postimg.cc/cHv2gG65/12.png" alt="">
</p>

## Need to quickly generate a QR code?

Consider using presets — predefined settings for generating QR codes.

You can easily create your own presets by extending the `vladpak1\TooSimpleQR\Preset\AbstractPreset` class.

Example of using a preset:

```php

$preset = new \vladpak1\TooSimpleQR\Preset\Presets\InstagramPreset('Hello World!');

// You can also set the driver and output interface for the preset.
$preset
    ->setDriver(\vladpak1\TooSimpleQR\OutputConstants::DRIVER_GD)
    ->setOutput(\vladpak1\TooSimpleQR\OutputConstants::OUTPUT_PNG);
    
// We get a branded Instagram QR code.
$preset->render()->save('path/to/qr.png');


```

## GD or Imagick?

This library will work with either GD or Imagick, but some settings are only supported by Imagick.
Probably in the future, I will make everything more consistent and the difference in drivers will not be so noticeable.
However, at the moment, Imagick is recommended.

The library will automatically select the driver to use, based on what is installed on your server, giving preference to
Imagick.

But you can also forcibly specify the driver:

```php
$qr->setDriver(OutputConstants::DRIVER_GD);
```

## Testing

TooSimpleQR uses [PHPUnit](https://github.com/sebastianbergmann/phpunit) for testing.
Currently, there are very few tests, mainly due to issues with the package architecture. I plan to address these issues
in future updates.

To run tests, use the following command:

```bash
composer test
```

## License

TooSimpleQR is licensed under the MIT license.