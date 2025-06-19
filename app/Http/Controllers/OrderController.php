<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderBySupplierRequest;
use App\Models\Finanse;
use App\Models\InsuranceAccountJournal;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Models\Supplier;
use App\Models\Driver;
use App\Models\Menedger;
use App\Models\Ride;
use Illuminate\Http\Request;
use DB;

class OrderController extends Controller
{
    public function indexbymanadger()
    {

        $user = Auth::user();
        $menedger = (Menedger::where('user_id', $user->id)->get())[0];

        $station = $menedger->station;

        $drivers = Driver::where('station_id', $station->id)->where('status', 'Відпочиває')->get();
        $drivers_indexes = [];
        foreach ($drivers as $driver) {
            array_push($drivers_indexes, $driver->id);
        }

        $drivers_on_station = Driver::where('current_station', $menedger->station_id)
            ->whereNotIn('id', $drivers_indexes)
            ->where('status', 'Відпочиває')
            ->get();
            $drivers_on_station_res=[];
            foreach ($drivers_on_station as $driver) {
                if(!DB::table('rides')->where('status','Призначено')->Where('driver_id',$driver->id)->exists()){
                    $drivers_on_station_res[]=$driver;
                }
            }
            $drivers_on_station=$drivers_on_station_res;
        //dd($drivers,$drivers_on_station);
        $orders = Order::where('status', 'Створено')->orderBy('point_A','asc')->get();
        foreach ($orders as $order) {
            $order->auto_type_value = match ($order->auto_type) {
                'Цистерна' => 'A',
                'Рефрежиратор' => 'B',
                'Контейнер' => 'C',
                default => '',
            };

            if (is_null($order->vutratu)) {
                $distance_km = $order->distance / 1000;
                $weight_factor = (0.01 * $order->weigth) / 100 + 1;
                $new_vutratu = round($distance_km * 5.1 * 1.5 * $weight_factor);
                $order->vutratu = $new_vutratu;
                Order::find($order->id)->update([
                    'vutratu' => $new_vutratu
                ]);
            }
        }

        return view("menedgers.orders", compact(['orders', 'drivers', 'drivers_on_station', 'station']));
    }

    public function removebymanadger($id)
    {
        Order::find($id)->update([
            "status" => "Відмовлено"
        ]);
        return redirect('/menedger/dashboard/orders');
    }

    public function indexbydriver()
    {
        $user = Auth::user();
        $driver = (Driver::where('user_id', $user->id)->get())[0];
        $rides = Ride::where('driver_id', $driver->id)->where('status', "Виконано")->get(); //$driver->rides;
        $new_rides = [];
        // dd($rides);
        foreach ($rides as $ride) {
            $points = json_decode($ride->points, true);
            if (isset($points[0]['title'])) {
                $routes = [];
                foreach ($points as $point) {
                    array_push($routes, $point['title']);
                }
                $ride->routes = implode(',', $routes);
                $ride->pointA = $routes[0];
                $ride->pointB = $routes[count($routes) - 1];
                array_push($new_rides, $ride);
            }
        }
        $rides = $new_rides;
        foreach ($rides as $ride) {
            $ride->orders = Order::where('ride_id', $ride->id)->get();
        }
        //dd($rides);
        return view("drivers.orders", compact(['rides', 'driver']));
    }

    public function indexbysupplier()
    {
        $user = Auth::user();
        $supplier = (Supplier::where('user_id', $user->id)->get())[0];
        $orders = Order::where('supplier_id', $supplier->id)->orderBy('status', 'desc')->orderBy('date_of_start', 'desc')->get();
        return view("suppliers.orders", compact('orders'));
    }

    public function show($id)
    {
        return Order::find($id);
    }

    public function showbydriver()
    {
        $user = Auth::user();
        $driver = (Driver::where('user_id', $user->id)->get())[0];
        //dd($supplier->orders);
        //find
        $orders = Order::where([
            ['driver_id', '=', $driver->id],
            ['status', '!=', 'Доставлено'],
            ['status', '!=', 'Скасовано'],
        ])->get();
        $order = Count($orders) != 0 ? $orders[0] : null;
        //dd($order);
        return view("drivers.order", compact(['order', 'driver']));
    }

    public function showbysupplier($id)
    {
        $user = Auth::user();
        $supplier = (Supplier::where('user_id', $user->id)->get())[0];
        $order = Order::find($id);
        return view("suppliers.order", compact(['supplier', 'order']));
    }

    public function createbysupplier()
    {
        $user = Auth::user();
        $supplier = (Supplier::where('user_id', $user->id)->get())[0];
        $suppliers = Supplier::all();
        return view("suppliers.order_add", compact(['supplier', 'suppliers']));
    }

    public function editbysupplier($id)
    {
        $user = Auth::user();
        $supplier = (Supplier::where('user_id', $user->id)->get())[0];
        $suppliers = Supplier::all();
        $order = Order::find($id);
        return view("suppliers.order_edit", compact(['supplier', 'suppliers', 'order']));
    }

    public function storebysupplier(OrderBySupplierRequest $request)
    {
        // dd($request);
        $product = $request->product;
        $weigth = $request->weigth;
        $car_type = $request->car_type;
        $point_A = $request->point_A;
        $point_B = $request->point_B;
        $date_of_start = $request->date_of_start;
        $distance = $request->distance;
        $vutratu = $request->vutratu;

        $user = Auth::user();
        $supplier = (Supplier::where('user_id', $user->id)->get())[0];

        $supplier_id = $supplier->id;
        $order=Order::create([
            'product' => $product,
            'weigth' => $weigth,
            'status' => "Створено",
            'auto_type' => $car_type,
            'point_A' => $point_A,
            'point_B' => $point_B,
            'date_of_start' => $date_of_start,
            'supplier_id' => $supplier_id,
            'distance' => $distance,
            'vutratu' => $vutratu,

        ]);



        $old_sum = InsuranceAccountJournal::latest()->first();

        $data = [
            "current_sum" => $old_sum->current_sum + $vutratu * 0.05,
            "user_id" => $user->id,
        ];
        InsuranceAccountJournal::create($data);

        return redirect('/supplier/dashboard/orders');
    }
    public function updatebysupplier($id, OrderBySupplierRequest $request)
    {
        $product = $request->product;
        $weigth = $request->weigth;
        $car_type = $request->car_type;
        $point_A = $request->point_A;
        $point_B = $request->point_B;
        $date_of_start = $request->date_of_start;

        Order::find($id)->update([
            'product' => $product,
            'weigth' => $weigth,
            'auto_type' => $car_type,
            'point_A' => $point_A,
            'point_B' => $point_B,
            'date_of_start' => $date_of_start,

        ]);

        return redirect('/supplier/dashboard/orders');
    }

    public function delete($id)
    {
        Order::where('id', $id)->delete();
        return redirect('/supplier/dashboard/orders');
    }
}
