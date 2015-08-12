<?php

namespace v3knet\module\system;

use Pimple\Container;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use v3knet\module\Module;
use v3knet\module\system\providers\Register;

/**
 * @TODO
 *
 *  Architect
 *  ---------------------
 *  - system-module or create a separated project as a composer plugin, so we can auto setup a
 *      new project without base-app structure.
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
        $isModular = $c instanceof ModularApp;
        (new Register($isModular))->register($c);

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