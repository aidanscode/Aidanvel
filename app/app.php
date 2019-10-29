<?php

require 'view.php';
require 'helpers.php';
require 'environment.php';
require 'request.php';
require 'route.php';
require '../routes/web.php';
require 'error_handler.php';
require 'response.php';

class App {

	private static $instance;

	public function __construct() {
		self::$instance = $this;

		Env::init();
	}

	public function handleRequest() {
		$path = cleanUriForPath($_SERVER['REQUEST_URI']);
		$route = Route::getMatchingRoute($path);

		if ($route != null)
			$response = new Response($route->getAction(), 200);
		else
			$response = new Response(null, 404);
		$response->send();
	}

}
