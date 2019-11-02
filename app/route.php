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
	 * @param string $path The request path
	 * @param mixed $action The action to execute upon the route being requested
	 * @param string $name The name of the route
	 *
	 * @return Route A new instance of the Route class made using the given parameters
	 */
	private static function declareRoute(int $method, string $path, $action, $name) {
		$route = new Route($method, $path, $action, $name);
		self::$declaredRoutes[] = $route;

		return $route;
	}

	/**
	 * Declare a GET route with the application
	 *
	 * @param string $path The request path
	 * @param mixed $action The action to execute upon the route being requested
	 * @param string $name (OPTIONAL) The name of the route
	 *
	 * @return Route A new instance of the Route class made using the given parameters
	 */
	public static function get(string $path, $action, string $name = null) {
		return self::declareRoute(MethodType::GET, $path, $action, $name);
	}

	/**
	 * Declare a POST route with the application
	 *
	 * @param string $path The request path
	 * @param mixed $action The action to execute upon the route being requested
	 * @param string $name (OPTIONAL) The name of the route
	 *
	 * @return Route A new instance of the Route class made using the given parameters
	 */
	public static function post(string $path, $action, string $name = null) {
		return self::declareRoute(MethodType::POST, $path, $action, $name);
	}

	/**
	 * Declare a PUT route with the application
	 *
	 * @param string $path The request path
	 * @param mixed $action The action to execute upon the route being requested
	 * @param string $name (OPTIONAL) The name of the route
	 *
	 * @return Route A new instance of the Route class made using the given parameters
	 */
	public static function put(string $path, $action, string $name = null) {
		return self::declareRoute(MethodType::PUT, $path, $action, $name);
	}

	/**
	 * Declare a DELETE route with the application
	 *
	 * @param string $path The request path
	 * @param mixed $action The action to execute upon the route being requested
	 * @param string $name (OPTIONAL) The name of the route
	 *
	 * @return Route A new instance of the Route class made using the given parameters
	 */
	public static function delete(string $path, $action, string $name = null) {
		return self::declareRoute(MethodType::DELETE, $path, $action, $name);
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
	 * Return an array containing whether or not two paths match as well as the supplied parameters if they do match
	 *
	 * @param string $declaredRaw The declared path (part of route definition)
	 * @param string $actualRaw The route actually called by the user
	 * @return array An array containing whether the two paths match as well as the supplied parameters if they do match
	 */
	private static function getPathsMatch(string $declaredRaw, string $actualRaw) {
		$parameters = [];

		$declared = explode("/", $declaredRaw);
		$actual = explode("/", $actualRaw);
		if (count($declared) != count($actual))
			return ['match' => false];

		for ($i = 0; $i < count($declared); $i++) {
			$curDeclared = $declared[$i];
			$curActual = $actual[$i];

			if (empty($curDeclared) && empty($curActual))
				continue;
			if (empty($curDeclared) && !empty($curActual))
				return ['match' => false];

			if ($curDeclared[0] != '{') {
				if ($curDeclared != $curActual) {
					return ['match' => false];
				}
			} else {
				$key = substr($curDeclared, 1, strlen($curDeclared) - 2);
				$parameters[$key] = $curActual;
			}
		}

		return ['match' => true, 'parameters' => $parameters];
	}

	/**
	 * Given a Request instance, returns a matching route if one exists, null otherwise
	 *
	 * @param Request $request The request to match with a declared route
	 * @return Route A Route instance matching the given request if one exists, null otherwise
	 */
	public static function getMatchingRoute(Request $request) {
		foreach(self::$declaredRoutes as $route) {
			$matchInfo = self::getPathsMatch($route->getPath(), $request->getPath());

			if ($matchInfo['match'] && $route->getMethod() == $request->getMethod()) {
				$request->setParameters($matchInfo['parameters']);

				return $route;
			}
		}

		return null;
	}

	//--------------------
	//   Instance logic
	//--------------------
	private $method, $path, $action, $name;

	/**
	 * Constructor
	 *
	 * @param int $method The request method, using the MethodType const
	 * @param string $path The request path
	 * @param mixed $action The action to execute upon the route being requested
	 * @param string $name (OPTIONAL) The name of the route
	 */
	private function __construct($method, $path, $action, $name = null) {
		//Make sure action is a callable or a valid string that can be parsed later
		if ($action == null || (!is_callable($action) && !is_string($action)))
			throw new Exception("The 'action' argument must be of type callable or string and not null.");

		//Store validated data
		$this->method = $method;
		$this->path = stripExtraSlashes($path);
		$this->action = $action;
		$this->name = $name;
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
	public function getPath() {
		return $this->path;
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
