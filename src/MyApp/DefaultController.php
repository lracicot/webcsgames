<?php
namespace MyApp;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Silex\Api\ControllerProviderInterface;

class DefaultController implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get('/', function (Application $app) {

            return $app['twig']->render('index.html.twig', array(
                'error'         => $app['security.last_error']($request),
                'last_username' => $app['session']->get('_security.last_username'),
            ));
        });

        $controllers->get('/login', function (Application $app, Request $request) {

            return $app['twig']->render('index.html.twig', array(
                'error'         => $app['security.last_error']($request),
                'last_username' => $app['session']->get('_security.last_username'),
            ));
        });

        $controllers->get('/main', function (Application $app) {
            return $app['twig']->render('main.html.twig', [
                'username' => 'dummy'      // TODO change for real username
            ]);
        });

        return $controllers;
    }
}
