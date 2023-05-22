<?php

namespace vladpak1\TooSimpleQR\Settings\Handler\ExtendHandler\Handlers;

use Intervention\Image\Image;
use Throwable;
use vladpak1\TooSimpleQR\Exception\RenderException;
use vladpak1\TooSimpleQR\Exception\SettingsLogicException;
use vladpak1\TooSimpleQR\Settings\Handler\ExtendHandler\AbstractExtendHandler;
use vladpak1\TooSimpleQR\Settings\QRSettings;

final class SizeHandler extends AbstractExtendHandler
{
    public string $settingName = 'size';

    public string $settingType = 'integer';

    public function handle(Image $image, mixed $settingValue): Image
    {
        $this->assertSettingType($settingValue);

        try {
            $image->resize($settingValue, $settingValue);
        } catch (Throwable $e) {
            throw new RenderException('Unable to resize image: ' . $e->getMessage());
        }

        return $image;
    }

    public function validateSettings(QRSettings $settings): void
    {
        $translatableSettings = $settings->getTranslatableSettings();
        $extendedSettings     = $settings->getExtendedSettings();

        if (!isset($extendedSettings[$this->settingName])) {
            return;
        }

        if ($extendedSettings[$this->settingName] < 1) {
            throw new SettingsLogicException('Size must be greater than 0.');
        }
    }
}
