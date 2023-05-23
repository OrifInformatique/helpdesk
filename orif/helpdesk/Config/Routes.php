<?php

$routes->group('helpdesk',function($routes){
    $routes->add('home','\Helpdesk\Controllers\Home');
    $routes->add('home/(:any)','\Helpdesk\Controllers\Home::$1');
    $routes->add('presence', '\Helpdesk\Controllers\Home::presence', ['filter' => 'filterAuth']);
});

?>