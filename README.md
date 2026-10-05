# Portal WordPress UAD FCPN

Tema y entorno local del portal de la **Unidad de Administración Desconcentrada de la Facultad de Ciencias Puras y Naturales — UMSA**.

## Funciones

- Biblioteca de documentos administrable desde WordPress.
- Categorías y gestiones/años.
- Número, fecha, palabras clave y archivo PDF.
- Búsqueda por título, descripción, número, palabras clave, categoría y gestión.
- Filtros AJAX con alternativa funcional sin JavaScript.
- Comunicados mediante entradas normales de WordPress.
- Diseño adaptable a computadora, tableta y teléfono.

## Instalación rápida del tema

Descarga [`release/uad-fcpn.zip`](release/uad-fcpn.zip) y en WordPress abre:

**Apariencia → Temas → Añadir nuevo → Subir tema**

Activa **UAD FCPN**. Las categorías documentales y la gestión del año actual se crearán automáticamente.

## Entorno local con Docker

Requisitos:

- Docker Desktop
- Docker Compose

Pasos:

1. Copia `docker/.env.example` como `docker/.env`.
2. Cambia las tres contraseñas marcadas con `CAMBIAR`.
3. Desde la carpeta `docker`, ejecuta:

```powershell
docker compose pull
docker compose up -d database wordpress
docker compose run --rm --entrypoint sh cli /scripts/setup-wordpress.sh
```

4. Abre <http://localhost:8088>.

La instalación crea seis documentos y dos comunicados demostrativos. Los documentos reales y sus archivos PDF se agregan desde **Documentos → Añadir documento**.

## Seguridad

El archivo `.env`, las contraseñas, la base de datos y los documentos subidos están excluidos del repositorio. La configuración Docker publica WordPress únicamente en `127.0.0.1`, por lo que el sitio local no queda expuesto a Internet ni a otros equipos de la red.

## Estructura

```text
theme/uad-fcpn/       Tema WordPress
release/uad-fcpn.zip  Tema listo para instalar
docker/               Entorno local reproducible
```
