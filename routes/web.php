<?php

Route::get('/', function() {
	return Response::download('blueVader.jpg');
});

Route::delete('/', function() {
	return "Welcome to DELETE /";
});

Route::get('/users', function() {
	return view('pages.form');
})->name('pages.index');

Route::post('/login', function(Request $request) {
	$msg = $request->input('username') . ' : ' . $request->input('pass');
	return $msg;
});

Route::get('/users/{id}/', 'PageController@hi')->name('pages.hi');
