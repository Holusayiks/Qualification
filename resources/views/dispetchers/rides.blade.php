@extends('layouts.main')
@section('title','Замовлення')


@section('navlinks')
@include('navigations.dispatcher')
@endsection
@section('responsive_navlinks')
@include('responsive_navigations.dispatcher')
@endsection

@section('content')
@if(count($rides)!=0)
<table class="w-full">
    <thead>
        <tr>
            <th class="border border-slate-300 text-center p-2">Статус</th>
            <th class="border border-slate-300 text-center p-2">Дата</th>
            <th class="border border-slate-300 text-center p-2">Замовлення</th>
            <th class="border border-slate-300 text-center p-2">Замовник</th>
            <th class="border border-slate-300 text-center p-2">Адреса поч.точки</th>
            <th class="border border-slate-300 text-center p-2">Адреса кін.точки</th>
            <th class="border border-slate-300 text-center p-2">Водій</th>
            <th class="border border-slate-300 text-center p-2">Дії</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rides as $ride)
        <tr>
            <?php
            $status_style = '';
            if ($ride->status == 'Виконано') {
                $status_style = 'text-green-500 bg-green-100';
            } else if ($ride->status == 'Аварійна ситуація' || $ride->status == 'Відмовлено водієм') {
                $status_style = 'text-rose-500 bg-rose-100';
            }
            ?>

            <td rowspan="{{count($ride->orders)}}" class="border border-slate-300 text-center p-2 {{$status_style}}">{{ $ride->status }}
                @if(!is_null($ride->old))
                 #{{$ride->old->station->nomer}} ({{$ride->old->manadger->phone}})
                 @endif
                </td>
            <td rowspan="{{count($ride->orders)}}" class="border border-slate-300 text-center p-2"><small>{{ date('d.m.Y',strtotime($ride->date)) }}</small></td>

            <td class="border border-slate-300 text-center p-2">
                {{ $ride->orders[0]->product }}<br><small> ({{ date('d.m.Y',strtotime($ride->orders[0]->date_of_start)) }})</small>
            </td>
            <td class="border border-slate-300 text-center p-2">
                {{ $ride->orders[0]->supplier->full_name }} <i class="font-thin">({{ $ride->orders[0]->supplier->phone }})</i>
            </td>
            <td class="border border-slate-300 text-center p-2">
                {{ $ride->orders[0]->point_A}}
            </td>
            <td class="border border-slate-300 text-center p-2">
                {{ $ride->orders[0]->point_B}}
            </td>
            <td rowspan="{{count($ride->orders)}}" class="border border-slate-300 text-center p-2">
                @if ($ride->driver!=null)
                {{$ride->driver->full_name}} ({{$ride->driver->auto->type}})
                @else
                {{' - '}}
                @endif
            </td>
            <td rowspan="{{count($ride->orders)}}" class="border border-slate-300 text-center p-2">
                @if ($ride->status=='Відмовлено водієм')
                <button onclick="ChangeDriver('{{$ride->orders[0]->auto_type}}','{{$ride->id}}')" class="btn bg-red-400 m-2 p-1 rounded-sm text-white">Назначити водія</button>
                @elseif($ride->status=='Призначено')
                <button onclick="ChangeDriver('{{$ride->orders[0]->auto_type}}','{{$ride->id}}')" class="btn bg-blue-400 m-2 p-1 rounded-sm text-white">Змінити водія</button>
                @endif
                @if ($ride->current_point==count(json_decode($ride->points))&&$ride->status!='Виконано'&&$ride->driver->current_station==$current_station)
                <button onclick="DriverComeToStation('{{$ride->id}}')" class="btn bg-green-400 m-2 p-1 rounded-sm text-white">Водій прибув</button>
                @endif
            </td>

        </tr>
        @if(count($ride->orders)>1)
        @foreach($ride->orders as $key=>$order)
        @if($key!=0)
        <tr>
            <td class="border border-slate-300 text-center p-2">
                {{ $order->product }}<br><small> ({{ date('d.m.Y',strtotime($order->date_of_start)) }})</small>
            </td>
            <td class="border border-slate-300 text-center p-2">
                {{ $order->supplier->full_name }} <i class="font-thin">({{ $order->supplier->phone }})</i>
            </td>
            <td class="border border-slate-300 text-center p-2">
                {{ $order->point_A }}
            </td>
            <td class="border border-slate-300 text-center p-2">
                {{ $order->point_B }}
            </td>
        </tr>
        @endif
        @endforeach
        @endif
        @endforeach
    </tbody>
</table>
@else
<div class="flex justify-center flex-col items-center">
    <img class="opacity-5 h-16 w-16" src="{{ url('images/empty_history.png') }}" alt="empty_box">
    <h2 class="opacity-25 ">Історія замовлень пуста</h2>

</div>
@endif
@endsection

@section('script')
<script>
    function DriverComeToStation(ride_id) {
        location.href = '/dispetcher/dashboard/rides/' + ride_id + '/end';
    }

    function ChangeDriver(auto_type, ride_id) {
        OpenModal();
        console.log(ride_id);
        document.getElementById("change_driver").action = '/dispetcher/dashboard/rides/' + ride_id + '/changedriver';
        document.getElementById('modal_auto_type').innerText = auto_type;
    }
    //MODAL
    let openModal = false;
    let modalId = 'modalChangeDriver';

    function OpenModal() {
        document.getElementById(modalId).classList.remove('hidden');
        openModal = !openModal;
    }

    function CloseModal() {
        document.getElementById(modalId).classList.add('hidden');
        openModal = !openModal;
    }
</script>
@endsection

@section('modal')<!-- Main modal -->
<div id="modalChangeDriver" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-50 flex justify-center items-center bg-gray-500 bg-opacity-50">
    <div class="relative p-4 w-full max-w-full h-full max-h-full bg-white">

        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Назначення водія
                </h3>
                <button onclick="CloseModal()" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <form action="/dashboard/rides/null/changedriver" method="POST" id="change_driver">
                @csrf
                <div class="p-4 md:p-5 space-y-4" id="modalContent">
                    <p>Тип авто: <small id="modal_auto_type"></small></p>
                    <label for="driver_id" class="block text-sm font-medium leading-6 text-gray-900">Водій:</label>
                    <select class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" id="driver_id" name="driver_id">
                        <option value="">Оберіть</option>
                        @foreach ($drivers as $driver)
                        @if($driver->auto!=null)
                        <option value="{{$driver->id}}">{{$driver->full_name}} ({{$driver->auto->type}})</option>
                        @endif
                        @endforeach
                    </select>

                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li class="bg-rose-50 text-lg text-center text-rose-500 border-rose-500 border-2 m-3 w-1/4 p-2 rounded-lg">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Зберегти</button>
                    <button onclick="CloseModal()" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Повернутись</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
