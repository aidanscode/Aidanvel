<?php

class Blade {

	private static $blade;

	/**
	 * Initializes the blade templating engine to be used by the application
	 */
	public static function init() {
		self::$blade = new Jenssegers\Blade\Blade(__DIR__ . '/../views', __DIR__ . '/cache');
	}

	public static function make(string $name, array $vars = []) {
		return self::$blade->make($name, $vars);
	}

}
