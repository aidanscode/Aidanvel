<?php

class Env {

	private static $env;

	public static function init() {
		$factory = new Dotenv\Environment\DotenvFactory([
			new Dotenv\Environment\Adapter\ArrayAdapter()
		]);

		$env = Dotenv\Dotenv::create(__DIR__ . '/../', null, $factory);
		self::$env = $env->load();
	}

	public static function get($key, $default = null) {
		return self::$env[$key] ?? $default;
	}

}
