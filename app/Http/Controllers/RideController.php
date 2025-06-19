<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Http\Requests\AccidentRequest;
use App\Http\Requests\ChangeDatesRequest;
use App\Http\Requests\ChangeDriverRequest;
use App\Http\Requests\ChangeStationRequest;
use App\Http\Requests\RideRequest;

use App\Models\Ride;
use App\Models\Driver;
use App\Models\Menedger;
use App\Models\Dispetcher;
use App\Models\Order;
use App\Models\Station;
use App\Models\InsuranceAccountJournal;
use function PHPUnit\Framework\isNull;

class RideController extends Controller
{
    public function indexbymanadger()
    {
        $user = Auth::user();
        $manadger = (Menedger::where('user_id', $user->id)->get())[0];
        $statuses = [
            'Аварійна ситуація',
            'Перенаправлено',
            'Прийнято',
            'Призначено',
            'Виконано'
        ];
        $drivers = Driver::where('station_id', $manadger->station_id)->get();
        $rides = Ride::where('station_start_id', $manadger->station->id)->orWhere('station_end_id', $manadger->station->id)->orderBy('date', 'desc')->get();
        $rides = $rides->sortBy(function ($ride) use ($statuses) {
            return array_search($ride->status, $statuses);
        })->values();

        foreach ($rides as $ride) {
            $ride->orders = Order::where('ride_id', $ride->id)->get();
        }

        $drivers = Driver::where('station_id', $manadger->station_id)->where('status', 'Відпочиває')->get();

        $filtered_rides = [];
        foreach ($rides as $ride) {
            if (count($ride->orders) != null) array_push($filtered_rides, $ride);
        }
        $rides = $filtered_rides;
        $stations = Station::all();
        return view("menedgers.rides", compact(['rides', 'drivers', 'stations', 'manadger']));
    }
    public function indexbydispatcher()
    {
        $user = Auth::user();
        $dispatcher = (Dispetcher::where('user_id', $user->id)->get())[0];
        $current_station=$dispatcher->station_id;
        $statuses = [
            'Аварійна ситуація',
            'Перенаправлено',
            'Прийнято',
            'Призначено',
            'Виконано'
        ];
        $rides = Ride::where('station_start_id', $dispatcher->station->id)->orWhere('station_end_id', $dispatcher->station->id)->orderBy('date', 'desc')->get();
        $rides = $rides->sortBy(function ($ride) use ($statuses) {
            return array_search($ride->status, $statuses);
        })->values();
        foreach ($rides as $ride) {
            $ride->orders = Order::where('ride_id', $ride->id)->get();
        }

        $drivers = Driver::where('station_id', $dispatcher->station_id)->where('status', 'Відпочиває')->get();

        $filtered_rides = [];
        foreach ($rides as $ride) {
            if (count($ride->orders) != null) array_push($filtered_rides, $ride);
        }
        $rides = $filtered_rides;
        foreach ($filtered_rides as $key => $ride) {
            if($ride->status=='Перенаправлено'&&!is_null($ride->accident_data)){
                $driver_old=Driver::find($ride->accident_data['old_driver_id']);
                $ride->old->manadger=$driver_old->station->manadger;
                $ride->old->station=$driver_old->station;
            }


        }
        return view("dispetchers.rides", compact(['rides', 'drivers','current_station']));
    }

    public function resolvingaccident($id)
    {
        $user = Auth::user();
        $dispatcher = (Dispetcher::where('user_id', $user->id)->get())[0];

        $driver = Driver::find($id);

        $ride = (Ride::where("driver_id", "=", $id)->where("status", "=", "Аварійна ситуація")->get())[0];

            $ride->current_point =((array)json_decode($ride->points))[$ride->current_point-1];
        return view("dispetchers.resolveaccident", compact(['dispatcher', 'driver', 'ride']));
    }


