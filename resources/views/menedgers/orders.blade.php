@extends('layouts.main')
@section('title', 'Cтворення пакетів замовлень')


@section('navlinks')
    @include('navigations.manadger')
@endsection
@section('responsive_navlinks')
    @include('responsive_navigations.manadger')
@endsection

@section('content')

    <div class="mt-0 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
        <div class="sm:col-span-3 overflow-y-scroll scroll-smooth h-80">
            <table class="w-full text-sm" id="AllOrderList">
                <thead>
                    <tr>
                        <th class="border border-slate-300">Продукт</th>
                        <th class="border border-slate-300">Вага</th>
                        <th class="border border-slate-300">Тип авто</th>
                        <th class="border border-slate-300">Початкова адреса</th>
                        <th class="border border-slate-300">Кінцева адреса</th>
                        <th class="border border-slate-300">
                            <div class="flex flex-row align-baseline items-baseline">
                                <div class="flex flex-col">
                                    Дата початку
                                </div>
                                <div class="flex flex-col">
                                    <span class="cursor-pointer sort-button" data-order="asc">&#8593</span>
                                    <span class="cursor-pointer sort-button" data-order="desc">&#8595</span>
                                </div>
                            </div>

                        </th>
                        <th class="border border-slate-300">Дії</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr class="auto-type-{{ $order->auto_type_value }} transition" id="order{{ $order->id }}"
                            _id='{{ $order->id }}' weigth='{{ $order->weigth }}'
                            auto_type='{{ $order->auto_type_value }}' product='{{ $order->product }}'
                            point_A='{{ $order->point_A }}' point_B='{{ $order->point_B }}'
                            date_of_start='{{ date('Y-m-d', strtotime($order->date_of_start)) }}'
                            vutratu='{{ $order->vutratu }}' supplier_name='{{ $order->supplier->full_name }}'
                            supplier_phone='{{ $order->supplier->phone }}'>
                            <td class="border border-slate-300 text-center">{{ $order->product }}</td>
                            <td class="border border-slate-300 text-center">{{ $order->weigth }}
                                {{ $order->auto_type != 'Цистерна' ? 'кг' : 'л' }}</td>
                            <td class="border border-slate-300 text-center">{{ $order->auto_type }}</td>
                            <td class="border border-slate-300 text-center">{{ $order->point_A }}</td>
                            <td class="border border-slate-300 text-center">{{ $order->point_B }}</td>
                            <td class="border border-slate-300 text-center">
                                {{ date('d/m/y h:i', strtotime($order->date_of_start)) }}</td>
                            <td class="border border-slate-300 text-center">
                                <svg class="cursor-pointer" onclick="ChooseOrder(this)" xmlns="http://www.w3.org/2000/svg"
                                    width="20" height="20" viewBox="0 0 24 24" fill="none">
                                    <path
                                        d="M15 12H12M12 12H9M12 12V9M12 12V15M17 21H7C4.79086 21 3 19.2091 3 17V7C3 4.79086 4.79086 3 7 3H17C19.2091 3 21 4.79086 21 7V17C21 19.2091 19.2091 21 17 21Z"
                                        stroke="#000000" stroke-width="2" stroke-linecap="round" />
                                </svg>
                                <svg class="cursor-pointer" title="Відмовити" onclick="ReturnOrder(this)"
                                    xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none">
                                    <path d="M4 7.00005L10.2 11.65C11.2667 12.45 12.7333 12.45 13.8 11.65L20 7"
                                        stroke="red" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M13 19H5C3.89543 19 3 18.1046 3 17V7C3 5.89543 3.89543 5 5 5H19C20.1046 5 21 5.89543 21 7V12"
                                        stroke="red" stroke-width="2" stroke-linecap="round" />
                                    <path d="M17 16L19 18M21 20L19 18M19 18L21 16M19 18L17 20" stroke="red"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>

                            </td>
                        </tr>
                    @endforeach
                    <tr id="no_orders_row" class="{{ $orders != null ? 'hidden' : '' }}">
                        <td colspan="7" class="border border-slate-300 text-center text-gray-700">Немає замовлень
                        </td>
                    </tr>
                </tbody>
            </table>


        </div>
        <div class="sm:col-span-3">
            <!-- <button class="btn bg-green-400 m-2 p-2 rounded-sm text-white hover:bg-green-600 transition"
                                                                    onclick="EmptyTableChoosedOrders('add')">Click</button> -->
            <div>
                <label class="block text-sm font-medium leading-6 text-gray-900" for="car_type">Тип авто:</label>
                <select name="car_type" id="car_type"
                    class="block w-full rounded-md
                border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300
                focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6"
                    onchange="FilterOrdersByAutoType(this)">
                    <option value="">Обрати</option>
                    <option value="A">Цистерна</option>
                    <option value="B">Рефрежиратор</option>
                    <option value="C">Контейнер</option>
                </select>
                <label class="block text-sm font-medium leading-6 text-gray-900" for="driver">Водій:</label>
                <select name="driver" id="driver"
                    class="block w-full rounded-md
                border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300
                focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm
                sm:leading-6"
                    onchange="FilterAutoTypeByDriver(this)">
                    <option value="" lifting_weigth="0" selected>Обрати</option>

                    @foreach ($drivers_on_station as $driver)
                        @if ($driver->auto != null)
                            <?php
                            $auto_type_value = '';
                            if ($driver->auto->type == 'Цистерна') {
                                $auto_type_value = 'A';
                            } elseif ($driver->auto->type == 'Рефрежиратор') {
                                $auto_type_value = 'B';
                            } elseif ($driver->auto->type == 'Контейнер') {
                                $auto_type_value = 'C';
                            }
                            ?>
                            <option class="option-driver-auto-type-{{ $auto_type_value }}"
                                lifting_weigth="{{ $driver->auto->lifting_weight }}" auto_type="{{ $auto_type_value }}"
                                is_home_station="false" home_station="{{ $driver->auto->station->address }}"
                                value="{{ $driver->id }}" full_name="{{ $driver->full_name }}">
                                &#11088{{ $driver->full_name }} (Тип авто: {{ $driver->auto->type }}) (Рідна станція:
                                Станція №{{ $driver->auto->station->nomer }})</option>
                        @endif
                    @endforeach
                    @foreach ($drivers as $driver)
                        @if ($driver->auto != null)
                            <?php
                            $auto_type_value = null;
                            if ($driver->auto->type == 'Цистерна') {
                                $auto_type_value = 'A';
                            } elseif ($driver->auto->type == 'Рефрежиратор') {
                                $auto_type_value = 'B';
                            } elseif ($driver->auto->type == 'Контейнер') {
                                $auto_type_value = 'C';
                            }
                            ?>
                            <option class="option-driver-auto-type-{{ $auto_type_value }}"
                                lifting_weigth="{{ $driver->auto->lifting_weight }}" auto_type="{{ $auto_type_value }}"
                                is_home_station="true" home_station="null" value="{{ $driver->id }}"
                                full_name="{{ $driver->full_name }}">{{ $driver->full_name }} (Тип авто:
                                {{ $driver->auto->type }})</option>
                        @endif
                    @endforeach

                </select>
                <p>
                <p class="align-middle inline-block">Кількість підзамовлень:</p>
                <p class="align-middle inline-block" id="resProductNum"></p>
                </p>
                <p>
                <p class="align-middle inline-block">Вага:</p>
                <p class="align-middle inline-block" id="resproductsWeight"></p> /
                <p class="align-middle inline-block" id="resLiftingWeight">0</p>
                <p class="align-middle inline-block">кг</p>
                </p>
            </div>
            <button class="btn bg-green-400 m-2 p-2 rounded-sm text-white hover:bg-green-600 transition"
                onclick="CombineOrders('{{ $station->address }}')">Сформувати замовлення</button>

            <div class="progress-bar bg-gray-300 rounded-sm h-2 w-100 mb-10">
                <div id="progress" class="progress bg-green-400 rounded-sm w-0 h-full"
                    style="transition: width 0.3s ease;"></div>
            </div>
            <table class="w-full" id="ChooseOrderList">
                <thead>
                    <tr>
                        <th class="border border-slate-300">Продукт</th>
                        <th class="border border-slate-300">Вага</th>
                        <!--<th class="border border-slate-300">Тип авто</th>
                                                                                    <th class="border border-slate-300">Початкова адреса</th>
                                                                                    <th class="border border-slate-300">Кінцева адреса</th>
                                                                                    <th class="border border-slate-300">
                                                                                        <div class="flex flex-row">
                                                                                            <div class="flex flex-col">
                                                                                                Дата початку
                                                                                            </div>
                                                                                            <div class="flex flex-col">
                                                                                                <span class="cursor-pointer" onclick="SortByDate('NewOrderList','asc',5)">&#8593</span>
                                                                                                <span class="cursor-pointer" onclick="SortByDate('NewOrderList','desc',5)">&#8595</span>
                                                                                            </div>
                                                                                        </div>

                                                                                    </th>-->
                        <th class="border border-slate-300">Дії</th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="no_orders_row_">
                        <td colspan="3" class="border border-slate-300 text-center text-gray-700">Немає замовлень
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="sm:col-span-3">
        <!--map-->

    </div>

