<?php
use \Illuminate\Support\Facades\Route;

Route::group(['prefix'=>'user/itinerary','middleware' => ['auth','verified']],function(){
    Route::get('/','ItineraryController@index')->name('itinerary.vendor.index');
    Route::get('/create','ItineraryController@create')->name('itinerary.vendor.create');
    Route::get('/edit/{id}','ItineraryController@edit')->name('itinerary.vendor.edit');
    Route::get('/del/{id}', 'ItineraryController@delete')->name('itinerary.vendor.delete');
    Route::post('/store/{id}','ItineraryController@store')->name('itinerary.vendor.store');
    // Route::get('/get_services', 'ItineraryController@getServiceForSelect2')->name('itinerary.vendor.getServices');
});
