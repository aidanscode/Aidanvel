<?php

class Env {

	private static $env;

	/**
	 * Initializes the environment variables, to be accessed through this class
	 */
	public static function init() {
		$factory = new Dotenv\Environment\DotenvFactory([
			new Dotenv\Environment\Adapter\ArrayAdapter()
		]);

		$env = Dotenv\Dotenv::create(__DIR__ . '/../', null, $factory);
		self::$env = $env->load();
	}

	/**
	 * Get the environment variable value under the given key
	 *
	 * @var string $key The key to find in the .env file
	 * @var mixed $default (OPTIONAL) The value to return if no value could be find matching the given key
	 * @return mixed The value matching the given key
	 */
	public static function get($key, $default = null) {
		return self::$env[$key] ?? $default;
	}

}