@endsection
@section('script')
    <script>
        let progressBar = document.getElementById('progress');
        let progress_bar_data = {
            value: 0,
            max: 0
        };

        function ProgressBarSetMax(max) {
            progress_bar_data.max = max;
            ProgressBarPrint();
        }

        function ProgressBarPrint() {
            let value = (progress_bar_data.value * 100) / progress_bar_data.max;
            if (progress_bar_data.max == 0) {
                progress_bar_data.value = 0;
                progressBar.style.width = '0%';
            } else {
                if (value > 100) {
                    value = 100;
                    progressBar.style.backgroundColor = "red";
                } else progressBar.style.backgroundColor = "green";
                progressBar.style.width = value + '%';
            }
        }
    </script>
    <script>
        const all_no_orders_row = document.getElementById('no_orders_row');
        const choosed_no_orders_row = document.getElementById('no_orders_row_');

        function EmptyTableAllOrders(option = "default") {
            if (option == "add") all_no_orders_row.classList.add('hidden');
            else if (option == "remove") all_no_orders_row.classList.remove('hidden');
            else all_no_orders_row.classList.toggle('hidden');
        }

        function EmptyTableChoosedOrders(option = "default") {
            if (option == "add") {
                document.getElementById('no_orders_row_').classList.add('hidden');
            } else if (option == "remove") document.getElementById('no_orders_row_').classList.remove('hidden');
            else choosed_no_orders_row.classList.toggle('hidden');
        }


        function FilterOrdersByAutoType(e) {
            if (e.value != "") {
                [...document.getElementsByClassName('auto-type-A')].map(x => x.classList.add('hidden'));
                [...document.getElementsByClassName('auto-type-B')].map(x => x.classList.add('hidden'));
                [...document.getElementsByClassName('auto-type-C')].map(x => x.classList.add('hidden'));


                [...document.getElementsByClassName('auto-type-' + e.value)].map(x => x.classList.remove('hidden'));
                // if (document.getElementsByClassName('auto-type-' + e.value).length == 0) {
                //     EmptyTableAllOrders("add")
                // } else {
                //     EmptyTableAllOrders("remove")
                // }
                auto_type_of_orders = e.value;
            } else {

                [...document.getElementsByClassName('auto-type-A')].map(x => x.classList.remove('hidden'));
                [...document.getElementsByClassName('auto-type-B')].map(x => x.classList.remove('hidden'));
                [...document.getElementsByClassName('auto-type-C')].map(x => x.classList.remove('hidden'));
            }

            choosedOrders.forEach(order => {
                document.getElementById(order.id).classList.add('hidden');
            });

            FilterDriverByAutoType(e.value);
        }

        function FilterDriverByAutoType(type) {
            if (type != "") {
                [...document.getElementsByClassName('option-driver-auto-type-A')].map(x => x.classList.add('hidden'));
                [...document.getElementsByClassName('option-driver-auto-type-B')].map(x => x.classList.add('hidden'));
                [...document.getElementsByClassName('option-driver-auto-type-C')].map(x => x.classList.add('hidden'));


                [...document.getElementsByClassName('option-driver-auto-type-' + type)].map(x => x.classList.remove(
                    'hidden'));

            } else {
                [...document.getElementsByClassName('option-driver-auto-type-A')].map(x => x.classList.remove('hidden'));
                [...document.getElementsByClassName('option-driver-auto-type-B')].map(x => x.classList.remove('hidden'));
                [...document.getElementsByClassName('option-driver-auto-type-C')].map(x => x.classList.remove('hidden'));


            }
        }

        function FilterAutoTypeByDriver(e) {
            auto_type_of_orders = e.options[e.selectedIndex].getAttribute('auto_type');
            let LiftingWeigth = e.options[e.selectedIndex].getAttribute('lifting_weigth');
            IsHomeStation = e.options[e.selectedIndex].getAttribute('is_home_station');
            StationEnd = e.options[e.selectedIndex].getAttribute('home_station');

            document.querySelectorAll('#resLiftingWeight')[0].innerText = LiftingWeigth;
            ProgressBarSetMax(LiftingWeigth);


            let selectAutoType = document.getElementById("car_type");
            selectAutoType.value = auto_type_of_orders;
            FilterOrdersByAutoType(selectAutoType);

            PrintResults();
        }
        document.querySelectorAll('#AllOrderList .sort-button').forEach(btn => {
            btn.addEventListener('click', e => {
                SortByDate('AllOrderList', e.target.dataset.order, 5);
            });
        });

        function SortByDate(table, param, n) {
            var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
            table = document.getElementById(table);
            switching = true;
            dir = param;
            while (switching) {
                switching = false;
                rows = table.rows;
                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName("TD")[n];
                    y = rows[i + 1].getElementsByTagName("TD")[n];
                    if (dir == "asc") {
                        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    } else if (dir == "desc") {
                        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                }
                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                    switchcount++;
                } else {
                    if (switchcount == 0 && dir == "asc") {
                        dir = "desc";
                        switching = true;
                    }
                }
            }
        }

        let choosedOrders = [];

        let number_of_orders = 0;
        let sum_weigth_of_orders = 0;
        let auto_type_of_orders = null;

        PrintResults()

        function ReturnOrder(e) {
            //отримати рядок
            let id = e.parentElement.parentElement.attributes._id.value;
            if (confirm("Видалення замовлення", "Чи хочете ви відмовити у замовлені?") == true) {
                location.href = '/menedger/dashboard/orders/remove/' + id;
            }
        }

        function ChooseOrder(e) {
            //отримати рядок
            let row = e.parentElement.parentElement;
            //формуємо об'єкт з усіма даними, щоб потім додати у масив
            let newChoosedRow = {
                id: row.attributes.id.value,
                _id: row.attributes._id.value,
                weigth: row.attributes.weigth.value * 1,
                auto_type: row.attributes.auto_type.value,
                product: row.attributes.product.value,
                point_A: row.attributes.point_A.value,
                point_B: row.attributes.point_B.value,
                date_of_start: row.attributes.date_of_start.value,
                vutratu: row.attributes.vutratu.value,
                supplier_name: row.attributes.supplier_name.value,
                supplier_phone: row.attributes.supplier_phone.value,
            };
            console.log("newChoosedRow", newChoosedRow);
            let canAddRow = true;
            if (choosedOrders.length > 0) {
                if (newChoosedRow.auto_type != auto_type_of_orders) {
                    alert("Замолвення мають різний тип авто!")
                    canAddRow = false;
                }
            }
            console.log("auto_type_of_orders", auto_type_of_orders);
            if (choosedOrders.length > 0 && auto_type_of_orders == 'A') {
                alert("Для авто типу 'Цистерна' можна додати лише одне замовлення!");
                canAddRow = false;
            }
            if (canAddRow) {
                auto_type_of_orders = newChoosedRow.auto_type
                choosedOrders.push(newChoosedRow);
                DrawRowInChoosedOrdersTable(newChoosedRow);
                document.querySelectorAll('#AllOrderList #order' + newChoosedRow._id)[0].classList.add('hidden');
                number_of_orders++;
                sum_weigth_of_orders += newChoosedRow.weigth;
                auto_type_of_orders = newChoosedRow.auto_type;

                PrintResults();

                if (CheckAllOrderListOnEmpty()) EmptyTableAllOrders("remove");
                EmptyTableChoosedOrders('add');

            }
        }

        function CheckAllOrderListOnEmpty() {
            let elems = document.querySelectorAll('#AllOrderList tbody tr');

            let res = true;
            elems.forEach(element => {
                if (!element.classList.contains('hidden')) res = false;
            });
            return res;
        }

        function UnchooseOrder(e) {
            //отримати рядок
            let row = e.parentElement.parentElement;
            //отримати id рядка
            let _id = row.attributes._id.value;
            //повернути елемент в таблицю з усіма замовленнями ()
            document.querySelectorAll('#AllOrderList #order' + _id)[0].classList.remove('hidden');

            // прибрати за масиву рядок
            choosedOrders = choosedOrders.filter(x => x._id != _id);

            //перемалювати таблицю з вибраними замовленнням
            document.querySelectorAll('#ChooseOrderList tbody')[0].innerHTML =
                '<tr id="no_orders_row_"><td colspan="3" class="border border-slate-300 text-center text-gray-700">Немає замовлень</td></tr>';
            console.log("choosedOrders:", choosedOrders)
            if (choosedOrders.length != 0) {
                EmptyTableChoosedOrders("add");
            }
            // обнуляємо дані

            number_of_orders = 0;
            sum_weigth_of_orders = 0;
            // auto_type_of_orders = null;


            choosedOrders.forEach(order => {
                DrawRowInChoosedOrdersTable(order);
                number_of_orders++;
                sum_weigth_of_orders += order.weigth;
                auto_type_of_orders = order.auto_type;
            });

            PrintResults();

        }

        function DrawRowInChoosedOrdersTable(newChoosedRow) {
            let html = '<tr class="transition" id="' + newChoosedRow.id + '" _id="' + newChoosedRow._id + '">' +
                '<td class="border border-slate-300">' + newChoosedRow.product + '</td>' +
                '<td class="border border-slate-300">' + newChoosedRow.weigth + ' кг</td>' +
                '<td class="border border-slate-300"><svg onclick="UnchooseOrder(this)" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"><g id="Edit / Add_Minus_Square"><path id="Vector" d="M8 12H16M4 16.8002V7.2002C4 6.08009 4 5.51962 4.21799 5.0918C4.40973 4.71547 4.71547 4.40973 5.0918 4.21799C5.51962 4 6.08009 4 7.2002 4H16.8002C17.9203 4 18.4801 4 18.9079 4.21799C19.2842 4.40973 19.5905 4.71547 19.7822 5.0918C20.0002 5.51962 20.0002 6.07967 20.0002 7.19978V16.7998C20.0002 17.9199 20.0002 18.48 19.7822 18.9078C19.5905 19.2841 19.2842 19.5905 18.9079 19.7822C18.4805 20 17.9215 20 16.8036 20H7.19691C6.07899 20 5.5192 20 5.0918 19.7822C4.71547 19.5905 4.40973 19.2842 4.21799 18.9079C4 18.4801 4 17.9203 4 16.8002Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></g></svg></td>' +
                '</tr>';

            document.querySelectorAll('#ChooseOrderList tbody')[0].innerHTML += html;
        }

        function PrintResults() {
            document.querySelectorAll('#resProductNum')[0].innerText = number_of_orders;
            document.querySelectorAll('#resproductsWeight')[0].innerText = sum_weigth_of_orders;
            progress_bar_data.value = sum_weigth_of_orders;
            ProgressBarPrint();

            //let LiftingWeight=document.querySelectorAll('#LiftingWeight')[0].innerHTML;
            //if(sum_weigth_of_orders>=30) document.querySelectorAll('#resproductsWeight')[0].style.color='red';
            let selectAutoType = document.getElementById("car_type");
            selectAutoType.value = auto_type_of_orders;
            FilterOrdersByAutoType(selectAutoType);
        }
    </script>
    <script>
        //функції
        function Permute(a, i, n) {
            let j;

            if (i == n) ArrayPush(a);
            else {
                let temp;
                for (j = i; j <= n; j++) {
                    // swap(a[i], a[j]);
                    temp = a[i];
                    a[i] = a[j];
                    a[j] = temp;

                    Permute(a, i + 1, n);

                    // swap(a[i], a[j]);
                    temp = a[i];
                    a[i] = a[j];
                    a[j] = temp;
                }
            }

        }

        function PrintArray(arr, text) {
            let res = "";
            arr.forEach(item => {
                res += item + " \n";
            });
        }

        function CalcDistancies(p1, p2) {
            if (p1 == p2) return 0;
            let res = 0;
            distancies.forEach(d => {
                if (d.point_A === p1 && d.point_B == p2) res = d.distance;
                else if (d.point_A === p2 && d.point_B === p1) res = d.distance;
            });
            if (res == 0) console.error("Помилка. Не вдалось знайти відстань в масиві з ними.");
            return res;
        }

        function CheckRoute(route) {
            let points = [];

            // Формуємо список точок маршруту з інформацією про тип і продукт
            route.forEach(r => {
                choosedOrders.forEach(order => {
                    if (order.point_A == r) {
                        points.push({
                            route: r,
                            type: "A",
                            product: order.product,
                        });
                    } else if (order.point_B == r) {
                        points.push({
                            route: r,
                            type: "B",
                            product: order.product,
                        });
                    }
                });
            });
            // Якщо маршрут починається з B — невалідний
            if (points.length > 0 && points[0].type == "B") return false;

            let pickedProducts = []; // які продукти вже забрали (A)

            for (let i = 0; i < points.length; i++) {
                let point = points[i];
                if (point.type == "A") {
                    pickedProducts.push(point.product);
                } else if (point.type == "B") {
                    // Якщо продукт ще не забирали — маршрут невалідний
                    if (!pickedProducts.includes(point.product)) {
                        return false;
                    }
                }
            }

            return true;
        }


        function ArrayPush(array_) {
            let new_arr = [];
            array_.forEach(i => {
                new_arr.push(i);
            });
            resArray.push({
                p: new_arr,
                sum: 0,
            });
        }

        async function FindClosestPoint(point, points_array) {
            try {

                const distancies = await Promise.all(
                    points_array.map(async (point_B) => {
                        const response = await getDistancies(point, point_B)

                        return {
                            point_B: point_B,
                            distance: response.distance // Обробка результату
                        };
                    })
                );

                // Знайти точку з мінімальною відстанню
                const closestPoint = distancies.reduce((min, current) =>
                    current.distance < min.distance ? current : min
                );

                return closestPoint;
            } catch (error) {
                console.error("Error fetching distances:", error);
                throw error; // Кидання помилки у зовнішній код
            }
        }
    </script>
    <script>
        let arr = [];
        let distancies = [];
        let resArray = [];


        async function getCoordinates(address) {
            const url =
                `https://cors-anywhere.herokuapp.com/https://maps.googleapis.com/maps/api/geocode/json?address=${encodeURIComponent(address)}&key=${apiKey}`;

            try {
                const response = await fetch(url);
                const data = await response.json();
                if (data.status == "OK") {
                    const location = data.results[0].geometry.location;
                    return {
                        latitude: location.lat,
                        longitude: location.lng
                    };
                } else {
                    console.warn(`Google Geocoding error: ${data.status}, пробую через OpenStreetMap...`);
                    return await getCoordinatesOSM(address);
                }
            } catch (error) {
                console.error("Error fetching coordinates:", error);
                return null;
            }
        }
        async function getCoordinatesOSM(address) {

            const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`;

            try {
                const response = await fetch(url, {
                    headers: {
                        'Accept-Language': 'uk', // щоб результати були локалізовані
                        'User-Agent': 'MyCargoApp/1.0 (mykhaylenkooleksandr208@gmail.com', // обов'язковий для Nominatim
                    }
                });
                const data = await response.json();

                if (data.length > 0) {
                    const location = data[0];
                    return {
                        latitude: location.lat,
                        longitude: location.lon
                    };
                } else {
                    console.error("Адресу не знайдено");
                    return null;
                }
            } catch (error) {
                console.error("Помилка запиту:", error);
                return null;
            }
        }
        async function getDistancies(v, w) {
            if (v == w) return {
                point_A: v,
                point_B: w,
                distance: 0
            };
            const originCoords = await getCoordinates(v);
            const destinationCoords = await getCoordinates(w);
            if (!originCoords || !destinationCoords) {
                console.error("Could not fetch coordinates for the given addresses.");
                return;
            }

            const requestBody = {
                "origin": {
                    "location": {
                        "latLng": originCoords
                    }
                },
                "destination": {
                    "location": {
                        "latLng": destinationCoords
                    }
                },
                "travelMode": "DRIVE",
                "routingPreference": "TRAFFIC_AWARE",
                "computeAlternativeRoutes": true
            };
            return new Promise((resolve, reject) => {
                fetch(`https://routes.googleapis.com/directions/v2:computeRoutes?key=${apiKey}`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-Goog-FieldMask": "*"
                        },
                        body: JSON.stringify(requestBody)
                    })
                    .then(response => response.json())
                    .then(data => {
                        const res = {
                            point_A: v,
                            point_B: w,
                            distance: data.routes[0].legs[0]
                                .distanceMeters // Приклад обробки результату
                        };
                        resolve(res);
                    })
                    .catch(error => {
                        console.error("Error fetching route:", error);
                    });
            });
        }

        function SubmitOrder() {
            //FIXME:
            console.log(modal_data)
            fetch("/api/rides/store", {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        points: JSON.stringify(modal_data.points),
                        driver_id: modal_data.driver,
                        distance: modal_data.distanse,
                        vutratu: modal_data.vutratu,
                        vuruchka: modal_data.vuruchka,
                        chista_vuruchka: modal_data.chista_vuruchka,
                        driver_zarplata: modal_data.driver_zarplata,
                        weigth: modal_data.weigth,
                        station_start: modal_data.station_start,
                        station_end: modal_data.station_end,
                    })
                })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error("HTTP status " + response.status);
                    }
                    return location.href = "/menedger/dashboard/rides";
                })
                .catch((error) => {
                    console.error("Error fetching data:", error);
                });


        }
        //KEY
        const apiKey = document.querySelector('meta[name="google-maps-api-key"]').getAttribute('content');

        //FIXME:
        let modal_data = {};

        let openModal = false;
        let modalId = 'modalWindowCreateOrder';

        let StationEnd = null;
        let IsHomeStation = true;

        function getOrders(id) {
            return new Promise((resolve, reject) => {
                return fetch("/api/orders/show/" + id)
                    .then((response) => {
                        return response.json();
                    })
                    .then((json) => {
                        resolve(json);
                    })
                    .catch((error) => {
                        console.error("Error fetching data:", error);
                    });
            });

        }



        function getStations() {
            return new Promise((resolve, reject) => {
                return fetch("/api/stations")
                    .then((response) => {
                        return response.json();
                    })
                    .then((json) => {
                        resolve(json);
                    })
                    .catch((error) => {
                        console.error("Error fetching data:", error);
                    });
            });
        }
        const progressBarModal = document.getElementById('modal_progress');
        let progress_bar_data_modal = {
            value: 0,
            max: 140
        };

        function ProgressBarModalUpdate(value) {
            progress_bar_data_modal.value += value;
            ProgressBarModalPrint();
        }

        function ProgressBarModalHide() {
            document.getElementById("modal_progress_bar").classList.add('hidden');
        }


        function ProgressBarModalReset() {
            progress_bar_data_modal = {
                value: 0,
                max: 140
            };
        }

        function ProgressBarModalPrint() {
            let value = (progress_bar_data_modal.value * 100) / progress_bar_data_modal.max;
            progressBarModal.style.width = value + '%';
        }
        window.CombineOrders = async function(StationStart) {
            if (document.getElementById("driver").value == "") {
                alert("Водій не обраний!")
            } else if (choosedOrders.length == 0) {
                alert("Замовлення не обрані!")
            } else if (progress_bar_data.value >= progress_bar_data.max) {
                alert("Авто буде перевантажене!")
            } else if (openModal) CloseModal();
            else {
                OpenModal();
                ModalReset();
                //1. отримання точок
                arr = [];
                for (const order of choosedOrders) {
                    const res = await getOrders(order._id);

                    arr.push(res.point_A);
                    arr.push(res.point_B);
                }

                ProgressBarModalUpdate(20);
                //2. отримати відстанні
                distancies = [];
                let start_index = 1;
                for (const v of arr) {
                    let arr2 = arr.slice(start_index);
                    for (const w of arr2) {
                        const res = await getDistancies(v, w);
                        distancies.push(res);
                    }
                    start_index++;
                    ProgressBarModalUpdate(20 / arr.length);
                }

                for (let i = 0; i < arr.length; i += 2) {
                    const res = await getDistancies(StationStart, arr[i]);
                    distancies.push(res);
                }

                ProgressBarModalUpdate(20);
                resArray = [];
                let resRoute = null;

                if (choosedOrders.length <= 1) {
                    resRoute = {
                        p: [arr[0], arr[1]],
                        sum: 0,
                    };
                    ProgressBarModalUpdate(20);
                    resRoute.sum = CalcDistancies(resRoute.p[0], resRoute.p[1]) + CalcDistancies(StationStart,
                        resRoute.p[0]);

                } else {
                    Permute(arr, 0, arr.length - 1);
                    ProgressBarModalUpdate(20);
                    for (const [index, elem] of resArray.entries()) {
                        if (CheckRoute(elem.p)) {
                            let sum = CalcDistancies(StationStart, elem.p[0]);
                            for (let i = 0; i < elem.p.length - 1; i++) {
                                sum += CalcDistancies(elem.p[i], elem.p[i + 1]);
                            }
                            elem.sum = sum;
                            console.log('elem2', elem);
                        }
                    };
                    let resArray2 = resArray.filter(x => x.sum != 0 && !isNaN(x.sum));
                    resRoute = resArray2[0];
                    resArray2.forEach((route) => {
                        if (route.sum <= resRoute.sum) resRoute = route;
                    });
                }
                ProgressBarModalUpdate(20);

                let driver_input = document.getElementById("driver").options[document.getElementById("driver")
                    .selectedIndex];
                let choosed_driver = driver_input.getAttribute('full_name');
                let choosed_driver_value = document.getElementById("driver").value;
                let is_home_station = driver_input.getAttribute('is_home_station');
                let home_station = driver_input.getAttribute('home_station');
                console.log("is_home_station", is_home_station);
                console.log("home_station", home_station);
                let stations = await getStations();
                stations = stations.map((elem) => {
                    return elem.address;
                });
                let StationEnd = null;
                if (is_home_station == false) {
                    console.log('is not home station')
                    StationEnd = home_station;
                }else if(is_home_station == 'false'){
                    console.log('is not home station 2')
                    StationEnd = home_station;

                } else {
                    StationEnd = await FindClosestPoint(resRoute.p[resRoute.p.length - 1], stations);
                    StationEnd = StationEnd.point_B;
                }
                let distance_to_station_end = await getDistancies(resRoute.p[resRoute.p.length - 1], StationEnd);
                resRoute.sum += distance_to_station_end.distance;

                const distanse = Math.round(resRoute.sum / 1000);
                const vutratu = Math.round(distanse * 5.1);
                let driver_zarplata = 0;
                if (sum_weigth_of_orders <= 2000) {
                    driver_zarplata = distanse * 10;
                } else {
                    driver_zarplata = distanse * 12;
                }
                let vuruchka = 0;
                choosedOrders.forEach((order) => {
                    vuruchka += order.vutratu * 1;
                });

                modal_data = {
                    driver: choosed_driver_value,
                    points: null,
                    distanse: distanse, //km
                    weigth: sum_weigth_of_orders,
                    vutratu: vutratu,
                    vuruchka: vuruchka,
                    driver_zarplata: driver_zarplata,
                    chista_vuruchka: vuruchka - vutratu-driver_zarplata,
                    station_start: StationStart,
                    station_end: StationEnd,
                }
                ProgressBarModalUpdate(20);
                ProgressBarModalHide();
                await FillModal(sum_weigth_of_orders, choosed_driver, auto_type_of_orders, distanse, vutratu,
                    resRoute, StationStart, StationEnd, driver_zarplata);
            }

        }

        function setDataForOrder(value, id) {
            modal_data.points.forEach(element => {
                if (element.index == id) {
                    element.date_of_start = value;
                }
            });

        }

        async function FillModal(weigth, driver, auto_type, distanse, vutratu, routes, StationStart, StationEnd,
            driver_zarplata) {

            if (auto_type == "A") auto_type = "Цистерна";
            else if (auto_type == "B") auto_type = "Рефрежиратор";
            else if (auto_type == "C") auto_type = "Контейнер";
            //params
            document.getElementById("modal_station_start").innerText = StationStart;
            document.getElementById("modal_station_end").innerText = StationEnd;
            document.getElementById("modal_sum_weigth").innerText = weigth;
            document.getElementById("modal_driver_and_auto_type").innerText = driver + " (" + auto_type + ") ";
            document.getElementById("modal_sum_vutratu").innerText = vutratu;
            document.getElementById("modal_sum_driver_zarplata").innerText = driver_zarplata;
            document.getElementById("modal_sum_vuruchka").innerText = modal_data.vuruchka;
            document.getElementById("modal_sum_chista_vuruchka").innerText = modal_data.chista_vuruchka;
            if (modal_data.chista_vuruchka <= 0) document.getElementById("modal_sum_chista_vuruchka").style.color =
                'red';
            document.getElementById("modal_sum_distance").innerText = distanse;
            /*
            let arr=[[A,B],[C,D],[A,E]];
            let points=[E,A,A,C,D,B];
            let points_full_data=[];
            */
            //table
            let rowshtml = "";
            let routes_ = [];
            let index = 1;

            // копіюємо замовлення один раз
            let choosedOrders_ = choosedOrders.map(order => ({
                ...order
            }));
            routes.p.forEach(route => {
                let found = false;

                for (let j = 0; j < choosedOrders_.length; j++) {
                    let order = choosedOrders_[j];

                    if (order.point_A === route) {
                        routes_.push({
                            id: order._id,
                            index: index,
                            title: route,
                            type: "Забрати",
                            product: order.product,
                            supplier_name: order.supplier_name,
                            supplier_phone: order.supplier_phone,
                            date_of_start: order.date_of_start,
                        });

                        // "використали" цю адресу
                        order.point_A = '';
                        found = true;
                        break;
                    } else if (order.point_B === route) {
                        routes_.push({
                            id: order._id,
                            index: index,
                            title: route,
                            type: "Привезти",
                            product: order.product,
                            supplier_name: order.supplier_name,
                            supplier_phone: order.supplier_phone,
                            date_of_start: order.date_of_start,
                        });

                        // "використали" цю адресу
                        order.point_B = '';
                        found = true;
                        break;
                    }
                }

                if (!found) {
                    // якщо точка ніде не знайшлась
                    routes_.push({
                        id: null,
                        index: index,
                        title: route,
                        type: null,
                        product: null,
                        supplier_name: null,
                        supplier_phone: null,
                        date_of_start: null,
                    });
                }

                index++;
            });
            modal_data.points = routes_;
            routes_.forEach(route => {
                const input_date = route.type == "Забрати" ?
                    "<input type='date' onchange='setDataForOrder(this.value," + route.index + ")' value='" +
                    route.date_of_start +
                    "' class='p-2 m-0 h-25 border-0' name='date_of_start_for_route_with_index_" + route.index +
                    "'/>" : "";
                rowshtml += "<tr>" +
                    "<td class='border border-slate-300'>" + route.index + "</td>" +
                    "<td class='border border-slate-300'>" + route.title + "</td>" +
                    "<td class='border border-slate-300'>" + route.type + "</td>" +
                    "<td class='border border-slate-300'>" + route.product + "</td>" +
                    "<td class='border border-slate-300'>" + route.supplier_name + "(" + route.supplier_phone +
                    ")</td>" +
                    "<td class='border border-slate-300'>" + input_date + "</td>" +
                    "</tr>";
            });

            document.getElementById("TableRoutes").innerHTML = rowshtml;

            // салювання мапи
            let stations_start_coordinations = await getCoordinates(StationStart);

            let map = new google.maps.Map(document.getElementById('map'), {
                zoom: 5,
                center: new google.maps.LatLng(stations_start_coordinations.latitude,
                    stations_start_coordinations.longitude),
                mapId: "949e9f51ddfb584a"
            });

            let waypoints = [];

            routes = routes.p;
            for (let route of routes.slice(1, -1)) {
                let route_ = await getCoordinates(route);
                waypoints.push({
                    location: {
                        latLng: {
                            latitude: route_.latitude,
                            longitude: route_.longitude
                        }
                    }
                });
            }

            const first_point = await getCoordinates(routes[0]);
            const last_point = await getCoordinates(routes[routes.length - 1]);
            let requestBody = {
                origin: {
                    location: {
                        latLng: {
                            latitude: first_point.latitude,
                            longitude: first_point.longitude
                        }
                    }
                },
                destination: {
                    location: {
                        latLng: {
                            latitude: last_point.latitude,
                            longitude: last_point.longitude
                        }
                    }
                },
                intermediates: waypoints,
                travelMode: "DRIVE"
            };

            let url = "https://cors-anywhere.herokuapp.com/https://routes.googleapis.com/directions/v2:computeRoutes";

            try {
                let response = await fetch(url, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-Goog-Api-Key": apiKey,
                        "X-Goog-FieldMask": "routes.duration,routes.distanceMeters,routes.polyline.encodedPolyline"
                    },
                    body: JSON.stringify(requestBody)
                });

                let data = await response.json();

                if (data.routes && data.routes.length > 0 && data.routes[0].polyline) {
                    let decodedPath = google.maps.geometry.encoding.decodePath(data.routes[0].polyline.encodedPolyline);
                    let polyline = new google.maps.Polyline({
                        path: decodedPath,
                        geodesic: true,
                        strokeColor: "blue",
                        strokeOpacity: 0.8, // Трохи прозорості
                        strokeWeight: 3
                    });
                    polyline.setMap(map);

                    const {
                        AdvancedMarkerElement
                    } = google.maps.marker;
                    const fixPoint = (point) => {
                        if (!point || typeof point.latitude !== "number" || typeof point.longitude !== "number") {
                            console.error("Некоректна точка:", point);
                            return null;
                        }
                        return point;
                    };

                    const allPoints = [
                        fixPoint(first_point),
                        ...waypoints.map(wp => fixPoint(wp.location.latLng)),
                        fixPoint(last_point)
                    ].filter(p => p !== null);
                    allPoints.forEach((point, index) => {
                        new AdvancedMarkerElement({
                            position: new google.maps.LatLng(point.latitude, point.longitude),
                            map: map,
                            title: `Точка ${index + 1}`
                        });
                    });
                } else {
                    console.error("Маршрут не знайдено");
                }
            } catch (error) {
                console.error("Помилка отримання маршруту:", error);
            }
            let modal_submit_order = document.getElementById('modal_submit_order');
            modal_submit_order.removeAttribute("disabled");
            modal_submit_order.classList.remove('bg-gray-200');
            modal_submit_order.classList.remove('hover:bg-gray-300');
            modal_submit_order.classList.remove('dark:bg-gray-100');
            modal_submit_order.classList.remove('dark:hover:bg-gray-200');
            modal_submit_order.classList.remove('dark:focus:ring-gray-300');
            modal_submit_order.classList.add('bg-blue-700');
            modal_submit_order.classList.add('hover:bg-blue-800');
            modal_submit_order.classList.add('dark:bg-blue-600');
            modal_submit_order.classList.add('dark:hover:bg-blue-700');
            modal_submit_order.classList.add('dark:focus:ring-blue-800');
        }

        function ModalReset() {
            document.getElementById("modal_station_start").innerText = 'Loading...';
            document.getElementById("modal_station_end").innerText = 'Loading...';
            document.getElementById("modal_sum_weigth").innerText = 'Loading...';
            document.getElementById("modal_driver_and_auto_type").innerText = 'Loading... ( Loading... )';
            document.getElementById("modal_sum_vutratu").innerText = 'Loading...';
            document.getElementById("modal_sum_vuruchka").innerText = 'Loading...';
            document.getElementById("modal_sum_driver_zarplata").innerText = 'Loading...';
            document.getElementById("modal_sum_chista_vuruchka").innerText = 'Loading...';
            document.getElementById("modal_sum_distance").innerText = 'Loading...';
            ProgressBarModalReset();
            document.getElementById("TableRoutes").innerHTML =
                "<tr><td class='border border-slate-300 text-center' colspan='6 '>Loading...</td></tr>";
            let modal_submit_order = document.getElementById('modal_submit_order');
            modal_submit_order.setAttribute("disabled", "");
            modal_submit_order.classList.add('bg-gray-200');
            modal_submit_order.classList.add('hover:bg-gray-300');
            modal_submit_order.classList.add('dark:bg-gray-100');
            modal_submit_order.classList.add('dark:hover:bg-gray-200');
            modal_submit_order.classList.add('dark:focus:ring-gray-300');
            modal_submit_order.classList.remove('bg-blue-700');
            modal_submit_order.classList.remove('hover:bg-blue-800');
            modal_submit_order.classList.remove('dark:bg-blue-600');
            modal_submit_order.classList.remove('dark:hover:bg-blue-700');
            modal_submit_order.classList.remove('dark:focus:ring-blue-800');
        }

        function OpenModal() {
            document.getElementById(modalId).classList.remove('hidden');
            openModal = !openModal;
        }

        function CloseModal() {
            document.getElementById(modalId).classList.add('hidden');
            openModal = !openModal;
        }
    </script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&sensor=false&libraries=drawing,geometry,places,marker">
    </script>

