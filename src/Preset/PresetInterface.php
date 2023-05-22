<?php

namespace vladpak1\TooSimpleQR\Preset;

use vladpak1\TooSimpleQR\Image\QRCodeImage;

/**
 * Preset Interface.
 *
 * Preset is an easy way to create a QR code with predefined settings
 * for a specific purpose.
 */
interface PresetInterface
{
    public function __construct(mixed $data);

    public function setOutput(string $output): self;

    /**
     * Optional.
     */
    public function setDriver(string $driver): self;

    public function render(): QRCodeImage;
}
