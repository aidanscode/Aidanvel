<?php

class MethodType {

	const GET = 0;
	const POST = 1;
	const PUT = 2;
	const DELETE = 3;

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
			default:
				return "";
		}
	}

}
