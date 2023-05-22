<?php

namespace vladpak1\TooSimpleQR\RenderExecutor;

use chillerlan\QRCode\Common\Mode;
use chillerlan\QRCode\Data\QRDataModeInterface;
use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Intervention\Image\Exception\ImageException;
use Intervention\Image\Image;
use Throwable;
use vladpak1\TooSimpleQR\Exception\RenderException;
use vladpak1\TooSimpleQR\Image\InterventionWrapperStatic;
use vladpak1\TooSimpleQR\Image\QRCodeImage;
use vladpak1\TooSimpleQR\Settings\QRSettings;
use vladpak1\TooSimpleQR\Settings\SettingsComposer;

abstract class AbstractRenderExecutor implements RenderExecutorInterface
{
    protected QRSettings $settings;

    protected mixed $encodedData;

    public bool $avoidExpensiveOperations = false;

    abstract public function init(QRSettings $settings, mixed $encodedData): void;

    abstract public function render(): QRCodeImage;

    /**
     * Prerender QR code using a native library (with translatable settings).
     */
    protected function preRender(): Image
    {
        $composedNativeSettings = $this->composeSettingsBeforePreRender();

        return $this->renderNative($composedNativeSettings);
    }

    protected function finalRender(Image $preRenderedImage, bool $avoidExpensiveOperations = false): Image
    {
        $composer = new SettingsComposer($this->settings);

        return $composer->composeExtendedSettings($preRenderedImage, $avoidExpensiveOperations);
    }

    protected function composeSettingsBeforePreRender(): QROptions
    {
        $composer = new SettingsComposer($this->settings);

        return $composer->composeBeforePreRender((new QROptions([
            'keepAsSquare' => [
                QRMatrix::M_FINDER,
                QRMatrix::M_FINDER_DOT,
                QRMatrix::M_SEPARATOR,
                QRMatrix::M_QUIETZONE,
            ],
            'returnResource' => true,
            'version'        => -1,
            'moduleValues'   => [],
        ])));
    }

    protected function renderNative(QROptions $nativeSettings): Image
    {
        $nativeQR = new QRCode($nativeSettings);

        foreach (Mode::INTERFACES as $dataInterface) {
            /**
             * @var QRDataModeInterface $dataInterface
             */
            if ($dataInterface::validateString($this->encodedData)) {
                $nativeQR->addSegment(new $dataInterface($this->encodedData));

                break;
            }
        }

        try {

            $rendered = $nativeQR->render();
            $qrImage  = InterventionWrapperStatic::make($rendered);

        } catch (ImageException $e) {

            throw new RenderException('Failed to create Intervention Image during QR code pre-rendering. Exception: '
                . $e->getMessage());

        } catch (Throwable $e) {

            throw new RenderException('QR code pre-rendering using native library failed. Check your data and setting. Error: '
                . $e->getMessage());

        }

        return $qrImage;
    }
}
