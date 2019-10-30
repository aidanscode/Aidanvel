<?php

class View {

	private $content;

	/**
	 * Constructor
	 *
	 * @param string $content The content of the view to be displayed to the user
	 */
	public function __construct($content) {
		$this->content = $content;
	}

	/**
	 * Return the content of the view
	 *
	 * @return string The content of the view
	 */
	public function __toString() {
		return $this->content;
	}

}
