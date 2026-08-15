<?php

use App\Http\Controllers\Api\Auth\Authcontroller;
use App\Http\Controllers\Api\Category\CategoryController;
use App\Http\Controllers\Api\Event\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Booking\BookingController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum' , 'role:admin'])->group(function(){
    //category
       Route::post('category.create', [CategoryController::class, 'create']);
       Route::put('category.update/{id}' , [CategoryController::class, 'update']);
       Route::delete('category.delete/{id}',[CategoryController::class, 'delete']);
    //event
       Route::post('event.create', [EventController::class, 'create']);  
       Route::post('event.update/{id}', [EventController::class, 'update']); 
       Route::delete('event.delete/{id}', [EventController::class, 'delete']); 
    //booking 
       Route::get('bokking.all', [BookingController::class, 'allBooking']);  
       Route::get('bokking.pending', [BookingController::class, 'pendingBooking']);
    //user
       Route::delete('user.delete/{id}', [Authcontroller::class, 'userDelete']);
       Route::get('users.all', [AuthController::class, 'allUsers']);
          

});

Route::middleware(['auth:sanctum' , 'role:user'])->group(function(){
    Route::post('booking/{event_id}', [BookingController::class, 'book']);
    Route::get('bokking.user', [BookingController::class, 'userBookimg']);  
});

//auth
Route::post('register', [Authcontroller::class, 'register']);
Route::post('login',[Authcontroller::class, 'login']);
Route::delete('logout',[Authcontroller::class, 'logout'])->middleware('auth:sanctum');

//category
Route::get('category.all',[CategoryController::class, 'index'] );
Route::get('category.one/{id}',[CategoryController::class, 'oneCategoty'] );
Route::get('category.show/{id}',[CategoryController::class, 'show'] );

//event
Route::get('events.all',[EventController::class, 'allEvents'] );
Route::get('events.one/{id}',[EventController::class, 'oneEvent'] );
Route::get('events.show/{id}',[EventController::class, 'show'] );
Route::get('all.w.category',[EventController::class, 'allwithcategory'] );
Route::get('show.w.category/{id}',[EventController::class, 'showwithcategory'] );

//booking
Route::post('booking.update',[BookingController::class, 'update'])->middleware('auth:sanctum');