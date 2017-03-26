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

            return $app->redirect('/main');
        });

        $controllers->get('/login', function (Application $app, Request $request) {

            return $app['twig']->render('index.html.twig', array(
                'error'         => $app['security.last_error']($request),
                'last_username' => $app['session']->get('_security.last_username'),
            ));
        });

        $controllers->post('/register', function (Application $app, Request $request) {
            $app['db']->insert('users', [
                'username' => $request->request->get('uname'),
                'password' => $request->request->get('pass'),
                'bio' => $request->request->get('bio'),
                'roles' => 'ROLE_ADMIN'
            ]);
            return $app->redirect('/main');
        });

        $controllers->get('/main', function (Application $app) {
            $token = $app['security.token_storage']->getToken();

            if (null !== $token) {
                $user = $token->getUser();
                return $app['twig']->render('main.html.twig', [
                    'username' => $user->getUsername(),
                ]);
            }
            return $app->redirect('/login');
        });

        return $controllers;
    }
}
