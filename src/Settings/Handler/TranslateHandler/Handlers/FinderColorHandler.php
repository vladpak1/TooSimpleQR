<?php

declare(strict_types=1);

namespace vladpak1\TooSimpleQR\Settings\Handler\TranslateHandler\Handlers;

use chillerlan\QRCode\Data\QRMatrix;
use vladpak1\TooSimpleQR\Image\ImageHelper;
use vladpak1\TooSimpleQR\Settings\Handler\TranslateHandler\AbstractTranslateHandler;

final class FinderColorHandler extends AbstractTranslateHandler
{
    public string $settingName = 'finderColor';

    public string $settingType = 'string';

    public function translate(string $settingName, mixed $settingValue): array
    {
        $this->assertSettingType($settingValue);

        $colorAsRgb = ImageHelper::hexToRgbArray($settingValue);

        if ($this->isImagick()) {
            $colorAsRgb = sprintf('rgb(%s, %s, %s)', $colorAsRgb[0], $colorAsRgb[1], $colorAsRgb[2]);
        }

        $dataColor = ImageHelper::hexToRgbArray('#1a33e0');

        if ($this->isImagick()) {
            $dataColor = sprintf('rgb(%s, %s, %s)', $dataColor[0], $dataColor[1], $dataColor[2]);
        }

        return [
            [
                'settingName'  => 'moduleValues',
                'settingValue' => [
                    QRMatrix::M_FINDER_DOT  => $colorAsRgb,
                    QRMatrix::M_FINDER_DARK => $colorAsRgb,
                    //                    QRMatrix::M_DATA_DARK      => $dataColor,
                    //                    QRMatrix::M_ALIGNMENT_DARK => $dataColor,
                    //                    QRMatrix::M_FORMAT_DARK    => $dataColor,
                    //                    QRMatrix::M_TIMING_DARK    => $dataColor,
                    //                    QRMatrix::M_DARKMODULE     => $dataColor,
                ],
            ],
        ];
    }
}
