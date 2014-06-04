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
use \Silex\ControllerProviderInterface;

class ControllerProvider implements ControllerProviderInterface
{
    /**
     *
     * @param \Silex\Application $app
     * @return \Silex\ControllerCollection
     */
    public function connect(Application $app)
    {
        $app['index.controller'] = $app->share(function() use ($app) {
            return new \SE\Supervisor\Web\Controller\IndexController(
                $app['supervisor'],
                $app['session'],
                $app['twig'],
                $app['url_generator']
            );
        });

        $controllers = $app['controllers_factory'];
        $controllers->get('/', 'index.controller:indexAction')->bind('supervisor_index');
        $controllers->get('/process/{id}/start', 'index.controller:processStartAction')->bind('supervisor_process_start');
        $controllers->get('/process/{id}/restart', 'index.controller:processRestartAction')->bind('supervisor_process_restart');
        $controllers->get('/process/{id}/stop', 'index.controller:processStopAction')->bind('supervisor_process_stop');
        $controllers->get('/process/{id}/log', 'index.controller:processLogAction')->bind('supervisor_process_log');

        return $controllers;
    }

} 