
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
            throw new Error(`Geocoding error: ${data.status}`);
        }
    } catch (error) {
        console.error("Error fetching coordinates:", error);
        return null;
    }
}

async function getDistancies(v, w) {
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
