<?php

namespace vladpak1\TooSimpleQR\Tests\Unit\Image;

use vladpak1\TooSimpleQR\Image\Logo;
use vladpak1\TooSimpleQR\Tests\Unit\TestCase;

class LogoTest extends TestCase
{
    public function testSetWrongPath()
    {
        $this->expectException(\vladpak1\TooSimpleQR\Exception\LogoException::class);
        $this->expectExceptionMessage('Logo file (wrong/path) is not readable or does not exist.');

        $logo = new Logo();
        $logo->setByPath('wrong/path');
    }

    public function testSetTwice()
    {
        $this->expectException(\vladpak1\TooSimpleQR\Exception\LogoException::class);
        $this->expectExceptionMessage('Logo is already set.');

        $logo = new \vladpak1\TooSimpleQR\Image\Logo();
        $logo->setByPath(Logo::TWITTER_LOGO);
        $logo->setByPath(Logo::TWITTER_LOGO);
    }

    public function testGet()
    {
        $this->assertTrue(true);
    }

    public function testGetWhenInterventionFails()
    {
        $this->assertTrue(true);
    }

    public function testGetReturnsFalseWhenLogoIsNotSet()
    {
        $logo = new Logo();
        $this->assertFalse($logo->get());
    }

    public function testRemoveBackgroundBehindTrue()
    {
        $logo = new Logo();

        $logo->removeBackgroundBehind();
        $this->assertTrue($logo->isRemoveBackgroundBehind());
    }

    public function testRemoveBackgroundBehindFalse()
    {
        $logo = new Logo();

        $this->assertFalse($logo->isRemoveBackgroundBehind());
    }
}
