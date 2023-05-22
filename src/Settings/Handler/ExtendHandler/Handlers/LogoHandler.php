<?php

namespace vladpak1\TooSimpleQR\Settings\Handler\ExtendHandler\Handlers;

use Intervention\Image\Image;
use vladpak1\TooSimpleQR\Exception\SettingsLogicException;
use vladpak1\TooSimpleQR\Image\ImageHelper;
use vladpak1\TooSimpleQR\Image\InterventionWrapperStatic;
use vladpak1\TooSimpleQR\Image\Logo;
use vladpak1\TooSimpleQR\OutputConstants;
use vladpak1\TooSimpleQR\Settings\Handler\ExtendHandler\AbstractExtendHandler;
use vladpak1\TooSimpleQR\Settings\QRSettings;

final class LogoHandler extends AbstractExtendHandler
{
    public string $settingName = 'logo';

    public string $settingType = 'object';

    public function handle(Image $image, mixed $settingValue): Image
    {
        $this->assertSettingType($settingValue);

        if (!($settingValue instanceof Logo)) {
            throw new SettingsLogicException('Logo must be instance of Logo class.');
        }

        if ($this->avoidExpensiveOperations) {
            $logoWithoutTransparentBackground = $settingValue->get();
        } else {
            $logoWithoutTransparentBackground = $settingValue->get()->trim('transparent');
        }

        $imageHeight = ceil($image->height() / 4);

        $logoWithoutTransparentBackground->resize(
            $imageHeight,
            $imageHeight,
            function ($constraint) {
                $constraint->aspectRatio();
            }
        );

        /**
         * If a hide background is set to true, we will insert a canvas with background color and logo on top of it.
         * @todo
         */
        if ($settingValue->isRemoveBackgroundBehind()) {
            /**
             * The first finder is always in the top-left corner,
             * so we can pick the color of the top-left pixel and use it as a finderColor.
             */
            $finderColor = $image->pickColor(0, 0);

            /**
             * Let's start iterating from the top-left corner (where the second finder is located)
             * down to the bottom-left corner until we find a pixel that is not a finder.
             *
             * This pixel will be the background color.
             */

            // Default backgroundColor is white if we can't find any other color.
            $backgroundColor = [255, 255, 255];

            for ($x = $image->getWidth() - 1; $x > 0; $x--) {
                $color = $image->pickColor($x, 0);

                if ($color !== $finderColor) {
                    $backgroundColor = $color;

                    break;
                }
            }

            $backgroundColorHex = ImageHelper::rgbArrayToHex($backgroundColor);

            $canvas = InterventionWrapperStatic::canvas($logoWithoutTransparentBackground->width(), $logoWithoutTransparentBackground->height(), '#ffffff');

            $canvas->insert(
                $logoWithoutTransparentBackground,
                'center'
            );

            $image->insert(
                $canvas,
                'center'
            );

            return $image;

        }

        $image->insert(
            $logoWithoutTransparentBackground,
            'center'
        );

        return $image;
    }

    public function validateSettings(QRSettings $settings): void
    {
        $translatableSettings = $settings->getTranslatableSettings();
        $extendedSettings     = $settings->getExtendedSettings();

        if (!isset($extendedSettings[$this->settingName])) {
            return;
        }

        if (!isset($translatableSettings['correctionLevel'])
        || OutputConstants::CORRECTION_LEVEL_H !=
        $translatableSettings['correctionLevel']) {
            throw new SettingsLogicException('Correction level must be set to H to include logo.');
        }
    }
}
