<?php

namespace App\Http\Controllers;

use App\Http\Requests\DispetcherRequest;
use App\Models\Auto;
use App\Models\Dispetcher;
use App\Models\Driver;
use App\Models\InsuranceAccountJournal;
use App\Models\Ride;
use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DispetcherController extends Controller
{
    public function show()
    {
        $user=Auth::user();
        $dispatcher=(Dispetcher::where('user_id',$user->id)->get())[0];
        $station=Station::find($dispatcher->station_id);

        $transdate = date('M Y');

        $timestamp = strtotime($transdate);
        $first_day = date('01-m-Y', $timestamp);
        $last_day  = date('t-m-Y', $timestamp);

        $rides_in_current_month = Ride::whereBetween('date', [$first_day, $last_day])->get();
        $v = 0;
        foreach ($rides_in_current_month as $ride_in_current_month) {
            $v += $ride_in_current_month->vutratu;
        }

        $drivers_in_ride_now=count(Driver::where('station_id',$station->id)->whereIn('status',['В дорозі','Аварійна ситуація'])->get());
        $vutratu_by_last_month=$v;
        $accidents_number=count(Driver::where('station_id',$station->id)->where('status','Аварійна ситуація')->get());

        $auto_type=[
            'Цистерна'=>count(Auto::where('station_id',$station->id)->where('type', 'Цистерна')->get()),
            'Рефрежиратор'=>count(Auto::where('station_id',$station->id)->where('type', 'Рефрежиратор')->get()),
            'Контейнер'=>count(Auto::where('station_id',$station->id)->where('type', 'Контейнер')->get()),
        ];

        $insurance=InsuranceAccountJournal::latest()->first();
        $insurance=$insurance->current_sum;
        return view("dispetchers.index",compact(['dispatcher','drivers_in_ride_now','vutratu_by_last_month','accidents_number','auto_type','insurance']));
    }

    public function edit($id)
    {
        $dispatcher=Dispetcher::find($id);
        return view("dispetchers.edit",compact("dispatcher"));
    }

    public function update(DispetcherRequest $request, $id)
    {
        $full_name = $request->full_name;
        $phone = $request->phone;

        Dispetcher::find($id)->update([
            'full_name'=>$full_name,
            'phone'=>$phone,
        ]);

        return redirect('/dispetcher/dashboard');
    }

    public function delete($id)
    {
        Dispetcher::where('id', $id)->delete();
        return redirect('/dispetcher/dashboard');
    }
}