    public function resolveaccidenthardlevel($id, AccidentRequest $request)
    {


        // читання даних
        $driver_id = $request->driver_id; //переназначити замовлення і підзамовлення
        $station_id = $request->station_id;
        $distance_for_old_driver=round($request->distance_for_old_driver/1000);
        $accident_address = $request->accident_address; //додаток на початок points
        $need_to_get_order = $request->need_to_get_order;
        $new_distanse = $request->new_distance;

        // пошук водія, що
        $driver = Driver::find($id);
        // поїздка, що буде передана новому водію
        $ride = (Ride::where('driver_id', $driver->id)->where('status', 'Аварійна ситуація')->get())[0];
        // ...
        $order_id = null;
        $points = json_decode($ride->points, true);
        foreach ($points as $point) {
            if ($point['index'] >= $ride->current_point){
            Order::find($point['id'])->update([
                'status' => "Призначено",
                'driver_id' => $driver_id,
            ]);
        }
        }

        // створення нової поїздки
        $new_points = [];
        $counter = 0; //щоб знайти не відвезені товари

        foreach ($points as $point) {
            if ($point['type'] > "Забрати") $counter += 1;
            else $counter -= 1;

            if ($point['index'] > $ride->current_point) array_push($new_points, $point);
        }

        // додавання точки на початок массиву, з якої треба забрати замовлення
        if ($need_to_get_order) {
            array_unshift($new_points, [
                "id" => "Аварія",
                "index" => 1,
                "title" => $accident_address,
                "type" => "Забрати",
                "product" => "",

            ]);
        }

        //
        for ($i = 0; $i < count($new_points); $i++) {
            $new_points[$i]['index'] = $i + 1;
        }

        // оновлення даних водія від якого треба забрати замовлення
        $driver->update([
            'status' => "Після аварії",
            "current_station"=>$driver->station_id
        ]);
        // "знімання" авто у водія
        // $driver->auto->update([
        //     'driver_id' => null
        // ]);

        $new_distanse=($new_distanse/1000);
        $new_driver_salary=$new_distanse*11;
        // оновлення даних у поїздки
        $ride->update([
            'status' => "Призначено",
            'station_start_id' => $station_id,
            'current_point' => 0,
            'points' => $new_points,
            'driver_id' => $driver_id,
            'vutratu'=>$new_distanse*5.1,
            'distance'=>$new_distanse,
            'driver_zarplata'=>$new_driver_salary,
            'accident_data'=>[
                "old_driver_id"=>$driver->id,
                "old_vutratu"=>$ride->vutratu,
                "old_distance"=>$ride->distance,
                "old_points"=> $ride->points,
                "point_of_accident"=>$ride->current_point,
                "old_driver_money"=>$ride->driver_zarplata,
            ],
        ]);
        $old_sum=InsuranceAccountJournal::latest()->first();

        $user = Auth::user();
        InsuranceAccountJournal::create([
            "user_id"=>$user->id,
            "current_sum"=>$old_sum->current_sum-$new_driver_salary,
        ]);
        //перехід на наступну сторінку
        return redirect('/dispetcher/dashboard/drivers');
    }


    public function store(RideRequest $request)
    {
        $points = json_decode($request->points);
        $driver_id = $request->driver_id;
        $station_start_address = $request->station_start;
        $station_end_address = $request->station_end;
        $station_start = (Station::where("address", "=", $station_start_address)->get())[0];
        $station_end = (Station::where("address", "=", $station_end_address)->get())[0];
        $distance = $request->distance;
        $vutratu = $request->vutratu;
        $driver_zarplata = $request->driver_zarplata;
        $dohid=$request->vuruchka;
        $chista_vuruchka=$request->chista_vuruchka;
        $weigth = $request->weigth;

        $ride = Ride::create([
            'driver_id' => $driver_id,
            'points' => json_encode($points),
            'status' => "Призначено",
            'weigth' => $weigth,
            'station_start_id' => $station_start->id,
            'station_end_id' => $station_end->id,
            'distance' => $distance,
            'vutratu' => $vutratu,
            'vuruchka' => $chista_vuruchka,
            'driver_zarplata' =>$driver_zarplata,
            'date' => Order::find($points[0]->id)->date_of_start,
        ]);

        $driver = Driver::find($driver_id);
        // $driver->update([
        //     'status' => "В дорозі"
        // ]);
        foreach ($points as $point) {
            $order=Order::find($point->id);
            if($point->type=="Забрати") $order->date_of_start=$point->date_of_start;
            $order->status="Призначено";
            $order->driver_id=$driver_id ;
            $order->auto_id=$driver->auto->id;
            $order->ride_id=$ride->id;
            $order->save();
        }
        return response()->json($ride, 200);
    }

