@extends('layouts.main')
@section('title','Замовлення')


@section('navlinks')
@include('navigations.manadger')
@endsection
@section('responsive_navlinks')
@include('responsive_navigations.manadger')
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
            <th class="border border-slate-300 text-center p-2">Статус замовлення</th>
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
            <td rowspan="{{count($ride->orders)}}" class="border border-slate-300 text-center p-2 {{$status_style}}">{{ $ride->status }}</td>
            <td rowspan="{{count($ride->orders)}}" class="border border-slate-300 text-center p-2"><small>{{ date('d.m.Y',strtotime($ride->date)) }}</small></td>
            <td class="border border-slate-300 text-center p-2">
                {{ $ride->orders[0]->product }}<br><small> ({{ date('d.m.Y',strtotime($ride->orders[0]->date_of_start)) }})</small>
            </td>
            <td class="border border-slate-300 text-center p-2">
                {{ $ride->orders[0]->supplier->full_name }}  <br><i class="font-thin">({{ $ride->orders[0]->supplier->phone }})</i>
            </td>
            <td class="border border-slate-300 text-center p-2 {{$ride->orders[0]->status=='Доставлено'?'text-green-500 bg-green-100':''}}">
                {{ $ride->orders[0]->status}}
            </td>
            <td rowspan="{{count($ride->orders)}}" class="border border-slate-300 text-center p-2">
                @if ($ride->driver!=null)
                {{$ride->driver->full_name}} <br>({{$ride->driver->auto->type}})
                @else
                {{' - '}}
                @endif
            </td>
            <td rowspan="{{count($ride->orders)}}" class="border border-slate-300 row text-sm p-2">
                @if($ride->status=='Призначено')
                {{-- <button onclick="ChangeStation('{{$ride->id}}')" class="btn bg-pink-400 m-1 p-1 rounded-sm text-white">Перенаправити замовлення</button> --}}
                @elseif ($ride->status=='Перенаправлено')
                {{-- <button onclick="AcceptRide('{{$ride->id}}')" class="btn bg-pink-400 m-1 p-1 rounded-sm text-white">Прийняти</button> --}}
                @endif
                @if($ride->status=='Призначено'||$ride->status=='Прийнято')
                <?php
                $dates_data = [];
                foreach ($ride->orders as $key => $order_) {
                    array_push($dates_data, [
                        "label" => $order_->product,
                        "supplier_full_name" => $order_->supplier->full_name,
                        "supplier_phone" => $order_->supplier->phone,
                        "date" =>date('Y-m-d',strtotime( $order_->date_of_start))
                    ]);
                }
                $dates_data_ = json_encode($dates_data);
                ?>
                <button onclick="ChangeDates('{{$ride->id}}','{{$dates_data_}}')" class="btn bg-green-400 m-1 p-1 rounded-sm text-white">Переобрати дати</button>
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
            <td class="border border-slate-300 text-center p-2 {{$order->status=='Доставлено'?'text-green-500 bg-green-100':''}}">
                {{ $order->status }}
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
    function AcceptRide(ride_id){
        //TODO: перерахувати маршрут
        location.href = '/menedger/dashboard/rides/'+ride_id+'/accept';
    }

    function ChangeStation(ride_id) {
        OpenModal('modalChangeStation');
        document.getElementById("change_station").action = '/menedger/dashboard/rides/' + ride_id + '/changestation';
    }

    function ChangeDates(ride_id, data) {
        OpenModal('modalChangeDates');
        document.getElementById("change_dates").action = '/menedger/dashboard/rides/' + ride_id + '/changedates';
        let modalContentDatesInputs = "";

        JSON.parse(data).forEach((item, index) => {
            modalContentDatesInputs += "<label class='block text-sm font-medium leading-6 text-gray-900' for='date_" + index + "'>" + index+1 + ". " + item.label + " "+item.supplier_full_name+" - (" + item.supplier_phone + ")</label>" +
                "<input value='" + item.date + "' class='block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6' type='date' name='dates[]' id='date_" + index + "'/></br>";
        });
        document.getElementById("modalContentDatesInputs").innerHTML = modalContentDatesInputs;
    }
    //MODAL
    let openModal = false;
    let modalId = "";

    function OpenModal(id) {
        modalId = id
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
<div id="modalChangeStation" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-50 flex justify-center items-center bg-gray-500 bg-opacity-50">
    <div class="relative p-4 w-full max-w-full h-full max-h-full bg-white">

        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Переназначення замовлення
                </h3>
                <button onclick="CloseModal()" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <form action="/dashboard/rides/null/changestation" method="POST" id="change_station">
                @csrf
                <div class="p-4 md:p-5 space-y-4" id="modalContent">
                    <label for="station_id" class="block text-sm font-medium leading-6 text-gray-900">Станція:</label>
                    <select class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" id="station_id" name="station_id">
                        <option value="">Оберіть</option>
                        @foreach ($stations as $station)
                        @if($station->id!=$manadger->station_id)
                        <option value="{{$station->id}}">{{$station->nomer}} ({{$station->address}}) Менеджер: {{$station->menedger->full_name}} ({{$station->menedger->phone}})</option>
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

<div id="modalChangeDates" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-50 flex justify-center items-center bg-gray-500 bg-opacity-50">
    <div class="relative p-4 w-full max-w-full h-full max-h-full bg-white">

        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Переназначення станції
                </h3>
                <button onclick="CloseModal()" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <form action="/dashboard/rides/null/changedates" method="POST" id="change_dates">
                @csrf
                <div class="p-4 md:p-5 space-y-4" id="modalContent">
                    <div id="modalContentDatesInputs"></div>

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
