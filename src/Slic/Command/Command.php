<?php

/*
 * This part is file of the Slic Micro Framework.
 *
 * (c) Jelmer Snoeck <jelmer.snoeck@siphoc.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slic\Command;

use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base class for the Slic Commands. This will house some basic functionality
 * so it's easier to work.
 *
 * @author Jelmer Snoeck <jelmer.snoeck@siphoc.com>
 */
abstract class Command extends BaseCommand implements ContainerAwareInterface
{
    /**
     * @var Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container = null;

    public function get($service)
    {
        // no container set
        if ($this->container === null) return null;

        if ($this->container->has($service)) {
            return $this->container->get($service);
        }
        else return null;
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
