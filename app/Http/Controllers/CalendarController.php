<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Resource;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index(Request $request)

    {
        if ($request->ajax()) {

            $data = Event::whereDate('start', '>=', $request->start)

                ->whereDate('end',   '<=', $request->end)

                ->get(['id', 'title', 'start', 'end', 'resourceId']);

            return response()->json($data);
        }
        return view('fullcalender');
    }

    public function getResources(Request $request)
    {
        if ($request->ajax()) {

            $data = Resource::all();

            return response()->json($data);
        }
        return view('fullcalender');
    }

    public function getResourceById(Request $request)
    {
        if ($request->ajax()) {

            $data = Resource::find($request->id);

            return response()->json($data);
        }
        return view('fullcalender');
    }

    /**

     * Write code on Method

     *

     * @return response()

     */

    public function ajax(Request $request)
    {

        switch ($request->type) {

            case 'add':

                $event = Event::create([

                    'title' => $request->title,

                    'start' => $request->start,

                    'end' => $request->end,

                    'resourceId' => $request->resourceId,

                ]);

                return response()->json($event);

                break;



            case 'update':

                $event = Event::find($request->id)->update([

                    'title' => $request->title,

                    'start' => $request->start,

                    'end' => $request->end,

                    'resourceId' => $request->resourceId,

                ]);



                return response()->json($event);

                break;



            case 'delete':

                $event = Event::find($request->id)->delete();



                return response()->json($event);

                break;

            default:

                # code...

                break;
        }
    }
}
