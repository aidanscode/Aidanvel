<?php

class ErrorHandler {

	public static function craftErrorView($statusCode) {
		return view('errors.general', ['status' => $statusCode]);
	}

}