    public function showbydriver()
    {
        //get user
        $user = Auth::user();
        //get driver
        $driver = (Driver::where('user_id', $user->id)->get())[0];
        $rides = Ride::where('driver_id', $driver->id)->whereIn('status', ['Призначено', 'Прийнято', 'Аварійна ситуація'])->get();

        if (count($rides) != 0) {
            $ride = $rides[0];
            $orders = Order::where('ride_id', $ride->id)->get();
            $points = json_decode($ride->points, true);

            $routes = [];
            foreach ($points as $point) {
                array_push($routes, $point["title"]);
            }
            // dd($routes);
            return view("drivers.order", compact(['ride', 'driver', 'orders', 'points', 'routes']));
        } else {
            //get user
            $user = Auth::user();
            //get driver
            $driver = (Driver::where('user_id', $user->id)->get())[0];
            return view("drivers.order", compact(['driver']));
        }
    }

    public function currentroutesbydriver($driver_id)
    {
        //get driver
        $driver = Driver::find($driver_id);
        $ride = (Ride::where('driver_id', $driver->id)->whereIn('status', ['Призначено', 'Прийнято', 'Аварійна ситуація'])->get())[0];
        // $orders = Order::where('ride_id', $ride->id)->get();
        $station_start=$ride->station_start->address;
        $points = json_decode($ride->points, true);
        $routes = [];
        foreach ($points as $point) {
            array_push($routes, $point["title"]);
        }
        $data = [
            'routes' => $routes,
            'station_start'=>$station_start
        ];
        return response()->json($data, 200);
    }

    public function updatepoint($point)
    {
        //get user
        $user = Auth::user();
        //get driver
        $driver = (Driver::where('user_id', $user->id)->get())[0];
        $ride = (Ride::where('driver_id', $driver->id)->whereIn('status', ['Призначено', 'Прийнято', 'Аварійна ситуація'])->get())[0];

        $ride->update([
            'current_point' => $point,
        ]);
        $points = json_decode($ride->points, true);
        foreach ($points as $p) {
            if ($point == $p['index']) {
                if ($p['type'] == 'Привезти') {
                    $order = (Order::where('id', $p['id'])->get())[0];
                    $order->update([
                        'status' => "Доставлено",
                    ]);
                }
            }
        }
        if ($point == 1) {
            $driver->update([
                'status' => "В дорозі",
                'current_station' => null,
            ]);
        } else {
            $driver->update([
                'status' => "В дорозі",
                'current_station' => $ride->station_end_id,
            ]);
        }
        return redirect('/driver/dashboard/orders/show');
    }

    public function endbydispetcher($ride_id){
        $ride = Ride::find($ride_id);
        $driver = $ride->driver;
        $points = json_decode($ride->points, true);
        $point = $ride->current_point;
        if ($point == count($points)) {
            $ride->update([
                'status' => "Виконано",
            ]);
            $driver->update([
                'status' => "Відпочиває",
            ]);
        }
        return redirect('/dispetcher/dashboard/rides');
    }

    public function returnride($ride_id, $driver_id)
    {
        $driver = Driver::find($driver_id);
        $driver->update([
            'status' => "Відпочиває",
        ]);
        $ride = Ride::find($ride_id);
        $ride->update([
            'driver_id' => null,
            'status' => "Відмовлено водієм",
        ]);
        $orders = Order::where("ride_id", $ride_id)->get();
        foreach ($orders as $order) {
            $order->update([
                'driver_id' => null,
            ]);
        }
        return redirect('/menedger/dashboard/rides');
    }

    public function confirmride($ride_id)
    {
        $ride = Ride::find($ride_id);
        $ride->update([
            'status' => "Прийнято",
        ]);
        $orders = Order::where("ride_id", $ride_id)->get();
        foreach ($orders as $order) {
            $order->update([
                'status' => "Прийнято",
            ]);
        }
        return redirect('/driver/dashboard/orders/show');
    }

