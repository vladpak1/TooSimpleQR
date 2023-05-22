<?php

namespace vladpak1\TooSimpleQR\Tests\Unit;

use vladpak1\TooSimpleQR\QRFactory;

class QRFactoryTest extends TestCase
{
    public function testQRCode()
    {
        $qr = QRFactory::QRCode();

        $this->assertInstanceOf(
            'vladpak1\TooSimpleQR\QRCode',
            $qr
        );
    }

    public function testQRSettings()
    {
        $qr = QRFactory::QRSettings();

        $this->assertInstanceOf(
            'vladpak1\TooSimpleQR\Settings\QRSettings',
            $qr
        );
    }
}
