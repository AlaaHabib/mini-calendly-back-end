<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SuccessResource;
use Symfony\Component\HttpFoundation\Response;

use App\Helpers\ZoomApiHelper;
use Illuminate\Http\Request;
use App\Models\Reserve;
use App\Models\Event;
use Mail;



class ReserveController extends Controller
{
    function store(Request $request){
        $input = $request->all();
        $input['event_id'] = Event::where('slug', $input['event_slug'])->value('id');
        $zoom = ZoomApiHelper::createZoomMeeting();
        if($zoom)
        $input['link'] =  $zoom['response']->join_url;
        $details = [
            'title' => 'Mail from Calendly.com',
            'body' => 'This is zoom meeting link' +$input['link']
        ];
       
        \Mail::to('your_receiver_email@gmail.com')->send(new \App\Mail\MyTestMail($details));
       
        Reserve::create($input);
        return new SuccessResource(Response::HTTP_OK, 'Slut reserved successfully');
    }
}
