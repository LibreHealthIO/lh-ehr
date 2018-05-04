<?php

Route::group(['middleware' => 'web', 'prefix' => 'reportgenerator', 'namespace' => 'Modules\ReportGenerator\Http\Controllers'], function()
{
    Route::get('/', 'ReportGeneratorController@index');
});
