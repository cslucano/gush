<?php

/*
 * This file is part of Gush.
 *
 * (c) Luis Cordova <cordoval@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Gush\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Console\Input\InputOption;
use Gush\Event\GushEvents;
use Gush\Event\CommandEvent;
use Symfony\Component\Console\Event\ConsoleEvent;
use Gush\Feature\TableFeature;
use Gush\Feature\TemplateFeature;

class TemplateSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            GushEvents::DECORATE_DEFINITION => 'decorateDefinition',
        ];
    }

    public function decorateDefinition(CommandEvent $event)
    {
        $command = $event->getCommand();

        if ($command instanceof TemplateFeature) {
            $command
                ->addOption(
                    'template',
                    't',
                    InputOption::VALUE_REQUIRED,
                    'Specify the template to use',
                    null
                )
            ;
        }
    }
}
