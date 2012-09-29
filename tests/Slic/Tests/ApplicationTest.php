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

class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Slic\Application */
    protected $application = null;

    public function setUp()
    {
        $this->application = new Application('Slic');
    }

    public function testConsturctor()
    {
        $this->assertInstanceOf(
            '\\Symfony\\Component\\Console\\Application',
            $this->application['console']
        );
    }
}
