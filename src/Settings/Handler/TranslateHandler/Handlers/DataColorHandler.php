<?php

declare(strict_types=1);

namespace vladpak1\TooSimpleQR\Settings\Handler\TranslateHandler\Handlers;

use chillerlan\QRCode\Data\QRMatrix;
use vladpak1\TooSimpleQR\Image\ImageHelper;
use vladpak1\TooSimpleQR\Settings\Handler\TranslateHandler\AbstractTranslateHandler;

final class DataColorHandler extends AbstractTranslateHandler
{
    public string $settingName = 'dataColorHandler';

    public string $settingType = 'string';

    public function translate(string $settingName, mixed $settingValue): array
    {
        $this->assertSettingType($settingValue);

        $dataColor = ImageHelper::hexToRgbArray($settingValue);

        if ($this->isImagick()) {
            $dataColor = sprintf('rgb(%s, %s, %s)', $dataColor[0], $dataColor[1], $dataColor[2]);
        }

        return [
            [
                'settingName'  => 'moduleValues',
                'settingValue' => [
                    QRMatrix::M_DATA_DARK      => $dataColor,
                    QRMatrix::M_ALIGNMENT_DARK => $dataColor,
                    QRMatrix::M_FORMAT_DARK    => $dataColor,
                    QRMatrix::M_TIMING_DARK    => $dataColor,
                    QRMatrix::M_DARKMODULE     => $dataColor,
                    QRMatrix::M_VERSION_DARK   => $dataColor,
                ],
            ],
        ];
    }
}
