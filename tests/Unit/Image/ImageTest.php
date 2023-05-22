<?php

namespace vladpak1\TooSimpleQR\Tests\Unit\Image;

use Intervention\Image\Exception\ImageException;
use vladpak1\TooSimpleQR\Tests\Unit\TestCase;

class ImageTest extends TestCase
{
    protected $interventionImageMock;

    protected $imageInstance;

    public function setUp(): void
    {
        $this->interventionImageMock = $this->createMock(\Intervention\Image\Image::class);
        $this->image                 = new \vladpak1\TooSimpleQR\Image\Image($this->interventionImageMock, 'png');
    }

    public function testToHtml()
    {
        $html = $this->image->toHtml();
        $this->assertStringContainsString('<img height', $html);
    }

    public function testEcho()
    {
        ob_start();

        $this->image->echo();
        $html = ob_get_clean();

        $this->assertStringContainsString('<img height', $html);
    }

    public function testGet()
    {
        $this->assertSame($this->interventionImageMock, $this->image->get());
    }

    public function testSaveSuccessful()
    {
        $path = 'path/to/image.png';

        $this->interventionImageMock->expects($this->once())
            ->method('save')
            ->with($path);

        $this->image->save($path);
    }

    public function testSaveThrowsExceptionWhenFormatDoesNotMatch()
    {
        $path = 'path/to/image.jpg';

        $this->expectException(\vladpak1\TooSimpleQR\Exception\RuntimeException::class);
        $this->expectExceptionMessage('The format from the path does not match the format of the image.');

        $this->image->save($path);
    }

    public function testSaveThrowsExceptionWhenInterventionFails()
    {
        $path = 'path/to/image.png';

        $this->interventionImageMock->expects($this->once())
            ->method('save')
            ->with($path)
            ->willThrowException(new \Exception('Intervention failed.'));

        $this->expectException(\vladpak1\TooSimpleQR\Exception\RuntimeException::class);
        $this->expectExceptionMessage('Failed to save the image.');

        $this->image->save($path);
    }

    public function testToHtmlEncodeFails()
    {
        $this->interventionImageMock->expects($this->once())
            ->method('encode')
            ->willThrowException(new ImageException('Intervention failed.'));

        $this->expectException(\vladpak1\TooSimpleQR\Exception\RuntimeException::class);
        $this->expectExceptionMessage('Failed to encode the image.');

        $this->image->toHtml();
    }
}
