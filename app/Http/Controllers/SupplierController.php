<?php

namespace App\Http\Controllers;
use App\Models\Supplier;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function show()
    {
        $user=Auth::user();
        $supplier=(Supplier::where('user_id',$user->id)->get())[0];
        $orders=Order::where('supplier_id',$supplier->id)->limit(3)->get();
        return view("suppliers.index",compact(['supplier','orders']));

    }

    public function edit($id)
    {
        $user=Auth::user();
        $supplier=(Supplier::where('user_id',$user->id)->get())[0];
        return view("suppliers.edit",compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);
        Supplier::find($id)->update([
            'full_name'=>$request->full_name,
            'phone'=>$request->phone,
            'address'=>$request->address,
        ]);
        return redirect('/supplier/dashboard');
    }

    public function delete($id)
    {
        Supplier::where('id', $id)->delete();
        return redirect('/supplier/dashboard');
    }
}
