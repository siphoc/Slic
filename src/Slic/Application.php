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
     * @return Symfony\Component\DependencyInjection\ContainerBuilder
     */
    public function getContainer()
    {
        return $this->container;
    }
}
