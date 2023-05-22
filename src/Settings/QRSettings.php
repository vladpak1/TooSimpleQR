<?php

namespace vladpak1\TooSimpleQR\Settings;

use vladpak1\TooSimpleQR\Exception\LogoException;
use vladpak1\TooSimpleQR\Image\Gradient;
use vladpak1\TooSimpleQR\Image\Logo;

/**
 * Class QRSettings.
 *
 * Use this class to set QRCode settings.
 */
class QRSettings
{
    protected array $translatableSettings = [
        'transparentBackground' => false,
    ];

    protected array $extendedSettings = [];

    /**
     * Sets the logo to be displayed in the center of the QR code.
     * Please note that correction level must be H.
     *
     * @param  Logo|string   $logo Logo path or Logo instance.
     * @throws LogoException If you pass a string, but the file does not exist.
     */
    public function setLogo(Logo|string $logo): self
    {
        if ($logo instanceof Logo) {
            $this->setExtendedSetting('logo', $logo);

            return $this;
        }

        $logoEntity = new Logo();
        $logoEntity->setByPath($logo);

        $this->setExtendedSetting('logo', $logoEntity);

        return $this;
    }

    /**
     * Sets the QR code size in pixels.
     *
     * In fact, the size of the QR code depends on its version, and the version
     * depends on the amount of encoded data.
     * So under the hood, an attempt will be made to determine the scale that will
     * generate the QR code of the desired size.
     *
     * The accuracy of the prediction varies within 5-10 pixels, so
     * the resulting QR code will be resized to the desired size.
     *
     * @param int $size Size in pixels.
     */
    public function setSize(int $size): self
    {
        $this->setExtendedSetting('size', $size);

        return $this;
    }

    /**
     * Sets the QR code background color.
     * Imagick required, cause GD does not support transparency at the moment.
     *
     * @param string $hex Hex color code.
     */
    public function setBackgroundColor(string $hex): self
    {
        $this->setExtendedSetting('backgroundColor', $hex);
        $this->setTranslatableSetting('transparentBackground', true);

        return $this;
    }

    /**
     * Sets the margin around the QR code.
     * You can also set 0 to get rid of the margin completely.
     *
     * @param int $margin Margin in pixels. The number must be greater than or equal to 0.
     */
    public function setMargin(int $margin): self
    {
        $this->setTranslatableSetting('noNativeMargin', true);
        $this->setExtendedSetting('margin', $margin);

        return $this;
    }

    /**
     * Sets background gradient.
     * Imagick required.
     *
     * @param string $startColor Required. Start color in HEX format.
     * @param string $endColor   Required. End color in HEX format.
     * @param string $direction  Optional. Gradient direction. Default: 'to bottom right'. Use constants from Gradient class.
     */
    public function setBackgroundGradient(string $startColor, string $endColor, string $direction = ''): self
    {
        $direction = $direction ?: Gradient::DIRECTION_LEFT_TO_RIGHT;

        $this->setExtendedSetting('backgroundGradient', [
            'colors' => [
                $startColor,
                $endColor,
            ],
            'direction' => $direction,
        ]);
        $this->setTranslatableSetting('transparentBackground', true);

        return $this;
    }

    /**
     * Sets background plasma.
     * Imagick required.
     *
     * @param  string $startColor Required. Start color in HEX format.
     * @param  string $endColor   Required. End color in HEX format.
     * @param  int    $blur       Optional. Blur radius. Default: 20.
     * @return $this
     */
    public function setBackgroundPlasma(string $startColor, string $endColor, int $blur = 20): self
    {
        $this->setExtendedSetting(
            'backgroundPlasma',
            [
                'colors' => [
                    $startColor,
                    $endColor,
                ],
                'blur' => $blur,
            ]
        );
        $this->setTranslatableSetting('transparentBackground', true);

        return $this;
    }

