@extends('layouts.main')
@section('title','Історія замовлень')


@section('navlinks')
@include('navigations.supplier')
@endsection
@section('responsive_navlinks')
@include('responsive_navigations.supplier')
@endsection

@section('before-content')
<button class="btn bg-green-400 m-2 p-2 rounded-lg text-white hover:bg-green-600 transition" onclick="location.href='/supplier/dashboard/orders/add'">Створити замовлення</button>
@endsection


@section('content')
@if(count($orders)!=0)
<table class="border-collapse border-spacing-2 border border-slate-400 w-full text-sm">
    <thead>
        <tr>
            <th class="border border-slate-300">Продукт</th>
            <th class="border border-slate-300">Вага</th>
            <th class="border border-slate-300">Статус</th>
            <th class="border border-slate-300">Відстань</th>
            <th class="border border-slate-300">Ціна</th>
            <th class="border border-slate-300">Тип авто</th>
            <th class="border border-slate-300">Станція</th>
            <th class="border border-slate-300">Менеджер на станції</th>
            <th class="border border-slate-300">Початкова адреса</th>
            <th class="border border-slate-300">Кінцева адреса</th>
            <th class="border border-slate-300">Дата початку</th>
            <th class="border border-slate-300">Дії</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $item)
        <tr>
            <td class="border border-slate-300 text-center p-2">{{ $item->product }}</td>
            <td class="border border-slate-300 text-center p-2">{{ $item->weigth }} кг</td>
            <?php
            $status_class = "";
            switch ($item->status) {
                case 'Створено':
                    $status_class = 'text-lime-500 bg-lime-100';
                    break;
                case 'Доставлено':
                    $status_class = 'text-green-500 bg-green-100';
                    break;
                case 'Скасовано':
                    $status_class = 'text-red-500 bg-red-100';
                    break;
                case 'Призначено':
                    $status_class = 'text-blue-500 bg-blue-100';
                    break;
                default:
                    # code...
                    break;
            }
            ?>
            <td class="border border-slate-300 text-center p-2 {{ $status_class }}">{{ $item->status }}</td>
            <td class="border border-slate-300 text-center p-2 tr-distacies"> {{ round($item->distance/1000) }} км</td>
            <td class="border border-slate-300 text-center p-2 tr-vutratu"> {{ $item->vutratu }} грн</td>
            <td class="border border-slate-300 text-center p-2">{{ $item->auto_type }}</td>
            <td class="border border-slate-300 text-center p-2">{{ $item->station==null?" - ":"#" . $item->station->nomer  }}</td>
            @if($item->station!=null)
            <td class="border border-slate-300 text-center p-2">{{ $item->station->menedger==null?' - ':$item->station->menedger->full_name . '(' . $item->station->menedger->phone . ')' }}</td>
            @else
            <td class="border border-slate-300 text-center p-2"> - </td>
            @endif
            <td class="border border-slate-300 text-center p-2">{{ $item->point_A }}</td>
            <td class="border border-slate-300 text-center p-2">{{ $item->point_B }}</td>
            <td class="border border-slate-300 text-center p-2">{{ date("d/m/Y", strtotime($item->date_of_start)) }}</td>
            <td class="border border-slate-300 text-center p-2">
                @if($item->status=="Створено")
                <button class="btn p-1 bg-orange-400 m-0 p-0.5 rounded-sm text-white text-xs hover:bg-orange-600 transition" onclick="location.href='/supplier/dashboard/orders/{{$item->id}}/delete'">Відмінити</button>
                @elseif($item->status=="Відмовлено")
                <button class="btn p-1 bg-red-400 m-0 p-0.5 rounded-sm text-white text-xs hover:bg-red-600 transition" onclick="location.href='/supplier/dashboard/orders/{{$item->id}}/delete'">Видалити</button>
                @else
                <button class="btn p-1 bg-gray-400 m-0 p-0.5 rounded-sm text-white text-xs hover:bg-gray-600 transition" disabled onclick="location.href='/supplier/dashboard/orders/{{$item->id}}/delete'">Відмінити</button>

                @endif
            </td>
        </tr>
        @endforeach

    </tbody>
</table>
@else
<div class="flex justify-center flex-col items-center">
    <img class="opacity-5 h-16 w-16" src="{{ url('images/empty_history.png') }}" alt="empty_box">
    <h2 class="opacity-25 ">Ви ще не створювали замовлень</h2>

</div>
@endif
@endsection
