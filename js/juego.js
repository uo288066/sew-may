class Test {
    constructor(preguntas) {
        this.preguntas = preguntas;
    }

    iniciarJuego() {
        const main = document.querySelector("main");
        main.innerHTML = ""; // Limpiar contenido previo

        const form = document.createElement("form");
        form.name = "quiz";

        this.preguntas.forEach((pregunta, index) => {
            const article = document.createElement("article");

            const preguntaElemento = document.createElement("h3");
            preguntaElemento.textContent = pregunta[0];
            article.appendChild(preguntaElemento);

            pregunta[1].forEach((opcion, i) => {
                const input = document.createElement("input");
                input.type = "radio";
                input.name = "p" + index;
                input.value = i;

                const label = document.createElement("label");
                label.appendChild(input);
                label.appendChild(document.createTextNode(opcion));

                article.appendChild(label);
                article.appendChild(document.createElement("br"));
            });

            form.appendChild(article);
        });

        const articleb = document.createElement("article");
        articleb.innerHTML = "";

        const botonFinalizar = document.createElement("button");
        botonFinalizar.type = "button";
        botonFinalizar.textContent = "Finalizar Juego";

        const resultado = document.createElement("p");

        const botonReiniciar = document.createElement("button");
        botonReiniciar.type = "button";
        botonReiniciar.textContent = "Reiniciar Juego";
        botonReiniciar.disabled = true;
        botonReiniciar.style.opacity = 0.5;

        botonFinalizar.onclick = () => this.calcularPuntaje(form, resultado, botonReiniciar);
        botonReiniciar.onclick = () => this.iniciarJuego();

        main.appendChild(form);
        main.appendChild(articleb);
        articleb.appendChild(botonFinalizar);
        articleb.appendChild(resultado);
        articleb.appendChild(botonReiniciar);
    }

    calcularPuntaje(form, resultado, botonReiniciar) {
        const respuestas = new FormData(form);
        let puntaje = 0;

        if ([...respuestas].length !== this.preguntas.length) {
            alert("Debes responder todas las preguntas.");
            return;
        }

        this.preguntas.forEach((pregunta, index) => {
            if (parseInt(respuestas.get("p" + index)) === pregunta[2]) {
                puntaje++;
            }
        });

        resultado.textContent = `Tu puntuación es: ${puntaje}/10`;
        botonReiniciar.disabled = false;
        botonReiniciar.style.opacity = 1;
    }
}

const preguntas = [
  ["1. ¿Cuál es la capital del concejo de Caravia?", ["Oviedo", "Gijón", "Caravia", "Madrid", "Santander"], 2],
  ["2. ¿Qué medio de transporte puedes utilizar para ir de ruta en Caravia?", ["Bicicleta", "Coche", "Transporte público", "A pie", "Tren"], 0],
  ["3. ¿Qué mar baña la costa de Caravia?", ["Mediterráneo", "Cantábrico", "Atlántico", "Rojo", "Negro"], 1],
  ["4. ¿Qué tipo de paisaje predomina en Caravia?", ["Desierto", "Montañoso", "Selva", "Llanura", "Costero"], 4],
  ["5. ¿Cuál es la fiesta más representativa de Caravia?", ["San Juan", "Semana Santa", "Nuestra Señora de la Consolación", "San Fermín", "Carnaval"], 2],
  ["6. ¿Qué industria es clave en Caravia?", ["Minería", "Pesca", "Turismo", "Agricultura", "Ganadería"], 2],
  ["7. ¿Qué ruta senderista famosa pasa por Caravia?", ["Camino de Santiago", "Ruta de la Plata", "GR-99", "Senda del Oso", "Anillo de Picos"], 0],
  ["8. ¿Cómo se llama la playa más conocida de Caravia?", ["Playa de Gulpiyuri", "Playa de Rodiles", "Playa Arenal de Morís", "Playa de Vega", "Playa del Silencio"], 2],
  ["9. ¿Qué monumento emblemático se encuentra en Caravia?", ["Santa María del Naranco", "La Cuevona", "Ermita de San Roque", "Castillo de Tudela", "Puente Romano"], 2],
  ["10. ¿Qué producto gastronómico es típico de Caravia?", ["Fabes con Jabalí", "Queso Cabrales", "Fabada", "Miel", "Arroz con leche"], 0]
];

var test = new Test(preguntas);
