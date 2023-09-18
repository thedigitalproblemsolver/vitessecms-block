<?php

declare(strict_types=1);

namespace VitesseCms\Block\Listeners;

use VitesseCms\Block\Enum\BlockEnum;
use VitesseCms\Block\Repositories\BlockRepository;
use VitesseCms\Cli\ConsoleApplication;
use VitesseCms\Cli\Interfaces\CliListenersInterface;

class CliListeners implements CliListenersInterface
{
    public static function setListeners(ConsoleApplication $di): void
    {
        $di->eventsManager->attach(
            BlockEnum::BLOCK_LISTENER->value,
            new BlockListeners(
                $di->eventsManager,
                new BlockRepository()
            )
        );
    }
}
