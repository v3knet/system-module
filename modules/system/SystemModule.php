<?php

namespace atsilex\module\system;

use atsilex\module\Module;
use atsilex\module\system\providers\Register;
use Pimple\Container;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * @TODO
 *
 *  Features
 *  ---------------------
 *  - Include Swift Mailer.
 *  - Hal
 *      - https://github.com/mikekelly/hal-browser
 *      - Nocarrier\Hal — https://github.com/blongden/hal
 *      - https://github.com/easybiblabs/silex-hal-provider
 *      - http://www.slideshare.net/LukeStokes/pox-to-hateoas-13077649
 *  - Support drupal navbar and/or github.com/vadikom/smartmenus
 */
class SystemModule extends Module
{

    protected $machineName = 'system';
    protected $name        = 'System Module';
    protected $description = 'Implements core functions.';

    public function register(Container $c)
    {
        if (!$c instanceof ModularApp) {
            throw new \RuntimeException('Container must be an instance of ' . __NAMESPACE__ . '\\ModularApp');
        }

        (new Register())->register($c);

        // Site front-page
        if (isset($c['site_frontpage']) && ('/' !== $c['site_frontpage'])) {
            $c->get('/', function (Container $c) {
                return $c->handle(Request::create($c['site_frontpage']));
            });
        }
    }

    public function connect(Application $app)
    {
        /** @var ControllerCollection $route */
        $route = $app['controllers_factory'];

        $route->get('/hello', '@system.ctrl.home:get')->bind('hello');
        $route->get('/login', '@system.ctrl.user:getLogin')->bind('user-login');
        $route->get('/logout', '@system.ctrl.user:getLogout')->bind('user-logout');

        return $route;
    }

}