    public function changedriver($ride_id, ChangeDriverRequest $request)
    {
        $driver_id = $request->driver_id;
        $ride = Ride::find($ride_id);
        $driver_old = $ride->driver;
        //  dd($driver_old);
        if ($driver_old != null) {
            $driver_old->update([
                'status' => 'Відпочиває',
            ]);
        }
        $ride->update([
            'driver_id' => $driver_id,
            'status' => 'Призначено'
        ]);
        $orders = Order::where("ride_id", $ride->id)->get();
        foreach ($orders as $order) {

            $order->update([
                'driver_id' => $driver_id,
            ]);
        }
        $driver = Driver::find($driver_id);
        $driver->update([
            'status' => 'В дорозі',
        ]);
        return redirect('/dispetcher/dashboard/rides');
    }
    public function changestation($ride_id, ChangeStationRequest $request) {

        $station_id=$request->station_id;
        $ride=Ride::find($ride_id);
        $orders=Order::where('ride_id',$ride_id)->get();
        $ride->update([
            'driver_id'=>null,
            'station_start_id'=>$station_id,
            'status'=>'Відмовлено водієм',//TODO: змінити статус на Перенаправлено
        ]);
        foreach ($orders as $key => $order) {
            $order->update([
                'driver_id'=>null,
                'station_id'=>$station_id,
                'status'=>'Перенаправлено',//TODO: змінити статус
            ]);
        };

        return redirect('/dispetcher/dashboard/rides');
    }

    public function changedates($ride_id, ChangeDatesRequest $request)
     {
        $orders=Order::where('ride_id',$ride_id)->get();
        for ($i=0; $i < count($orders); $i++) {
            $orders[$i]->update([
                'date_of_start'=>$request->dates[$i],
            ]);
        };
        Ride::find($ride_id)->update([
            'date'=>$request->dates[0],
        ]);
        return redirect('/menedger/dashboard/rides');
    }

    public function accept($ride_id)
    {
        $ride=Ride::find($ride_id);
        $ride->update([
            'status'=>'Призначено',
        ]);
        $orders=Order::where('ride_id',$ride_id)->get();
        foreach ($orders as $order) {
            $order->update([
                'status'=>'Відмовлено водієм',//TODO: змінити статус 'Призначено',
            ]);
        }
        return redirect('/dispetcher/dashboard/rides');
    }

    public function finansebymenedger()
    {
        $user = Auth::user();
        $manadger = (Menedger::where('user_id', $user->id)->get())[0];
        $drivers = Driver::where('station_id', $manadger->station_id)->get();

        $drivers_ids = [];
        foreach ($drivers as $key => $driver) {
            array_push($drivers_ids, $driver->id);
        }
        $rides = Ride::where('station_start_id', $manadger->station->id)
            ->orWhere('station_end_id', $manadger->station->id)
            ->orWhereIn('driver_id', $drivers_ids)->orderBy('status')
            ->orderBy('date', 'desc')->get();
            $statuses = [
                'Аварійна ситуація',
                'Перенаправлено',
                'Прийнято',
                'Призначено',
                'Виконано'
            ];
            $rides = $rides->sortBy(function ($ride) use ($statuses) {
                return array_search($ride->status, $statuses);
            })->values();
        foreach ($rides as $ride) {
            $ride->orders = Order::where('ride_id', $ride->id)->get();
        }

        $drivers = Driver::where('station_id', $manadger->station_id)->where('status', 'Відпочиває')->get();

        $filtered_rides = [];
        foreach ($rides as $ride) {
            if (count($ride->orders) != null) array_push($filtered_rides, $ride);
            if(is_null($ride->vuruchka)){
                $v2=0;
                foreach ($ride->orders as $key => $order) {
                   $v2+=$order->vutratu;
                }
                $vuruchka=$v2-$ride->vutratu;
                $ride->vuruchka=$vuruchka;
                Ride::find($ride->id)->update([
                    'vuruchka'=>$vuruchka
                ]);
            }
        }
        $rides = $filtered_rides;
        $stations = Station::all();
        return view("menedgers.finanse", compact(['rides', 'drivers', 'stations', 'manadger']));
    }
}
