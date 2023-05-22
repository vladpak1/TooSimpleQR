<?php

namespace vladpak1\TooSimpleQR\Settings\Handler\ExtendHandler\Handlers;

use Imagick;
use Intervention\Image\Image;
use vladpak1\TooSimpleQR\Exception\SettingsLogicException;
use vladpak1\TooSimpleQR\Image\ImageHelper;
use vladpak1\TooSimpleQR\Image\InterventionWrapperStatic;
use vladpak1\TooSimpleQR\Settings\Handler\ExtendHandler\AbstractExtendHandler;
use vladpak1\TooSimpleQR\Settings\QRSettings;

final class CustomFinderShapeHandler extends AbstractExtendHandler
{
    public string $settingName = 'customFinderShape';

    public string $settingType = 'string';

    public function handle(Image $image, mixed $settingValue): Image
    {
        $this->assertSettingType($settingValue);

        if (!$this->isImagick()) {
            /**
             * There is a bug when GD is used. I'll fix it later.
             */
            throw new SettingsLogicException('Custom finder shape is not supported for GD driver at the moment.');
        }

        $size            = $this->findFinderSize($image);
        $backgroundColor = $this->getBackgroundColor();

        $corners = [
            [0, 0],
            [$image->width() - $size, 0],
            [0, $image->height() - $size],
        ];

        $tempBackground = [89, 255, 0, 1];

        /**
         * We need the background color because I haven't found a way to remove regions
         * from the image. So we will draw the finders on the background color and then
         * remove the background color from the image.
         *
         * This is a very bad implementation. Need to be refactored.
         */
        if ($backgroundColor == [0, 0, 0, 0]) {
            // Sets "chromakey" for our shape.
            $backgroundColor = $tempBackground;
        }

        foreach ($corners as $corner) {
            $image->rectangle($corner[0], $corner[1], $corner[0] + $size, $corner[1] + $size, function ($draw) use ($backgroundColor) {
                $draw->background($backgroundColor);
            });
        }

        /**
         * Draw new finders in the corners.
         */
        $finderColor = $this->settingsContext->getSetting('finderColor');

        $finderColor = (null === $finderColor) ? '#000000' : $finderColor;

        $newFinderCanvas = InterventionWrapperStatic::canvas($size, $size, [0, 0, 0, 0]);

        switch ($settingValue) {
            case 'circle':

                //Second greater circle
                $outerCircleRadius = $size;
                $innerCircleRadius = $size / 1.3;

                // Draw the outer circle
                $newFinderCanvas->circle(
                    $outerCircleRadius,
                    $size / 2,
                    $size / 2,
                    function ($draw) use ($finderColor) {
                        $draw->background($finderColor);
                    }
                );

                // Draw the inner circle
                $newFinderCanvas->circle(
                    $innerCircleRadius,
                    $size / 2,
                    $size / 2,
                    function ($draw) use ($backgroundColor) {
                        $draw->background($backgroundColor);
                    }
                );

                // Draw dot in the center
                $newFinderCanvas->circle(
                    $size / 2.5,
                    $size / 2,
                    $size / 2,
                    function ($draw) use ($finderColor) {
                        $draw->background($finderColor);
                    }
                );

                break;
            case 'squircle':

                $newFinderCanvas->rectangle(
                    0,
                    0,
                    $size,
                    $size,
                    function ($draw) use ($finderColor) {
                        $draw->background($finderColor);
                    }
                );

                // Draw the inner rectangle
                $innerSquareScaleFactor = 7;
                $newFinderCanvas->rectangle(
                    $size / $innerSquareScaleFactor,
                    $size / $innerSquareScaleFactor,
                    $size - $size / $innerSquareScaleFactor,
                    $size - $size / $innerSquareScaleFactor,
                    function ($draw) use ($backgroundColor) {
                        $draw->background($backgroundColor);
                    }
                );

                // Draw dot in the center
                $newFinderCanvas->circle(
                    $size / 2.5,
                    $size / 2,
                    $size / 2,
                    function ($draw) use ($finderColor) {
                        $draw->background($finderColor);
                    }
                );

                // Round the corners

                if (!$this->isImagick()) {
                    ImageHelper::gdRoundCorners($newFinderCanvas->getCore(), 15);

                    break;
                }

                /**
                 * @var Imagick $imagickCore
                 */
                $imagickCore = $newFinderCanvas->getCore();

                $imagickCore->roundCornersImage(15, 10, 0, 0, 0);

                break;
            case 'squircle-with-square':

                $newFinderCanvas->rectangle(
                    0,
                    0,
                    $size,
                    $size,
                    function ($draw) use ($finderColor) {
                        $draw->background($finderColor);
                    }
                );

                // Draw the inner rectangle
                $innerSquareScaleFactor = 7;
                $newFinderCanvas->rectangle(
                    $size / $innerSquareScaleFactor,
                    $size / $innerSquareScaleFactor,
                    $size - $size / $innerSquareScaleFactor,
                    $size - $size / $innerSquareScaleFactor,
                    function ($draw) use ($backgroundColor) {
                        $draw->background($backgroundColor);
                    }
                );

                // Draw square in the center
                $newFinderCanvas->rectangle(
                    $size / 4,
                    $size / 4,
                    $size - $size / 4,
                    $size - $size / 4,
                    function ($draw) use ($finderColor) {
                        $draw->background($finderColor);
                    }
                );

                // Round the corners

                if (!$this->isImagick()) {
                    ImageHelper::gdRoundCorners($newFinderCanvas->getCore(), 15);

                    break;
                }

                /**
                 * @var Imagick $imagickCore
                 */
                $imagickCore = $newFinderCanvas->getCore();
                $imagickCore->roundCornersImage(15, 10, 0, 0, 0);

                break;
            default:
                throw new SettingsLogicException(sprintf(
                    'Unknown custom finder shape "%s".',
                    $settingValue
                ));
        }

        // Insert the finders into the corners
        foreach ($corners as $corner) {
            $image->insert($newFinderCanvas, 'top-left', $corner[0], $corner[1]);
        }

        if ($backgroundColor == $tempBackground) {
            /**
             * I hate this solution, but it works...
             *
             * @var Imagick $imagick
             */
            $imagick = $image->getCore();
            $target  = 'rgba(89,255,0,1)';

            $imagick->transparentPaintImage($target, 0, 29500, false);

        }

        return $image;
    }

    public function validateSettings(QRSettings $settings): void
    {
        $translatableSettings = $settings->getTranslatableSettings();
        $extendedSettings     = $settings->getExtendedSettings();
    }

    private function findFinderSize(Image $image): int
    {
        $width = $image->width();
        //        $height = $image->height();

        /**
         * Here we're assuming that the finder always a square and
         * located in the three corners of the image. (Except the bottom right corner).
         *
         * Also, the finder always has an equal width and height.
         * And the background at this point is white or transparent.
         */

        $backgroundColor = $this->getBackgroundColor();

        /**
         * Start iterating from left top corner to right until we find the background corner.
         * This will help us determine the finder size.
         */
        $finderSize = 0;

        for ($x = 0; $x < $width; $x++) {
            $pixelColor = $image->pickColor($x, 0);

            if ($pixelColor == $backgroundColor || $pixelColor == [255, 255, 255, 0]) {
                $finderSize = $x;

                break;
            }
        }

        if ($finderSize === 0) {
            throw new SettingsLogicException('Unable to determine the finder size. Try to change the background color for using the custom finder shape.');
        }

        return $finderSize;
    }
}
