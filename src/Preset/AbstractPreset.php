<?php

namespace vladpak1\TooSimpleQR\Preset;

use vladpak1\TooSimpleQR\Exception\QRCodeException;
use vladpak1\TooSimpleQR\Image\QRCodeImage;
use vladpak1\TooSimpleQR\OutputConstants;
use vladpak1\TooSimpleQR\QRFactory;
use vladpak1\TooSimpleQR\Settings\QRSettings;

abstract class AbstractPreset implements PresetInterface
{
    protected mixed $data;

    protected string $driver;

    protected string $output;

    protected int $size;

    public function __construct(mixed $data)
    {
        $this->data = $data;

        $this->autoSelectDriver();
    }

    public function render(): QRCodeImage
    {
        $qrCode = QRFactory::QRCode();

        $qrCode
            ->setData($this->data)
            ->setDriver($this->driver)
            ->setSettings($this->prepareSettings());

        return $qrCode->render();
    }

    public function setOutput(string $output): PresetInterface
    {
        $this->output = $output;

        return $this;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function setDriver(string $driver): PresetInterface
    {
        $this->driver = $driver;

        return $this;
    }

    /**
     * Select a driver automatically,
     * imagick has higher priority than gd.
     */
    protected function autoSelectDriver(): void
    {
        if (extension_loaded('imagick')) {
            $this->driver = OutputConstants::DRIVER_IMAGICK;
        } elseif (extension_loaded('gd')) {
            $this->driver = OutputConstants::DRIVER_GD;
        } else {
            throw new QRCodeException('No drivers available. You need to install Imagick or GD extension.');
        }
    }

    protected function isImagick(): bool
    {
        return $this->driver === OutputConstants::DRIVER_IMAGICK;
    }

    abstract protected function prepareSettings(): QRSettings;
}
