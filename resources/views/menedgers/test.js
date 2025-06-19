
//1. отримання точок
arr = [];
for (const order of choosedOrders) {
    const res = await getOrders(order._id);

    arr.push(res.point_A);
    arr.push(res.point_B);
}
distancies = [];
let start_index = 1;
for (const v of arr) {
    let arr2 = arr.slice(start_index);
    for (const w of arr2) {
        const res = await getDistancies(v, w);
        distancies.push(res);
    }
    start_index++;
}

for (let i = 0; i < arr.length; i += 2) {
    const res = await getDistancies(StationStart, arr[i]);
    distancies.push(res);
}
resArray = [];
let points_in_route = arr;

if (distancies.length != 1) {
    //кількість "підмаршрутів"
    numRoutes = arr.length - 1;
    //створення всіх комбінації
    Permute(arr, 0, arr.length - 1);
} else {
    numRoutes = 1;
    resArray.push({
        p: [arr[0], arr[1]],
        sum: 0,
    });
}
//
let stations = await getStations();
stations = stations.map((elem) => {
    return elem.address;
});

if (numRoutes == 1) {
    resArray[0].sum = CalcDistancies(resArray[0].p[0], resArray[0].p[1]);
    resArray[0].sum += await CalcDistancies(StationStart,resArray[0].p[0]);
}
else { 
    //перебір всіх комбінацій і вирахунок їх загальної суми довжин
    for (const [index, elem] of resArray.entries()) {
        if (CheckRoute(elem.p, index)) {
            let sum = 0;
            for (let i = 0; i < numRoutes - 1; i++) {
                sum += CalcDistancies(elem.p[i], elem.p[i + 1]);
            }
            //новий код
            sum += await CalcDistancies(StationStart, elem.p[0]);
            // закінчення нового коду
            elem.sum = sum;
        }
    };
    let resArray2 = resArray.filter(x => x.sum != 0);
    let resRoute = resArray2[0];
    if (numRoutes != 1) {
        resArray2.forEach((route) => {
            if (route.sum <= resRoute.sum) resRoute = route;
        });
    }
}

let station = await FindClosestPoint(resRoute.p[resRoute.p.length - 1], stations);
resRoute.sum += await getDistancies(resRoute.p[resRoute.p.length - 1], station);
