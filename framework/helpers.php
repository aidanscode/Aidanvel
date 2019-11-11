<?php

/**
 * Parses a given URI for the path portion of a URI, neglecting the query
 *
 * @param string $uri The URI to be parsed for the path
 * @return string The path portion of the given URI, neglecting the query
 */
function cleanUriForPath($uri) {
	return explode("?", $uri)[0];
}

/**
 * Returns an instance of a view given a path to the view file (starting at /views/)
 *
 * @param string $path The path to the view, starting at /views. Directories can be specified through slashes or dots
 * @param array $with (Optional) An array of $k => $v pairs where a key is a variable name and a value is the value of the given key, to be used in the view.
 *
 * @return Blade An instance of a view found at the given path
 */
function view(string $path, array $vars = []) {
	return Blade::make($path, $vars);
}

/**
 * Includes a PHP file found at the given path, starting from ../controllers/
 *
 * @param string $path The path to the file to include. ".php" is automatically added to the name
 */
function includeController($path) {
	include __DIR__ . '/../app/controllers/' . $path . '.php';
}

/**
 * Given a formatted string representing a controller class and a method (ex: "PageController@hi"),
 * the file is included, a new instance of the class is created, and the function is returned as a callable variable.
 *
 * @param string $reference A formatted string representing a controller class and a method inside it
 */
function getFunctionFromControllerReference(string $reference) {
	$fullPath = null;
	$controllerName = null;
	$methodName = null;

	$split = explode("@", $reference);
	$fullPath = $split[0];
	$methodName = $split[1];

	$split = explode("/", $split[0]);
	$controllerName = $split[count($split) - 1];

	includeController($fullPath);
	return [
		new $controllerName,
		$methodName
	];
}

/**
 * Strip all leading and ending slashes (/) from a path (string)
 *
 * @param string $path The path to be modified
 * @return string $path The supplied path without any leading or ending slashes (/)
 */
function stripExtraSlashes(string $path) {
	//Keep removing starting slashes until none are left
	while(substr($path, 0, 1) === '/') {
		$path = substr($path, 1);
	}

	//Keep removing ending slashes until none are left
	while(substr($path, strlen($path) - 1) === '/') {
		$path = substr($path, 0, strlen($path) - 1);
	}

	return $path;
}

/**
 * Call the given function with the given parameters in the order they're supplied.
 * If the function's first parameter has a type of Request, the request will be inserted before the supplied parameters
 *
 * @param mixed $function The callable function to be called
 * @param array $parameters The list of parameters to be supplied to the function (can be empty)
 * @return mixed The return value of the function supplied
 */
function callFunctionWithParameters($function, array $parameters) {
	$closure = Closure::fromCallable($function);
	$reflection = new ReflectionFunction($closure);
	$reflectionParams = $reflection->getParameters();

	if (count($reflectionParams) > 0 && $reflectionParams[0]->getType() == 'Request')
		array_unshift($parameters, Request::getRequest());

	return call_user_func_array($function, $parameters);
}
