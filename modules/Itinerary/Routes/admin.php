<?php
use Illuminate\Support\Facades\Route;

Route::get('/','ItinerayController@index')->name('itinerary.admin.index');
Route::get('/create','ItineraryController@create')->name('itinerary.admin.create');