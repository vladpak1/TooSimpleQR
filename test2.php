<?php

//load composer
require_once __DIR__ . '/vendor/autoload.php';

use vladpak1\TooSimpleQR\OutputConstants;
use vladpak1\TooSimpleQR\Image\Logo;

// Загружаем composer
require_once __DIR__ . '/vendor/autoload.php';

$qr = \vladpak1\TooSimpleQR\QRFactory::QRCode();

$settings = \vladpak1\TooSimpleQR\QRFactory::QRSettings();

$logo = new Logo();
$logo->setByPath(Logo::TWITTER_LOGO);

$settings
    ->setOutput(OutputConstants::OUTPUT_PNG)
    ->setSize(500)                              // Размер в пикселях.     // Цвет фона в формате HEX.
    ->setMargin(50)                             // Отступ от краев в пикселях.
    ->setFinderColor('#1d9bf0')                 // Цвет файндеров в формате HEX.
    ->setDataColor('#3284bc')                   // Цвет данных в формате HEX.
    ->setCorrectionLevel(OutputConstants::CORRECTION_LEVEL_H) // Уровень коррекции ошибок.
    ->setCircularModules(true)                  // Округлять модули.
    ->setCircleRadius(0.75)                     // Радиус окружности.
    ->setLogo($logo)                            // Логотип.
    ->setCustomFinderShape(OutputConstants::SHAPE_CIRCLE) // Форма файндеров.
    ->setBackgroundGradient('#FFD8D8', '#FFF9F9', \vladpak1\TooSimpleQR\Image\Gradient::DIRECTION_BOTTOM_TO_TOP);

$qrCodeImage = $qr
    ->setData('Hello World!')
    ->setSettings($settings)
    ->render();

// Теперь мы можем сохранить или сразу вывести (как HTML) QR код.
$qrCodeImage->echo();
