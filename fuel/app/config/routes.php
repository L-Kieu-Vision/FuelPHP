<?php
return array(
	'_root_'  => 'welcome/index',  // The default route
	'_404_'   => 'welcome/404',    // The main 404 route
	
	'hello(/:name)?' => array('welcome/hello', 'name' => 'hello'),
	'store/(:any)'   => 'store/$1',
	'/api/home/:page' => '/api/home/',
	'/store/home/index/' => '/store/home/index',
	'/store/home/index/:page' => '/store/home/index'
);
