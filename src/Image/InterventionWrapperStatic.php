<?php

namespace vladpak1\TooSimpleQR\Image;

use Intervention\Image\Image;
use Intervention\Image\ImageManager;

class InterventionWrapperStatic
{
    protected static string $driver = '';

    /**
     * Initiates an Image instance from different input types.
     */
    public static function make(mixed $data): Image
    {
        return self::getManager()->make($data);
    }

    /**
     * Creates an empty image canvas.
     */
    public static function canvas(int $width, int $height, mixed $background = null): Image
    {
        return self::getManager()->canvas($width, $height, $background);
    }

    public static function getManager(): ImageManager
    {
        $manager = new ImageManager();
        $manager->configure([
            'driver' => self::$driver,
        ]);

        return $manager;
    }

    public static function getDriver(): string
    {
        return self::$driver;
    }

    public static function setDriver(string $driver): void
    {
        self::$driver = $driver;
    }
}
