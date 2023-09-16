<?php

namespace vladpak1\TooSimpleQR;

use chillerlan\QRCode\Common\Mode;
use chillerlan\QRCode\Data\QRCodeDataException;
use chillerlan\QRCode\Data\QRData;
use chillerlan\QRCode\Data\QRDataModeInterface;
use chillerlan\QRCode\QROptions;
use Imagick;
use vladpak1\TooSimpleQR\Exception\QRCodeException;
use vladpak1\TooSimpleQR\Image\InterventionWrapperStatic;
use vladpak1\TooSimpleQR\Image\QRCodeImage;
use vladpak1\TooSimpleQR\RenderExecutor\Executors\DefaultRenderExecutor;
use vladpak1\TooSimpleQR\RenderExecutor\RenderExecutorInterface;
use vladpak1\TooSimpleQR\Settings\QRSettings;

class QRCode implements QRCodeInterface
{
    protected QRSettings $settings;

    protected mixed $encodedData = null;

    protected RenderExecutorInterface $renderExecutor;

    /**
     * Class QRCode constructor.
     *
     * The main class of the library.
     * Usage:
     * 1. Create an instance of QRCode.
     *  The recommended way is to use the factory method QRFactory::QRCode().
     *
     * 2. Create an instance of QRSettings (optional).
     *  The recommended way is to use the factory method QRFactory::QRSettings().
     *  Set the settings you need by calling the setSetting() method. See documentation
     *  for more details about the settings.
     *
     * 3. Set the data to encode by calling the setData() method.
     * 4. Finally, call the render() method to get the QRCodeImage object.
     * 5. Do whatever you want with the QRCodeImage object: save, echo, etc.
     *
     * NOTE:
     * You must have one of the extensions installed: GD or Imagick.
     * You can also specify the driver yourself by calling the setDriver() method.
     * If not specified, the library will try to determine the driver itself (Imagick is preferred).
     */
    public function __construct()
    {
        $this->settings = QRFactory::QRSettings();
        $this->determineDriver();

        $this->renderExecutor = new DefaultRenderExecutor();
    }

    /**
     * Sets the settings class.
     *
     * Please note that settings validation is performed only when calling the render() method.
     */
    public function setSettings(QRSettings $settings): self
    {
        $this->settings = $settings;

        return $this;
    }

    /**
     * Get the current QRSettings instance.
     */
    public function getSettings(): QRSettings
    {
        return $this->settings;
    }

    /**
     * Sets the data to encode.
     *
     * You can pass any data that can be encoded by the encoder.
     */
    public function setData(mixed $data): self
    {
        $this->encodedData = $data;

        return $this;
    }

    /**
     * Get the current encoded data.
     */
    public function getData(): mixed
    {
        return $this->encodedData;
    }

    /**
     * Set driver for image rendering.
     * Use OutputConstants::DRIVER_GD or OutputConstants::DRIVER_IMAGICK.
     *
     * The recommended driver is imagick (fast and high quality).
     * Using a GD driver can cause problems with performance and memory usage. (Sorry)
     */
    public function setDriver(string $driver): self
    {
        $drivers = ['gd', 'imagick'];

        if (!in_array($driver, $drivers, true)) {
            throw new QRCodeException('Driver can be "gd" or "imagick"');
        }

        if ($driver === 'gd' && !function_exists('gd_info')) {
            throw new QRCodeException('GD driver is not available. You should install gd extension or use imagick driver.');
        }

        if ($driver === 'imagick' && !class_exists(Imagick::class)) {
            throw new QRCodeException('Imagick driver is not available. You should install imagick extension or use gd driver.');
        }

        InterventionWrapperStatic::setDriver($driver);

        return $this;
    }

    public function setRenderExecutor(RenderExecutorInterface $renderExecutor): void
    {
        $this->renderExecutor = $renderExecutor;
    }

    public function getRenderExecutor(): RenderExecutorInterface
    {
        return $this->renderExecutor;
    }

    /**
     * Render the QR code.
     */
    public function render(): QRCodeImage
    {
        if ($this->encodedData === null) {
            throw new QRCodeException('Data to encode is not set.');
        }

        if ($this->settings->getSetting('scale') === null
            && $this->settings->getSetting('size') !== null
            && $this->settings->getSetting('version') === null) {

            try {
                $this->settings->setTranslatableSetting('scale', $this->estimateScale());
            } catch (QRCodeDataException $e) {
                throw new QRCodeException('Cannot estimate scale. This usually means that the QR code will '
                    . 'fail to render, but you can try to set the scale manually.');
            }

        }

        $this->renderExecutor->init($this->settings, $this->encodedData);

        return $this->renderExecutor->render();
    }

    protected function determineDriver(): void
    {
        if (InterventionWrapperStatic::getDriver() == '') {
            /**
             * Set imagick driver if available, otherwise set gd.
             */
            $imagickAvailable = class_exists(Imagick::class) && extension_loaded('imagick');
            $gdAvailable      = function_exists('gd_info');

            if ($imagickAvailable) {
                InterventionWrapperStatic::setDriver(OutputConstants::DRIVER_IMAGICK);
            } elseif ($gdAvailable) {
                InterventionWrapperStatic::setDriver(OutputConstants::DRIVER_GD);
            } else {
                throw new QRCodeException('No drivers available. You should install imagick (recommended) or gd extension.');
            }
        }
    }

    /**
     * Estimate the scale of the QR code based on the size selected by the user.
     *
     * @throws QRCodeDataException
     */
    protected function estimateScale(): int
    {
        $tempOptions = new QROptions();

        if ($this->settings->getSetting('correctionLevel') !== null) {
            $tempOptions->eccLevel = $this->settings->getSetting('correctionLevel');
        }

        $segment = null;

        foreach (Mode::INTERFACES as $dataInterface) {
            /**
             * @var QRDataModeInterface $dataInterface
             */
            if ($dataInterface::validateString($this->getData())) {
                $segment = (new $dataInterface($this->getData()));

                break;
            }
        }

        if ($segment === null) {
            throw new QRCodeException('Data is not valid.');
        }

        $dataSegments = [
            $segment,
        ];

        $qrData          = new QRData($tempOptions, $dataSegments);
        $qrSizeInModules = 21 + 4 * ($qrData->getMinimumVersion()->getVersionNumber() - 1);

        $estimated = $this->settings->getSetting('size') / $qrSizeInModules;

        if (($qrSizeInModules * 4) > $this->settings->getSetting('size')) {
            throw new QRCodeException('The selected size is too small for the data.');
        }

        return round($estimated);
    }
}
