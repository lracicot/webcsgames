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

        $controllers->get('/profil', function (Application $app) {
            $token = $app['security.token_storage']->getToken();

            if (null !== $token) {
                $user = $token->getUser();

                $messages = $app['db']->fetchAll('SELECT * FROM messages where ffrom = ? or tto = ?', [
                    $user->getUsername(),
                    $user->getUsername(),
                ]);

                return $app['twig']->render('profil.html.twig', [
                    'username' => $user->getUsername(),
                    'bio' => $user->getBio(),
                    'photo' => $user->getPicture(),
                    'messages' => $messages,
                ]);
            }
            return $app->redirect('/login');
        });

        $controllers->post('/upload_picture', function (Application $app, Request $request) {
            $app['db']->update('users', [
                'picture' => $request->request->get('url'),
            ], ['username' => $request->request->get('user')]);
            return $app->redirect('/profil');
        });

        $controllers->post('/send_message', function (Application $app, Request $request) {
            $token = $app['security.token_storage']->getToken();

            if (null !== $token) {
                $user = $token->getUser();

                $app['db']->insert('messages', array(
                  'ffrom' => $user->getUsername(),
                  'tto' => $request->request->get('username'),
                  'message' => $request->request->get('msg')
                ));
                return $app->redirect('/profil');
            }
        });

        return $controllers;
    }
}
