<?php

namespace vladpak1\TooSimpleQR;

final class OutputConstants
{
    public const OUTPUT_PNG = 'png';

    public const OUTPUT_JPG = 'jpg';

    /**
     * This correction level allows restoring up to 7% of the data.
     */
    public const CORRECTION_LEVEL_L = 0b01;
    /**
     * This correction level allows restoring up to 15% of the data.
     */
    public const CORRECTION_LEVEL_M = 0b00;
    /**
     * This correction level allows restoring up to 25% of the data.
     */
    public const CORRECTION_LEVEL_Q = 0b11;
    /**
     * This correction level allows restoring up to 30% of the data.
     */
    public const CORRECTION_LEVEL_H = 0b10;

    public const SHAPE_CIRCLE = 'circle';

    public const SHAPE_SQUIRCLE = 'squircle';

    public const SHAPE_SQUIRCLE_WITH_SQUARE = 'squircle-with-square';

    public const DRIVER_GD = 'gd';

    public const DRIVER_IMAGICK = 'imagick';

}
