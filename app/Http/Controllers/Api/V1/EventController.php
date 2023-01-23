<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Event;
use App\Models\User;
use App\Models\Reserve;


use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Http\Resources\ErrorResource;
use Symfony\Component\HttpFoundation\Response;




class EventController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = Event::where('user_id', auth()->user()->id)->get();

        foreach($events as $event){
            $event['timeSteps'] = timeSteps($event['start_date'],$event['end_date'], $event['start_time'], $event['end_time'],$event['duration']);
        }
        return EventResource::collection($events);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreEventRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEventRequest $request)
    {
        $data = $request->all();
        $data['user_id'] =  auth()->user()->id;
        $event = Event::create($data);
        return EventResource::make($event);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        if($event['user_id'] === auth()->user()->id){
            $event['timeSteps'] = timeSteps($event['duration'], $event['start_time'], $event['end_time']);
            return EventResource::make($event);
        }else{
            return new ErrorResource(Response::HTTP_NOT_FOUND, 'Event not found', 'NOT_FOUND');

        }
    }

       /**
     * Display the specified resource.
     * @param   $user_id
     * @param   $event
     * @return \Illuminate\Http\Response
     */
    public function userEvent($user_slug, $event_slug)
    {
        $eventId = Event::where('slug', $event_slug)->value('id');
        $reserve = Reserve::where('event_id', $eventId)->orderBy('date')->get(['date','start_time','end_time'])->toArray();
        // dd($reserve);
        $user = User::where('slug',$user_slug)->with(['events' => function($q) use($event_slug){
                 $q->where('slug', $event_slug)->get();
             }])->first();

        if($user && isset($user->events) && count($user->events) > 0){
            $event = $user->events[0];
            $event['timeSteps'] = timeSteps($event['start_date'], $event['end_date'], $event['start_time'], $event['end_time'], $event['duration'], $reserve);
            return EventResource::make($event->load('manger'));
        }else{
            return new ErrorResource(Response::HTTP_NOT_FOUND, 'Event not found', 'NOT_FOUND');

        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEventRequest  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        //
    }

}
