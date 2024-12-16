<?php

namespace Teufelaudio\SprykerDebug\Tests\Support;

use Psr\Container\ContainerInterface;

interface TestBootstrap
{
    public function bootContainer(): ContainerInterface;
}
