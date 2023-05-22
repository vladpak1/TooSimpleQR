<?php

namespace vladpak1\TooSimpleQR\Preset\Presets;

use vladpak1\TooSimpleQR\Image\Logo;
use vladpak1\TooSimpleQR\OutputConstants;
use vladpak1\TooSimpleQR\Preset\AbstractPreset;
use vladpak1\TooSimpleQR\QRFactory;
use vladpak1\TooSimpleQR\Settings\QRSettings;

/**
 * Class TwitterPreset.
 *
 * Preset for Twitter QR Code. Branded with logo and colors.
 * Fully supported by all drivers.
 */
final class TwitterPreset extends AbstractPreset
{
    protected function prepareSettings(): QRSettings
    {
        $logo = new Logo();
        $logo->setByPath(Logo::TWITTER_LOGO);

        $settings = QRFactory::QRSettings()
            ->setSize($this->size ?? 500)
            ->setCorrectionLevel(OutputConstants::CORRECTION_LEVEL_H)
            ->setMargin(50)
            ->setLogo($logo)
            ->setCircularModules(true)
            ->setCircleRadius(0.75)
            ->setFinderColor('#1d9bf0')
            ->setDataColor('#3284bc')
            ->setOutput(OutputConstants::OUTPUT_JPG);

        return $settings;
    }
}
