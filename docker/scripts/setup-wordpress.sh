#!/bin/sh
set -eu

cd /var/www/html

echo "Esperando a que WordPress y la base de datos estén disponibles..."
attempt=0
until wp db check --quiet >/dev/null 2>&1; do
  attempt=$((attempt + 1))
  if [ "$attempt" -ge 40 ]; then
    echo "La base de datos no respondió a tiempo." >&2
    exit 1
  fi
  sleep 3
done

new_install=0
if ! wp core is-installed >/dev/null 2>&1; then
  wp core install \
    --url="$WP_SITE_URL" \
    --title="$WP_SITE_TITLE" \
    --admin_user="$WP_ADMIN_USER" \
    --admin_password="$WP_ADMIN_PASSWORD" \
    --admin_email="$WP_ADMIN_EMAIL" \
    --skip-email
  new_install=1
fi

wp theme activate uad-fcpn
wp option update timezone_string America/La_Paz
wp option update date_format 'j \d\e F \d\e Y'
wp option update time_format 'H:i'
wp option update blogdescription 'Portal de información y documentación administrativa'
wp option update permalink_structure '/%postname%/'
wp rewrite flush --hard

if [ "$new_install" -eq 1 ]; then
  wp post delete 1 --force >/dev/null 2>&1 || true
  wp post delete 2 --force >/dev/null 2>&1 || true

  wp post create \
    --post_type=post \
    --post_status=publish \
    --post_title='Actualización de procedimientos administrativos' \
    --post_excerpt='Se informa a las unidades dependientes sobre la actualización de procedimientos administrativos internos.' \
    --post_content='La Unidad de Administración Desconcentrada informa que los procedimientos administrativos serán publicados y actualizados en este portal institucional.' >/dev/null

  wp post create \
    --post_type=post \
    --post_status=publish \
    --post_title='Recepción y seguimiento de documentación' \
    --post_excerpt='Información sobre presentación, recepción, registro y seguimiento de documentación administrativa.' \
    --post_content='Para consultas sobre el seguimiento de documentación, comuníquese con la Unidad de Administración Desconcentrada.' >/dev/null

  create_document() {
    title="$1"
    excerpt="$2"
    category="$3"
    number="$4"
    keywords="$5"

    post_id=$(wp post create \
      --post_type=uad_documento \
      --post_status=publish \
      --post_title="$title" \
      --post_excerpt="$excerpt" \
      --post_content="$excerpt" \
      --porcelain)

    wp post term add "$post_id" uad_categoria_documento "$category" >/dev/null
    wp post term add "$post_id" uad_gestion_documento 2026 >/dev/null
    wp post meta update "$post_id" _uad_document_number "$number" >/dev/null
    wp post meta update "$post_id" _uad_document_date '2026-10-05' >/dev/null
    wp post meta update "$post_id" _uad_document_keywords "$keywords" >/dev/null
    wp post meta update "$post_id" _uad_demo 1 >/dev/null
  }

  create_document \
    'Instructivo para la gestión de trámites administrativos' \
    'Lineamientos para la presentación y seguimiento de trámites administrativos.' \
    instructivos 'UAD N.º 001/2026' 'trámites, administración, procedimientos'

  create_document \
    'Circular Administrativa — Gestión 2026' \
    'Disposiciones administrativas vigentes para las unidades dependientes.' \
    circulares 'FCPN N.º 012/2026' 'circular, administración, gestión 2026'

  create_document \
    'Comunicado Institucional' \
    'Información general emitida por la Unidad de Administración Desconcentrada.' \
    comunicados 'UAD COM. 003/2026' 'comunicado, información, FCPN'

  create_document \
    'Reglamento Interno Administrativo' \
    'Reglamento de referencia para la gestión administrativa institucional.' \
    reglamentos 'REG. INT. 01/2026' 'reglamento, gestión, institucional'

  create_document \
    'Normativa para Procesos Administrativos' \
    'Normativa aplicable a procedimientos y documentación administrativa.' \
    normativas 'NORM. 005/2026' 'normativa, procesos, documentación'

  create_document \
    'Circular sobre Presentación de Documentación' \
    'Requisitos para la entrega y recepción de documentación administrativa.' \
    circulares 'FCPN N.º 021/2026' 'presentación, documentos, recepción'
fi

echo "WordPress UAD está instalado y configurado en $WP_SITE_URL"

