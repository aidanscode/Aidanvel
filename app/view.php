<?php

class View {

	private $content;

	public function __construct($content) {
		$this->content = $content;
	}

	public function __toString() {
		return $this->content;
	}

}
