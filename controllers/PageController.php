<?php

class PageController {

	public function hi(Request $request, $id) {
		$msg = 'Welcome to: ';
		$msg .= $request->getRoute()->getPath() . "<br>";
		$msg .= "You requested: " . $id;

		return $msg;
	}

}
