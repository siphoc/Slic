<?php

/*
 * This part is file of the Slic Micro Framework.
 *
 * (c) Jelmer Snoeck <jelmer.snoeck@siphoc.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slic\Tests\Command;

use Slic\Command\Command;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Console;

class CommandMock extends Command {}

/**
 * Test case for a basic command.
 *
 * @author Jelmer Snoeck <jelmer.snoeck@siphoc.com>
 */
class CommandTest extends \PHPUnit_Framework_TestCase
{
    /** @var Slic\Command\Command */
    private $command = null;

    public function setUp()
    {
        $this->command = new CommandMock('slic:demo');
    }

    /**
     * Test the setters and getters for the container.
     */
    public function testContainer()
    {
        $container = new ContainerBuilder();

        $this->assertNull($this->command->getContainer());

        $this->command->setContainer($container);
        $this->assertSame($container, $this->command->getContainer());
    }

    /**
     * Test the service methods for the container inside a command.
     */
    public function testServices()
    {
        $this->assertNull($this->command->get('console'));

        $container = new ContainerBuilder();
        $console = $container->register(
            'console', '\\Symfony\\Component\\Console\\Application'
        );
        $console->addArgument('slic');

        $this->command->setContainer($container);
        $this->assertInstanceOf(
            '\\Symfony\\Component\\Console\\Application',
            $this->command->get('console')
        );
    }
}
