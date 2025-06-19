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
    {{-- todo --}}
    <div class="row">
        <table>
            <caption>Підзамовлення</caption>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Товар</th>
                    <th>Вага</th>
                    <th>Тип авто</th>
                    <th>Замовник</th>
                    <th>Точка поч.зам.</th>
                    <th>Точка кін.зам.</th>
                    <th>Статус</th>
                    <th>Дата</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->product }}</td>
                        <td>{{ $order->weigth }} кг</td>
                        <td>{{ $order->auto_type }} </td>
                        <td>{{ $order->supplier->full_name }}</td>
                        <td>{{ $order->point_A }} </td>
                        <td>{{ $order->point_B }} </td>
                        <?php
                        $class = '';
                        if ($order->status == 'Створено') {
                            $class = 'green';
                        } elseif ($order->status == 'Скасовано') {
                            $class = 'red';
                        } elseif ($order->status == 'Завершено') {
                            $class = 'orange';
                        }
                        ?>
                        <td class="{{ $class }}">{{ $order->status }}</td>
                        <td>{{ $order->date_of_start }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <table>
            <caption>Замовлення</caption>
            <thead>
                <tr>
                    <th>#</th>
                    <th>station</th>
                    <th>driver</th>
                    <th>auto type</th>
                    <th>weight</th>
                    <th>status</th>
                    <th>date</th>
                    {{-- <th>points</th> --}}
                    {{-- <th>current</th> --}}

                    <th>product</th>
                    <th>date</th>
                    <th>status</th>
                    <th>supplier</th>
                    <th>weight</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($rides as $ride)
                    <tr>
                        <?php
                        $class = '';
                        // if ($order->status == 'Створено') {
                        //     $class = 'green';
                        // } elseif ($order->status == 'Скасовано') {
                        //     $class = 'red';
                        // } elseif ($order->status == 'Завершено') {
                        //     $class = 'orange';
                        // }
                        $orders_count = count($ride->orders);
                        $order_first = $ride->orders[0];
                        ?>
                        <td colspan={{ $order_count }}>{{ $ride->id }}</td>
                        <td colspan={{ $order_count }}>{{ $ride->station->nomer }} ({{ $ride->station->address }})
                        </td>
                        <td colspan={{ $order_count }}>{{ $ride->driver->full_name }}</td>
                        <td colspan={{ $order_count }}>{{ $ride->driver->auto->type }}</td>
                        <td colspan={{ $order_count }}>{{ $ride->weigth }}</td>
                        <td colspan={{ $order_count }} class={{ $class }}>{{ $ride->status }}</td>
                        <td colspan={{ $order_count }}>{{ $ride->date }}</td>
                        {{-- <td colspan={{ $order_count }}>{{ $ride->points }}</td> --}}
                        {{-- <td colspan={{ $order_count }}>{{ $ride->current_point }}</td> --}}

                        <td>{{ $order_first->product }}</td>
                        <td>{{ $order_first->date }}</td>
                        <td>{{ $order_first->status }}</td>
                        <td>{{ $order_first->supplier->full_name }}</td>
                        <td>{{ $order_first->weight }}</td>
                    </tr>
                    @foreach ($ride->orders as $order)
                        <td>{{ $order->product }}</td>
                        <td>{{ $order->date }}</td>
                        <td>{{ $order->status }}</td>
                        <td>{{ $order->supplier->full_name }}</td>
                        <td>{{ $order->weight }}</td>
                    @endforeach
                @endforeach
            </tbody>
        </table>

    </div>
</body>

</html>
