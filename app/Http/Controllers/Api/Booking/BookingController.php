<?php

namespace App\Http\Controllers\Api\Booking;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Event;
use App\Http\Resources\EventResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function book(Request $request, $event_id){
        $event = Event::find($event_id);
        if(!$event){
            return response()->json([
                'success'   => false,
                'message'   => "event not found",
            ],404);
        }
        $user = Auth::user();
        $booking = Booking::where('user_id', $user->id)->where('event_id', $event->id)->exists();

        if($booking){
             return response()->json([
                'success'   => false,
                'message'   => "you booked this event before",
             ],400);
        }

        if($event->avaliable_seats <= 0){
             return response()->json([
                'success'   => false,
                'message'   => "no avaliable seats",
             ],200);
        }

        $booking = Booking::create([
            'user_id'   => $user->id,
            'event_id'  => $event->id,
            'status'    => $request->status ?? "pending",
        ]);
        $event ->decrement("avaliable_seats");

        return response()->json([
            'message'  => "event booked successfully",
            'booking'  => new BookingResource($booking),
        ],200);
    }


    public function allBooking(){
        $booking = Booking::with(['user', 'event'])->get();

        return response()->json([
            'success'  => true,
            'booking'  => BookingResource::collection($booking),
        ],200);

    }
    public function pendingBooking(){
        $booking = Booking::with(['user', 'event'])->pending()->get();

        return response()->json([
            'success'  => true,
            'booking'  => BookingResource::collection($booking),
        ],200);
    }

    public function userBooking(){
        $user_id= Auth::id();
        $booking = Booking::with(['user', 'event'])->where('user_id' , $user_id)->get();

        return response()->json([
            'success'  => true,
            'booking'  => BookingResource::collection($booking),
        ],200);

    }
    public function update(Request $request){
        $user = Auth::user();
        if($user->role == "user"){
            $request->merge([
                'user_id' => $user->id,
            ]);
        }
        $request -> validate([
            'user_id'  =>"required|integer|exists:users,id",
            'event_id' =>"required|integer|exists:events,id",
            'status'   =>"required|string",
        ]);
        $booking = Booking::where('user_id', $request->user->id)->where('event_id', $request->event->id)->exists();

        if($booking){
             return response()->json([
                'success'   => false,
                'message'   => "Not Found",
             ],404);
        }

        if($user->role == "user" && $request->status != "canceled"){
            return response()->json([
            'success'  => true,
            'message'  => "user unauthorized",
        ],403);
        }
        $booking->update([
            'user_id'  =>$request->user_id,
            'event_id' =>$request->event_id,
            'status'   =>$request->status,
        ]);
        
        return response()->json([
            'success'  => true,
            'message'  => "updated successfuly",
        ],200);
    }
}
