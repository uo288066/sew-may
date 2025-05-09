import xml.etree.ElementTree as ET
import os
import svgwrite
import matplotlib.pyplot as plt

def crear_svg(archivo_xml):
    # Leer el archivo XML
    try:
        arbol = ET.parse(archivo_xml)
    except IOError:
        print(f'No se encuentra el archivo {archivo_xml}')
        return
    except ET.ParseError:
        print(f'Error procesando el archivo XML {archivo_xml}')
        return

    raiz = arbol.getroot()

    # Iterar sobre las rutas
    for ruta in raiz.findall('ruta'):  # Buscar todas las rutas
        # Obtener el nombre de la ruta como un atributo
        nombre_ruta = ruta.find('nombre')
        if nombre_ruta is None:
            print("No se encontró el nombre de la ruta.")
            continue
        nombre_ruta = nombre_ruta.text

        archivo_svg = f'xml/{nombre_ruta.replace(" ", "_")}.svg'  # Crear un nombre de archivo SVG basado en el nombre de la ruta

        # Listas para almacenar las distancias y altitudes
        distancias = []
        altitudes = []

        # Iterar sobre los hitos para recolectar las coordenadas y las altitudes
        for hito in ruta.findall('hitos/hito'):
            coordenadas_hito = hito.find('coordenadas')
            lat = float(coordenadas_hito.get('latitud'))
            lon = float(coordenadas_hito.get('longitud'))
            alt = float(coordenadas_hito.get('altitud'))

            distancias.append(lon)  # Usamos longitud como distancia horizontal
            altitudes.append(alt)   # Altitud para el gráfico

        # Crear el gráfico en formato SVG
        plt.figure(figsize=(8, 6))
        plt.plot(distancias, altitudes, marker='o', color='b', linestyle='-', linewidth=2, markersize=5)
        plt.title(f'Altimetría de la Ruta: {nombre_ruta}')
        plt.xlabel('Distancia (Longitud)')
        plt.ylabel('Altitud (m)')
        plt.grid(True)

        # Crear un nuevo archivo SVG
        dwg = svgwrite.Drawing(archivo_svg, profile='tiny', size=("800px", "600px"))

        # Establecer fondo claro
        dwg.add(dwg.rect(insert=(0, 0), size=("100%", "100%"), fill="white"))

        # Título de la ruta
        dwg.add(dwg.text(f'Altimetría de la Ruta: {nombre_ruta}', insert=(20, 30), font_size="20", fill="black"))

        # Definir márgenes y área del gráfico
        margin = 50
        width = 700
        height = 400
        max_distancia = max(distancias)
        min_distancia = min(distancias)
        max_altitud = max(altitudes)
        min_altitud = min(altitudes)

        # Ajustar las escalas para que los datos caben en el gráfico
        scale_x = width / (max_distancia - min_distancia)
        scale_y = height / (max_altitud - min_altitud)

        # Dibujar el camino (usando una línea suave)
        points = [(margin + (distancia - min_distancia) * scale_x, height + margin - (altitud - min_altitud) * scale_y)
                  for distancia, altitud in zip(distancias, altitudes)]

        # Usar una línea curva (path) para hacerla más natural
        path = dwg.path(d="M{},{}".format(points[0][0], points[0][1]), fill="none", stroke="blue", stroke_width=3)

        for point in points[1:]:
            path.push(f"L {point[0]},{point[1]}")

        dwg.add(path)

        # Agregar puntos de los hitos
        for point in points:
            dwg.add(dwg.circle(center=point, r=5, fill="red"))

        # Añadir etiquetas de altitud cerca de los puntos
        for i, point in enumerate(points):
            dwg.add(dwg.text(f'{altitudes[i]}m', insert=(point[0] + 5, point[1]), font_size="10", fill="black"))

        # Añadir un marco con el borde del gráfico
        dwg.add(dwg.rect(insert=(margin, margin), size=(width, height), fill="none", stroke="black"))

        # Guardar el archivo SVG
        dwg.save()

        print(f'Archivo SVG generado: {archivo_svg}')


def main():
    archivo_xml = 'xml/Rutas.xml'  # Ruta del archivo XML
    crear_svg(archivo_xml)


if __name__ == '__main__':
    main()
