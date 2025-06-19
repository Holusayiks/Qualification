<?php

namespace App\Http\Controllers;

use App\Http\Requests\AutoRequest;
use App\Models\auto;
use App\Models\Dispetcher;
use App\Models\Driver;
use App\Models\Menedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AutoController extends Controller
{
    public function indexbymanadger()
    {

        $user = Auth::user();
        $manadger = (Menedger::where('user_id', $user->id)->get())[0];

        $autos =Auto::select('*')->where('station_id','=',$manadger->station_id)->orderBy('status','desc')->get();

        return view("menedgers.autos", compact('autos'));
    }
    public function indexbydispatcher(){
        $user=Auth::user();
        $dispatcher=(Dispetcher::where('user_id',$user->id)->get())[0];
        $autos=$dispatcher->station->autos;
        return view('dispetchers.autos',compact('autos'));
    }
    public function delete($id)
    {
        Auto::where('id', $id)->delete();
        return redirect('/dashboard');
    }

    public function showapi($id)
    {
        $auto=Auto::find($id);
        return json_encode($auto);
    }

    public function changedriver( $driver,$auto)
    {
        if ($auto == "n") {
            $driver_=Driver::find($driver);

            $auto_=$driver_->auto;
            Auto::find($auto_->id)->update([
                "driver_id" => null
            ]);
        } else {
            Auto::find($auto)->update([
                "driver_id" => $driver
            ]);
        }
        return redirect('/dispetcher/dashboard/drivers');
    }

    public function createbydispetcher()
    {
        return view('dispetchers.auto_create');
    }

    public function storebydispetcher(AutoRequest $request)
    {
        $user=Auth::user();
        $station=(Dispetcher::where('user_id',$user->id)->get())[0]->station;

        Auto::create([
            'nomer' => $request->nomer,
            'type' => $request->type,
            'lifting_weight' =>  $request->lifting_weight,
            'station_id' => $station->id,
        ]);
        return redirect('/dispetcher/dashboard/autos');
    }
}
