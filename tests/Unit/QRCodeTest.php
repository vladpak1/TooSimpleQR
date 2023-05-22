<?php

namespace vladpak1\TooSimpleQR\Tests\Unit;

use vladpak1\TooSimpleQR\QRCode;

class QRCodeTest extends TestCase
{
    public function testQRCodeImplementsQRCodeInterface()
    {
        $qr = new QRCode();

        $this->assertInstanceOf(
            'vladpak1\TooSimpleQR\QRCodeInterface',
            $qr
        );
    }
}
