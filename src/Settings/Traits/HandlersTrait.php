<?php

namespace vladpak1\TooSimpleQR\Settings\Traits;

use vladpak1\TooSimpleQR\Settings\Handler\ExtendHandler\Handlers\BackgroundColorHandler;
use vladpak1\TooSimpleQR\Settings\Handler\ExtendHandler\Handlers\BackgroundGradientHandler;
use vladpak1\TooSimpleQR\Settings\Handler\ExtendHandler\Handlers\BackgroundPlasmaHandler;
use vladpak1\TooSimpleQR\Settings\Handler\ExtendHandler\Handlers\CustomFinderShapeHandler;
use vladpak1\TooSimpleQR\Settings\Handler\ExtendHandler\Handlers\LogoHandler;
use vladpak1\TooSimpleQR\Settings\Handler\ExtendHandler\Handlers\MarginHandler;
use vladpak1\TooSimpleQR\Settings\Handler\ExtendHandler\Handlers\SizeHandler;
use vladpak1\TooSimpleQR\Settings\Handler\TranslateHandler\Handlers\CircleRadiusHandler;
use vladpak1\TooSimpleQR\Settings\Handler\TranslateHandler\Handlers\CircularModulesHandler;
use vladpak1\TooSimpleQR\Settings\Handler\TranslateHandler\Handlers\CorrectionLevelHandler;
use vladpak1\TooSimpleQR\Settings\Handler\TranslateHandler\Handlers\DataColorHandler;
use vladpak1\TooSimpleQR\Settings\Handler\TranslateHandler\Handlers\FinderColorHandler;
use vladpak1\TooSimpleQR\Settings\Handler\TranslateHandler\Handlers\NoNativeMarginHandler;
use vladpak1\TooSimpleQR\Settings\Handler\TranslateHandler\Handlers\OutputHandler;
use vladpak1\TooSimpleQR\Settings\Handler\TranslateHandler\Handlers\ScaleHandler;
use vladpak1\TooSimpleQR\Settings\Handler\TranslateHandler\Handlers\TransparentBackgroundHandler;
use vladpak1\TooSimpleQR\Settings\Handler\TranslateHandler\Handlers\VersionHandler;

trait HandlersTrait
{
    /**
     * A list of handlers that will be used to process the settings.
     *
     * Please note that the extend handlers will be executed sequentially
     * according to the order in which they are listed in this array.
     *
     * @var array<string, class-string>
     */
    protected array $handlers = [
        //Translate handlers
        'output'                => OutputHandler::class,
        'correctionLevel'       => CorrectionLevelHandler::class,
        'noNativeMargin'        => NoNativeMarginHandler::class,
        'transparentBackground' => TransparentBackgroundHandler::class,
        'scale'                 => ScaleHandler::class,
        'finderColor'           => FinderColorHandler::class,
        'circularModules'       => CircularModulesHandler::class,
        'circleRadius'          => CircleRadiusHandler::class,
        'version'               => VersionHandler::class,
        'dataColor'             => DataColorHandler::class,

        //Extend handlers
        'size'               => SizeHandler::class,
        'logo'               => LogoHandler::class,
        'customFinderShape'  => CustomFinderShapeHandler::class,
        'margin'             => MarginHandler::class,
        'backgroundGradient' => BackgroundGradientHandler::class,
        'backgroundColor'    => BackgroundColorHandler::class,
        'backgroundPlasma'   => BackgroundPlasmaHandler::class,

    ];
}
