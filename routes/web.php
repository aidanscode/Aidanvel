<?php

Route::get('/', function() {
	return "Welcome to /";
});

Route::get('/users', function() {
	return view('pages.form');
})->name('pages.index');

Route::get('/users/{id}/', 'PageController@hi')->name('pages.hi');
