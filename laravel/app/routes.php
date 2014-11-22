<?php
Route::get('/', function()
{
	return View::make('index');
});
Route::get('edit', function()
{
	return View::make('edit');
});
Route::get('meta/',function()
{
	return Response::view('error.404',array('message'=>'illegal path.'),'404');
});

Route::get('meta/{id}/{tag?}',array('uses'=>'InfoController@getText'));

Route::post('meta/{id}/{tag?}',array('uses'=>'InfoController@updateText'));
