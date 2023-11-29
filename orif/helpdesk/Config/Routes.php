<?php

$routes->group('helpdesk',function($routes)
{
    $routes->add('home','\Helpdesk\Controllers\Home');
    $routes->add('home/(:any)','\Helpdesk\Controllers\Home::$1');
   
    $routes->add('presences/(:any)','\Helpdesk\Controllers\Presences::$1');
    
    $routes->add('holidays/(:any)','\Helpdesk\Controllers\Holidays::$1');

    $routes->add('planning/(:any)','\Helpdesk\Controllers\Planning::$1');
});

?>