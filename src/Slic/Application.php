<?php

/*
 * This part is file of the Slic Micro Framework.
 *
 * (c) Jelmer Snoeck <jelmer.snoeck@siphoc.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slic;

use Slic\Command\Command as SlicCommand;

use Symfony\Component\Console;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Loader\IniFileLoader;

/**
 * The Slic Application. This will handle registering several commands and
 * running the console.
 *
 * @author Jelmer Snoeck <jelmer.snoeck@siphoc.com>
 */
class Application
{
    /**
     * The versionnumber of the Slic Micro Framework.
     *
     * @var string
     */
    const VERSION = '1.0.0';

    /**
     * @var string
     */
    private $applicationName, $configFile;

    /**
     * The container which we'll use to pass to our commands.
     *
     * @var Symfony\Component\DependencyInjection\ContainerBuilder
     */
    private $container;

    /**
     * Register the Console and other necessary components.
     *
     * @param string $name
     * @param string[optional] $configFile
     */
    public function __construct($name, $configFile = null)
    {
        $this->applicationName = (string) $name;
        $this->configFile = (string) $configFile;

        $this->loadContainer();
    }

    /**
     * Fetch a specific command.
     *
     * @param string $name
     * @return Slic\Command\Command
     */
    public function getCommand($name)
    {
        $console = $this->container->get('console');

        if ($console->has($name)) return $console->get($name);
        // @todo throw exception
        else return false;
    }

    /**
     * @return Symfony\Component\DependencyInjection\ContainerBuilder
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param ContainerInterface
     * @return Symfony\Component\Config\Loader\DelegatingLoader
     */
    protected function getContainerLoader(ContainerInterface $container)
    {
        $fileLocator = new FileLocator($this);
        $resolver = new LoaderResolver(array(
            new XmlFileLoader($container, $fileLocator),
            new YamlFileLoader($container, $fileLocator),
            new IniFileLoader($container, $fileLocator),
        ));

        return new DelegatingLoader($resolver);
    }

    public function loadConfig($configFile)
    {
        if (!file_exists($configFile)) {
            throw new \InvalidArgumentException(
                'The config file does not exist.'
            );
        }

        $this->getContainerLoader($this->container)->load($configFile);
    }

    /**
     * Load the console, configured in the config, into our container.
     */
    protected function loadConsole()
    {
        if (!$this->container->has('console')) {
            $this->registerService('console', array(
                'class' => '\Symfony\Component\Console\Application',
                'arguments' => array(
                    $this->applicationName
                )
            ));
        }
    }

    /**
     * Load the container from a specified file.
     */
    protected function loadContainer()
    {
        $this->container = new ContainerBuilder();

        // we have a base config that we need. If no config is given, load
        // this configuration file.
        $configFile = __DIR__ . '/Resources/config/config.yml';
        if ($this->configFile !== '') {
            $configFile = $this->configFile;
        }

        $this->loadConfig($configFile);
        $this->loadConsole();
    }

    /**
     * Register a command that we'll be able to use.
     *
     * @param Sic\Command\Command $command
     * @return Slic\Application
     */
    public function registerCommand(SlicCommand $command)
    {
        $command->setContainer($this->getContainer());

        $helperSet = new HelperSet();
        $helperSet->set(new FormatterHelper(), 'formatter');
        $command->setHelperSet($helperSet);

        $this->container->get('console')->add($command);

        return $this;
    }

    /**
     * Register multiple commands at once.
     *
     * @param array $commands
     * @return Slic\Application
     */
    public function registerCommands(array $commands)
    {
        foreach ($commands as $command) {
            $this->registerCommand($command);
        }

        return $this;
    }

    /**
     * Register a new service.
     *
     * @param string $name
     * @param array $parameters
     * @return Slic\Application
     */
    public function registerService($name, array $parameters)
    {
        if (!isset($parameters['class']) || !class_exists($parameters['class'])) {
            throw new \InvalidArgumentException(
                'You must provide a class to load the service from'
            );
        }

        $service = $this->container->register($name, $parameters['class']);

        if (isset($parameters['arguments'])) {
            foreach ((array) $parameters['arguments'] as $argument) {
                $service->addArgument($argument);
            }
        }

        return $this;
    }

    /**
     * Execute the Slic Application.
     *
     * @param bool $interactive Run this application interactively or not?
     */
    public function run($interactive = false)
    {
        $app = $this->container->get('console');

        if ($interactive) {
            $app = new Console\Shell($app);
        }

        $app->run();
    }

    /**
     * Set a custom container. Always validate if our console is set, we'll
     * use this in many other parts of the framework.
     *
     * @param Symfony\Component\DependencyInjection\ContainerInterface $container
     * @return Slic\Application
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
        $this->loadConsole();

        return $this;
    }
}