@endsection

@section('modal')<!-- Main modal -->
    <div id="modalWindowCreateOrder" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-50 flex justify-center items-center bg-gray-500 bg-opacity-50">
        <div class="relative p-4 w-full max-w-full h-full max-h-full bg-white">

            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Об'єднання маршруту
                    </h3>
                    <button onclick="CloseModal()" type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5 space-y-4" style="height:450px;" id="modalContent">
                    <div id="modal_progress_bar" class="w-full h-auto">
                        <div class="text-center w-full">
                            <h4>Формування маршруту...</h4>
                        </div>
                        <div class="progress-bar bg-gray-300 rounded-sm h-2 w-100 mb-10">
                            <div id="modal_progress" class="progress bg-green-400 rounded-sm w-0 h-full"
                                style="transition: width 0.3s ease;"></div>
                        </div>
                    </div>

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-3 h-80 text-sm">
                            <p>Заповненість: <small id="modal_sum_weigth">Loading...</small> кг</p>
                            <p>Водій та тип авто: <small id="modal_driver_and_auto_type"> Loading... ( Loading... )</small>
                            </p>
                            <p>Довжина шляху: <small id="modal_sum_distance">Loading...</small> км</p>
                            <p>Витрати (Дизель): ~<small id="modal_sum_vutratu">Loading...</small> грн</p>
                            <p>Прибуток: ~<small id="modal_sum_vuruchka">Loading...</small> грн</p>
                            <p>Зарплата водія: ~<small id="modal_sum_driver_zarplata">Loading...</small> грн</p>
                            <p>Чистий прибуток: ~<small id="modal_sum_chista_vuruchka">Loading...</small> грн</p>
                            <p>Станція відправлення: <small id="modal_station_start">Loading...</small></p>
                            <p>Станція завершення маршруту: <small id="modal_station_end">Loading...</small></p>
                            <table class="overflow-y w-full">
                                <thead>
                                    <tr>
                                        <th class="border border-slate-300">#</th>
                                        <th class="border border-slate-300">Адреса</th>
                                        <th class="border border-slate-300">Дія</th>
                                        <th class="border border-slate-300">Продукт</th>
                                        <th class="border border-slate-300">Замовник</th>
                                        <th class="border border-slate-300">Дата відправлення</th>
                                    </tr>
                                </thead>
                                <tbody id="TableRoutes">
                                    <tr>
                                        <td class='border border-slate-300 text-center' colspan="6">Loading...</td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                        <div class="sm:col-span-3 h-80">
                            <div id="map" style="height: 320px; width: 450px;"></div>

                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button onclick="SubmitOrder()" id="modal_submit_order" disabled type="button"
                        class="text-white bg-gray-200 focus:ring-4 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center">Зберегти</button>
                    <button onclick="CloseModal()" type="button"
                        class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Повернутись</button>
                </div>
            </div>
        </div>
    </div>
@endsection
