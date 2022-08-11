<?php

class Plantilla extends MY_Controller {

	function __construct() {

		parent::__construct();

		/////////////////////
		/// Configuración ///
		/////////////////////

		$controller_config["script"] = "plantilla";

		// Opcional
		// $controller_config["model"]  = $controller_config["script"] . "_model";

		// Solo para los controladores que se ven en un iframe o popup
		/*$controller_config['parents'] = array(
        	'muestras' => array(
				'foreign_key'=>'muestra_id',
				'parent_model'=>'muestras_model'
			)
        );*/


		//////////////////////////////
		/// Opciones de las vistas ///
		//////////////////////////////

		// Nombre del listado
		// Se muesta como "Listado de plantillas"
		$controller_config["name"] = "plantillas";


		/// Opcionales ///

		//// Generales ////
		// Definir vistas personalizadas para este controlador
		// $this->vistas['add']  = 'mi-nueva-vista_form';
		// $this->vistas['edit'] = 'mi-nueva-vista_form';
		// $this->vistas['list'] = 'mi-nueva-vista_list';

		
		//// Vistas: add o edit ////

		// Cambia URL a la que se vuelve al cancelar o confirmar la edición o creación de un ítem
		$controller_config["return_url"] = base_url() . 'url-alternativa';


		////  Vista: Listado ////
		// Habilita menú con acciones para aplicar sobre varios items.
		// Para crear una nueva acción hay que:
		// 1- Agregar un archivo JS en js/acciones-en-lote/<key-accion>.js
		// 2- Agregar en ese archivo una función nombrada "accionEnLote_<key-accion>"
		$controller_config["acciones_en_lote"] = [
			'eliminar' => ['label' => 'Eliminar']
		];

		// Permite ocultar botón "Agregar" o cambiar su texto
		$controller_config["add_button"] = false;
		$controller_config["label_add"] = "Agregar item";

		// Activa paginador
		// El número va a definir la cantidad de páginas a mostrar
		$controller_config["paginator_results_per_page"] = 20;

		// Activa funcionalidad de drag & drop para ordenar los ítems
		$controller_config["ordenar"] = true;

		// Muestra botón para guardar todos los items con campos con la opción "list_edit"
		$controller_config["guardar_lista"] = true;

		// Agrega botón para descargar XLS con listado de items
		$controller_config["xls_btn"] = true;

		// Permite agregar botones personalizados al lado del botón "Agregar"
		$controller_config["botones_extra"] = [
			'<a class="boton" href="javascript:alert(\'hola!\');">Saludar</a>',
		];

		/// Fin opcionales ///


		// Ordeno el listado
		// if(! isset($_GET['order'])){
	  //   $_GET['order'] = array(
		// 		'pais' => 'ASC',
		// 		'localidad' => 'ASC',
		// 		'nombre' => 'ASC',
		// 	);
	  // }

		// Filtro el listado
		// if(! isset($_GET['pais'])){
		// 	$_GET['pais'] = '3';
		// }

		// Acciones
		$controller_config['actions_list'] = array(
			'editar'  =>	base_url() . $controller_config['script'] . '/edit/{id}/{uriParameters}',
			// 'clonar'  =>	base_url().$controller_config['script'].'/clonar/{id}',
			// 'guardar' => 'javascript:guardarItemLista({id})',
			// 'preview' =>	base_url().'../nuevo/emprendimiento/{id}/vista-previa/',
			// 'ficha'   =>	base_url().'newsletter/generar_ficha/?prop1={id}{uriParameters}',
			// 'copiar'	=>	'javascript:copiarHTML(\''.base_url().'../plantillas-ml/0km/{id}/\', true)',
			'delete'	=>	'javascript:eliminarAjax({id});'
		);


		///////////////////////////////////
		/// Configuración de los campos ///
		///////////////////////////////////


		/* Traducir los campos

		Solo agregá la opción "idiomas" => true y el campo se va a cargar en todos los
		idiomas que estén definidos en MY_Controler.php. El primer idioma va a ser el
		idioma por defecto.

		Los campos que estén en varios idiomas va a haber que agregarlos con el formato
		$campo.'_'.$codigo_idioma en el modelo y en la base de datos.

		Ej: nombre_es, nombre_en
		*/

		/* Opciones para varios campos

			Hace editable un campo desde el listado
			Agregar acción "guardar" setear en true opción "guardar_lista" para guardar cambios
			'list_edit' => true,

			Permite setear un nombre especial para mostrar en el listado
			Normalmente lo usaríamos para mostrar un nombre abreviado
			'label_list'	=> '%', // El label regular es "Descuento"
		*/

		$controller_config["campos_form"] = array(

			/* Input text */
			array(
				'key'	=> 'titulo',
				'label'	=> 'Titulo',
				'type'	=> 'form_input',
				'filter' => false,
				'list'	=> true,
				'class'	=> 'form-third label-up',
				'properties' => array(
					'name'      => 'titulo',
					'maxlength' => '255',
					'filtros' => array('nombre-del-filtro' => 'valor-del-filtro valor-del-filtro'),
				)
			),

			/* Textarea común */
			array(
				'key'	=> 'descripcion',
				'label'	=> 'Descripción',
				'type'	=> 'form_textarea',
				'filter' => false,
				'list'	=> false,
				'class'	=> 'form-half clear label-up',
				'properties' => array(
					'name'  => 'descripcion',
					'id'    => 'descripcion'
				)
			),

			/* Checkbox */
			array(
				'key'	=> 'activo',
				'label'	=> 'Activo',
				'type'	=> 'form_checkbox',
				'filter' => false,
				'list'	=> true,
				'class'	=> 'form-half clear label-up',
				'properties' => array(
					'name'     => 'activo',
					'id'       => 'activo',
					'value'    => '1',
					'checked'    => 'checked',
				)
			),

			/* Imagen simple (se recorta/redimensiona automáticamente) */
			array(
				'key'    => 'foto',
				'label'  => 'Portada',
				'type'   => 'images',
				'filter' => false,
				'list'   => false,
				'class'  => 'form-third label-up',
				'comentario' => '<a href="' . base_url() . '../archivos/categorias_portada.psd" target="_blank">Descargar el PSD base.</a>',
				'properties' => array(
					'id'       => 'foto',
					'name'     => 'foto',
					'quantity' => 1,
					'sizes'    => array(
						array('width' => '650', 'height' => '200', 'master_dim' => 'width', 'method' => 'resize'),
					),
					'controller' => $controller_config["script"]
				)
			),

			/* Imagen con control para recortar */
			array(
				'key'    => 'imagen_id',
				'label'  => 'Imagen',
				'type'   => 'jcropimage',
				'filter' => false,
				'list'   => true,
				'class'  => 'form-third label-up',
				'properties' => array(
					'id'       => 'imagen_id',
					'name'     => 'imagen_id',
					'quantity' => 1,
					'sizes'    => array(
						array('width' => '1280', 'height' => '300', 'method' => 'crop'),
						array('width' => 'auto', 'height' => '50', 'method' => 'crop'),
					),
					'siempre_jpg' => true,
					'margenes'    => false,
					'controller'  => $controller_config["script"]
				)
			),

			/* Input select con opciones estáticas */
			array(
				'key'	=> 'autor2_id',
				'label'	=> 'Autor 2',
				'type'	=> 'form_dropdown',
				'filter' => false,
				'list'	=> true,
				'class'	=> 'form-full label-up',
				'properties' => array(
					'name'    => 'autor2_id',
					'es_filtro' => false,
					'options' => array(
						"" => "Elija una opción",
						1 => "Opción 1",
						2 => "Opción 2",
						3 => "Opción 3",
					)
				)
			),

			/* Input select con opciones dinámicas */
			array(
				'key'	=> 'marca_id',
				'label'	=> 'Marca',
				'type'	=> 'form_dropdown',
				'filter' => 'match',
				'list'	=> true,
				'class'	=> 'form-full label-up',
				'properties' => array(
					'name'      => 'marca_id',
					'es_filtro' => false,
					'options'   => array("" => "Elija una marca") + $this->getOptions('marcas_model', 'nombre')
				)
			),


			/* Input select con opciones dinámicas y capacidad para buscar enntre las opciones */
			array(
				'key' => 'relacionado_id',
				'label' => 'Producto relacionado',
				'type'	=> 'form_dropdown_busqueda',
				'filter' => false,
				'list' => true,
				'class'	=> 'form-half label-up cl-b',
				'properties' => array(
					'name'  => 'relacionado_id',
					'options' => array("" => "Elija un producto") + $this->getOptions('productos_model', 'nombre'),
					'ajax' => true,
					// Con ajax=true hacen falta estos datos
					'controller'  => $controller_config["script"],
					'child_model' => 'productos',
					'child_describe_field'	=> 'nombre',
				)
			),

			/* Input select con opciones dinámicas y posibilidad de agregar nueva opción*/
			/* Input select con opciones dinámicas y posibilidad de agregar nueva opción
			* Carga las opciones desde un campo del mismo modelo.
			* Ej: Si estampos cargando el campo "categoria" de un producto vamos a listar todas las
			* categorías que se cargaron para los productos y dar la posibilidad de cargar una nueva
			* categoría.
			*/
			array(
				'key'	=> 'categoria',
				'label'	=> 'Cartegoría',
				'type'	=> 'form_dropdown_nueva_opcion_simple',
				'filter' => false,
				'list'	=> false,
				'class'	=> 'form-full label-up',
				'properties' => array(
					'name'       => 'categoria',
					'es_filtro' => false,
					'btn_agregar' => 'Agregar nueva categoría',
				),
				'opciones' => array(
					'defecto' => array('' => 'Elija una categoría'),
					'campo_descriptor' => 'categoria',
				)
			),

			/* Input select con opciones dinámicas y posibilidad de agregar nueva opción
			* Carga las opciones desde un modelo independiente.
			*/
			array(
				'key'	=> 'categoria_id',
				'label'	=> 'Cartegoría',
				'type'	=> 'form_dropdown_nueva_opcion',
				'filter' => false,
				'list'	=> false,
				'class'	=> 'form-full label-up',
				'properties' => array(
					'name'       => 'categoria_id',
					'es_filtro' => false,
					'btn_agregar' => 'Agregar nueva categoría',
				),
				'opciones' => array(
					'defecto' => array('' => 'Elija una categoría'),
					'modelo' => 'categorias_model',
					'campo_descriptor' => 'nombre',
				)
			),

			/* select con opciones dinámicas en base a otro select */
			array(
				'key'    => 'subcategoria_id',
				'label'  => 'Subcategoría',
				'type'   => 'form_dropdown_ajax',
				'filter' => 'match',
				'list'   => true,
				'class'  => 'form-full label-up',
				'properties' => array(
					'name' => 'subcategoria_id',
					'id'   => 'subcategoria_id',
					'child_model' => 'subcategorias',
					'child_describe_field'	=> 'nombre',
					'child_foreign_key'		=> 'categoria_id',
					'parent_select_id'		=> 'categoria_id',
					'options' => array("" => "Elija una subcategoría") + $this->getOptions('subcategorias_model', 'nombre')
				)
			),

			/* Input select multiple (simple) */
			array(
				'key'    => 'clientes_id',
				'label'  => 'Clientes',
				'type'   => 'form_multiselect_simple',
				'filter' => false,
				'list'   => false,
				'class'  => 'form-third label-up',
				'comentario' => 'Para seleccionar varios filtros mantené apretada la tecla Ctrl o Command.',
				'properties' => array(
					'name'    => 'clientes_id',
					'options' =>  $this->getOptions('clientes_model', 'nombre')
				)
			),

			/* Input select múltiple (simple) con AJAX */
			array(
				'key'    => 'tipos',
				'label'  => 'Filtros',
				'type'   => 'form_multiselect_simple_ajax',
				'filter' => false,
				'list'   => false,
				'class'  => 'form-third label-up',
				'comentario' => 'Para seleccionar varios filtros mantené apretada la tecla Ctrl o Command.',
				'properties' => array(
					'name'      	=> 'tipos',
					'id'        	=> 'tipos',
					'child_model'			=> 'tipos',
					'child_describe_field'	=> 'nombre',
					'child_foreign_key'		=> 'categoria_id',
					'parent_select_id'		=> 'categoria_id',
					'local_key'				=> 'producto_id',
					'local_model'			=>  $controller_config['script'],
					'options'				=>  $this->getOptions('tipos_model', 'nombre')
				)
			),

			/* Input file */
			array(
				'key'    => 'pdf_id',
				'label'  => 'PDF',
				'type'   => 'form_upload',
				'filter' => false,
				'list'	=> false,
				'class'  => 'form-half clear label-up',
				'properties' => array(
					'name'          => 'pdf_id',
					'allowed_types' => 'pdf|doc|docx',
					'thumbnail' => false,
				)
			),

			/* Galería */
			array(
				'key'    => 'galeria',
				'label'  => '',
				'type'   => 'gallery',
				'filter' => false,
				'list'   => false,
				'titulo' => 'Galería',
				'comentario' => 'Las fotos deben tener al menos 800px de ancho y alto para verse bien. <br />Podés reordenar las fotos
					 agarrandolas y arrastrandolas.',
				'properties' => array(
					'name'  => 'galeria',
					'sizes' => array(
						array('width' => 1200, 'height' => 1200, 'method' => 'crop'),
						array('width' => 800, 'height' => 800, 'method' => 'crop'),
						array('width' => 235, 'height' => 235, 'method' => 'crop'),
						array('width' => 110, 'height' => 110, 'method' => 'crop'),
						
						// Si queremos que no se recorten las fotos y se rellene con un fondo blanco
						// no hay que definir el método y poner master_dim en "fit".
						array('width' => 110, 'height' => 110, 'master_dim'=>'fit'),
					),
					'marca_de_agua' => false,
					'auto_crear' => false,
					'controller'    => $controller_config['script']
				)
			),

			/* Selector de fecha y hora */
			array(
				'key'    => 'fecha',
				'label'  => 'Fecha',
				'type'   => 'datetime',
				'filter' => false,
				'list'   => false,
				'class'  => 'label-up clear',
				'properties' => array(
					'name' => 'fecha',
					'id'   => 'fecha',
					// http://api.jqueryui.com/datepicker/
					'opciones' => array(
						'constrainInput' => true
					)
				)
			),

			/* Selector de fecha*/
			array(
				'key'    => 'fecha',
				'label'  => 'Fecha',
				'type'   => 'date',
				'filter' => false,
				'list'   => false,
				'class'  => 'form-quarter label-up cl-b',
				'properties' => array(
					'name' => 'fecha',
					'id'   => 'fecha',
					// http://api.jqueryui.com/datepicker/
					'opciones' => array(
						'constrainInput' => true
					)
				)
			),

			/* Selector de hora*/
			array(
				'key'    => 'hora',
				'label'  => 'Hora',
				'type'   => 'time',
				'filter' => false,
				'list'   => false,
				'class'  => 'form-half label-up cl-b',
				'properties' => array(
					'name' => 'hora',
					'id'   => 'hora',
				)
			),

			/* Elemento hijo en iframe */
			array(
				'key'    => 'combinaciones_colores',
				'label'  => '',
				'type'   => 'child_relation_iframe',
				'filter' => false,
				'titulo' => 'Combinaciones de colores',
				'properties' => array(
					'child_controller' => 'colores_combinaciones',
					'child_model'      => 'colores_combinaciones_model',
					'foreign_key'      => 'producto_id',
					'controlador_hermano' => 'colores',
					'fk_hermano' => 'color_id',
					'campo_descriptor'  => 'nombre'
				)
			),

			/* Lista todos los items del controlador hermano y da la posibilidad
  			de agregarlos a la relación a través de un checkbox.
  			Este campo depende del método it actualizarRelacion() del modelo hijo*/
			array(
				'key' => 'novedades',
				'label' => '',
				'type' => 'child_relation_list',
				'filter' => false,
				'list' => false, // true|false
				'titulo' => 'Novedades',
				'properties' => array(
					'controlador_hermano' => 'novedades',
					'controlador_hijo' => 'sitios_novedades',
					'fk_propia' => 'sitio_id',
					'fk_hermano' => 'novedad_id',
					'campo_descriptor' => 'nombre', // Campo del controlador hermano para mostrar en listados
					'custom_filter' => '', // SQL opcional para filtrar las opciones
				)
			),

			/* Popup con lista para seleccionar elementos relacionados
			* Funciona igual que el campo "child_relation_list" pero muestra los
			* elementos en un popup y te da la opción de filtrarlos.
			*/
			array(
				'key'    => 'autos',
				'label'  => '',
				'titulo' => 'Autos',
				'type'   => 'child_relation_popup_list',
				'filter' => false,
				'properties' => array(
					'controlador_hermano' => 'autos',
					'controlador_hijo' => 'plantillasml_autos',
					'fk_propia' => 'plantillaml_id',
					'fk_hermano' => 'auto_id',
				)
			),

			/**
       * Dos listas para relacionar los elementos arrastrándolos de una a otra
			 *
			 * Crea relaciones 1 a muchos entre el elemento padre y los elementos
			 * hijos seleccionados (los que se arrastraron a la segunda columna).
			 *
			 * La relación se guarda en la tabla del modelo hijo, en la columna
			 * definida como foreign_key.
			 *
			 */
			array(
				'key' => 'comedores',
				'label' => '',
				'type' => 'child_relation_sortable',
				'filter' => false,
				'titulo' => 'Comedores',
				'properties' => array(
					'child_model' => 'comedores_model',
					'child_describe_field' => 'nombre',
					'foreign_key' => 'grupo_banners_id',
				)
			),

			// Mapa
			array(
				'key'    => 'mapa_id',
				'label'  => '',
				'type'   => 'mapa',
				'titulo' => 'Mapa',
				'comentario' => '',
				'properties' => array(
					'name'   => 'mapa_id',
					'activo' => true,
					'lat'    => false,
					'lng'    => false,
					'opciones' => array(
						//'streetview' => false
					),
					'controller' => $controller_config['script'],
					'filtros' => array('operacion' => 'venta'),
				)
			),

			// Selector de colores
			array(
				'key'	=> 'codigo',
				'label'	=> 'Color',
				'type'	=> 'form_input',
				'filter' => false,
				'list'	=> true,
				'class'	=> 'form-half label-up',
				'properties' => array(
					'name'         => 'codigo',
					'id'           => 'codigo',
					'maxlength'    => '6',
					'class'		=> 'colorpicker_selector',
					'readonly' => 'readonly'
				)
			),

		);

		$this->cargar_config($controller_config);
	}
}


  /////////////////////////////////////////
 ///////////////  TRUCOS  ////////////////
/////////////////////////////////////////

/* Dividir un controlador en categorías

 Para mostrar el contenido de un mismo controlador en varias pestañas
 discriminado los ítems según un categoría seguí estos pasos:

   1 - En el menú agregá las pestañas y definile una categoría a cada una
   2 - Agregá el campo categ en el modelo y en la base de datos.
   3 - ¡Listo! ¿simple, no?

*/

/* Mostrar 1 solo ítem en una pestaña

 Para acceder desde una pestaña a la pantalla de edición de un ítem en particular
 en vez de mostrar un listado de ítems:

 Andá a la pestaña menú y en el campo "listar" del controlador que quieras modificar
 poné el ID del único ítem que querés que se pueda cer y editar.

*/
