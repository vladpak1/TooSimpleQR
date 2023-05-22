<?php

namespace vladpak1\TooSimpleQR\Tests\Unit\Image;

use vladpak1\TooSimpleQR\Image\ImageHelper;

class ImageHelperTest extends \vladpak1\TooSimpleQR\Tests\Unit\TestCase
{
    public function testHexToRgbArray()
    {
        $this->assertSame([0, 0, 0], ImageHelper::hexToRgbArray('#000000'));
        $this->assertSame([255, 255, 255], ImageHelper::hexToRgbArray('#ffffff'));
        $this->assertSame([255, 0, 0], ImageHelper::hexToRgbArray('#ff0000'));
        $this->assertSame([0, 255, 0], ImageHelper::hexToRgbArray('#00ff00'));
        $this->assertSame([0, 0, 255], ImageHelper::hexToRgbArray('#0000ff'));
        $this->assertSame([255, 255, 0], ImageHelper::hexToRgbArray('#ffff00'));
        $this->assertSame([0, 255, 255], ImageHelper::hexToRgbArray('#00ffff'));
        $this->assertSame([161, 31, 40], ImageHelper::hexToRgbArray('#a11f28'));
    }

    public function testRgbArrayToHex()
    {
        $this->assertSame('#000000', ImageHelper::rgbArrayToHex([0, 0, 0]));
        $this->assertSame('#ffffff', ImageHelper::rgbArrayToHex([255, 255, 255]));
        $this->assertSame('#ff0000', ImageHelper::rgbArrayToHex([255, 0, 0]));
        $this->assertSame('#00ff00', ImageHelper::rgbArrayToHex([0, 255, 0]));
        $this->assertSame('#0000ff', ImageHelper::rgbArrayToHex([0, 0, 255]));
        $this->assertSame('#ffff00', ImageHelper::rgbArrayToHex([255, 255, 0]));
        $this->assertSame('#00ffff', ImageHelper::rgbArrayToHex([0, 255, 255]));
        $this->assertSame('#a11f28', ImageHelper::rgbArrayToHex([161, 31, 40]));
    }
}
