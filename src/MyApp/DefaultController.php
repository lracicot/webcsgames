<?php
namespace MyApp;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;

class DefaultController implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get('/', function (Application $app) {
            return $app['twig']->render('index.html.twig', [
                'foo' => 'bar'
            ]);
        });

        $controllers->get('/main', function (Application $app) {
            return $app['twig']->render('main.html.twig', [
                'username' => 'dummy'      // TODO change for real username
            ]);
        });

        return $controllers;
    }
}
