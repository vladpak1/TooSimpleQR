<?php

namespace vladpak1\TooSimpleQR;

use vladpak1\TooSimpleQR\Image\QRCodeImage;
use vladpak1\TooSimpleQR\Settings\QRSettings;

/**
 * Interface QRCodeInterface.
 *
 * Defines the contract for a QRCode.
 */
interface QRCodeInterface
{
    public function setSettings(QRSettings $settings): self;

    public function getSettings(): QRSettings;

    public function setDriver(string $driver): self;

    public function setData(mixed $data): self;

    public function getData(): mixed;

    public function render(): QRCodeImage;
}
