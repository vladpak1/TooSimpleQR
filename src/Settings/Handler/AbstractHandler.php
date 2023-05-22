<?php

namespace vladpak1\TooSimpleQR\Settings\Handler;

use vladpak1\TooSimpleQR\Exception\InvalidArgumentException;
use vladpak1\TooSimpleQR\Exception\SettingsLogicException;
use vladpak1\TooSimpleQR\Image\InterventionWrapperStatic;
use vladpak1\TooSimpleQR\OutputConstants;
use vladpak1\TooSimpleQR\Settings\QRSettings;

abstract class AbstractHandler implements HandlerInterface
{
    protected string $settingName;

    protected string $settingType;

    protected QRSettings $settingsContext;

    public function assertSettingType(mixed $settingValue): void
    {
        $settingType = gettype($settingValue);

        if ($settingType !== $this->settingType) {

            throw new InvalidArgumentException(
                sprintf(
                    'The setting "%s" must be of type %s, %s given.',
                    $this->settingName,
                    $this->settingType,
                    $settingType,
                )
            );

        }
    }

    public function getSettingName(): string
    {
        return $this->settingName;
    }

    public function getSettingType(): string
    {
        return $this->settingType;
    }

    public function validateTranslated(array $settings): void
    {
        // No validation by default.
    }

    public function validateSettings(QRSettings $settings): void
    {
        // No validation by default.
    }

    public function setSettingsContext(QRSettings $settings): void
    {
        $this->settingsContext = $settings;
    }

    protected function validationException(string $message, ...$args): void
    {
        throw new SettingsLogicException(
            sprintf(
                $message,
                ...$args
            )
        );
    }

    protected function imagickRequired(): void
    {
        if (InterventionWrapperStatic::getDriver() !== OutputConstants::DRIVER_IMAGICK) {
            throw new SettingsLogicException(
                sprintf(
                    'The setting "%s" requires the "%s" driver.',
                    $this->settingName,
                    OutputConstants::DRIVER_IMAGICK,
                )
            );
        }
    }

    protected function isImagick(): bool
    {
        return InterventionWrapperStatic::getDriver() === OutputConstants::DRIVER_IMAGICK;
    }

    /**
     * Detecting the background color of the QR code.
     *
     * Please note that this will work only if the QR code has a white background or is transparent.
     * Handlers that change the background color should be executed after the handler that calls this method.
     *
     * @return array [r, g, b, a] or [0, 0, 0, 0] if the QR code is transparent.
     */
    protected function getBackgroundColor(): array
    {
        /**
         * We assume that the QR code at this stage can be either
         * transparent or have a white background (by Translatable settings).
         */
        $translatableSettings = $this->settingsContext->getTranslatableSettings();

        if (isset($translatableSettings['transparentBackground']) && $translatableSettings['transparentBackground']) {
            return [0, 0, 0, 0];
        }

        return [255, 255, 255, 1];
    }
}
