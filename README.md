# Simple Auto Title

## ¿Por qué este módulo?

En Drupal, los títulos de los nodos son un campo obligatorio. Pero en muchos proyectos (catálogos de productos, portales de noticias, directorios), el título no debería ser libre, sino generarse automáticamente a partir de otros campos.

El módulo `auto_nodetitle` (muy popular en Drupal 7) quedó abandonado. `auto_entitylabel` es potente, pero demasiado complejo para necesidades sencillas.

**Simple Auto Title** nace para cubrir ese hueco: una solución ligera, fácil de configurar y enfocada exclusivamente en nodos.

## Descripción
Módulo ligero para Drupal 10/11 que genera automáticamente el título de los nodos basado en patrones de tokens. Perfecto para catálogos, portales de noticias o cualquier sitio donde el título deba ser predecible y consistente.

## Características
- ✅ Generación automática de títulos usando tokens
- ✅ Selección granular por tipo de contenido
- ✅ Opción para ocultar el campo título en los formularios
- ✅ Limpieza automática al eliminar tipos de contenido
- ✅ Instalación y desinstalación limpias (sin basura en la base de datos)
- ✅ Compatible con Drupal 10 y 11

## Instalación
1. Colocar el módulo en `/web/modules/custom/`
2. Ejecutar `drush en simple_auto_title -y`
3. Configurar en `/admin/config/content/simple-auto-title`

## Configuración
1. Selecciona los tipos de contenido donde aplicar el título automático
2. Define el patrón (ej: `[node:field_marca] [node:field_modelo] - [node:nid]`)
3. Opcional: oculta el campo título en los formularios

## Tokens útiles
- `[node:nid]` - ID del nodo
- `[node:title]` - Título original (si está visible)
- `[node:created:custom:Y-m-d]` - Fecha de creación
- `[node:field_NOMBRE]` - Cualquier campo personalizado

## Requisitos
- Drupal 10 o 11
- Módulo Token (https://www.drupal.org/project/token)

## Autor
[Carmelo Garmendia]  
🔗 [LinkedIn](https://www.linkedin.com/in/carmelo-garmendia-desarrollo-web/)  
🐙 [GitHub](https://www.github.com/carmelogarmendia-dev)
