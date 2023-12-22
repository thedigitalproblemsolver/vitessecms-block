<?php

declare(strict_types=1);

namespace VitesseCms\Block\Listeners;

use VitesseCms\Block\Enum\BlockEnum;
use VitesseCms\Cli\ConsoleApplication;
use VitesseCms\Cli\Interfaces\CliListenersInterface;

class CliListeners implements CliListenersInterface
{
    public static function setListeners(ConsoleApplication $di): void
    {
        $di->eventsManager->attach(
            BlockEnum::LISTENER->value,
            new BlockListeners(
                $di->eventsManager,
                $di->request->isAjax()
            )
        );
    }
}
