# Change Log
El formato de este documento está basado en [Keep a Changelog]
(http://keepachangelog.com/) y usa versionado semántico [http://semver.org/].


## [Unreleased]
### Fixed
- El campo jcropimages no soporta gif dinámicos

## [1.9.6] - 2017-12-06
### Added
- Nueva plantilla para crear modelos!
  (core\models\_plantilla_model.php)

- El límite del tamaño máximo para la subida de una foto ahora se calcula automáticamentetanto para recorte de fotos comop para las galerías
  (core\controllers\jcrop_ajax.php[load_uploader()] - core\controllers\gallery_ajax.php[load_uploader()])

### Fixed
- El límite del tamaño máximo para la subida de una foto ahora se calcula automáticamente
  (core\controllers\jcrop_ajax.php[load_uploader()] - core\controllers\gallery_ajax.php[load_uploader()])

- No intento redimensionar una foto si tiene ancho y altos automáticos.
 (core\controllers\jcrop_ajax.php[crop_photo()])

- Corrijo un error introducido en algn momento que hacía que no se mostrara el sistema de recorte al subir una foto que sí debía cortarse (solo apsaba cuando no había ninguna foto con algún lado automático)
 (core\controllers\jcrop_ajax.php[load_uploader()])

- Ya no se rompe cuando se carga una foto con extensiónes en mayúsculas.
 (core\controllers\jcrop_ajax.php[upload_photo()])


## [1.9.5] - 2017-10-02
### Fixed
- No filtro cuando la fecha no está definida.
  (core\core\MY_Controller.php[getFilters()])

## [1.9.4] - 2017-09-07
### Added
- Ahora se puede hacer validaciones dentro del método actualizarRelacion()
  (core\MY_Controller.php)

### Fixed
- Oculto el campo usando overflow en vez de display para que se envía el valor con el formulario AJAX.
  (core\views\form.php)

- Actualizo la URI de las fotos que se muestran en los listados para no mostrar una foto cacheada.
  (views\includes\lista\campos.php)

- Vuelvo a la URL de retorno cuando cancelo la edición de un elemento
  (core\views\form.php)

- Permito que un filtro definido con $_GET sirva aunque el campo en cuestión no tenga filter = true.
  (core\MY_Controller.php [getFilters()])

- Mantengo el filtro por categoría ($_GET['categ']) cuando se limpia una búsqueda
  (views\includes\lista\buscador.php)

- Aplico los filtros actuales en los elementos que se exportan en el XLS
  (views\includes\lista\boton_xml.php)

- Filtro los campos por defecto de orden por la tabla principal del modelo. Si hacías algun JOIN se rompía.
  (core\core\MY_Controller.php[getOrders()])


## [1.9.3] - 2017-08-30
### Added
- Nueva opción "tipo" para los campos child_relation_popup_list.
Ahora puede ser iframe o popup.
(views\campos\child_relation_popup_list.php - core\MY_Controller.php)

- Nueva opción para deshabilitar el redireccionamiento a la URL de retorno
con $_GET['return_url']=false
(core\MY_Controller.php)

### Changed
- Muestro un error en log si hubo un problema guardando un ítem.
  (core\MY_Model.php)

### Fixed
- Campo "child_relation_popup_list": Las relaciones se estaban guardando al revés. Además elimino toda las relaciones, no solo una (podían llegar a estar repetidas).
  (core\MY_Controller.php[actualizarRelacionCRPL()]

- Mantengo el orden de los nuevos criterios de orden
  (core\MY_Controller.php[getOrders()])


## [1.9.2] - 2017-08-29
### Added
-  Ahora los campos form_textarea se pueden listar: se muestra solo lo primeros 20 carateres 
y el resto se puede ver como un tooltip.
  (+core\helpers\recortar_cadena_helper.php - core/views/includes/lista/campos.php)


## [1.9.1] - 2017-08-09
### Added
- Nueva opción  "NO_RECORTAR_RV" en Gallery_AJAX para no recortar la imagen cuando 
  la original es vertical.
  (core\controllers\gallery_ajax.php)


## [1.9] - 2017-07-27
### Added
- Nueva opción "thumbnail" para los campos file_upload que suben PDFs.
  (core\core\MY_Controller.php - cms\core\libraries\functions.php)

- Ahora un campo checkbox puede usar como filtro
  (core\js\functions.js[filtrar()] - core\libraries\functions.php[make_form()])

- Nueva opción "acciones_en_lote". Permite habilitar un menú en el listado para eliminar varios items al mismo tiempo.
  (core\core\MY_Controller.php[_generar_data('lista') + eliminarEnLote()] - core\views\lista.php - 
  core\views\includes\lista\cabecera.php - core\js\functions.js - core\controllers\_plantilla.php - core\css\theme_synapsis.css)

- Nueva función "eliminarAjax" para eliminar elementos del listado sin recargar la página.
  (core\js\functions.js[+eliminarAjax()] - core\controllers\_plantilla.php)

- Actualizo automáticamente las URLs del JS principal y del CSS principal para actualizar la caché.
  (core\views\includes\head.php)

### Fixed
- Corrijo las redirección a la página que querías entrar cuando no estás logueado
  (core\controllers\login.php[dologin()])

- Ajusto _deleteGallery() para que funcione siempre con el controlador que lo llama.
  (core\core\My_Controller.php [_deleteGallery()] )

- Se estaba calculando mal el max_size del campo file_upload.
  (core\core\MY_Controller.php [_uploadFile])

- Se estaba intentando generar siempre una marca de agua y es rompía la transparencia de las fotos PNG (y quedaban con fondo verde).
  (core\Gallery_ajax.php [upload_photo])
  
### Changed
- Ajusto cómo se filtran los campos en los listados
  (core\views\includes\lista\cabecera.php - core\views\includes\lista\campos.php)


## [1.8.2] - 2017-07-16
### Added
- Si hay un problema en una consulta AJAX y no hay definido un mensaje de error muestro
 el mensaje que devuelve el servidor.
 (core\js\functions.js[ajaxForm()])


## [1.8.1] - 2017-06-22
### Added
- Nuevo campo form_dropdown_busqueda. Es un select con capacidad para filtrar los ítems listados.
  (core\views\includes\lista\campos.php - cms\core\core\MY_Controller.php[exportXLS() - prepare_add_fields()]
  - cms\core\libraries\functions.php[make_form()] - cms\core\controllers\_plantilla.php)

### Fixed
- Ajusto el campo fileupload para que funcione.
 (core\views\campos\fineuploader_cargar.php)

## [1.8] - 2017-06-02
### Added
- Nuevo campo child_relation_popup_list para relacionar varios elementos a otro seleccionándolos 
  de una lista, con la posibilidad de filtrarlos.
  (core\controllers\_plantilla.php, +core\views\campos\child_relation_popup_list.php, 
  core\libraries\functions.php[make_form()] , core\css\theme_synapsis.css, 
  core\core\MY_Controller.php[+actualizarRelacionCRPL(), +load_popup_list_view(), 
  prepare_edit_fields(), prepare_add_fields(), cargar_controlador()] - core\views\includes\lista\cabecera.php)

- Nuevo campo fileupload para subir cualquier tipo de archivo sin límite de peso!
 (+ core/views/campos/fineuploader_cargar.php + core/views/campos/fineuploader_mostrar.php -
  core\css\theme_synapsis.css - core\libraries\functions.php[make_form()] + 
  core/controllers/campos/fileUploader.php - core/core/MY_Controller.php[prepare_add_fields() - 
  prepare_edit_fields(), doSave()] + core/third_party/fineuploader/*)

- Muestro un loader mientras se está recortando la foto.
  (core\js\images.js[recortarImagen()] - core\controllers\jcrop_ajax.php[load_jcrop()])

- Nuevo botón "copiar".
  (core\js\functions.js[+copiarHTML() + selectText()] - core\controllers\_plantilla.php)

### Fixed
- MY_Controller::crear_carpeta() ya no pincha si no se puede crear la carpeta (porque ya existe o porque no tiene permisos).
 (-core/core/MY_controller.php [crear_carpeta()])

- El campo child_relation_list se rompía cuando eran menos de 20 items
  (core\libraries\functions.php)

- El campo child_relation_list ya funciona bien con otro child_relation_list
  (core\js\campos\child_relation_list.js)

- Decodifico los separadores de los tipos de archivo ( | ) para que se pueda definir varios tipos de archivos.
  (core\core\MY_Controller.php[uploadFile()])

- Corrijo cómo se calculaba el tamaño máximo de subida de un archivo (decía cualquier cosa!)
  (core\libraries\functions.php make_form() - +core\helpers\miscelanea_helper.php)

- En la vista "lista" el mensaje "No hay datos para mostrar" ahora ocupa toda la tabla.
  (core\views\lista.php)

- Los campos child_relation_list hacían que no se guardara nada si no se cargaba ningún hijo en la relación.
  (core\core\MY_Controller.php[doSave()])

- Mantengo los filtros al cambiar el orden de un listado (cliqueando sobre el nombre de alguna columna) y
  el orden al cambiar el orden
  (core\views\includes\lista\cabecera.php - core\views\includes\lista\buscador.php)

### Changed
- MY_Controller::cargar_controlador() ahora carga también los controladores en la carpeta controllers/campos/.
 (-core/core/MY_controller.php [cargar_controlador()])

- Cambio botón "Subir foto" por "Subir fotos" y agrego cursor:move a las fotos de las galerías.
  (core\controllers\gallery_ajax.php [load_uploader()] - core\css\theme_synapsis.css)

- Quito espacios y llevo a minúsculas los datos de los botones "acciones" de los listados
  (core\views\includes\lista\acciones.php)

- Ajusto popup del campo jcropimage para que se adapte al tamaño de la foto.
  (core\css\theme_synapsis.css - core\controllers\jcrop_ajax.php (load_jcrop()))

- Cambio el enlace del campo child_relation por un botón.
  (core\libraries\functions.php(make_form()))

- Cambio la clase de los estilos del Checkbox AJAX
  (core\views\includes\lista\campos.php - core\css\theme_synapsis.css)
estro un loader mientras

## [1.7.12] - 2017-04-21
### Fixed
- Ajusto la configuración del plugin jbimages de tynmice, nuestro editor WYSIWYG. 
  Ahora se pueden subir imágenes usando el editor.
  (+ core/js/tinymce/plugins/jbimages/ci/* - core/js/tinymce/plugins/jbimages/config.php)

- Corrijo nombre de la función ini_get() (le faltaba la "i" del principio)
 (core\libraries\functions.php [make_form()])


## [1.7.11] - 2017-04-19
### Added
- Agrego la opción "auto_crear" a los campos tipo galería que permite que las galerías
 se creen automáticamente.
 (core\libraries\functions.php [getGallery()] - core\controllers\_plantilla.php)


## [1.7.10] - 2017-04-19
### Added
- Agrego campo "titulo" a los archivos y la posibilidad de editarlo.
 (core\core\MY_Controller.php[doSave()] - core\js\functions.js [+archivoCambiarTitulo()] - 
 core\core\MY_Controller.php [+get_file_title(),+set_file_title(), _uploadFile()], 
 core\models\archivos_model.php)


## [1.7.9] - 2017-04-17
### Added
- Los elementos seleccionados en un campo "child_relation_list" no se estaban guardando al
  crear un nuevo ítem (esto tal vez no pasaba con todos los servidores)
 (core\libraries\functions.php[File::buildModule()])

### Changed
- Los elementos de los campos "child_relation_list" se muestran en 2 columnas cuando son más de 20.
 (core\libraries\functions.php[getChildRelationList()])


## [1.7.8] - 2017-04-12
### Added
- Imprimo en el log todos los errores que se devuelven para las llamadas AJAX de la 
  subida de archivos.
 (core\core\MY_Controller.php[_uploadFile()])

- También agregé mensajes de error cuando no se puede crear una carpeta o cambiar sus permisos.
 (core\core\MY_Controller.php[_uploadFile() - _generateImages()])

### Fixed
- Hice dinámico el max_size del uploader de archivos para que no haga falta declarar
  el max_size del campo "form_upload". Con esto evito que el uploader bloquee las 
  subidas de archivos cuando se define un max_size mayor al permitido por el sistema.
  (core\libraries\functions.php[make_form()] - core\controllers\_plantilla.php)

- Hago opcional el parámetro "config" del método _upload_file. Siempre debería haberlo sido.
  (core\core\MY_Controller.php[_upload_file()])

- Cambio los permisos cada vez que creo una carpeta. En algunos servidores no se puede crear
  carpetas con permisos 777 pero sí asignárselos después de creadas.
  (core\core\MY_Controller.php[_uploadFile() - _generateImages()])

- Desactivo la validación de todas las llamadas al método save_item() cuando no se guarda un
  recurso (foto, galería, archivo o mapa).
  (core\core\MY_Controller.php)


## [1.7.7] - 2017-04-10
### Added
- Guardo un error en el log si no se pudo crear un carpeta para subir la foto.
  Uso las constantes GALLERIES_RESOURCES_PATH, IMAGES_RESOURCES_PATH y DIR_WRITE_MODE.
  (core\controllers\gallery_ajax.php)

- Agrego botón para editar el epígrafe (o alt) de las fotos de las galerías.
  (core\controllers\gallery_ajax.php [load_gallery()], core\css\theme_synapsis.css)

- Ahora se pueden generar fotos con distintas proporciones recortando con Jcrop. Además
 cambio las rutas y URLS por contantes IMAGES_RESOURCES_URL e IMAGES_RESOURCES_PATH
  (core\controllers\jcrop_ajax.php)


## [1.7.6] - 2017-03-21
### Added
- Agrego documentación para un campo con selector de colores
  (core\controllers\_plantilla.php [getChildRelationList()])


## [1.7.5] - 2017-03-17
### Fixed
- Ajusto las instrucciones de instalación del README.md

- Corrijo error del campop "child_relation_list" cuando no hay cargado ningún dato
  (core\libraries\functions.php [getChildRelationList()])


## [1.7.4] - 2017-03-16
### Added
- Nueva constante DEBUG para mostrar todas las acciones de MY_Model.php
  (core\core\MY_Model.php)

### Fixed
- Ajusto las instrucciones de instalación del README.md

- Corrijo error del campop "child_relation_list" cuando no hay cargado ningún dato
  (core\libraries\functions.php [getChildRelationList()])


## [1.7.3] - 2017-03-08
### Added
- Guardo un error en el log si no se pudo crear un carpeta para subir la foto
  (core\controllers\jcrop_ajax.php [upload_photo()])
  
### Fixed
- No tira error si hay una variable pasado por GET de tipo array
  (core\views\includes\lista\buscador.php)


## [1.7.2] - 2017-02-22
### Added
- Agrego la opción de filtrar los ítems que se van a exportar con MY_Controller::exportXLS()
  (exportXLS() - +exportXLS_filter())

### Fixed
- No tira error si se están mostrando las jcropimages en el listado y no se cargó una foto
  (core\views\includes\lista\campos.php)


## [1.7.1] - 2017-02-17
### Fixed
- Desactivo las validaciones de los modelos cuando actualizo el orden de los ítems
  (core\core\MY_Controller.php [actualizarOrden()])


## [1.7.0] - 2017-02-09
### Security
- Bloqueo el acceso al CHANGELOG y al README
  (.htaccess)

### Added
- Nuevo campo form_dropdown_nueva_opcion. Es un desplegable dinámico (carga de 
una tabla) que de la posibilidad de agregar una nueva opción.
  (core/core/MY_Controller.php [doSave() - cargar_config()] - 
  core\libraries\functions.php[make_form()] - 
  core\views\includes\lista\buscador.php - core\views\includes\lista\campos.php 
  - core\css\styles_theme.php)

- Nuevo botón para agregar alt a las imágenes.
  (core\js\images.js [+changeEpigraph()] - 
  core\controllers\jcrop_ajax.php [get_epigraph() - set_epigraph() - 
  load_image()] - core\css\theme_synapsis.css (Contenedor de los thumnails))

- Ahora se pueden listar los campos tipo "images", "jcropimage" y "gallery" 
(y no se muestran si no hay foto).
  (core\views\includes\lista\campos.php - core\models\fotos_model.php 
  [+fotos_galeria()])

- Nuevo campo child_relation_list. Lista todos los items del controlador hermano 
y da la posibilidad de agregarlos a la relación a través de un checkbox.
  (core\libraries\functions.php [+getChildRelationList()] - 
  core\core\MY_Controller.php[prepare_add_fields() - prepare_edit_fields() - 
  doSave() - cargar_controlador()] - core\views\includes\lista\campos.php - 
  core\views\includes\lista\cabecera.php - core\views\lista.php - 
  core\views\admins_lista.php - core\js\campos\child_relation_list.js)

- Al clonar un elemento de un controlador hijo este puede redireccionar a la 
vista del controlador padre.
  (core/core/MY_Controller.php [clonar()]

- Opción para exportar un campo en XLS
  (core/views/*_lista.php - core/views/includes/lista/boton_xml.php - 
  core/libs/PHPExcel/ - core\core\MY_Controller.php [_generar_data() - 
  exportXLS()] -cms\core\css\theme_synapsis.css)

### Changed
- Nuevo ícono para la acción "clonar".
  (core\views\includes\lista\acciones.php)

- Ahora se puede agregar una foto tipo jcropimage antes de haber creado el ítem 
    (core/controllers/jcrop_ajax.php[save_photo() - upload_photo() - 
    crop_photo()] - core\libraries\functions.php [getJcropImage()] - 
    core\core\MY_Controller.php[prepare_add_fields() - doSave()])

- Soporte para propiedad "style" para campo tipo "child_relation_iframe"
    (core\libraries\functions.php [make_form()])

- Ajusto el formato del README y agrego una breve explicación del sistema.

- El Gallery Manager muestra los resultados a medida que los va generando. 
  (core/conrollers/Gallery_manager.php)

### Fixed
- Ya no se muestra el valor vacío de un dropdown en el listado Ej: "Seleccione
una opción" (core\views\includes\lista\campos.php)

- Evito que se clonen los campos input_file, mapa y jcropimage
  (core\core\MY_Controller.php [clonar()])

- No funcionaba el filtro de los dropdowns cuando el valor de los filtros es 
un número.
  (core\libraries\functions.php [make_form()])

- Corrijo "conversión a string de array" cuando agrego la opción "filtros" en 
el listado de "properties" de los campos form_input.
  (core/libraries/functions.php)

- Hago más compatible la forma en que se eliminan las fotos de las galerías.
  (core/controllers/gallery_ajax.php)

- Reviso si existe apache_setenv() antes de usarla
  (core/core/MY_Controller.php [exportCSV()])
  


1.6
(2016-06-23)

Características
- Agrego opcion "idiomas" para los campos. Si es true se genera el campo automáticamente
  en todas las variantes según los idiomas definidos al principio de MY_Controller. Los campos de
  los modelos hay que cargarlos manualmente.
  (core/core/MY_Controller.php [+cargar_config()])

- Ahora en los controladores es opcional definir el modelo.
  El paginador está deshabilitado por defecto.
  (core/core/MY_Controller.php [cargar_config()])

- Agrego opción "es_filtro" para los campos form_dropdown y form_dropdown_ajax. Si es true filtra
  todos los campos que tienen el atributo data-filtro igual a la opción seleccionada.
  (core/views/includes/footer.php - core/js/functions.js [filtrar()] -
  core/libraries/functions.php [make_form()] - core/views/includes/lista/campos.php -
  core/views/includes/lista/cabecera.php - MY_Controller.php [prepare_add_fields() - prepare_edit_fields()])

- Ya no muestro "en Español" en el listado.
  (core/core/MY_Controller.php [cargar_config()] - core\views\includes\lista\cabecera.php)

- Agrego la opción "=" para los campos "date" t agrego el botón "limpiar" para resetear la búsqueda
  (core/core/MY_Controller.php [getFilters()])

- Ahora se puede agregar en la URL de las acciones cualquier propiedad de los objetos (antes era solo {id}).
  (core\views\includes\lista\acciones.php)

- Agrego DOCUMENT_ROOT y CONTROLLER en el header de todas las vistas. Lo quito de functions.js
  (core\views\includes\head.php - core\js\functions.js)

- Nueva opción para configurar la URL de retorno (después de crear o editar un ítem)
  (core\views\includes\head.php - core\js\functions.js -core\core\MY_Controller [_generar_data('cabecera')])

- Nuevo campo "mapa"
  (core\libraries\functions.php[make_form()]- core\core\MY_Controller.php[doSave()] - cms\core\css\theme_synapsis.css -
  core\js\jquery.geolocation.edit.min.js)

- Los checkbox de los listados ahora son editables
  ( core\core\MY_Controller.php[+ actualizarCheckbox()] - cms\core\css\theme_synapsis.css -
  core\js\vistas\lista\checkbox_ajax.js - core\views\includes\lista\acciones.php -
  core\views\lista.php - core\views\includes\lista\campos.php)



Correcciones
- Ajusto el estilo del código de todos los controladores y actualizo la plantilla
  (core/controllers/*)

- Mejoro el manejo de los errores.
  (core/core/MY_Controller.php[ errorHeader() - delete() - doSave()])

- Corrijo el CHARSET de la configuración de la base de datos. Pasó de UTF-8 a utf8
  (core/config/database.php)

- Los campos tipo "date" ahora tienen un ancho fijo para no mostrarse en 2 líneas
  cuando hay muchas columnas.
  (core/core/MY_Controller.php [cargar_config()] - core\views\includes\lista\cabecera.php)

- Ajusto el drag & drop de las listas para que solo se aplique sobre un nuevo ícono y no
  sobre toda la fila (antes a veces cuandque querías clicear en alguna acción se activaba
  el Drag & Drop).
  (core\views\includes\lista\cabecera.php - core\views\lista.php - core\css\theme_synapsis.css)

- Paso por POST las variables del AJAX actualizarOrden() para que no se rompa cuando hay
  demasiados elementos para ordenar.
  (core\views\lista.php - core/core/MY_Controller.php [actualizarOrden()])

- Corrijo los estilos del paginador para cuando tiene muchas páginas
  (core\views\lista.php - core\css\theme_synapsis.css)

- Ajusto el Drag & Drop para que sea más cómodo de usar: habilito selección de texto,
  pongo un placeholder y muestro mensajes.
  (core\views\lista.php - core\css\theme_synapsis.css)

- Corrigo problema cuando se filtraba sin valor. Deshabilito el filtro cuando no se le pasa un valor.
  (core/core/MY_Controller.php [getFilters()])

- Corrigo el orden del listado cuando se filtra. Ahora siempre se le agregan los criterios de orden por defecto.
  (core/controllers/MY_Controller.php [getOrders()])

- Ajusto JS del listado AJAX para que cargue cuando se le asigna un valor por
  defecto a la lista padre relacionada.
  (core/js/functions.js [ajaxListarOpciones()])

- Transformo dropdown_ajax en form_dropdown en el buscador para que no se rompa
  (core/views/includes/lista/buscador.php)

- Quito los scripts JS de la vista lista.php
  (core/views/lista.php - +core/js/vistas/ordenar.js)

- Agrego una validación para que la opción "filter" de los campos sea opcional.php
  (core\views\includes\lista\buscador.php)

- Mejoro la compatibilidad del CMS con futuras versiones de PHP:
  > Uso __construct()
  (core\libraries\configvars.php - core\libraries\MY_Image_lib.php - core\libraries\xlswriter.php)

  > No devuelvo una referencia
  (libs\codeigniter\core\Common.php[get_config()])

  > Uso mysqli en vez de mysql
  (core\config\database.php)

  > No uso arrays para construir variables dinámicas o le agrego {}
  (core\views\includes\lista\campos.php - core\core\MY_Controller.php)



1.5.4
(2016-06-16)

Correcciones
- Los dropdowns simples ahora pueden tener cualquier cantidad de atributos HTML
  (core/libraries/functions.php [make_form()])

- Agrego ícono pdf.png y report.png (para la ficha de propiedad)
  (core/css/img/*, core/css/theme-synapsis.css)

- Actualizo función filtrar() (Ej: para filtrar campos según el tipo de inmueble)
  (core/views/includes/footer.php)


1.5.3
(2016-05-17)

Correcciones
- Ajusto los estilos de los mensajes de los drag & drop de los archivos cargados por ajax
  (core/js/fileuploader/fileuploader.css)

- Corrijo el form_hidden que se contruía mal (se imprimían varios campos ocultos)
    (core/libraries/functions.php [make_form()])

- Corrijo error por variable $figura_original sin definir
    core/controllers/gallery_ajax.php [generate_sizes()])



1.5.2
(2016-04-08)

Características
- Actualizo la plantilla de controladores
  (core/controllers/_plantilla.php)

- Muestro un '-' en el listado cuando el campo no tiene valor
  (core/views/includes/lista/campos.php)



Correcciones
- Arreglo form_dropdown_ajax que no se cargaba automáticamente onLoad
  (core/libraries/functions.php [make_form()])

- Arreglo CSS de las pestañas
  (core/css/theme_synapsis.css)



1.5.1
(2016-04-07)

Características
- Actualizo JCrop a la versión 1.5 (agrega soporte para imágenes con medidas automáticas)
(core/controllers/jcrop_ajax.php - core/js/images.js - core/libraries/functions.php)

- Valor por defecto para campos multiple_select_simple
(core/libraries/functions.php [make_form()])

- Convierto multiple_select_simple en form_dropdown en el buscador de las listas
(core/views/includes/lista/buscador.php)

- Muestro una leyenda en las listas que se pueden ordenar
(core/views/lista.php)

- Nuevo método clonar() genérico
(core/core/MY_Controller.php)


Correcciones
- Traduzco los textos de la interfaz del uploader de las galerías
(core/controllers/gallery_ajax.php [load_uploader()])

- Corregido form_multiselect_simple
  (core/core/MY_Controller.php [prepare_edit_fields()])

- Corrijo problema de seguridad en _cleanResourses()
  (core/core/MY_Controller.php [_cleanResourses()])



1.5.0
(2016-02-17)

Correcciones

-Cualquier acción que elimine ahora redirije a la URL anterior REAL y no a una armada
(core/core/MY_Controller.php)

-Ajusto el CSS del mensaje de error
(core/css/theme_synapsis.css)

-Remuevo /?/ de la URL que devuelve la función current_url()
(core/helpers/url_helper.php)


Características
-Botón opcional para descargar listado en formato CSV
(core/core/MY_Controller.php [exportCSV() - _getListFields() - _getExportFields() - getFilters() - _generar_data()] -
 core/views/includes/lista/buscador.php - core/views/includes/lista/boton_csv.php - core/views/lista.php - core/views/includes/head.php -
 core/css/theme_synapsis.css)

-Nueva propiedad "opciones" para los campos date y datetime para pasarle opciones al datepiker.
(core/libraries/Functions.php [make_form()])

-Opción categoría en el controlador Menu para poder crear varias pestañas que respondan al mismo
controlador pero diferenciando el contenido por una categoría.
(core/models/Menu_model.php - core/controllers/menu.php - core/views/menu.php -
core/core/MY_Controller.php [getFilters() - _get_uriParameters() - doSave()])

-Convierto el campo upload_file en un campo AJAX
(core/core/MY_Controller.php - core/libraries/functions.php - core/libraries/FileUploader.php -
core/third_party/qqUploader.php - core/models/archivos_model.php)

-Actualizo MY_Controller::_cleanResources() para eliminar los archivos y galerías relacionados a un ítem cuando se lo elimina
(core/core/MY_Controller.php)

-Si entrás al administrador desde alguna página interna y te redirige al login, una vez logueado ahora te redirige
a la página a la que habías intentado entrar en un principio
(core/controllers/login.php - core/libraries/auth.php

-Traducción de los mensajes de CodeIgniter
(core/config/config.php - ../libs/codeigniter/language/spanish/*)

-El campo para subir archivos ya no depende del ID del controlador que sube el archivo y muestra los errores
(core/core/MY_Controller.php - core/libraries/functions.php - core/models/Archivos_model.php)

-Actualizo el qqFileUploader a la versión 2.0 y traduzco todos los textos de la UI
(core/js/fileuploader/*)

-Creo las constantes IMAGES_RESOURCES_PATH, FILES_RESOURCES_PATH y GALLERIES_RESOURCES_PATH
(core/core/MY_Controller.php - core/libraries/functions.php)




1.4.1
(2016-01-18)

Correcciones
-Quito los "?>" de cierre y el comentario de apertura de todos los modelos
(core/models/*)

-Quito el comentario de apertura de todos los controladores
(core/controllers/*)

-Agrego favicon
(core/views/includes/head.php)


Características
-Actualizo JCrop a la versión 1.4
(core/controllers/jcrop_ajax.php - core/js/images.js - core/libraries/functions.php)

-Nueva plantilla con tipos de campos
(core/controllers/_plantilla.php)

-Muestro error cuando no se puede crear la carpeta para una galería
(core/controllers/gallery_ajax.php)

-Agrego restricciones con JS para los campos
(core/views/includes/footer.php)

-El texto "Agregar" de los listados ahora es configurable
(core/views/lista.php - core/core/MY_Controller.php)

-Opción para activar la función para ordenar los listados
(core/views/lista.php - core/core/MY_Controller.php)



1.4
(2015-11-20)

Correcciones
-Cambio el separador"," por "-" porque al servidor no le gusta
(core/core/MY_Controller.php - core/views/lista.php)

-Elimino css basura
(core/css/*)



Características
-Fecha actual como valor por defecto para el campo tipo datetime
(core/libraries/functions.php)



1.3
(2015-10-30)

Características
-Nuevo campo form_dropdown_ajax (Select que se carga con ajax en base al valor de otro select).
(core/core/MY_Controller.php - core/libraries/functions.php - core/js/functions.php)

-Nuevo campo form_multiselect_simple_ajax (Select múltiple que se carga con ajax en base al valor de otro select y que guarda todos los valores en un solo campo de la base de datos como una cadena de elementos separados por comas).
(core/core/MY_Controller.php - core/libraries/functions.php - core/js/functions.php)

-Funcionalidad para reordenar los elementos de los listados con Drag & Drop
(core/core/MY_Controller.php - core/views/lista.php)

-Posibilidad de agregar una marca de agua a todas las imágenes de una galería
(core/core/libraries/functions.php - core/js/gallery.js - core/controllers/gallery_ajax.php)

-Nuevo gestor de galerías: crea todos los tamaños de las imágenes de todas las galerías, con o sin marca de agua, sobrescribiendo o no las imágenes
(core/core/libraries/functions.php - core/js/gallery.js - core/controllers/gallery_ajax.php)


Correciones
-Arreglo paginador
(core/core/MY_Controller.php)



1.2
(2015-10-26)

Características
-Hago editable la vistas principales (/add/edit/lista) desde la configuración de cada controlador. Ya no hay necesidad de sobrecargar métodos.
(core/core/MY_Controller.php)

-Agrego las opciones 'comentario', 'titulo' y 'html_extra' a la configuración de los campos de los controlers para hacer las vistas más personalizables.
(core/views/form.php)


Correciones
-Coloco las vistas de cabecera y footer dentro de la carpeta "includes"
(core/core/MY_Controller.php)

-Unifico el formato del código para todos los controladores
(core/controllers/*)



1.1
(2015-09-25)

Características
-Ahora los filtros de los listados están en un include
(views/lista.php)


Correciones
-Desactivo la caché de las peticiones AJAX para evitar que no se vea actualizado el contenido del administrador en algunos servidores
(views/header.php - views/header_popup.php)

-Los campos textarea ahora se muestran como input_text
(views/includes/buscador.php)

-No muestro el buscador cuando no hay definido ningún campo para filtrar
(views/includes/buscador.php)



1.0
(2015-09-10)

Características
-Removido el "/index.php/" de las URL. Ya no es obligatorio.
(.htaccess - config/config.php)

-Cambio de formato en las URLs en el menú, listados y forms
(controller/menu.php - views/*)

-Los superadmin pueden verse en el listado de admins y pueden crear nuevos superadmins
(controllers/admins.php - views/lista_admins.php)

-Nueva pestaña para modificar el menú y nuevo código de color para los distintos tipos de visibilidad de las pestañas.

-Posibilidad de mostrar un valor por defecto para los inputs_text y form_dropdowns


Correciones
-Ahora se muestran bien los caracteres especiales leídos de una BD en UTF-8
(libraries/functions.php)

-Corrijo los filtros del listado que buscaban siempre por todos los criterios
(views/lista.php)

-Extiendo el límite de caracteres de los campos de los Admins para que no recorten la contraseña ni el usuario
(controllers/admins.php)

-Ubico el botón "Agregar" arriba a la derecha en vez de abajo
(css/stiles-theme.css - views/lista.php)

-Correjido error que aparece cuando en el listado se muestra un campo de tipo dropdown sin un valor asignado
(views/lista.php)

