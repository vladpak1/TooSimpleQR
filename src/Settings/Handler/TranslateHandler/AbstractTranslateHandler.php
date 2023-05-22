<?php

declare(strict_types=1);

namespace vladpak1\TooSimpleQR\Settings\Handler\TranslateHandler;

use vladpak1\TooSimpleQR\Settings\Handler\AbstractHandler;

abstract class AbstractTranslateHandler extends AbstractHandler implements TranslateHandlerInterface
{
    abstract public function translate(string $settingName, mixed $settingValue): array;
}
