<?php

declare(strict_types=1);

namespace vladpak1\TooSimpleQR\Settings\Handler\TranslateHandler\Handlers;

use vladpak1\TooSimpleQR\Settings\Handler\TranslateHandler\AbstractTranslateHandler;

final class CircularModulesHandler extends AbstractTranslateHandler
{
    public string $settingName = 'circularModules';

    public string $settingType = 'boolean';

    public function translate(string $settingName, mixed $settingValue): array
    {
        $this->assertSettingType($settingValue);

        return [
            [
                'settingName'  => 'drawCircularModules',
                'settingValue' => $settingValue,
            ],
        ];
    }
}
