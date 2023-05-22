<?php

declare(strict_types=1);

namespace vladpak1\TooSimpleQR\Settings\Handler\TranslateHandler\Handlers;

use vladpak1\TooSimpleQR\Settings\Handler\TranslateHandler\AbstractTranslateHandler;

final class CircleRadiusHandler extends AbstractTranslateHandler
{
    public string $settingName = 'circleRadius';

    public string $settingType = 'double';

    public function translate(string $settingName, mixed $settingValue): array
    {
        $this->assertSettingType($settingValue);

        return [
            [
                'settingName'  => 'circleRadius',
                'settingValue' => $settingValue,
            ],
        ];
    }
}
