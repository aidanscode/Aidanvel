<?php

require 'helpers.php';
require 'services/environment.php';
require 'services/blade.php';
require 'http/request.php';
require 'http/route.php';
require '../routes/web.php';
require 'http/error_handler.php';
require 'http/response.php';

class App {

	private static $instance;

	/**
	 * Initializes the application
	 */
	public function __construct() {
		self::$instance = $this;

		Env::init();
		Blade::init();
	}

	/**
	 * Process the incoming request and fordward the execution to its corresponding route handler
	 */
	public function handleRequest() {
		$request = Request::getRequest();
		$route = Route::getMatchingRoute($request);

		if ($route != null) {
			$request->setRoute($route);
			$response = new Response($route->getAction(), 200);
		} else
			$response = new Response(null, 404);
		$response->send();
	}

}
