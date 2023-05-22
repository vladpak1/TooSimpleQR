<?php

declare(strict_types=1);

namespace vladpak1\TooSimpleQR\Settings\Handler\TranslateHandler\Handlers;

use vladpak1\TooSimpleQR\OutputConstants;
use vladpak1\TooSimpleQR\Settings\Handler\TranslateHandler\AbstractTranslateHandler;
use vladpak1\TooSimpleQR\Settings\QRSettings;

final class TransparentBackgroundHandler extends AbstractTranslateHandler
{
    public string $settingName = 'transparentBackground';

    public string $settingType = 'boolean';

    public function translate(string $settingName, mixed $settingValue): array
    {
        $this->assertSettingType($settingValue);

        if ($settingValue) {
            $this->imagickRequired();
        }

        return [
            [
                'settingName'  => 'imageTransparent',
                'settingValue' => $settingValue,
            ],
        ];
    }

    public function validateTranslated(array $settings): void
    {
        if (!isset($settings['imageTransparent'])) {
            return;
        }

        if (true === $settings['imageTransparent']) {
            if (!in_array($settings['outputType'], ['png', 'imagick'], true)) {
                $this->validationException(
                    'Current output interface (%s) does not support transparent background.',
                    $settings['outputType']
                );
            }
        }
    }

    public function validateSettings(QRSettings $settings): void
    {
        if ($settings->getSetting($this->settingName) === true && $settings->getSetting('output') === OutputConstants::OUTPUT_JPG) {
            $this->validationException(
                'Current output interface (%s) does not support transparent background.',
                $settings->getSetting('output')
            );
        }
    }
}
