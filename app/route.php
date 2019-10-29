<?php

class Route {
	//--------------------
	//    Static Logic
	//--------------------
	private static $declaredRoutes = [];

	private static function declareRoute(int $method, string $route, $action, $name ) {
		$route = new Route($method, $route, $action, $name);
		self::$declaredRoutes[] = $route;

		return $route;
	}

	public static function get(string $route, $action, string $name = null) {
		return self::declareRoute(MethodType::GET, $route, $action, $name);
	}

	public static function post(string $route, $action, string $name = null) {
		return self::declareRoute(MethodType::POST, $route, $action, $name);
	}

	public static function put(string $route, $action, string $name = null) {
		return self::declareRoute(MethodType::PUT, $route, $action, $name);
	}

	public static function delete(string $route, $action, string $name = null) {
		return self::declareRoute(MethodType::DELETE, $route, $action, $name);
	}

	public static function getAll() {
		return self::$declaredRoutes;
	}

	public static function getMatchingRoute($path) {
		foreach(self::$declaredRoutes as $route) {
			if ($route->getRoute() == $path)
				return $route;
		}

		return null;
	}

	//--------------------
	//   Instance logic
	//--------------------
	private $method, $route, $action, $name;

	private function __construct($method, $route, $action, $name = null) {
		$this->method = $method;
		$this->route = $route;
		$this->name = $name;

		if ($action != null && (is_callable($action) || is_string($action)))
			$this->action = $action;
		else
			throw new Exception("The 'action' argument must be of type callable or string and not null.");
	}

	public function getMethod() {
		return $this->method;
	}

	public function getRoute() {
		return $this->route;
	}

	public function getAction() {
		return $this->action;
	}

	public function getName() {
		return $this->name;
	}

	public function setName(string $name) {
		$this->name = $name;
	}

	public function name(string $name) {
		$this->setName($name);
	}

	public function __toString() {
		$response = MethodType::toString($this->method) . ' to ' . $this->route;
		if ($this->name != null)
			$response = $this->name . ': ' . $response;
		else
			$response = 'N/A: ' . $response;

		return $response;
	}

}
