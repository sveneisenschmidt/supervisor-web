<?php

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
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     */
    public function boot(Application $app)
    {
        // TODO: Implement boot() method.
    }


} 