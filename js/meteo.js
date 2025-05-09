class Meteo {
    constructor() {
    }

    cargarDatosMeteo() {
    const lat = 43.4672201;
    const lon = -5.1878242;
    const apiKey = "6a8c88d5dfc3c77a60ac49819cabd17a";
    const url = `https://api.openweathermap.org/data/2.5/forecast?lat=${lat}&lon=${lon}&appid=${apiKey}&units=metric&lang=es&mode=xml`;

    $.ajax({
        url: url,
        method: 'GET',
        dataType: 'xml',
        success: function(data) {
            $("main").empty(); // Clear the previous content

            // Parse the XML response
            const forecastList = $(data).find('time');
            let forecastsByDate = {};

            forecastList.each(function() {
                const date = $(this).attr('from'); 
                const day = date.split("T")[0]; 

                // Store the forecast for each day (get the forecast closest to 12:00 UTC)
                if (!forecastsByDate[day]) {
                    forecastsByDate[day] = $(this);
                }
            });

            // Process the forecasts for each day (limit to 7 days)
            let daysProcessed = 0;
            for (let day in forecastsByDate) {
                // if (daysProcessed >= 7) break;

                const forecast = forecastsByDate[day];
                const date = forecast.attr('from'); // Date of forecast
                const formattedDate = new Date(date).toLocaleDateString('es-ES', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                const temp = forecast.find('temperature').attr('max');
                const humidity = forecast.find('humidity').attr('value');
                const rain = forecast.find('precipitation').attr('value') || 0; // Rain (fallback to 0 if no rain data)
                const description = forecast.find('symbol').attr('name');
                const icono = forecast.find('symbol').attr('var');
                const iconUrl = `http://openweathermap.org/img/wn/${icono}@2x.png`;

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

                daysProcessed++;
            }
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
        this.crearElemento("main","","footer");     // Crea un elemento con DOM para los datos obtenidos con XML
        this.cargarDatosMeteo();
    }
}

var meteo = new Meteo();
