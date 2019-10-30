<?php

class ErrorHandler {

	/**
	 * Return the general error view showing the given status code
	 *
	 * @return View An instance of the general error view for the given status code
	 */
	public static function craftErrorView($statusCode) {
		return view('errors.general', ['status' => $statusCode]);
	}

}
