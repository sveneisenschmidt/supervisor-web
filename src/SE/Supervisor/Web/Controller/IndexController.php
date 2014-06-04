<?php
/**
 * This file is part of the Supervisor\Web php app
 *
 * (c) Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SE\Supervisor\Web\Controller;

use \Silex\Application;

use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\RedirectResponse;
use \Symfony\Component\HttpFoundation\Session\Session;
use \Symfony\Component\Routing\Generator\UrlGenerator;

use \Twig_Environment;
use \Indigo\Supervisor\Supervisor;
use \Indigo\Supervisor\Process;

class IndexController
{
    const PROCESS_STARTED = 'Process %s started.';
    const PROCESS_RESTARTED = 'Process %s restarted.';
    const PROCESS_STOPPED = 'Process %s stopped.';
    const PROCESS_NOT_FOUND = 'Process not found.';

    /**
     * @var \Indigo\Supervisor\Supervisor
     */
    protected $supervisor;

    /**
     * @var \Symfony\Component\HttpFoundation\Session\Session
     */
    protected $session;

    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @var \Symfony\Component\Routing\Generator\UrlGenerator
     */
    protected $urlGenerator;

    /**
     * @param \Indigo\Supervisor\Supervisor $supervisor
     * @param \Symfony\Component\HttpFoundation\Session\Session $session
     * @param \Twig_Environment $twig
     */
    public function __construct(
        Supervisor $supervisor,
        Session $session,
        Twig_Environment $twig,
        UrlGenerator $urlGenerator
    )
    {
        $this->supervisor = $supervisor;
        $this->session = $session;
        $this->twig = $twig;
        $this->urlGenerator = $urlGenerator;
    }

    public function indexAction()
    {
        return $this->twig->render('Index/index.html.twig', [
            'all_process' => $this->supervisor->getAllProcess()
        ]);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function processStartAction($id)
    {
        return $this->withProcess($id, function(Process $process) {
            $process->start(true);
            return self::PROCESS_STARTED;
        });
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function processStopAction($id)
    {
        return $this->withProcess($id, function(Process $process) {
            $process->stop(true);
            return self::PROCESS_STOPPED;
        });
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function processRestartAction($id)
    {
        return $this->withProcess($id, function(Process $process) {
            $process->restart(true);
            return self::PROCESS_RESTARTED;
        });
    }

    public function processLogAction(Request $request, $id)
    {
        /* @var \Indigo\Supervisor\Process $process */
        $all_process = $this->supervisor->getAllProcess();
        $process = isset($all_process[$id]) ? $all_process[$id] : null;

        if($process === null || $process instanceof Process === false) {
            $this->session->getFlashBag()->add('error', self::PROCESS_NOT_FOUND);
            $target = $this->urlGenerator->generate('supervisor_index');
            return new RedirectResponse($target);
        }

        $page = (int)$request->get('page', 1);
        if($page < 1 || is_numeric($page) === false) {
            $page = 1;
        }

        $offset = $page*4092;

        list($temp, $length) = $process->tailStdoutLog(0, 0);

        if($length - $offset < 0) {
            $offset = $length;
        }

        $contents = $process->readStdoutLog($length - $offset, 4092);

        $total = floor($length / 4092);
        $previous = false;
        $next = false;

        if($page > 1 && $page < $total) {
            $previous = $page-1;
            $next = $page+1;
        } else
        if($page > 1) {
            $previous = $page-1;
            $next = false;
        } else
        if($page == 1) {
            $previous = false;
            $next = $page+1;
        }

        if($offset == $length) {
            $next =  false;
            $previous = floor($length / 4092);
        }

        return $this->twig->render('Process/log.html.twig', [
            'process' => $process,
            'contents' => $contents,
            'pagination' => [
                'curr' => $page,
                'prev' => $previous,
                'next' => $next,
                'total' => $total
            ]
        ]);
    }

    /**
     * @param $id
     * @param \Closure $closure
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function withProcess($id, \Closure $closure)
    {
        /* @var \Indigo\Supervisor\Process $process */
        $all_process = $this->supervisor->getAllProcess();
        $process = isset($all_process[$id]) ? $all_process[$id] : null;

        if($process === null || $process instanceof Process === false) {
            $this->session->getFlashBag()->add('error', self::PROCESS_NOT_FOUND);
            $target = $this->urlGenerator->generate('supervisor_index');
            return new RedirectResponse($target);
        }

        try {
            $message = call_user_func_array($closure, [$process]);
            $this->session->getFlashBag()->add('success', sprintf($message, $process->getName()));
        } catch (\Exception $e) {
            $this->session->getFlashBag()->add('error', $e->getMessage());
        }

        $target = $this->urlGenerator->generate('supervisor_index');
        return new RedirectResponse($target);
    }

} 