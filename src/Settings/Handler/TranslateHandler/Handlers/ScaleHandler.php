<?php

declare(strict_types=1);

namespace vladpak1\TooSimpleQR\Settings\Handler\TranslateHandler\Handlers;

use vladpak1\TooSimpleQR\Settings\Handler\TranslateHandler\AbstractTranslateHandler;

final class ScaleHandler extends AbstractTranslateHandler
{
    public string $settingName = 'scale';

    public string $settingType = 'integer';

    public function translate(string $settingName, mixed $settingValue): array
    {
        $this->assertSettingType($settingValue);

        return [
            [
                'settingName'  => 'scale',
                'settingValue' => $settingValue,
            ],
        ];
    }

    public function validateTranslated(array $settings): void
    {
        if (!isset($settings['scale'])) {
            return;
        }

        if ($settings['scale'] <= 0) {
            $this->validationException('Scale must be greater than 0.');
        }
    }
}
