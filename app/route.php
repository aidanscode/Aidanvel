<?php

class Route {
	//--------------------
	//    Static Logic
	//--------------------
	private static $declaredRoutes = [];

	/**
	 * Declare a route with the application
	 *
	 * @param int $method The request method, using the MethodType const
	 * @param string $route The request route
	 * @param mixed $action The action to execute upon the route being requested
	 * @param string $name The name of the route
	 *
	 * @return Route A new instance of the Route class made using the given parameters
	 */
	private static function declareRoute(int $method, string $route, $action, $name) {
		$route = new Route($method, $route, $action, $name);
		self::$declaredRoutes[] = $route;

		return $route;
	}

	/**
	 * Declare a GET route with the application
	 *
	 * @param string $route The request route
	 * @param mixed $action The action to execute upon the route being requested
	 * @param string $name (OPTIONAL) The name of the route
	 *
	 * @return Route A new instance of the Route class made using the given parameters
	 */
	public static function get(string $route, $action, string $name = null) {
		return self::declareRoute(MethodType::GET, $route, $action, $name);
	}

	/**
	 * Declare a POST route with the application
	 *
	 * @param string $route The request route
	 * @param mixed $action The action to execute upon the route being requested
	 * @param string $name (OPTIONAL) The name of the route
	 *
	 * @return Route A new instance of the Route class made using the given parameters
	 */
	public static function post(string $route, $action, string $name = null) {
		return self::declareRoute(MethodType::POST, $route, $action, $name);
	}

	/**
	 * Declare a PUT route with the application
	 *
	 * @param string $route The request route
	 * @param mixed $action The action to execute upon the route being requested
	 * @param string $name (OPTIONAL) The name of the route
	 *
	 * @return Route A new instance of the Route class made using the given parameters
	 */
	public static function put(string $route, $action, string $name = null) {
		return self::declareRoute(MethodType::PUT, $route, $action, $name);
	}

	/**
	 * Declare a DELETE route with the application
	 *
	 * @param string $route The request route
	 * @param mixed $action The action to execute upon the route being requested
	 * @param string $name (OPTIONAL) The name of the route
	 *
	 * @return Route A new instance of the Route class made using the given parameters
	 */
	public static function delete(string $route, $action, string $name = null) {
		return self::declareRoute(MethodType::DELETE, $route, $action, $name);
	}

	/**
	 * Return an array of all declared routes
	 *
	 * @return array An array of all declared routes
	 */
	public static function getAll() {
		return self::$declaredRoutes;
	}

	/**
	 * Given a Request instance, returns a matching route if one exists, null otherwise
	 *
	 * @param Request $request The request to match with a declared route
	 * @return Route A Route instance matching the given request if one exists, null otherwise
	 */
	public static function getMatchingRoute(Request $request) {
		foreach(self::$declaredRoutes as $route) {
			if ($route->getRoute() == $request->getPath() && $route->getMethod() == $request->getMethod())
				return $route;
		}

		return null;
	}

	//--------------------
	//   Instance logic
	//--------------------
	private $method, $route, $action, $name;

	/**
	 * Constructor
	 *
	 * @param int $method The request method, using the MethodType const
	 * @param string $route The request route
	 * @param mixed $action The action to execute upon the route being requested
	 * @param string $name (OPTIONAL) The name of the route
	 */
	private function __construct($method, $route, $action, $name = null) {
		$this->method = $method;
		$this->route = $route;
		$this->name = $name;

		if ($action != null && (is_callable($action) || is_string($action)))
			$this->action = $action;
		else
			throw new Exception("The 'action' argument must be of type callable or string and not null.");
	}

	/**
	 * Return the request method required for this route
	 *
	 * @return int The request method required for this route
	 */
	public function getMethod() {
		return $this->method;
	}

	/**
	 * Return the path required for this route
	 *
	 * @return string The path required for this route
	 */
	public function getRoute() {
		return $this->route;
	}

	/**
	 * Return the action to be executed upon requesting this route
	 *
	 * @return mixed The action to be executed upon requesting this route
	 */
	public function getAction() {
		return $this->action;
	}

	/**
	 * Return the name assigned to this route, can be null
	 *
	 * @return string The name of this route (can be null)
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Set the name of this route
	 *
	 * @param string $name The name to assign to this route
	 */
	public function setName(string $name) {
		$this->name = $name;
	}

	/**
	 * Set the name of this route
	 *
	 * @param string $name The name to assign to this route
	 */
	public function name(string $name) {
		$this->setName($name);
	}

	/**
	 * Translate the route's properties to a string
	 *
	 * @return string The route's properties translated to a string
	 */
	public function __toString() {
		$response = MethodType::toString($this->method) . ' to ' . $this->route;
		if ($this->name != null)
			$response = $this->name . ': ' . $response;
		else
			$response = 'N/A: ' . $response;

		return $response;
	}

}
