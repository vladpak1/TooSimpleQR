<?php

//load composer
use vladpak1\TooSimpleQR\Image\Logo;
use vladpak1\TooSimpleQR\OutputConstants;
use vladpak1\TooSimpleQR\Preset\Presets\TwitterPreset;

require_once __DIR__ . '/vendor/autoload.php';

$presetTwitter   = new TwitterPreset('Hello!');
$presetYoutube   = new \vladpak1\TooSimpleQR\Preset\Presets\YoutubePreset('https://www.youtube.com/watch?v=dQw4w9WgXcQ');
$presetInstagram = new \vladpak1\TooSimpleQR\Preset\Presets\InstagramPreset('https://www.instagram.com/p/CP7Qq9YhZ0E/');

$presetTwitter->render()->echo();
$presetYoutube->render()->echo();
$presetInstagram->render()->echo();

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
