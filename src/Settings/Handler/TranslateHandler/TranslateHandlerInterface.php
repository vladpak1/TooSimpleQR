<?php

namespace vladpak1\TooSimpleQR\Settings\Handler\TranslateHandler;

use vladpak1\TooSimpleQR\Settings\Handler\HandlerInterface;

interface TranslateHandlerInterface extends HandlerInterface
{
    /**
     * $return array[] Array of arrays containing translated
     * to native format settingName and settingValue.
     */
    public function translate(string $settingName, mixed $settingValue): array;
}
