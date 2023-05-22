<?php

namespace vladpak1\TooSimpleQR\Image;

use Imagick;
use vladpak1\TooSimpleQR\Entity\Base64;
use vladpak1\TooSimpleQR\Exception\RenderException;

/**
 * A class that represents a plasma.
 *
 * Imagick is required to use this class!
 */
class Plasma
{
    /**
     * A HEX color defining the first color of the plasma.
     */
    protected string $startColor;

    /**
     * A HEX color defining the second color of the plasma.
     */
    protected string $endColor;

    protected int $plasmaBlur = 20;

    protected int $canvasWidth;

    protected int $canvasHeight;

    public function setStartColor(string $startColor): self
    {
        $this->startColor = $startColor;

        return $this;
    }

    public function setEndColor(string $endColor): self
    {
        $this->endColor = $endColor;

        return $this;
    }

    public function setPlasmaBlur(int $plasmaBlur): self
    {
        $this->plasmaBlur = $plasmaBlur;

        return $this;
    }

    public function setCanvasSize(int $width, int $height): self
    {
        $this->canvasWidth  = $width;
        $this->canvasHeight = $height;

        return $this;
    }

    public function getCanvasSize(): array
    {
        return [
            'width'  => $this->canvasWidth,
            'height' => $this->canvasHeight,
        ];
    }

    public function generate(): Image
    {
        $plasma = $this->render();
        $base64 = new Base64($plasma->encode('data-url'));

        return new Image($base64);
    }

    protected function render(): \Intervention\Image\Image
    {
        $plasma = $this->createPlasmaUsingImagick();
        $plasma->blur($this->plasmaBlur);

        return $plasma;
    }

    /** @noinspection PhpUnnecessaryCurlyVarSyntaxInspection
     * @noinspection PhpUnnecessaryCurlyVarSyntaxInspection
     */
    protected function createPlasmaUsingImagick(): \Intervention\Image\Image
    {
        if (!extension_loaded('imagick')) {
            throw new RenderException('Imagick extension is required to generate plasma!');
        }

        $plasma = new Imagick();

        $plasma->newPseudoImage($this->canvasWidth, $this->canvasHeight, "plasma:{$this->startColor}-{$this->endColor}");
        $plasma->setImageFormat('png');

        return InterventionWrapperStatic::make($plasma);
    }
}
