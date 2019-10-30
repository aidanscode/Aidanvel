<?php

Route::post('/hello', 'PageController@hi')->name('pages.hi');

Route::get('/', function() {
	return view('pages.form');
})->name('pages.index');
