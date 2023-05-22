<?php

namespace vladpak1\TooSimpleQR\Settings\Handler\ExtendHandler;

use Intervention\Image\Image;
use vladpak1\TooSimpleQR\Settings\Handler\HandlerInterface;

interface ExtendHandlerInterface extends HandlerInterface
{
    public function handle(Image $image, mixed $settingValue): Image;

    public function avoidExpensiveOperations(bool $bool): void;
}
