@extends('layouts.main')
@section('title','Замовлення')


@section('navlinks')
@include('navigations.manadger')
@endsection
@section('responsive_navlinks')
@include('responsive_navigations.manadger')
@endsection

@section('content')
@if(count($journal)!=0)
<table class="w-full text-sm">
    <thead>
        <tr>
            <th class="border border-slate-300 text-center p-1">Користувач</th>
            <th class="border border-slate-300 text-center p-1">Сума</th>
            <th class="border border-slate-300 text-center p-1">Дата</th>
        </tr>
    </thead>
    <tbody>
        @foreach($journal as $row)
        <tr>
            <td class="border border-slate-300 text-center p-1">{{ $row->user->name }} ({{ $row->user->role=='supplier'?'замовник':'диспетчер' }})</td>
            <td class="border border-slate-300 text-center p-1">{{ $row->current_sum }} (<span style={{ $row->type=='plus'?'color:green':'color:red' }}>{{ $row->type=='plus'?'+'.$row->change:$row->change }}</span>) грн</td>
            <td class="border border-slate-300 text-center p-1">{{ date('d/m/y',strtotime($row->created_at)) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<div class="flex justify-center flex-col items-center">
    <img class="opacity-5 h-16 w-16" src="{{ url('images/empty_history.png') }}" alt="empty_box">
    <h2 class="opacity-25 ">Історія стархового фонду пуста</h2>

</div>
@endif
@endsection

