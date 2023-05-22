<?php

namespace vladpak1\TooSimpleQR\Settings\Handler\ExtendHandler\Handlers;

use Intervention\Image\Image;
use Throwable;
use vladpak1\TooSimpleQR\Exception\SettingsLogicException;
use vladpak1\TooSimpleQR\Image\InterventionWrapperStatic;
use vladpak1\TooSimpleQR\Settings\Handler\ExtendHandler\AbstractExtendHandler;
use vladpak1\TooSimpleQR\Settings\QRSettings;

final class MarginHandler extends AbstractExtendHandler
{
    public string $settingName = 'margin';

    public string $settingType = 'integer';

    public function handle(Image $image, mixed $settingValue): Image
    {
        $this->assertSettingType($settingValue);

        $originalSize = $image->getWidth();
        $resizedSize  = $originalSize - ($settingValue * 2);

        if ($resizedSize < 1) {
            throw new SettingsLogicException('Margin cannot be greater than half the size.');
        }

        try {
            $image->resize($resizedSize, $resizedSize);
        } catch (Throwable $e) {
            throw new SettingsLogicException(
                sprintf(
                    'An error occurred while resizing the QR code (resize arg: %d). %s',
                    $resizedSize,
                    $e->getMessage()
                )
            );
        }

        $backgroundColor = $this->getBackgroundColor();
        $canvasSize      = $image->getWidth() + $settingValue * 2;

        $marginCanvas = InterventionWrapperStatic::canvas(
            $canvasSize,
            $canvasSize,
            $backgroundColor
        );

        try {

            $marginCanvas->insert($image, 'top-left', $settingValue, $settingValue);

        } catch (Throwable $e) {
            throw new SettingsLogicException(
                sprintf(
                    'An error occurred while inserting the QR code into the margin canvas. %s',
                    $e->getMessage()
                )
            );
        }

        return $marginCanvas;
    }

    public function validateSettings(QRSettings $settings): void
    {
        $translatableSettings = $settings->getTranslatableSettings();
        $extendedSettings     = $settings->getExtendedSettings();

        if (!isset($extendedSettings[$this->settingName])) {
            return;
        }

        if (isset($extendedSettings['size'])) {
            if ($extendedSettings['margin'] >= $extendedSettings['size']) {
                throw new SettingsLogicException('Margin cannot be greater or equal to size.');
            }
        }

    }
}
