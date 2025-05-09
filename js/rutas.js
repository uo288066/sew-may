class Ruta {
    constructor() {}

    cargarDatosRutas() {
        fetch("xml/Rutas.xml")
            .then(response => response.text())
            .then(str => new window.DOMParser().parseFromString(str, "text/xml"))
            .then(xml => {
                document.querySelector("main").innerHTML = "";  // Limpiar contenido previo

                xml.querySelectorAll("ruta").forEach(rutaXML => {
                    const nombre = rutaXML.querySelector("nombre").textContent;
                    const tipo = rutaXML.querySelector("tipo").textContent;
                    const descripcion = rutaXML.querySelector("descripcion").textContent;
                    const duracion = rutaXML.querySelector("duracion").textContent;
                    const medioTransporte = rutaXML.querySelector("transporte").textContent;
                    const agencia = rutaXML.querySelector("agencia").textContent;
                    const altimetria = rutaXML.querySelector("Altimetria").getAttribute("archivo");
                    const planimetria = rutaXML.querySelector("Planimetria").getAttribute("archivo");

                    // Coordenadas de inicio para el mapa
                    const coords = rutaXML.querySelector("inicio coordenadas");
                    const lat = coords.getAttribute("latitud");
                    const lon = coords.getAttribute("longitud");

                    // Crear sección para la ruta
                    const article = document.createElement("article");

                    // Contenido principal de la ruta
                    article.innerHTML = `
                        <h2>${nombre}</h2>
                        <p><strong>Tipo:</strong> ${tipo}</p>
                        <p><strong>Descripción:</strong> ${descripcion}</p>
                        <p><strong>Duración:</strong> ${duracion}</p>
                        <p><strong>Medio de Transporte:</strong> ${medioTransporte}</p>
                        <p><strong>Agencia:</strong> ${agencia}</p>
                    `;

                    //=========================================================
                    // Hitos
                    const hitosArticle = document.createElement("article");
                    let hitosHTML = "<h3>HITOS</h3><ul>";
                    rutaXML.querySelectorAll("hitos hito").forEach(hito => {
                        const hitoNombre = hito.querySelector("nombre").textContent;
                        const hitoDescripcion = hito.querySelector("descripcion").textContent;
                        let imagenesHTML = "";
                        hito.querySelectorAll("fotos foto").forEach(foto => {
                            imagenesHTML += `<img src="${foto.textContent}" alt="${hitoNombre}" width="100"> `;
                        });
                        hitosHTML += `<li><strong>${hitoNombre}</strong> ${hitoDescripcion}<br>${imagenesHTML}</li>`;
                    });
                    hitosHTML += "</ul>";

                    hitosArticle.innerHTML = hitosHTML;
                    article.append(hitosArticle);

                    //=========================================================
                    // Crear aside para altimetría y mapa
                    const aside = document.createElement("aside");

                    //=========================================================
                    // Altimetría
                    const img = document.createElement("img");
                    img.setAttribute("src", altimetria);
                    img.setAttribute("style", "object-fit: contain;");  // Asegura que el contenido se ajuste sin recorte

                    //=========================================================
                    // Titulo y contenedor del mapa
                    const contenedorMapa = document.createElement("article");
                    contenedorMapa.style.width = "100%";
                    contenedorMapa.style.height = "300px";
                    contenedorMapa.style.margin = "10px 0";
                    
                    // Generar un id único para cada mapa
                    const mapaId = `mapa-${Math.random().toString(36).substr(2, 9)}`;
                    contenedorMapa.setAttribute("id", mapaId);

                    //=========================================================
                    // Añadir elementos al aside
                    aside.appendChild(img);
                    aside.appendChild(contenedorMapa);

                    // Añadir aside al section y section al main
                    article.appendChild(aside);
                    document.querySelector("main").appendChild(article);

                    // Crear el mapa Leaflet y añadirlo
                    setTimeout(() => {
                        const mapa = L.map(mapaId).setView([lat, lon], 14);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                        }).addTo(mapa);
                        L.marker([lat, lon]).addTo(mapa).bindPopup(nombre).openPopup();

                        // Cargar KML con omnivore
                        const kmlFile = planimetria;
                        omnivore.kml(kmlFile).on('ready', function () {
                            mapa.fitBounds(this.getBounds());
                        }).on('error', function () {
                            console.error("Error al cargar el archivo KML:", kmlFile);
                        }).addTo(mapa);
                    }, 100);  // Esperar un momento para que se cargue el mapa correctamente
                });
            })
            .catch(error => {
                console.error("Error al cargar el archivo XML:", error);
                alert("Error al cargar las rutas.");
            });
    }

    crearElemento(tipoElemento, texto) {
        let elemento = document.createElement(tipoElemento);
        elemento.textContent = texto;
        document.querySelector("body").appendChild(elemento);
    }

    verXML() {
        const main = document.querySelector("main");
        console.log(main); // Verifica que el elemento 'main' existe

        if (main) {
            this.crearElemento("main", "");
            this.cargarDatosRutas();
        } else {
            console.error("Elemento 'main' no encontrado");
        }
    }
}

var ruta = new Ruta();
ruta.verXML();
