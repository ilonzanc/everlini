<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Core\Plugin;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

/**
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 *
 */
Router::defaultRouteClass(DashedRoute::class);

Router::scope('/', function (RouteBuilder $routes) {
    /**
     * Here, we are connecting '/' (base path) to a controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, src/Template/Pages/home.ctp)...
     */
    $routes->connect('/', ['controller' => 'Pages', 'action' => 'home']);

    /**
     * ...and connect the rest of 'Pages' controller's URLs.
     */
    $routes->connect('/pages/*', ['controller' => 'Pages', 'action' => 'display']);

    /**
     * Connect catchall routes for all controllers.
     *
     * Using the argument `DashedRoute`, the `fallbacks` method is a shortcut for
     *    `$routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'DashedRoute']);`
     *    `$routes->connect('/:controller/:action/*', [], ['routeClass' => 'DashedRoute']);`
     *
     * Any route class can be used with this method, such as:
     * - DashedRoute
     * - InflectedRoute
     * - Route
     * - Or your own route class
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */
    $routes->fallbacks(DashedRoute::class);
});

/**
 * Load all plugin routes. See the Plugin documentation on
 * how to customize the loading of plugin routes.
 */
Plugin::routes();


/**
 * Load all the api routes
 */

/* Router::scope('/api', function ($routes) {
    // Prior to 3.5.0 use `extensions()`
        $routes->setExtensions(['json']);
        $routes->resources('Recipes');

}); */


    // Because you are in the api scope,
    // you do not need to include the /api prefix
    // or the api route element.




Router::prefix('api', function ($routes) {
    // Because you are in the api scope,
    // you do not need to include the /api prefix
    // or the api route element.
    $routes->setExtensions(['json']);

    //Admins Routes

    $routes->connect(
        '/admins/add',
        ['controller' => 'Admins', 'action' => 'add']
    );

    $routes->connect(
        '/admins',
        ['controller' => 'Admins', 'action' => 'getAdminsByOrganisationId', '?' => ['organisation' => '[0-9]+', 'pass' => ['organisation']]]
    );

    //Attachments Routes

    $routes->connect(
        '/attachments/add',
        ['controller' => 'Attachments', 'action' => 'add']
    );

    //Events Routes

    $routes->connect(
        '/events',
        ['controller' => 'Events', 'action' => 'index']
    );

    $routes->connect(
        '/events/:id',
        ['controller' => 'Events', 'action' => 'view']
    )->setPass(['id']);

    $routes->connect(
        '/events/add',
        ['controller' => 'Events', 'action' => 'add']
    );

    $routes->connect(
        '/events/:id/edit',
        ['controller' => 'Events', 'action' => 'edit']
    )->setPass(['id']);

    $routes->connect(
        '/events/:id/delete',
        ['controller' => 'Events', 'action' => 'delete']
    )->setPass(['id']);

    $routes->connect(
        '/events/organisation/:organisationid',
        ['controller' => 'Events', 'action' => 'getEventsByOrganisationId']
    )->setPass(['organisationid']);

    //Favorites Routes

    $routes->connect(
        '/favorites',
        ['controller' => 'Favorites', 'action' => 'index']
    );

    $routes->connect(
        '/favorites/add',
        ['controller' => 'Favorites', 'action' => 'add']
    );

    $routes->connect(
        '/favorites/delete',
        ['controller' => 'Favorites', 'action' => 'delete']
    );

    $routes->connect(
        '/favorites/user/:userid',
        ['controller' => 'Favorites', 'action' => 'getFavoritesByUserId']
    )->setPass(['userid']);

    //Interests Routes

    $routes->connect(
        '/interests',
        ['controller' => 'Interests', 'action' => 'index']
    );

    $routes->connect(
        '/interests',
        ['controller' => 'Interests', 'action' => 'getParentInterests', '?' => ['parent' => 'true', 'pass' => ['parent']]]
    );

    //Organisations Routes

    $routes->connect(
        '/organisations',
        ['controller' => 'Organisations', 'action' => 'getOrganisationsByUser', '?' => ['user' => '[0-9]+', 'pass' => ['user']]]
    );

    $routes->connect(
        '/organisations/:id',
        ['controller' => 'Organisations', 'action' => 'view']
    )->setPass(['id']);

    $routes->connect(
        '/organisations/add',
        ['controller' => 'Organisations', 'action' => 'add']
    );

    //Posts Routes

    $routes->connect(
        '/posts',
        ['controller' => 'Posts', 'action' => 'index']
    );

    $routes->connect(
        '/posts/:id',
        ['controller' => 'Posts', 'action' => 'view']
    )->setPass(['id']);

    $routes->connect(
        '/posts/add',
        ['controller' => 'Posts', 'action' => 'add']
    );

    $routes->connect(
        '/posts/:id/edit',
        ['controller' => 'Posts', 'action' => 'edit']
    )->setPass(['id']);

    $routes->connect(
        '/posts/:id/delete',
        ['controller' => 'Posts', 'action' => 'delete']
    )->setPass(['id']);

    //Profiles Routes

    //$routes->resources('Profiles');

    /* $routes->connect(
        '/profiles/:id',
        ['controller' => 'Profiles', 'action' => 'view']
    )->setPass(['id']); */

    $routes->connect(
        '/profiles/:id/edit',
        ['controller' => 'Profiles', 'action' => 'edit']
    )->setPass(['id']);

    $routes->connect(
        '/profiles/:username',
        ['controller' => 'Profiles', 'action' => 'getProfileByUsername']
    )->setPass(['username']);

    //Reviews Routes

    $routes->resources('Reviews');

    //Users Routes

    $routes->connect(
        '/register',
        ['controller' => 'Users', 'action' => 'register']
    );
    $routes->connect(
        '/login',
        ['controller' => 'Users', 'action' => 'login']
    );


});