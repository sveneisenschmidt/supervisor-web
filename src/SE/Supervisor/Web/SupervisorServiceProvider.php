<?php
/**
 * This file is part of the Supervistor\Web php app
 *
 * (c) Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SE\Supervisor\Web;

use \Silex\Application;
use \Silex\ServiceProviderInterface;

use \Indigo\Supervisor\Supervisor;
use \Indigo\Supervisor\Connector\InetConnector;

class SupervisorServiceProvider implements ServiceProviderInterface
{
    /**
     *
     * @param \Silex\Application $app
     */
    public function register(Application $app)
    {
        $app['supervisor'] = $app->share(function ($app) {
            $host = isset($app['supervisor.host']) ? $app['supervisor.host'] : 'localhost';
            $port = isset($app['supervisor.port']) ? $app['supervisor.port'] : 9001;
            return new Supervisor(new InetConnector($host, $port));
        });
    }

    /**
     *
     * @param \Silex\Application $app
     */
    public function boot(Application $app)
    {}


} 