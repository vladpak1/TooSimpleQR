<?php

declare(strict_types=1);

namespace vladpak1\TooSimpleQR\Settings\Handler\TranslateHandler\Handlers;

use vladpak1\TooSimpleQR\Image\InterventionWrapperStatic;
use vladpak1\TooSimpleQR\Settings\Handler\TranslateHandler\AbstractTranslateHandler;

final class OutputHandler extends AbstractTranslateHandler
{
    public string $settingName = 'output';

    public string $settingType = 'string';

    public function translate(string $settingName, mixed $settingValue): array
    {
        $this->assertSettingType($settingValue);

        if (InterventionWrapperStatic::getDriver() === 'imagick') {
            $outputType = 'imagick';
        } else {
            $outputType = $settingValue;
        }

        return [
            [
                'settingName'  => 'imagickFormat',
                'settingValue' => $settingValue,
            ],
            [
                'settingName'  => 'outputType',
                'settingValue' => $outputType,
            ],
        ];
    }

    public function validateTranslated(array $settings): void
    {
        if (!isset($settings['outputType'])) {
            $this->validationException('You must set output setting. This is the only required setting.');
        }
    }
}
