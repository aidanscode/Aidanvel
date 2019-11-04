<?php

class Response {

	//--------------------
	//    Static Logic
	//--------------------

	/**
	 * Send a JSON response and set the Content-Type header to application/json
	 * The argument supplied to this function is ran through the PHP json_encode function
	 *
	 * @param mixed $data The data to be sent to the user in JSON format (ran through json_encode)
	 */
	public static function json($data) {
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	/**
	 * Send a file to be downloaded by the user
	 *
	 * @param string $path The path to the file (starting in the storage folder)
	 * @param string $name (OPTIONAL) The name the file should be downloaded as (defaults to the name of the file set in arg 1)
	 * @param array $headers (OPTIONAL) Additional headers to add to the response
	 */
	public static function download($path, string $name = null, array $headers = []) {
		$seperator = DIRECTORY_SEPARATOR;
		//Get actual name of file
		if ($name == null) {
			//Get file name from path
			$name = explode($seperator, $path);
			$name = $name[count($name) - 1];
		}

		$headers['Content-Disposition'] = 'attachment; filename="' . $name . '"';
		self::file($path, $headers);

	}

	/**
	 * Send a file to be viewed by the user
	 *
	 * @param string $path The path to the file (starting in the storage folder)
	 * @param array $headers (OPTIONAL) Additional headers to add to the response
	 */
	public static function file($path, array $headers = []) {
		$seperator = DIRECTORY_SEPARATOR;

		$path = '..' . $seperator . 'storage' . $seperator . $path;
		$mimeType = mime_content_type($path);
		$fileSize = filesize($path);

		//Set response headers
		foreach($headers as $k => $v)
			header($k . ': ' . $v);
		header('Content-Type: ' . $mimeType);
		header('Content-length: ' . $fileSize);

		readfile($path);
	}

	/**
	 * Send an error response with a given status code.
	 * Also sends the general error view supplied with the framework.
	 * NOTE: All PHP execution is ended at the end of this function.
	 *
	 * @param int $status The integer status code to be sent as part of the response
	 */
	public static function sendError($status) {
		http_response_code($status);
		echo ErrorHandler::craftErrorView($status);
		die;
	}

	//--------------------
	//   Instance logic
	//--------------------

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
	 * Send this instance of a response to the user.
	 * Handles the conversion from an action to a response.
	 * Will send a 500 error as a response in the event of any exceptions
	 */
	public function send() {
		if ($this->status != 200) {
			self::sendError($this->status);
			return;
		}

		try {
			if (is_string($this->action))
				$function = getFunctionFromControllerReference($this->action);
			else
				$function = $this->action;

			$response = callFunctionWithParameters($function, Request::getRequest()->getParameters());

			//strval here rather than implied __toString through echo because
			//we want to ensure the response properly converts to a string before we set the response code
			$response = strval($response);
			http_response_code($this->status);
			echo $response;
		} catch(Exception $e) {
			self::sendError(500);
		}
	}

}
