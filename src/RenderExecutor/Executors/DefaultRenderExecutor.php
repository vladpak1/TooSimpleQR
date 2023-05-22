<?php

namespace vladpak1\TooSimpleQR\RenderExecutor\Executors;

use vladpak1\TooSimpleQR\Image\QRCodeImage;
use vladpak1\TooSimpleQR\RenderExecutor\AbstractRenderExecutor;
use vladpak1\TooSimpleQR\Settings\QRSettings;

class DefaultRenderExecutor extends AbstractRenderExecutor
{
    public function init(QRSettings $settings, mixed $encodedData): void
    {
        $this->settings    = $settings;
        $this->encodedData = $encodedData;
    }

    public function render(): QRCodeImage
    {
        $image = $this->preRender();
        $image = $this->finalRender($image, $this->avoidExpensiveOperations);

        return new QRCodeImage($image, $this->settings->getSetting('output'));
    }
}
