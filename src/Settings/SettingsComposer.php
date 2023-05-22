<?php

namespace vladpak1\TooSimpleQR\Settings;

use chillerlan\QRCode\QROptions;
use Intervention\Image\Image;
use vladpak1\TooSimpleQR\Settings\Handler\HandlerExecutor;

class SettingsComposer
{
    protected array $translatableSettings = [];

    protected array $extendedSettings = [];

    protected HandlerExecutor $handlerExecutor;

    public function __construct(QRSettings $settings)
    {
        $this->translatableSettings = $settings->getTranslatableSettings();
        $this->extendedSettings     = $settings->getExtendedSettings();

        $this->handlerExecutor = new HandlerExecutor($settings);
    }

    public function composeBeforePreRender(QROptions $nativeSettings): QROptions
    {

        $translated = $this->handlerExecutor->handleTranslatable();

        foreach ($translated as $settingName => $settingValue) {

            $nativeSettings->__set($settingName, $settingValue);

        }

        return $nativeSettings;
    }

    public function composeExtendedSettings(Image $preRenderedImage, bool $avoidExpensiveOperations): Image
    {

        $this->handlerExecutor->setPreRenderedImage($preRenderedImage);

        return $this->handlerExecutor->handleExtended($avoidExpensiveOperations);
    }
}
