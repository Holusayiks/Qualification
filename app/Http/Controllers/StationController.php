<?php

namespace App\Http\Controllers;

use App\Models\Auto;
use App\Models\Driver;
use App\Models\Order;
use App\Models\Ride;
use App\Models\Station;
use DB;
use Illuminate\Http\Request;

class StationController extends Controller
{
    public function indexbymanadger()
    {
        $stations = Station::all();
        return view('menedgers.stations', compact('stations'));
    }
    public function indexbydispatcher()
    {
        $stations = Station::all();
        return view('dispetchers.stations', compact('stations'));
    }
    public function index()
    {
        $stations=Station::all();
        return json_encode($stations);
    }

    function drivers($id,$type)
    {
        // Вибірка водіїв зі станції $id з авто потрібного типу
        $drivers = Driver::with('auto')
        ->where("station_id", $id)
        ->where("status", "Відпочиває")
        ->get()
        ->filter(function ($driver) use ($type) {
            return $driver->auto && $driver->auto->type == $type;
        })
        ->values();

        // id цих водіїв
        $driversIds = $drivers->pluck('id');

        // Вибірка інших водіїв на станції, яких немає в попередньому списку
        $drivers_on_station = Driver::with(['auto', 'station'])
        ->where("current_station", $id)
        ->where("status", "Відпочиває")
        ->whereNotIn('id', $driversIds)
        ->get();

        // Опціонально: якщо потрібні тільки ті, в кого авто певного типу:
        $drivers_on_station_filtered = $drivers_on_station->filter(function ($driver) use ($type) {
        return $driver->auto && $driver->auto->type == $type;
        })->values();

        $data = [
        "drivers" => $drivers,
        "drivers_on_station" => $drivers_on_station_filtered
        ];

        return response()->json($data);
    }

    public function autodata($id)
    {
        $station = Station::find($id);

        $auto_with_drivers = Auto::where('station_id',$station->id)->where("driver_id", "!=", null)->get();
        $auto_at_home = [];
        $auto_in_ride = [];
        foreach ($auto_with_drivers as $auto) {
            if ($auto->driver->status == "Відпочиває") array_push($auto_at_home, $auto);
            else if ($auto->driver->status != "Відпочиває") array_push($auto_in_ride, $auto);
        }

        $auto_without_drivers = Auto::where('station_id',$station->id)->where("driver_id", "=", null)->get();

        $data = [
            "series" => [count($auto_at_home), count($auto_in_ride), count($auto_without_drivers)],
            "labels" => ["Вільні водії", "В дорозі", "Без водія"],
        ];

        return json_encode($data);
    }

    public function finanse($id)
    {
        $transdate = date('M Y');

        $timestamp = strtotime($transdate);
        $first_day = date('01-m-Y', $timestamp);
        $last_day  = date('t-m-Y', $timestamp);

        $rides_in_current_month = Ride::whereBetween('date', [$first_day, $last_day])->get();
        $v = 0;
        $p = 0;
        foreach ($rides_in_current_month as $ride_in_current_month) {
            $v += $ride_in_current_month->vutratu-( $ride_in_current_month->vutratu * 0.4);
            $p += $ride_in_current_month->vutratu * 0.4;
        }

        $data = ["message" => "Замовлень в цьому місяці не було"];
        if (count($rides_in_current_month) != 0) {
            $data = [
                "series" => [$v, $p],
                "labels" => ["Витрати", "Прибуток"],
            ];
        }

        return json_encode($data);
    }

    public function finanse2($id)
    {
        $transdate = date('M Y');

        $months = [];

        for ($i = 0; $i < 6; $i++) {

            $timestamp = strtotime($transdate);
            $first_day = date('Y-m-01 00:00:00', $timestamp);
            $last_day  = date('Y-m-t 00:00:00', $timestamp);

            $title = date('M', $timestamp);
            array_unshift($months, [$title, $first_day, $last_day]);
            //next
            $transdate =  date('M Y', strtotime('-' . $i + 1 . ' months'));
        }

        $param1 = [];
        $param2 = [];
        $param3 = [];
        $labels = [];

        foreach ($months as $month) {
            $p1= DB::select('SELECT COUNT(id) as count from orders WHERE status!="Скасовано" AND date_of_start BETWEEN ? AND ?', [$month[1], $month[2]])[0]->count;
            $p2= DB::select('SELECT COUNT(id) as count from orders WHERE status="Скасовано" AND date_of_start BETWEEN ? AND ?', [$month[1], $month[2]])[0]->count;
            $p3 = $p1 + $p2;

            array_push($param1, $p1);
            array_push($param2, $p2);
            array_push($param3, $p3);
            array_push($labels, $month[0]);
        }

        $data = [
            "param1" => $param1, //доставлено
            "param2" =>  $param2, //скасовано
            "param3" =>  $param3, //всього
            "labels" => $labels,
        ];
        // dd($data);

        return json_encode($data);
    }
}
