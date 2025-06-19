@extends('layouts.main')
@section('title', 'Водій')

@section('navlinks')
    @include('navigations.driver')
@endsection
@section('responsive_navlinks')
    @include('responsive_navigations.driver')
@endsection

@section('content')
    <h3 class="text-xl">{{ $driver->full_name }}</h3>
    @if ($driver->auto != null)
        <p><b>Авто:</b> {{ $driver->auto->nomer }} ({{ $driver->auto->type }})</p>
    @else
        <p><b>Авто:</b> - </p>
    @endif
    <p><b>Номер телефону:</b> {{ $driver->phone }}</p>
    <p><b>Станція:</b> №{{ $driver->station->nomer }}</p>

    <p><b>Менеджер:</b> {{ $driver->station->menedger->full_name }} ({{ $driver->station->menedger->phone }})</p>
    <p><b>Диспетчер:</b> {{ $driver->station->dispetcher->full_name }} ({{ $driver->station->menedger->phone }})</p>

    @if ($driver->status != 'В дорозі')
        <p><b>Поточна станція:</b> {{ $driver->current_station == null ? 'В дорозі' : '№' . $driver->current_station }}</p>
    @endif
    <?php
    $vacation_clr = 'yellow';
    if ($driver->status == 'В відпусці' || $driver->status == 'Хочу в відпустку' || $driver->status == 'В дорозі') {
        $vacation_clr = 'gray';
    }
    ?>
    <p class="tx-{{ $vacation_clr }}-500"><b class='text-black'>Статус:</b>
        {{ $driver->status == 'Хочу в відпустку' ? 'Очікування підтвердження на відпустку з ' . date('d/m/Y', strtotime($driver->date_of_vacation)) . ' на ' . $driver->days_in_vacation . ' днів' : $driver->status }}
        <!-- vacantioncancel -->
        @if ($driver->status == 'Хочу в відпустку')
            <button class="btn text-white bg-cyan-400 text-sm p-0.5 m-0 rounded"
                onclick="location.href='/driver/dashboard/vacantioncancel'">Повернути заявку</button>
        @endif
    </p>
    @if ($message != '')
        <div class="flex items-center p-4 mb-4 text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400"
            role="alert">
            <svg class="shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                viewBox="0 0 20 20">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <div class="ms-3 text-sm font-medium">
                {{ $message }}
              </div>
            <button onclick="location.href='/driver/dashboard/messagereaded'" type="button"
                class="ms-auto -mx-1.5 -my-1.5 bg-blue-50 text-blue-500 rounded-lg focus:ring-2 focus:ring-blue-400 p-1.5 hover:bg-blue-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-blue-400 dark:hover:bg-gray-700">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>
    @endif
    <p><b>Дата наймання на роботу:</b> {{ date('d M Y', strtotime($driver->hire_date)) }}</p>
    <button class="btn bg-sky-400 m-1 p-1 rounded-sm text-white"
        onclick="location.href='/driver/dashboard/{{ $driver->id }}/edit'">Змінити мої дані</button>
    <button class="btn m-1 p-1 rounded-sm text-white bg-{{ $vacation_clr }}-400"
        {{ $vacation_clr == 'yellow' ? '' : 'disabled' }} onclick="OpenModal('modalVacation')">Хочу в відпустку</button>

@endsection
@section('script')

    <script>
        let openModal = false;
        let modalId = null;

        function OpenModal(modal_id) {
            modalId = modal_id;
            document.getElementById(modalId).classList.remove('hidden');
            openModal = !openModal;
        }

        function CloseModal() {
            document.getElementById(modalId).classList.add('hidden');
            openModal = !openModal;
        }
    </script>
    @if ($errors->any())
        <script>
            OpenModal('modalVacation');
        </script>
    @endif
@endsection

@section('modal')<!-- Main modal -->
    <div id="modalVacation" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-50 flex justify-center items-center bg-gray-500 bg-opacity-50">
        <div class="relative p-4 w-full max-w-full h-full max-h-full bg-white">

            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Заявка на відпустку
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
                <form action="/driver/dashboard/status" method="POST">
                    @csrf
                    <div class="p-4 md:p-5 space-y-4" id="modalContent">
                        <label for="date_of_vacation" class="block text-sm font-medium leading-6 text-gray-900">Дата початку
                            відпустки:</label>
                        <input
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900
                    shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400
                    focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                            type="date" min="{{ date('Y-m-d') }}" id="date_of_vacation" name="date_of_vacation">
                        <label for="days_in_vacation" class="block text-sm font-medium leading-6 text-gray-900">Кількість
                            днів:</label>
                        <input
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900
                    shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400
                    focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                            type="number" min="1" max="31" id="days_in_vacation" name="days_in_vacation">
                        @if ($errors->any())
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 mt-3 py-3 rounded relative"
                                role="alert">
                                <strong class="font-bold">Помилка</strong>
                                <span class="block sm:inline">

                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </span>
                                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                                    <svg class="fill-current h-6 w-6 text-red-500" role="button"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <title>Close</title>
                                        <path
                                            d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                                    </svg>
                                </span>
                            </div>

                        @endif
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                        <button type="submit"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Подати
                            заявку</button>
                        <button onclick="CloseModal()" type="button"
                            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Повернутись</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
