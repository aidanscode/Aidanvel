<?php

Route::get('/hello', 'PageController@hi')->name('pages.hi');

Route::get('/', function() {
	return 'Welcome to the index page';
})->name('pages.index');
