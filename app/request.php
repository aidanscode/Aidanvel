<?php

class Request {

	//--------------------
	//    Static Logic
	//--------------------

	private static $instance = null;

	/**
	 * Return the singleton instance of the Request class, containing helpful information about the request
	 *
	 * @return Request The singleton instance of the Request class
	 */
	public static function getRequest() {
		if (self::$instance == null) {
			$method = MethodType::fromString($_SERVER['REQUEST_METHOD']);
			$path = cleanUriForPath($_SERVER['REQUEST_URI']);

			self::$instance = new Request($method, $path);
		}

		return self::$instance;
	}

	//--------------------
	//   Instance logic
	//--------------------

	private $method, $path, $parameters, $route, $input;

	/**
	 * Constructor
	 *
	 * @param int $method The request method, using MethodType const
	 * @param string $path The path of the request
	 */
	public function __construct(int $method, string $path) {
		$this->method = $method;
		$this->path = stripExtraSlashes($path);
		$this->parameters = [];

		parse_str(file_get_contents("php://input"), $input);
		$this->input = array_merge($input, $_GET);
	}

	/**
	 * Returns the request method
	 *
	 * @return int The request method, compare using MethodType const
	 */
	public function getMethod() {
		return $this->method;
	}

	/**
	 * Return the path of the request
	 *
	 * @return string The path of the request
	 */
	public function getPath() {
		return $this->path;
	}

	/**
	 * Set the request's parameters
	 *
	 * @param array $parameters The parameters supplied with the original request
	 */
	public function setParameters(array $parameters) {
		$this->parameters = $parameters;
	}

	/**
	 * Return the request's parameters
	 *
	 * @return array The parameters supplied with the original request
	 */
	public function getParameters() {
		return $this->parameters;
	}

	/**
	 * Set the request's route
	 *
	 * @param Route $route The route called in the request
	 */
	public function setRoute(Route $route) {
		$this->route = $route;
	}

	/**
	 * Return the request's route
	 *
	 * @return Route The route called by the original request
	 */
	public function getRoute() {
		return $this->route;
	}

	/**
	 * Get the value of an input variable of the given key.
	 * This works for all HTTP method types
	 *
	 * @param string $key The key to search for
	 * @param mixed $default (OPTIONAL) The value to be returned if no value is found under the key, null by default
	 * @return mixed The value stored under the given key
	 */
	public function input(string $key, mixed $default = null) {
		return $this->input[$key] ?? $default;
	}

	/**
	 * Return an array of all input values
	 *
	 * @return array An associative array of all input values
	 */
	public function all() {
		return $this->input;
	}

}

class MethodType {

	const GET = 0;
	const POST = 1;
	const PUT = 2;
	const DELETE = 3;
	const PATCH = 4;

	/**
	 * Return the request method as a string
	 *
	 * @param int $type The request method, using RequestMethod const
	 * @return string The request method as a string
	 */
	public static function toString(int $type) {
		switch($type) {
			case self::GET:
				return "GET";
			case self::POST:
				return "POST";
			case self::PUT:
				return "PUT";
			case self::DELETE:
				return "DELETE";
			case self::PATCH:
				return "PATCH";
			default:
				return "";
		}
	}

	/**
	 * Convert from string to request method const
	 *
	 * @param string $type The request method as a string
	 * @return int The request method represented using the MethodType const
	 */
	public static function fromString($type) {
		switch($type) {
			case 'GET':
				return MethodType::GET;
			case 'POST':
				return MethodType::POST;
			case 'PUT':
				return MethodType::PUT;
			case 'DELETE':
				return MethodType::DELETE;
			case 'PATCH':
				return MethodType::PATCH;
			default:
				return MethodType::GET;
		}
	}

}
