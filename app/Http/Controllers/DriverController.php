<?php

namespace App\Http\Controllers;

use App\Http\Requests\DriverRequest;
use App\Http\Requests\VacationRequest;
use App\Models\Dispetcher;
use App\Models\Driver;
use App\Models\Ride;
use App\Models\Order;
use App\Models\Auto;
use App\Models\Menedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DriverController extends Controller
{

    public function indexbymanadger()
    {

        $user = Auth::user();
        $manadger = (Menedger::where('user_id', $user->id)->get())[0];

        $drivers = Driver::select('*')->where('station_id', '=', $manadger->station_id)
            ->orderBy('status', 'desc')->get();
        return view("menedgers.drivers", compact('drivers'));
    }

    public function indexbydispatcher()
    {

        $user = Auth::user();
        $dispatcher = (Dispetcher::where('user_id', $user->id)->get())[0];

        $autos = Auto::select('*')->where('driver_id', null)->get();
        $drivers = Driver::select('*')
            ->where('station_id', '=', $dispatcher->station_id)
            ->orderBy('status', 'desc')
            ->get();

        return view("dispetchers.drivers", compact(['drivers', 'autos']));
    }

    public function invacationbydispatcher()
    {
        $user = Auth::user();
        $dispatcher = (Dispetcher::where('user_id', $user->id)->get())[0];

        $drivers = Driver::select('*')
            ->where('station_id', '=', $dispatcher->station_id)
            ->where('status', '=', 'В відпусці')
            ->orderBy('status', 'desc')
            ->get();

        return view("dispetchers.drivers_in_vacation", compact('drivers'));
    }

    public function show()
    {
        $user = Auth::user();
        $driver = (Driver::where('user_id', $user->id)->get())[0];

        $message = "";
        if ($driver->status == "Відмовлено у відпусці") {
            $message = "У надані відпустки було відмовлено!";
        }

        return view("drivers.index", compact(["driver", "message"]));
    }

    public function messagereaded()
    {
        $user = Auth::user();
        $driver = (Driver::where('user_id', $user->id)->get())[0];
        $driver->update([
            'status' => "Відпочиває",
        ]);
        return redirect('/driver/dashboard');
    }

    public function vacantioncancel()
    {
        $user = Auth::user();
        $driver = (Driver::where('user_id', $user->id)->get())[0];
        $driver->update([
            'status' => "Відпочиває",
            'days_in_vacation' => null,
            'date_of_vacation' => null,
        ]);
        return redirect('/driver/dashboard');
    }

    public function edit($id)
    {
        $driver = Driver::find($id);
        return view("drivers.edit", compact("driver"));
    }

    public function update(DriverRequest $request, $id)
    {
        $full_name = $request->name;
        $phone = $request->phone;

        Driver::find($id)->update([
            'full_name' => $full_name,
            'phone' => $phone,
        ]);

        return redirect('/driver/dashboard');
    }

    public function status(VacationRequest $request)
    {
        $user = Auth::user();
        $driver = (Driver::where('user_id', $user->id)->get())[0];

        $days_in_vacation = $request->days_in_vacation;
        $date_of_vacation = $request->date_of_vacation;


        $driver->update([
            'status' => "Хочу в відпустку",
            'days_in_vacation' => $days_in_vacation,
            'date_of_vacation' => $date_of_vacation,
        ]);
        // dd($driver);
        return redirect('/driver/dashboard');
    }
    public function statusbydispatcher($driver_id, $anwser)
    {
        $driver = Driver::find($driver_id);

        if ($anwser == "yes") {
            $driver->update([
                'status' => "В відпусці",
            ]);
        } else if ($anwser == "no") {
            $driver->update([
                'status' => "Відмовлено у відпусці",
                'days_in_vacation' => null,
                'date_of_vacation' => null,
            ]);
        } else if ($anwser == "returnfromvacation") {
            $driver->update([
                'status' => "Відпочиває",
                'days_in_vacation' => null,
                'date_of_vacation' => null,
            ]);
            return redirect('/dispetcher/dashboard/invacation');
        }

        return redirect('/dispetcher/dashboard');
    }
    public function showapi($id)
    {
        $driver = Driver::find($id);
        $driver->auto_id = $driver->auto != null ? $driver->auto->id : null;
        return $driver;
    }
    public function atstation($id)
    {
        Driver::find($id)->update([
            'status' => "Відпочиває",
            'accident_type' => null,
        ]);
        return redirect('/dispetcher/dashboard/drivers');
    }
    public function accident($type)
    {
        // dd($type);
        $user = Auth::user();
        $driver = (Driver::where('user_id', $user->id)->get())[0];
        $ride = (Ride::where('driver_id', $driver->id)->whereIn('status', ['Призначено', 'Прийнято', 'Аварійна ситуація'])->get())[0];
        $order_id = null;
        $points = json_decode($ride->points, true);
        foreach ($points as $point) {
            if ($point['index'] == $ride->current_point) $order_id = $point['id'];
        }
        $driver->update([
            'status' => "Аварійна ситуація",
            'accident_type'=>$type
        ]);
        // $old_vutratu=$ride->vutratu;
        $ride->update([
            'status' => "Аварійна ситуація"
        ]);
        if ($order_id != null) {
            Order::find($order_id)->update([
                'status' => "Аварійна ситуація"
            ]);
        }

        return redirect('/driver/dashboard/orders/show');
    }

    public function disableaccident()
    {
        $user = Auth::user();
        $driver = (Driver::where('user_id', $user->id)->get())[0];
        $ride = (Ride::where('driver_id', $driver->id)->whereIn('status', ['Призначено', 'Прийнято', 'Аварійна ситуація'])->get())[0];
        $order_id = null;
        $points = json_decode($ride->points, true);
        foreach ($points as $point) {
            if ($point['index'] == $ride->current_point) $order_id = $point['id'];
        }
        $driver->update([
            'status' => "В дорозі"
        ]);
        $ride->update([
            'status' => "Прийнято"
        ]);
        if ($order_id != null) {
            Order::find($order_id)->update([
                'status' => "Прийнято"
            ]);
        }
        return redirect('/driver/dashboard/orders/show');
    }

    public function ridestart(){
        $user = Auth::user();
        $driver = (Driver::where('user_id', $user->id)->get())[0];
        $ride = (Ride::where('driver_id', $driver->id)->whereIn('status', ['Призначено', 'Прийнято', 'Аварійна ситуація'])->get())[0];
        $order_id = null;
        $points = json_decode($ride->points, true);
        foreach ($points as $point) {
            if ($point['index'] == $ride->current_point) $order_id = $point['id'];
        }
        $driver->update([
            'status' => "В дорозі"
        ]);

        return redirect('/driver/dashboard/orders/show');
    }

    public function delete($id)
    {
        Driver::where('id', $id)->delete();
        return redirect()->route('dashboard');
    }
}
