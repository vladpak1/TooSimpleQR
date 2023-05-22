<?php

declare(strict_types=1);

namespace vladpak1\TooSimpleQR\Settings\Handler\TranslateHandler\Handlers;

use vladpak1\TooSimpleQR\Settings\Handler\TranslateHandler\AbstractTranslateHandler;

final class NoNativeMarginHandler extends AbstractTranslateHandler
{
    public string $settingName = 'noNativeMargin';

    public string $settingType = 'boolean';

    public function translate(string $settingName, mixed $settingValue): array
    {
        $this->assertSettingType($settingValue);

        return [
            [
                'settingName'  => 'addQuietzone',
                'settingValue' => !$settingValue,
                // We hide the native padding
            ],
        ];
    }
}
