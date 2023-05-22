<?php

namespace vladpak1\TooSimpleQR\RenderExecutor;

use vladpak1\TooSimpleQR\Image\QRCodeImage;
use vladpak1\TooSimpleQR\Settings\QRSettings;

/**
 * Interface RenderExecutorInterface.
 *
 * Defines the contract for a render executors.
 */
interface RenderExecutorInterface
{
    /**
     * RenderExecutorInterface init method.
     *
     * @param QRSettings $settings    The settings to use for rendering.
     * @param mixed      $encodedData The data to be included in the QR code.
     */
    public function init(QRSettings $settings, mixed $encodedData);

    /**
     * Render the QR code.
     */
    public function render(): QRCodeImage;
}
