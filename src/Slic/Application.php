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

use \Symfony\Component\Console;

/**
 * The Slic Application. This will handle registering several commands and
 * running the console.
 *
 * @author Jelmer Snoeck <jelmer.snoeck@siphoc.com>
 */
class Application extends \Pimple
{
    /**
     * The versionnumber of the Slic Micro Framework.
     *
     * @var string
     */
    const VERSION = '1.0.0';

    /**
     * Register the Console and other necessary components.
     *
     * @todo read out config file.
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this['console'] = $this->share(function() use($name) {
            return new Console\Application($name);
        });
    }
}
