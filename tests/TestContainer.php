<?php

namespace Teufelaudio\SprykerDebug\Tests;

use GuzzleHttp\Client;
use Teufelaudio\SprykerDebug\Tests\Support\ApplicationBuilder;
use Teufelaudio\SprykerDebug\Tests\Support\Workspace\Workspace;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Psr\Container\ContainerInterface;
use Pyz\Zed\Application\Communication\ApplicationCommunicationFactory;
use Spryker\Client\RabbitMq\RabbitMqClient;
use Spryker\Client\RabbitMq\RabbitMqClientInterface;
use Spryker\Service\Container\Container;
use Spryker\Shared\Application\Application;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Config\Config;
use Spryker\Shared\RabbitMq\RabbitMqEnv;
use Spryker\Shared\Twig\TwigFilesystemLoader;
use Spryker\Zed\Console\Communication\Bootstrap\ConsoleBootstrap;
use Symfony\Component\Debug\Debug;
use Twig\Environment;

class TestContainer extends Container
{
    public function __construct()
    {
        Debug::enable();
        parent::__construct();
        $this->registerServices();
    }

    private function registerServices(): void
    {
        $this->registerApplication();
        $this->registerSupport();
        $this->registerRabbit();
        $this->registerYves();
    }

    private function registerApplication(): void
    {
        $this[Application::class] = $this->share(function () {
            return $this->initApplication();
        });

        $this[ConsoleBootstrap::class] = function (Container $container) {
            $container->get(Application::class);

            return new ConsoleBootstrap();
        };

        $this[Environment::class] = $this->share(function (Container $container) {
            return ApplicationCommunicationFactory::$applicationContainer->get('twig');
        });
    }

    private function registerSupport(): void
    {
        $this[Workspace::class] = $this->share(function () {
            return $this->createWorkspace();
        });
    }

    private function registerRabbit(): void
    {
        $this[RabbitMqClientInterface::class] = $this->share(function () {
            return new RabbitMqClient();
        });
        $this[AMQPStreamConnection::class] = $this->share(function () {
            $this->initApplication();
            return new AMQPStreamConnection(
                Config::get(RabbitMqEnv::RABBITMQ_HOST),
                Config::get(RabbitMqEnv::RABBITMQ_PORT),
                Config::get(RabbitMqEnv::RABBITMQ_USERNAME),
                Config::get(RabbitMqEnv::RABBITMQ_PASSWORD)
            );
        });
    }

    private function registerYves()
    {
        $this[Client::class] = $this->share(function (Container $container) {
            return new Client([
                'base_uri' => sprintf(
                    'http://%s:%s',
                    Config::get(ApplicationConstants::HOST_YVES),
                    Config::get(ApplicationConstants::PORT_YVES)
                )
            ]);
        });
    }

    private function initApplication(): ContainerInterface
    {
        $app = ApplicationBuilder::create(__DIR__ . '/App', 'GB')
            ->build();

        $this->createWorkspace()->ensureExists();

        $app->extend('twig.loader.zed', function (TwigFilesystemLoader $zedLoader) {
            $zedLoader->addPath(__DIR__ . '/Workspace', 'workspace');

            return $zedLoader;
        });

        return $app;
    }

    private function createWorkspace(): Workspace
    {
        return new Workspace(__DIR__ . '/Workspace');
    }
}
