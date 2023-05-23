<?php
$routes->get('migration','\Migration\Controllers\Migration::index');
$routes->add('migration/(:any)','\Migration\Controllers\Migration::$1');
$routes->post('migration/authenticate','\Migration\Controllers\AuthenticateMigration::authenticate');

?>