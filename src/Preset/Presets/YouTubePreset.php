<?php

namespace vladpak1\TooSimpleQR\Preset\Presets;

use vladpak1\TooSimpleQR\Image\Logo;
use vladpak1\TooSimpleQR\OutputConstants;
use vladpak1\TooSimpleQR\Preset\AbstractPreset;
use vladpak1\TooSimpleQR\QRFactory;
use vladpak1\TooSimpleQR\Settings\QRSettings;

class YouTubePreset extends AbstractPreset
{
    protected function prepareSettings(): QRSettings
    {
        $logo = new Logo();
        $logo->setByPath(Logo::YOUTUBE_LOGO);

        $settings = QRFactory::QRSettings()
            ->setSize($this->size ?? 500)
            ->setCorrectionLevel(OutputConstants::CORRECTION_LEVEL_H)
            ->setMargin(50)
            ->setLogo($logo)
            ->setFinderColor('#d4101d')
            //->setDataColor('#9c0610')
            ->setOutput($this->output ?? OutputConstants::OUTPUT_PNG);

        if ($this->isImagick()) {
            $settings
                ->setCustomFinderShape(OutputConstants::SHAPE_SQUIRCLE);
        }

        return $settings;
    }
}
