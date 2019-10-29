<?php

class Response {

	private $action, $status;

	/**
	 * Constructor of class
	 *
	 * @param mixed $action The action to be executed to get the response of the request.
	 * 	Can be a callable or a formatted string referencing a controller method; ex: 'PageController@index'
	 * @param int $status The integer status code to be sent as part of the response
	 */
	public function __construct($action, int $status = 200) {
		$this->action = $action;
		$this->status = $status;
	}

	/**
	 * Send an error response with a given status code.
	 * Also sends the general error view supplied with the framework.
	 * NOTE: All PHP execution is ended at the end of this function.
	 *
	 * @param int $status The integer status code to be sent as part of the response
	 */
	public function sendError($status) {
		http_response_code($status);
		echo ErrorHandler::craftErrorView($status);
		die;
	}

	/**
	 * Send this instance of a response to the user.
	 * Handles the conversion from an action to a response.
	 * Will send a 500 error as a response in the event of any exceptions
	 */
	public function send() {
		if ($this->status != 200)
			$this->sendError($this->status);
		else {
			try {
				$response = null;

				if (is_string($this->action)) {
					$func = getFunctionFromControllerReference($this->action);
					$response = $func();
				} else {
					$response = ($this->action)();
				}

				//strval here rather than implied __toString through echo because
				//we want to ensure the response properly converts to a string before we set the response code
				$response = strval($response);
				http_response_code($this->status);
				echo $response;
			} catch(Exception $e) {
				$this->sendError(500);
			}
		}
	}

}
