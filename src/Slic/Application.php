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
use Symfony\Component\DependencyInjection\ContainerBuilder;

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
     * The container which we'll use to pass to our commands.
     *
     * @var Symfony\Component\DependencyInjection\ContainerBuilder
     */
    private $container;

    /**
     * Register the Console and other necessary components.
     *
     * @todo read out config file.
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this->container = new ContainerBuilder();

        $console = $this->container->register(
            'console', '\\Symfony\\Component\\Console\\Application'
        );
        $console->addArgument($name);
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
     * Register a command that we'll be able to use.
     *
     * @param Sic\Command\Command $command
     * @return Slic\Application
     */
    public function registerCommand(SlicCommand $command)
    {
        $command->setContainer($this->getContainer());
        $this->container->get('console')->add($command);

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
}
