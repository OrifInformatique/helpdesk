<?php

/**
 * Routes for helpdesk project
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
 */

$routes->group('helpdesk',function($routes)
{
    $routes->add('home/(:any)','\Helpdesk\Controllers\Home::$1');
   
    $routes->add('presences/','\Helpdesk\Controllers\Presences::index');
    $routes->add('presences/(:any)','\Helpdesk\Controllers\Presences::$1');
    
    $routes->add('holidays/','\Helpdesk\Controllers\Holidays::index');
    $routes->add('holidays/(:any)','\Helpdesk\Controllers\Holidays::$1');
    
    $routes->add('planning/','\Helpdesk\Controllers\Planning::index');
    $routes->add('planning/(:any)','\Helpdesk\Controllers\Planning::$1');
    
    $routes->add('terminal/','\Helpdesk\Controllers\Terminal::index');
    $routes->add('terminal/(:any)','\Helpdesk\Controllers\Terminal::$1');

    $routes->add('technician/','\Helpdesk\Controllers\Technician::index');
    $routes->add('technician/(:any)','\Helpdesk\Controllers\Technician::$1');
});

?>