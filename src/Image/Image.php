<?php

namespace vladpak1\TooSimpleQR\Image;

use vladpak1\TooSimpleQR\Exception\RuntimeException;

/**
 * Class Image.
 *
 * Represents an image.
 * Intervention Image is used as a base.
 */
class Image
{
    protected \Intervention\Image\Image $image;

    /**
     * @var string The format of the image being encoded.
     */
    protected string $format;

    public function __construct(\Intervention\Image\Image $image, string $format)
    {
        $this->image  = $image;
        $this->format = $format;

        $this->image->encode($format);
    }

    /**
     * Save the image to the given path.
     *
     * @throws RuntimeException
     */
    public function save(string $path): void
    {

        $formatFromPath = pathinfo($path, PATHINFO_EXTENSION);

        if ($formatFromPath !== $this->format) {
            throw new RuntimeException('The format from the path does not match the format of the image.');
        }

        try {
            $this->image->save($path);
        } catch (\Throwable $e) {
            throw new RuntimeException('Failed to save the image.', 0, $e);
        }
    }

    /**
     * An alias for toHtml() that echoes the image.
     */
    public function echo(?int $size = null, $alt = ''): void
    {
        echo $this->toHtml($size, $alt);
    }

    /**
     * Convert the image to an HTML image tag.
     *
     * @param  int|null $size Optional. The size of the image. Defaults to the width of the image. (Assumes a square image.)
     * @param  string   $alt  Optional. The alt text of the image. Defaults to an empty string.
     * @return string   The HTML image tag encoded as a string (base64).
     */
    public function toHtml(?int $size = null, $alt = ''): string
    {

        if (null === $size) {
            $size = $this->image->width();
        }

        try {
            $this->image->mime = 'image/' . $this->format;
            $encoded           = $this->image->encode('data-url', 100);
        } catch (\Throwable $e) {
            throw new RuntimeException('Failed to encode the image.', 0, $e);
        }

        return sprintf(
            '<img height="%1$d" width="%1$d" src="%2$s" alt="%3$s">',
            $size,
            $encoded,
            $alt,
        );
    }

    /**
     * @return \Intervention\Image\Image The Intervention Image instance.
     */
    public function get(): \Intervention\Image\Image
    {
        return $this->image;
    }
}
