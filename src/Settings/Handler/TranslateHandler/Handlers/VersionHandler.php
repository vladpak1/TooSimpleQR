<?php

declare(strict_types=1);

namespace vladpak1\TooSimpleQR\Settings\Handler\TranslateHandler\Handlers;

use vladpak1\TooSimpleQR\Settings\Handler\TranslateHandler\AbstractTranslateHandler;

final class VersionHandler extends AbstractTranslateHandler
{
    public string $settingName = 'version';

    public string $settingType = 'integer';

    public function translate(string $settingName, mixed $settingValue): array
    {
        $this->assertSettingType($settingValue);

        return [
            [
                'settingName'  => 'version',
                'settingValue' => $settingValue,
            ],
        ];
    }
}
