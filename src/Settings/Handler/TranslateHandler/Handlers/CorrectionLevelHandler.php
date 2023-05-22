<?php

declare(strict_types=1);

namespace vladpak1\TooSimpleQR\Settings\Handler\TranslateHandler\Handlers;

use vladpak1\TooSimpleQR\Settings\Handler\TranslateHandler\AbstractTranslateHandler;

final class CorrectionLevelHandler extends AbstractTranslateHandler
{
    public string $settingName = 'correctionLevel';

    public string $settingType = 'string';

    public function translate(string $settingName, mixed $settingValue): array
    {
        $this->assertSettingType($settingValue);

        return [
            [
                'settingName'  => 'eccLevel',
                'settingValue' => $settingValue,
            ],
        ];
    }
}
