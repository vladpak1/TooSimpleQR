<?php

namespace vladpak1\TooSimpleQR;

use vladpak1\TooSimpleQR\Settings\QRSettings;

/**
 * Class QRFactory.
 *
 */
class QRFactory
{
    /**
     * @return QRCode Returns a new QRCode instance.
     */
    public static function QRCode(): QRCode
    {
        return new QRCode();
    }

    /**
     * @return QRSettings Returns a new QRSettings instance.
     */
    public static function QRSettings(): QRSettings
    {
        return new QRSettings();
    }
}
