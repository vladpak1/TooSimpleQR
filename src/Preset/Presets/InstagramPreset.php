<?php

namespace vladpak1\TooSimpleQR\Preset\Presets;

use vladpak1\TooSimpleQR\Image\Logo;
use vladpak1\TooSimpleQR\OutputConstants;
use vladpak1\TooSimpleQR\Preset\AbstractPreset;
use vladpak1\TooSimpleQR\QRFactory;
use vladpak1\TooSimpleQR\Settings\QRSettings;

/**
 * Class InstagramPreset.
 *
 * Preset for Instagram QR Code.
 * Branded with logo and colors.
 *
 * If an Imagick driver is available (or selected) - finder shape will be changed to circle.
 */
final class InstagramPreset extends AbstractPreset
{
    protected function prepareSettings(): QRSettings
    {
        $logo = new Logo();
        $logo->setByPath(__DIR__ . '/../../../assets/logo/instagram.png');
        $settings = QRFactory::QRSettings()
            ->setFinderColor('#de1296')
            ->setDataColor('#f64cba')
            ->setSize($this->size ?? 500)
            ->setCorrectionLevel(OutputConstants::CORRECTION_LEVEL_H)
            ->setLogo($logo)
            ->setMargin(50)
            ->setCircularModules(true)
            ->setCircleRadius(0.45)
            ->setOutput($this->output ?? OutputConstants::OUTPUT_PNG);

        if ($this->isImagick()) {
            $settings
                ->setCustomFinderShape(OutputConstants::SHAPE_CIRCLE);
        }

        return $settings;
    }
}
