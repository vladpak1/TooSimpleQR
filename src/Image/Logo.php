<?php

namespace vladpak1\TooSimpleQR\Image;

use Intervention\Image\Exception\ImageException;
use vladpak1\TooSimpleQR\Exception\LogoException;

/**
 * Class Logo.
 *
 * Represents a logo.
 */
class Logo
{
    /**
     * @var string The path to the Twitter logo.
     */
    public const TWITTER_LOGO = __DIR__ . '/../../assets/logo/twitter.png';

    /**
     * @var string The path to the Facebook logo.
     */
    public const INSTAGRAM_LOGO = __DIR__ . '/../../assets/logo/instagram.png';

    /**
     * @var string The path to the Facebook logo.
     */
    public const YOUTUBE_LOGO = __DIR__ . '/../../assets/logo/youtube.png';

    protected bool $isSet = false;

    protected string $path;

    protected string $base64;

    protected bool $removeBackgroundBehind = false;

    /**
     * Sets the logo by path.
     * Recommended to use png images with transparent background.
     *
     * @param  string        $path The path to the logo.
     * @throws LogoException If the logo is already set or the logo file is not readable or does not exist.
     * @return $this
     */
    public function setByPath(string $path): self
    {
        if ($this->isSet) {
            throw new LogoException('Logo is already set.');
        }

        $this->path  = realpath($path);
        $this->isSet = true;

        if (!file_exists($path) || !is_readable($path) || !is_file($path)) {
            throw new LogoException(sprintf('Logo file (%s) is not readable or does not exist.', $path));
        }

        return $this;
    }

    /**
     * Whether to remove the background behind the logo or not (matrix will not be visible through the logo).
     */
    public function removeBackgroundBehind(): self
    {
        $this->removeBackgroundBehind = true;

        return $this;
    }

    /**
     * Get the logo intervention representation.
     *
     * @throws LogoException                   If cannot create Intervention Image.
     * @return \Intervention\Image\Image|false The Intervention Image instance or false if the logo is not set.
     */
    public function get(): \Intervention\Image\Image|false
    {
        if (!$this->isSet) {
            return false;
        }

        if (isset($this->path)) {
            try {
                return InterventionWrapperStatic::make($this->path);
            } catch (ImageException $e) {
                throw new LogoException('Failed to create Intervention Image during logo creation.');
            }
        }

        return false;
    }

    public function isRemoveBackgroundBehind(): bool
    {
        return $this->removeBackgroundBehind;
    }
}
