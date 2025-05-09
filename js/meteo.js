class Meteo {
    constructor() {
    }

    cargarDatosMeteo() {
        const lat = 43.4672201;
        const lon = -5.1878242;
        const apiKey = "85cb0cb228094d10838155202250905"; // Reemplaza con tu clave API
        const url = `https://api.weatherapi.com/v1/forecast.json?key=${apiKey}&q=${lat},${lon}&days=7&lang=es`;

        $.ajax({
            url: url,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                $("main").empty(); // Clear the previous content

                // Process the forecast for each day
                const forecastDays = data.forecast.forecastday;
                forecastDays.forEach(function(forecast) {
                    const date = new Date(forecast.date);
                    const formattedDate = date.toLocaleDateString('es-ES', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                    const temp = forecast.day.avgtemp_c;
                    const humidity = forecast.day.avghumidity;
                    const rain = forecast.day.totalprecip_mm || 0; // Rain (fallback to 0 if no rain data)
                    const description = forecast.day.condition.text;
                    const icon = forecast.day.condition.icon;
                    const iconUrl = `https:${icon}`;

                    $("main").append(`
                        <section>
                            <img src="${iconUrl}" alt="${description}">
                            <h3>${formattedDate}</h3>
                            <p>${description}</p>
                            <p>${temp}ºC</p>
                            <p>Humedad: ${humidity}%</p>
                            <p>Lluvia: ${rain} mm</p>
                        </section>
                    `);
                });
            },
            error: function() {
                alert("¡No se pudo obtener los datos del pronóstico!");
            }
        });
    }

    crearElemento(tipoElemento, texto, insertarAntesDe){
        var elemento = document.createElement(tipoElemento); 
        elemento.innerHTML = texto;
        $(insertarAntesDe).before(elemento);
    }

    verXML(){
        this.crearElemento("h4","Datos","footer");  // Crea un elemento con DOM 
        this.crearElemento("main","","footer");     // Crea un elemento con DOM para los datos obtenidos con JSON
        this.cargarDatosMeteo();
    }
}

var meteo = new Meteo();
