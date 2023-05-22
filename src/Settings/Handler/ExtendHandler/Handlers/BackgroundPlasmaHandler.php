<?php

namespace vladpak1\TooSimpleQR\Settings\Handler\ExtendHandler\Handlers;

use Intervention\Image\Image;
use Throwable;
use vladpak1\TooSimpleQR\Exception\SettingsLogicException;
use vladpak1\TooSimpleQR\Image\InterventionWrapperStatic;
use vladpak1\TooSimpleQR\Image\Plasma;
use vladpak1\TooSimpleQR\Settings\Handler\ExtendHandler\AbstractExtendHandler;
use vladpak1\TooSimpleQR\Settings\QRSettings;

final class BackgroundPlasmaHandler extends AbstractExtendHandler
{
    public string $settingName = 'backgroundPlasma';

    public string $settingType = 'array';

    public function handle(Image $image, mixed $settingValue): Image
    {
        $this->assertSettingType($settingValue);
        $this->imagickRequired();

        try {
            $plasma = new Plasma();

            $plasmaImage = $plasma
                ->setStartColor($settingValue['colors'][0])
                ->setEndColor($settingValue['colors'][1])
                ->setCanvasSize($image->width(), $image->height())
                ->generate();

            $base64Encoded    = $plasmaImage->getBase64()->getBase64();
            $plasmaBackground = InterventionWrapperStatic::make($base64Encoded);

            return $plasmaBackground->insert($image, 'center');

        } catch (Throwable $th) {
            throw new SettingsLogicException(
                sprintf(
                    'An error occurred while generating plasma background. Error: %s',
                    $this->settingName
                )
            );
        }

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

        if (isset($extendedSettings['backgroundColor'])) {
            throw new SettingsLogicException(
                sprintf(
                    'The setting "%s" cannot be used with "backgroundColor". Please remove "backgroundColor" setting.',
                    $this->settingName
                )
            );
        }
    }

    public function assertSettingType(mixed $settingValue): void
    {
        if (!isset($settingValue['colors'])) {
            throw new SettingsLogicException(
                sprintf(
                    'The setting "%s" must contain "colors" key.',
                    $this->settingName
                )
            );
        }

        if (count($settingValue['colors']) !== 2) {
            throw new SettingsLogicException(
                sprintf(
                    'The setting "%s" must contain exactly 2 colors.',
                    $this->settingName
                )
            );
        }
    }
}
