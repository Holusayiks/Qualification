@extends('layouts.main')
@section('title', 'Головна')

@section('navlinks')
    @include('navigations.manadger')
@endsection
@section('responsive_navlinks')
    @include('responsive_navigations.manadger')
@endsection
<?=
$hide_main=true;
?>
@section('before-content')
    <main class="flex-1 p-6 space-y-6">

        <!-- Блок статистики -->
        <!-- <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded shadow">
                <h2 class="text-xl font-semibold mb-2">Водії у рейсі</h2>
                <p class="text-3xl font-bold">...</p>
            </div>
            <div class="bg-white p-6 rounded shadow">
                <h2 class="text-xl font-semibold mb-2">Авто в ремонті</h2>
                <p class="text-3xl font-bold">...</p>
            </div>
            <div class="bg-white p-6 rounded shadow">
                <h2 class="text-xl font-semibold mb-2">Відкриті замовлення</h2>
                <p class="text-3xl font-bold">...</p>
            </div>
        </section> -->
        <!-- Блок статистики -->
        <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="flex flex-col">
                <div class="bg-white p-6 rounded shadow flex flex-col justify-between mb-3">
                    <div class="h-auto w-full">
                        <h2 class="text-xl font-semibold mb-2">{{ $manadger->full_name }}</h2>
                        <p><b>Телефон:</b> {{ $manadger->phone }}</p>
                        <p><b>Станція:</b> №{{ $manadger->station->nomer }}</p>
                    </div>
                    <button class="btn bg-sky-400 m-1 p-1 rounded-sm text-white"
                        onclick="location.href='/menedger/dashboard/{{ $manadger->id }}/edit'">Змінити дані</button>
                </div>
                <div class="bg-white p-6 rounded shadow flex flex-col justify-between mt-3">
                    <div class="h-auto w-full text-xl">
                        <p><b>Загальний страховий фонд:</b><br> {{ $insurance }} грн</p>
                    </div>
                </div>


            </div>
            <div class="bg-white p-6 rounded shadow">
                <h2 class="text-xl font-semibold mb-2">Замовлення за останній місяць</h2>
                @if (count($orders)==0)
                <p>Замовлень в цьому місяці не було</p>
                @else
                <ul class="overflow-y-scroll max-h-[215px]">
                    @foreach ($orders as $order)
                        <li><b>{{ $order->status }}</b> {{ $order->product }}
                            <i>{{ date('d.m.Y', strtotime($order->date_of_start)) }}
                                <small>({{ $order->supplier->phone }})</small></i></li>
                    @endforeach
                </ul>
                @endif
            </div>
            <div class="bg-white p-6 rounded shadow">
                <h2 class="text-xl font-semibold mb-2">Витрати та прибуток за місяць</h2>
                <div id="chart">
                </div>
            </div>
        </section>
        <!-- Блок статистики -->
        <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded shadow sm:col-span-2">
                <h2 class="text-xl font-semibold mb-2">Статистика за останні півроку</h2>
                <div id="chart3">
                </div>
            </div>
            <div class="bg-white p-6 rounded shadow sm:col-span-1">
                <h2 class="text-xl font-semibold mb-2">Авто на станції</h2>
                <div id="chart2"></div>
            </div>
        </section>
    </main>

@endsection
@section('content')


@endsection

@section('script')
    <script>
        function getFinanse() {
            return new Promise((resolve, reject) => {
                return fetch("http://localhost:8000/api/stations/" + <?php echo $manadger->station->id; ?> + "/finanse")
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

        function getFinanse2() {
            return new Promise((resolve, reject) => {
                return fetch("http://localhost:8000/api/stations/" + <?php echo $manadger->station->id; ?> + "/finanse2")
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

        function getAutoData() {
            return new Promise((resolve, reject) => {
                return fetch("http://localhost:8000/api/stations/" + <?php echo $manadger->station->id; ?> + "/autodata")
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

        function FillApexChart(data) {
            if (data.message == null) {
                var options = {
                    series: data.series,
                    chart: {
                        width: 340,
                        type: 'pie',
                    },
                    fill: {
                        colors: ['#667AD9', '#89E551']
                    },
                    labels: data.labels,
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            chart: {
                                width: 180
                            },
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }]
                };

                var chart = new ApexCharts(document.querySelector("#chart"), options);
                chart.render();

            } else {
                document.getElementById('chart').innerText = data.message;
            }
        }

        function FillApexChart2(data) {
            if (data.message == null) {
                var options = {
                    series: data.series,
                    chart: {
                        width: 340,
                        type: 'pie',
                    },
                    fill: {
                        colors: ['#E2EA41', '#F37070', '#CD7CD4']
                    },
                    labels: data.labels,
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            chart: {
                                width: 180
                            },
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }]
                };

                var chart = new ApexCharts(document.querySelector("#chart2"), options);
                chart.render();

            } else {
                document.getElementById('chart').innerText = data.message;
            }
        }

        function FillApexChart3(data) {
            var options = {
                series: [{
                    name: 'Доставлено',
                    data: data.param1,
                }, {
                    name: 'Скасовано',
                    data: data.param2,
                }, {
                    name: 'Всього',
                    data: data.param3,
                }],
                chart: {
                    type: 'bar',
                    height: 350
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        borderRadius: 5,
                        borderRadiusApplication: 'end'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: data.labels,
                },
                yaxis: {
                    title: {
                        text: 'Замовлення'
                    }
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return " " + val + " замовлень"
                        }
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#chart3"), options);
            chart.render();


        }
        async function Start() {
            const data = await getFinanse();
            const data2 = await getAutoData();
            const data3 = await getFinanse2();
            FillApexChart(data)
            FillApexChart2(data2)
            FillApexChart3(data3)
        }
        Start();
    </script>
@endsection
