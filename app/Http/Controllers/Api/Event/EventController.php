<?php

namespace App\Http\Controllers\Api\Event;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventCreateRequest;
use App\Http\Requests\UpdateEventRequest;
use Illuminate\Http\Request;
use App\Http\Resources\EventResource;
use App\Http\service\mediaService;
use App\Models\Event;

class EventController extends Controller
{
    protected $mediaService;
    public function __construct(mediaService $mediaService)
    {
        $this->mediaService = $mediaService;
    }
    public function allEvents(){
        $events = Event::get();

        return response()->json([
            'success'  => true,
            'events' => EventResource::collection($events),            
        ],200);
    }

    public function oneEvent(int $id){
        $event = $this->returnEvent($id);
    }

    public function show(int $id){
       $event = $this->returnEvent($id);
        
        return response()->json([
            'success'    => true,
            'event'   =>new EventResource($event)
        ],200); 
    }

    public function returnEvent( int $id){
        $event = Event::find($id);
        if(!$event){
            return response()->json([
                'success'   => false,
                'message'   => "event not found",
            ],404);
        }
        return $event;
    }

    public function create(EventCreateRequest $request){
         $request->validated();

        $event = Event::create([
        
            'title'             => $request->title,
            'describtion'       => $request->descrbtion,
            'address'           => $request->address,
            'start date'        => $request->start_date,
            'avaliable seats'   => $request->avaliable_seats,
            'location'          => $request->location,
            'category_id'       => $request->category_id,
        ]);

        //$event->addMedia($request->file('image'))->toMediaCollection('main_image');

        if($request->hasFile('images')){
            foreach($request->file('images') as $image){
                $this->mediaService->createMedia($event , $image , 'main_image' );
            }
        }
        
        return response()->json([
            'success'   => true,
            'message'   => "event created successfully",
            'event'  => new EventResource($event),
        ],201);
    }

     public function allwithcategory(){
        $events = Event::with('category')->get();  //this like join to reduce load(eager loading)
       /*alternative way to show category
        foreach($events as $event){
            ($event->category);
        };
        */

        return response()->json([
            'success'  => true,
            'events' => EventResource::collection($events),            
        ],200);
    }

    public function showwithcategory(int $id){
       $event = Event::with('category')->find($id);
        if(!$event){
            return response()->json([
                'success'   => false,
                'message'   => "event not found",
            ],404);
        }
        return response()->json([
            'success'    => true,
            'event'   =>new EventResource($event)
        ],200); 
    }

    public function update(UpdateEventRequest $request , $id){
        $request->validated();
        $event = $this->returnEvent($id);
        $event->update([
            'title'             => $request->title,
            'describtion'       => $request->descrbtion,
            'address'           => $request->address,
            'start date'        => $request->start_date,
            'avaliable seats'   => $request->avaliable_seats,
            'location'          => $request->location,
            'category_id'       => $request->category_id,
        ]);
        if($request->hasFile('image')){
            $this->mediaService->updateMedia($event , $request->file('image') , 'main_image');
        }
        return response()->json([
            'success'   => true,
            'message'   => "event updated successfully",
        ],200);
    }

    public function delete($id){
        $event = $this->returnEvent($id);

        $this->mediaService->deleteMedia($event , 'main_image');
        $event->delete();

         return response()->json([
            'success'   => true,
            'message'   => "event deleted successfully",
        ],200);
    }

    
        
  
}
