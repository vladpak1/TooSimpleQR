<?php

namespace vladpak1\TooSimpleQR\Settings\Handler;

use vladpak1\TooSimpleQR\Exception\SettingsLogicException;
use vladpak1\TooSimpleQR\Settings\QRSettings;

interface HandlerInterface
{
    public function getSettingName(): string;

    public function getSettingType(): string;

    /**
     * Please note that this method is called after all TranslatableSettings was translated.
     * And our settings are passed to this method.
     *
     * This is necessary to be able to validate logic including extended settings.
     *
     * @throws SettingsLogicException
     */
    public function validateSettings(QRSettings $settings): void;

    /**
     *
     * Please note that this method is called after all TranslatableSettings was translated.
     * And TRANSLATED settings are passed to this method.
     *
     * @throws SettingsLogicException
     */
    public function validateTranslated(array $settings): void;

    public function setSettingsContext(QRSettings $settings): void;
}
