<?php

declare(strict_types=1);

namespace vladpak1\TooSimpleQR\Settings\Handler;

use Intervention\Image\Image;
use vladpak1\TooSimpleQR\Exception\SettingsException;
use vladpak1\TooSimpleQR\Settings\Handler\ExtendHandler\ExtendHandlerInterface;
use vladpak1\TooSimpleQR\Settings\Handler\TranslateHandler\TranslateHandlerInterface;
use vladpak1\TooSimpleQR\Settings\QRSettings;
use vladpak1\TooSimpleQR\Settings\Traits\HandlersTrait;

class HandlerExecutor
{
    use HandlersTrait;

    protected QRSettings $settings;

    protected Image $preRenderedImage;

    public function __construct(QRSettings $settings)
    {
        $this->settings = $settings;
    }

    public function handleTranslatable(): array
    {
        $settings = $this->settings->getTranslatableSettings();

        $translatedSettings = [];

        foreach ($settings as $settingName => $settingValue) {

            $translated = $this->translateSetting($settingName, $settingValue);

            foreach ($translated as $setting) {

                if (isset($translatedSettings[$setting['settingName']])) {
                    /**
                     * Allow array_push for moduleValues.
                     */
                    if ($setting['settingName'] == 'moduleValues') {

                        if (!isset($translatedSettings['moduleValues'])) {
                            $translatedSettings['moduleValues'] = [];
                        }

                        $translatedSettings['moduleValues'] = ($translatedSettings['moduleValues'] ?? []) + $setting['settingValue'];

                        continue;
                    }
                    // Other settings are not allowed to be overridden.
                    throw new SettingsException(
                        sprintf(
                            'An attempt to override the native setting "%s" with "%s" (%s) while translating the "%s" setting.',
                            $setting['settingName'],
                            print_r($setting['settingValue'], true),
                            gettype($setting['settingValue']),
                            $settingName
                        )
                    );
                }

                $translatedSettings[$setting['settingName']] = $setting['settingValue'];
            }
        }

        /**
         * Validate settings.
         */
        foreach ($this->handlers as $handlerClass) {

            /**
             * @var HandlerInterface $handler
             */
            $handler = new $handlerClass();

            $handler->validateSettings($this->settings);
            $handler->validateTranslated($translatedSettings);
        }

        return $translatedSettings;
    }

    public function handleExtended(bool $avoidExpensiveOperations): Image
    {
        /**
         * Execute handlers in order.
         */
        foreach ($this->handlers as $handlerClass) {

            /**
             * @var HandlerInterface $handler
             */
            $handler = new $handlerClass();

            if (!($handler instanceof ExtendHandlerInterface)) {
                continue;
            }

            if ($avoidExpensiveOperations) {
                $handler->avoidExpensiveOperations(true);
            }

            $handlerSetting = $handler->getSettingName();

            if (!isset($this->settings->getExtendedSettings()[$handlerSetting])) {
                continue;
            }

            $handler->setSettingsContext($this->settings);

            $this->preRenderedImage = $handler->handle(
                $this->preRenderedImage,
                $this->settings->getExtendedSettings()[$handlerSetting]
            );
        }

        return $this->preRenderedImage;
    }

    public function setPreRenderedImage(Image $preRenderedImage): void
    {
        $this->preRenderedImage = $preRenderedImage;
    }

    protected function translateSetting(string $settingName, mixed $settingValue): array
    {
        if (!isset($this->handlers[$settingName])) {
            throw new SettingsException("Handler class for setting $settingName not found.");
        }
        $handlerClass = $this->handlers[$settingName];

        /**
         * @var TranslateHandlerInterface $handler
         */
        $handler = new $handlerClass();
        $handler->setSettingsContext($this->settings);

        /**
         * Throw an exception if not TranslateHandlerInterface.
         */
        if (!($handler instanceof TranslateHandlerInterface)) {
            throw new SettingsException(
                sprintf(
                    'The handler "%s" must implement "%s" interface.',
                    $handlerClass,
                    TranslateHandlerInterface::class,
                )
            );
        }

        return $handler->translate($settingName, $settingValue);
    }
}
