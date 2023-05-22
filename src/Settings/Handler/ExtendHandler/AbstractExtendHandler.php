<?php

namespace vladpak1\TooSimpleQR\Settings\Handler\ExtendHandler;

use vladpak1\TooSimpleQR\Settings\Handler\AbstractHandler;

abstract class AbstractExtendHandler extends AbstractHandler implements ExtendHandlerInterface
{
    protected bool $avoidExpensiveOperations = false;

    public function avoidExpensiveOperations(bool $bool): void
    {
        $this->avoidExpensiveOperations = $bool;
    }
}
