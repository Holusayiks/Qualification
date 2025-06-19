<?php

namespace App\Http\Controllers;

use App\Http\Requests\MenedgerRequest;
use App\Models\Driver;
use App\Models\InsuranceAccountJournal;
use App\Models\Menedger;
use App\Models\Order;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenedgerController extends Controller
{

    public function show()
    {
        //get manadger
        $user = Auth::user();
        $manadger = (Menedger::where('user_id', $user->id)->get())[0];
        $drivers = Driver::where('station_id', $manadger->station_id)->get();
        $driver_id = [];
        foreach ($drivers as $driver) {
            array_push($driver_id, $driver->id);
        }
        $transdate = date('M Y');

        $timestamp = strtotime($transdate);
        $first_day = date('Y-m-01', $timestamp);
        $last_day = date('Y-m-t', $timestamp);

        // get orders
        $orders = [];
        $orders_ = DB::select("SELECT * from orders WHERE status!='Скасовано' AND ride_id IN (SELECT id FROM rides WHERE station_start_id=?) AND date_of_start BETWEEN ? AND ? ORDER BY date_of_start desc", [$manadger->station_id, $first_day, $last_day]);
        foreach ($orders_ as $order) {
            $orders[] = Order::find($order->id);
        }

        //get insurens
        $insurance = InsuranceAccountJournal::latest()->first();
        $insurance = $insurance->current_sum;
        return view("menedgers.index", compact(["manadger", "orders", "insurance"]));
    }

    public function edit($id)
    {
        $user = Auth::user();
        $menedger = (Menedger::where('user_id', $user->id)->get())[0];
        return view("menedgers.edit", compact("menedger"));
    }

    public function update(MenedgerRequest $request, $id)
    {
        $full_name = $request->full_name;
        $phone = $request->phone;

        Menedger::find($id)->update([
            'full_name' => $full_name,
            'phone' => $phone,

        ]);

        return redirect('/menedger/dashboard/');
    }

    public function fanansejournal()
    {

        $journal = InsuranceAccountJournal::select('*')->orderBy('id', 'desc')->get();
        for ($i = 0; $i < count($journal); $i++) {
            $change = 0;
            if ($i == (count($journal)-1))
                $change = $journal[$i]->current_sum;
            else
                $change = $journal[$i]->current_sum - $journal[$i + 1]->current_sum;

            $journal[$i]->change =  round($change);
            if ($change < 0)
                $journal[$i]->type = 'minus';
            else
                $journal[$i]->type = 'plus';

            if($i == count($journal)-1) $journal[$i]->type = 'plus';
        }
        return view("menedgers.journal", compact(['journal']));
    }
    public function delete($id)
    {
        Menedger::where('id', $id)->delete();
        return redirect('/dispetcher/dashboard');
    }
}
