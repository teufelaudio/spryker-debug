<?php

namespace Teufelaudio\Zed\SprykerDebug\Communication;

use GuzzleHttp\Client;
use Propel\Runtime\Propel;
use Spryker\Shared\Config\Config;
use Spryker\Shared\RabbitMq\RabbitMqEnv;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Teufelaudio\Zed\SprykerDebug\Communication\Model\Propel\TableNameFinder;
use Teufelaudio\Zed\SprykerDebug\Communication\Model\Propel\TablesFactory;
use Teufelaudio\Zed\SprykerDebug\Communication\Model\Rabbit\RabbitClient;
use Teufelaudio\Zed\SprykerDebug\Model\Propel\PropelConfig;

/**
 * @method \Teufelaudio\Zed\SprykerDebug\SprykerDebugConfig getConfig()
 */
class SprykerDebugCommunicationFactory extends AbstractCommunicationFactory
{
    public function getRabbitClient(): RabbitClient
    {
        return new RabbitClient(
            new Client([
                'base_uri' => sprintf(
                    'http://%s:%s/api',
                    Config::get(RabbitMqEnv::RABBITMQ_API_HOST),
                    Config::get(RabbitMqEnv::RABBITMQ_API_PORT),
                ),
                'auth' => [
                    Config::get(RabbitMqEnv::RABBITMQ_API_USERNAME),
                    Config::get(RabbitMqEnv::RABBITMQ_API_PASSWORD),
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]),
        );
    }

    public function createPropelTablesFactory(): TablesFactory
    {
        return new TablesFactory(Propel::getDatabaseMap(), $this->createPropelTableNameFinder());
    }

    public function createPropelTableNameFinder(): TableNameFinder
    {
        return new TableNameFinder(new PropelConfig(Config::get('PROPEL')));
    }
}
