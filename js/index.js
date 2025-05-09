class Carrusel {
    constructor(contenedor) {
        this.contenedor = contenedor;
        this.imagenes = [
            'multimedia/img/carrusel/mapa.png',
            'multimedia/img/carrusel/carrusel1.jpg',
            'multimedia/img/carrusel/carrusel2.jpg',
            'multimedia/img/carrusel/carrusel3.jpg',
            'multimedia/img/carrusel/carrusel4.jpg'
        ];
        this.indiceActual = 0;
        this.iniciarCarrusel();
    }

    iniciarCarrusel() {
        const self = this;
        $(this.contenedor).html(`<img src="${this.imagenes[this.indiceActual]}" alt="Imagen del Carrusel">`);
        setInterval(function() {
            self.indiceActual = (self.indiceActual + 1) % self.imagenes.length;
            $(self.contenedor).html(`<img src="${self.imagenes[self.indiceActual]}" alt="Imagen del Carrusel">`);
        }, 2500);
    }
}

class Noticias {
    constructor(contenedor) {
        this.contenedor = contenedor;
        this.cargarNoticias();
    }

    cargarNoticias() {
        const self = this;
        const url = 'https://newsapi.org/v2/everything?q=Asturias&apiKey=949b922f2b504af58b76805646bed895&pageSize=5&language=es';

        $.getJSON(url, function(datos) {
            const totalResultados = datos.totalResults;
            console.log("Total de resultados: ", totalResultados);

            $(self.contenedor).append(`<p>Total de resultados: ${totalResultados}</p>`);

            if (totalResultados > 0) {
                datos.articles.forEach(function(noticia) {
                    $(self.contenedor).append(`
                        <article>
                            <h2><a href="${noticia.url}" target="_blank">${noticia.title}</a></h2>
                            <p>${noticia.description}</p>
                        </article>
                    `);
                });
            } else {
                $(self.contenedor).append('<p>No se encontraron noticias relacionadas con Caravia o Asturias en español.</p>');
            }
        }).fail(function() {
            $(self.contenedor).append('<p>Error al cargar las noticias.</p>');
        });
    }
}

$(document).ready(function() {
    const carrusel = new Carrusel('section');
    const noticias = new Noticias('main');
});