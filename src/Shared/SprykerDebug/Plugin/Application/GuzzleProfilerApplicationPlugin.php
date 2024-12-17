<?php

namespace Teufelaudio\Shared\SprykerDebug\Plugin\Application;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Shared\ZedRequest\Client\HandlerStack\HandlerStackContainer;
use Teufelaudio\Shared\SprykerDebug\Plugin\Guzzle\GuzzleStopwatchProfilerMiddleware;

class GuzzleProfilerApplicationPlugin implements ApplicationPluginInterface
{
    /**
     * {@inheritDoc}
     */
    public function provide(ContainerInterface $container): ContainerInterface
    {
        $stack = (new HandlerStackContainer());
        $stack->addMiddleware(new GuzzleStopwatchProfilerMiddleware($container->get('stopwatch')));

        return $container;
    }
}