    /**
     * Sets the QR code finder shape. Use constants from OutputConstants class.
     * Imagick required.
     *
     * @param string $shape Shape name.
     */
    public function setCustomFinderShape(string $shape): self
    {
        $this->setExtendedSetting('customFinderShape', $shape);
        $this->setTranslatableSetting('noNativeMargin', true);

        return $this;
    }

    /**
     * Sets an output type. Use constants from OutputConstants class.
     *
     * @param string $outputType Output type.
     */
    public function setOutput(string $outputType): self
    {
        $this->setTranslatableSetting('output', $outputType);

        return $this;
    }

    /**
     * Sets transparent background.
     * Imagick required, cause GD does not support transparency at the moment.
     *
     * @param bool $transparentBackground If true, the background will be transparent.
     */
    public function setTransparentBackground(bool $transparentBackground): self
    {
        $this->setTranslatableSetting('transparentBackground', $transparentBackground);

        return $this;
    }

    /**
     * Sets the scale factor.
     *
     * Please be careful with this setting.
     * Using this setting without understanding its impact is not recommended.
     * Usually, you should use the setSize() method instead to control the size of the QR code.
     *
     * @param  int   $scale Scale in pixels.
     * @return $this
     */
    public function setScale(int $scale): self
    {
        $this->setTranslatableSetting('scale', $scale);

        return $this;
    }

    /**
     * Sets the correction level of the QR code.
     * The higher the correction level, the more data can be restored in case of damage to the QR code.
     *
     * @param string $correctionLevel Error correction level. Use constants from OutputConstants class.
     */
    public function setCorrectionLevel(string $correctionLevel): self
    {
        $this->setTranslatableSetting('correctionLevel', $correctionLevel);

        return $this;
    }

    /**
     * Sets finder color.
     * Finders are the square patterns at three corners of the QR code.
     *
     * @param string $hex Hex color code.
     */
    public function setFinderColor(string $hex): self
    {
        $this->setTranslatableSetting('finderColor', $hex);

        return $this;
    }

    /**
     * Sets data color.
     * Data in this context is all the elements of the internal matrix except the finders.
     *
     * @param string $hex Hex color code.
     */
    public function setDataColor(string $hex): self
    {
        $this->setTranslatableSetting('dataColor', $hex);

        return $this;
    }

    /**
     * Sets circular modules.
     * You can specify the radius of the circle with the setCircleRadius() method. Default: 0.45.
     *
     * @param bool $circularModules If true, the modules will be circular.
     */
    public function setCircularModules(bool $circularModules): self
    {
        $this->setTranslatableSetting('circularModules', $circularModules);

        if ($this->getSetting('circleRadius') === false) {
            $this->setCircleRadius(0.45);
        }

        return $this;
    }

    /**
     * Sets the radius of the circles.
     * No impact if circular modules are false.
     *
     * @param float $circleRadius Radius of the circle.
     */
    public function setCircleRadius(float $circleRadius): self
    {
        $this->setTranslatableSetting('circleRadius', $circleRadius);

        return $this;
    }

    /**
     * @param int $version QR code version.
     */
    public function setVersion(int $version): self
    {
        $this->setTranslatableSetting('version', $version);

        return $this;
    }

    public function setTranslatableSetting(string $settingName, mixed $settingValue): void
    {
        $this->translatableSettings[$settingName] = $settingValue;
    }

    public function setExtendedSetting(string $settingName, mixed $settingValue): void
    {
        $this->extendedSettings[$settingName] = $settingValue;
    }

    public function getTranslatableSettings(): array
    {
        return $this->translatableSettings;
    }

    public function getExtendedSettings(): array
    {
        return $this->extendedSettings;
    }

    /**
     * @return mixed Returns setting value or null if setting is not found.
     */
    public function getSetting(string $settingName): mixed
    {
        if (isset($this->translatableSettings[$settingName])) {
            return $this->translatableSettings[$settingName];
        }

        if (isset($this->extendedSettings[$settingName])) {
            return $this->extendedSettings[$settingName];
        }

        return null;
    }
}
