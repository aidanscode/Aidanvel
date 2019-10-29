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
 * @return View An instance of a view found at the given path
 */
function view(string $path, array $with = array()) {
	$output = null;

	$path = str_replace('.', '/', $path);
	$fullPath = '../views/' . $path . '.php';

	if (!file_exists($fullPath))
		throw new Exception("The specified view could not be found: views/" . $path . ".php");

	//Include the specified view and supply the file with the variables defined in $with, then return the result
	extract($with);
	ob_start();
	include $fullPath;
	$output = ob_get_clean();

	return new View($output);
}

/**
 * Includes a PHP file found at the given path, starting from ../controllers/
 *
 * @param string $path The path to the file to include. ".php" is automatically added to the name
 */
function includeController($path) {
	include '../controllers/' . $path . '.php';
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
