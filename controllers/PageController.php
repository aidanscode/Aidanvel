<?php

class PageController {

	public function testBlade() {
		return view('pages.blade_test', ['userName' => 'Aidan']);
	}

}
