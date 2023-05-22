<?php

namespace vladpak1\TooSimpleQR\Settings\Handler\ExtendHandler\Handlers;

use Intervention\Image\Image;
use Throwable;
use vladpak1\TooSimpleQR\Exception\RenderException;
use vladpak1\TooSimpleQR\Exception\SettingsLogicException;
use vladpak1\TooSimpleQR\Image\InterventionWrapperStatic;
use vladpak1\TooSimpleQR\Settings\Handler\ExtendHandler\AbstractExtendHandler;
use vladpak1\TooSimpleQR\Settings\QRSettings;

final class BackgroundColorHandler extends AbstractExtendHandler
{
    public string $settingName = 'backgroundColor';

    public string $settingType = 'string';

    public function handle(Image $image, mixed $settingValue): Image
    {
        $this->assertSettingType($settingValue);
        $this->imagickRequired();

        $width  = $image->width();
        $height = $image->height();

        try {
            $backgroundCanvas = InterventionWrapperStatic::canvas(
                $width,
                $height,
                $settingValue
            );

            $image = $backgroundCanvas->insert($image, 'center');

        } catch (Throwable $e) {
            throw new RenderException('Cannot create background canvas or insert it: ' . $e->getMessage());
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

        if (!isset($translatableSettings['transparentBackground']) || true !== $translatableSettings['transparentBackground']) {
            throw new SettingsLogicException(
                sprintf(
                    'The setting "%s" cannot be used without transparent background. Please set "transparentBackground" to true.',
                    $this->settingName
                )
            );
        }
    }
}
