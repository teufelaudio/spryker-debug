<?php

namespace Teufelaudio\Zed\SprykerDebug\Model\Propel;

class PropelConfig
{
    /**
     * @var array
     */
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function schemaDir(): string
    {
        return $this->config['paths']['schemaDir'] . DIRECTORY_SEPARATOR;
    }
}
