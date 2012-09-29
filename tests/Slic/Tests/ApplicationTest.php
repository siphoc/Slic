<?php

/*
 * This part is file of the Slic Micro Framework.
 *
 * (c) Jelmer Snoeck <jelmer.snoeck@siphoc.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slic\Tests;

use Slic\Application;
use Slic\Command\Command as SlicCommand;

class CommandMock extends SlicCommand {}

/**
 * The application tests will ensure that everything combined works properly.
 *
 * @author Jelmer Snoeck <jelmer.snoeck@siphoc.com>
 */
class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Slic\Application */
    protected $application = null;

    public function setUp()
    {
        $this->application = new Application('Slic');
    }

    /**
     * Test if the basic actions that are required for our framework are
     * properly handled.
     */
    public function testConsturctor()
    {
        $this->assertInstanceOf(
            '\\Symfony\\Component\\Console\\Application',
            $this->application->getContainer()->get('console')
        );
    }

    /**
     * Test the functionallity to properly set a command and the functionality
     * that comes from it.
     */
    public function testCommandRegistration()
    {
        $commandName = 'slic:test';
        $command = new CommandMock($commandName);

        $this->assertSame(
            $this->application, $this->application->registerCommand($command)
        );

        $this->assertSame(
            $command, $this->application->getCommand($commandName)
        );

        $this->assertInstanceOf(
            '\\Symfony\\Component\\DependencyInjection\\ContainerInterface',
            $this->application->getCommand($commandName)->getContainer()
        );
    }
}
