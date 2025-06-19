<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        .row {
            display: flex;
            flex-direction: row;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid grey;
        }

        th,
        td {
            padding: 8px;
            text-align: center;
            border-bottom: 1px solid #DDD;
        }

        tr:hover {
            background-color: #D6EEEE;
        }

        .red {
            color: red;
            background: lightpink;
        }

        .green {
            color: green;
            background: lightgreen;
        }

        .orange {
            color: orange;
            background: lightgoldenrodyellow;
        }

        .blue {
            color: darkblue;
            background: lightskyblue;
        }

        .purple {
            color: purple;
            background: lavenderblush;
        }
    </style>
    <title>Admin</title>
</head>

<body>
    <h2 class="text-center">Користувачі системи</h2>
    <p class="text-red-500">*Пароль (для всіх!) - 12345678</p>
    <div class="row text-sm">
        <table class="text-sm">
            <caption>Водії</caption>
            <thead>
            <tr>
                <th>#</th>
                <th>ПІБ</th>
                <th>Ел.адреса</th>
                <th>Статус</th>
                <th>Станція</th>
                {{-- <th>Кількість замовлень</th> --}}
                {{-- <th>Дата наймання</th> --}}
            </tr>
        </thead>
        <tbody>

            @foreach ($drivers as $driver)
                <tr>
                    <td>{{ $driver->id }}</td>
                    <td>{{ $driver->full_name }}</td>
                    <td>{{ $driver->user->email }}</td>
                    <td>{{ $driver->status }} </td>
                    <td>{{ $driver->station->nomer }}</td>
                    {{-- <td>{{ count($driver->orders??0) }}({{ count($driver->rides??0) }} поїздок)</td> --}}
                </tr>
            @endforeach
        </tbody></table>
        <table><caption>Замовники</caption><thead>
            <tr>
                <th>#</th>
                <th>ПІБ</th>
                <th>Ел.адреса</th>
                <th>Кількість замовлень</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($suppliers as $supplier)
                <tr>
                    <td>{{ $supplier->id }}</td>
                    <td>{{ $supplier->full_name }}</td>
                    <td>{{ $supplier->user->email }}</td>
                    <td>{{ count($supplier->orders??0) }}</td>
                </tr>
            @endforeach
        </tbody></table>
        <table><caption>Менеджери</caption><thead>
            <tr>
                <th>#</th>
                <th>ПІБ</th>
                <th>Ел.адреса</th>
                <th>Станція</th>
                {{-- <th>Кількість замовлень</th> --}}
                {{-- <th>Дата наймання</th> --}}
            </tr>
        </thead>
        <tbody>

            @foreach ($menedgers as $menedger)
                <tr>
                    <td>{{ $menedger->id }}</td>
                    <td>{{ $menedger->full_name }}</td>
                    <td>{{ $menedger->user->email }}</td>
                    <td>{{ $menedger->station->nomer }}</td>
                    {{-- <td>{{ count($driver->orders??0) }}({{ count($driver->rides??0) }} поїздок)</td> --}}
                </tr>
            @endforeach
        </tbody></table>
        <table class="table"><caption>Диспетчери</caption><thead>
            <tr>
                <th>#</th>
                <th>ПІБ</th>
                <th>Ел.адреса</th>
                <th>Станція</th>
                {{-- <th>Кількість замовлень</th> --}}
                {{-- <th>Дата наймання</th> --}}
            </tr>
        </thead>
        <tbody>

            @foreach ($dispetchers as $dispetcher)
                <tr>
                    <td>{{ $dispetcher->id }}</td>
                    <td>{{ $dispetcher->full_name }}</td>
                    <td>{{ $dispetcher->user->email }}</td>
                    <td>{{ $dispetcher->station->nomer }}</td>
                    {{-- <td>{{ count($driver->orders??0) }}({{ count($driver->rides??0) }} поїздок)</td> --}}
                </tr>
            @endforeach
        </tbody></table>
    </div>

</body>

</html>
