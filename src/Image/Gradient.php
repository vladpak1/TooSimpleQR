<?php

namespace vladpak1\TooSimpleQR\Image;

use Imagick;
use ImagickException;
use vladpak1\TooSimpleQR\Exception\RenderException;

/**
 * A class that represents a gradient.
 *
 * Imagick is required to use this class!
 */
class Gradient
{
    public const DIRECTION_TOP_TO_BOTTOM = 'topToBottom';

    public const DIRECTION_BOTTOM_TO_TOP = 'bottomToTop';

    public const DIRECTION_LEFT_TO_RIGHT = 'leftToRight';

    public const DIRECTION_RIGHT_TO_LEFT = 'rightToLeft';

    public const GRADIENT_TYPE_LINEAR = '';

    public const GRADIENT_TYPE_RADIAL = 'radial-';

    /**
     * A HEX color defining the start color of the gradient.
     */
    protected string $startColor;

    /**
     * A HEX color defining the end color of the gradient.
     */
    protected string $endColor;

    /**
     * A string defining the direction of the gradient.
     */
    protected string $direction;

    protected string $gradientType = self::GRADIENT_TYPE_LINEAR;

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

    public function setDirection(string $direction): self
    {
        $this->direction = $direction;

        return $this;
    }

    public function getDirection(): string
    {
        return $this->direction;
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

    public function setGradientType(string $gradientType): self
    {
        $this->gradientType = $gradientType;

        return $this;
    }

    public function generate(): Image
    {

        return new Image($this->renderGradient(), 'png');
    }

    protected function renderGradient(): \Intervention\Image\Image
    {
        $gradient = $this->createGradientUsingImagick();
        $this->rotateGradient($gradient);

        return $gradient;
    }

    protected function rotateGradient(\Intervention\Image\Image $image)
    {
        switch ($this->direction) {
            case self::DIRECTION_TOP_TO_BOTTOM:
                break;
            case self::DIRECTION_BOTTOM_TO_TOP:
                $image->rotate(180);

                break;
            case self::DIRECTION_LEFT_TO_RIGHT:
                $image->rotate(90);

                break;
            case self::DIRECTION_RIGHT_TO_LEFT:
                $image->rotate(-90);

                break;
            default:
                throw new RenderException('Invalid gradient direction!');
        }
    }

    /**
     * @throws ImagickException
     */
    protected function createGradientUsingImagick(): \Intervention\Image\Image
    {
        if (!extension_loaded('imagick')) {
            throw new RenderException('Imagick extension is required to generate gradient!');
        }

        $gradient = new Imagick();

        /** @noinspection PhpUnnecessaryCurlyVarSyntaxInspection */
        /** @noinspection PhpUnnecessaryCurlyVarSyntaxInspection */
        $gradient->newPseudoImage($this->canvasWidth, $this->canvasHeight, "{$this->gradientType}gradient:{$this->startColor}-{$this->endColor}");
        $gradient->setImageFormat('png');

        return InterventionWrapperStatic::make($gradient);
    }
}
