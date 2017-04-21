<?php
\App\Helpers\Route::loadFromFile('web-api.routes');

Route::get('/branch/detail', 'BranchController@show');
Route::post('/branch/{branch_id}', 'BranchController@update');
