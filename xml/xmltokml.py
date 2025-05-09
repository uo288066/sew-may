import xml.etree.ElementTree as ET
import os

def crear_kml(archivo_xml):
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
    for ruta in raiz.findall('ruta'):  # Asegurarse de que "ruta" esté correctamente
        # Obtener el nombre de la ruta
        nombre_ruta = ruta.find('nombre')
        if nombre_ruta is None:
            print("No se encontró el nombre de la ruta.")
            continue
        nombre_ruta = nombre_ruta.text

        archivo_kml = f'xml/{nombre_ruta.replace(" ", "_")}.kml'  # Crear un nombre de archivo KML basado en el nombre de la ruta

        # Crear el archivo KML para cada ruta
        with open(archivo_kml, 'w', encoding='utf-8') as salida:
            salida.write('<?xml version="1.0" encoding="UTF-8"?>\n')
            salida.write('<kml xmlns="http://www.opengis.net/kml/2.2">\n')
            salida.write('<Document>\n')

            salida.write(f'<name>{nombre_ruta}</name>\n')

            # Extraer y añadir las coordenadas iniciales
            inicio = ruta.find('inicio/coordenadas')
            if inicio is None:
                print(f"No se encontraron las coordenadas de inicio para la ruta {nombre_ruta}.")
                continue
            latitud = inicio.get('latitud')
            longitud = inicio.get('longitud')
            altitud = inicio.get('altitud')

            salida.write('<Placemark>\n')
            salida.write('    <name>Punto de Inicio</name>\n')
            salida.write('    <Point>\n')
            salida.write(f'        <coordinates>{longitud},{latitud},{altitud}</coordinates>\n')
            salida.write('    </Point>\n')
            salida.write('</Placemark>\n')

            # Extraer los hitos y crear líneas
            salida.write('<Placemark>\n')
            salida.write('    <name>Tramos de la Ruta</name>\n')
            salida.write('    <LineString>\n')
            salida.write('        <coordinates>\n')

            # Iterar sobre los hitos y añadir coordenadas
            for hito in ruta.findall('hitos/hito'):
                coordenadas_hito = hito.find('coordenadas')
                lat = coordenadas_hito.get('latitud')
                lon = coordenadas_hito.get('longitud')
                alt = coordenadas_hito.get('altitud')

                salida.write(f'            {lon},{lat},{alt}\n')

            salida.write('        </coordinates>\n')
            salida.write('    </LineString>\n')
            salida.write('</Placemark>\n')

            salida.write('</Document>\n')
            salida.write('</kml>\n')

        print(f'Archivo KML generado: {archivo_kml}')


def main():
    archivo_xml = 'xml/Rutas.xml'  # Ruta del archivo XML
    crear_kml(archivo_xml)


if __name__ == '__main__':
    main()
