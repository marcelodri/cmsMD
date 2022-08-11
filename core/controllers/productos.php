<?php

class Productos extends MY_Controller
{

	function __construct()
	{

		parent::__construct();

		/////////////////////
		/// Configuración ///
		/////////////////////

		$controller_config["script"] = "productos";

		//////////////////////////////
		/// Opciones de las vistas ///
		//////////////////////////////

		// Nombre del listado
		$controller_config["name"] = "productos";

		$controller_config["ordenar"] = true;

		// Ordeno el listado
		if (!isset($_GET['order'])) {
			$_GET['order'] = array(
				'orden' => 'ASC',
				'titulo' => 'ASC',
			);
		}

		// Acciones
		$controller_config['actions_list'] = array(
			// 'preview' =>	base_url().'../nuevo/emprendimiento/{id}/vista-previa/',
			'editar'  =>	base_url() . $controller_config['script'] . '/edit/{id}/{uriParameters}',
			'delete'	=>	'javascript:eliminarAjax({id});'
		);


		///////////////////////////////////
		/// Configuración de los campos ///
		///////////////////////////////////

		$campos = array();

		$campos[] =	array(
			'key'	=> 'codigo',
			'label'	=> 'Código',
			'type'	=> 'form_input',
			'filter' => 'like',
			'list'	=> true,
			'class'	=> 'form-quarter label-up cl-b',
			'properties' => array(
				'name'      => 'codigo',
				'maxlength' => 45,
			)
		);

		$campos[] =	array(
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
		);

		$campos[] =	array(
			'key'	=> 'titulo',
			'label'	=> 'Título',
			'type'	=> 'form_input',
			'filter' => 'like',
			'list'	=> true,
			'class'	=> 'form-quarter label-up cl-b',
			'properties' => array(
				'name'      => 'titulo',
				'maxlength' => 45,
			)
		);

		$campos[] =	array(
			'key'	=> 'descripcion_corta',
			'label'	=> 'Descripción corta',
			'type'	=> 'form_textarea',
			'filter' => false,
			'list'	=> false,
			'class'	=> 'form-half clear label-up',
			'properties' => array(
				'name'  => 'descripcion_corta',
				'id'    => 'descripcion_corta',
				'maxlength' => 100,
				'rows' => 3,
			)
		);

		$campos[] =	array(
			'key'	=> 'keywords',
			'label'	=> 'Palabras claves',
			'type'	=> 'form_textarea',
			'filter' => false,
			'list'	=> false,
			'class'	=> 'half-quarter label-up cl-b',
			'comentarios'	=> 'Separe con comas cada palabra clave',
			'properties' => array(
				'name'      => 'keywords',
				'maxlength' => 1000,
				'rows' => 2,
			)
		);

		$campos[] =	array(
			'key'    => 'foto_id',
			'label'  => 'Foto principal',
			'type'   => 'jcropimage',
			'filter' => false,
			'list'   => true,
			'class'  => 'form-full label-up',
			'properties' => array(
				'id'       => 'foto_id',
				'name'     => 'foto_id',
				'quantity' => 1,
				'sizes'    => array(
					array('width' => '484', 'height' => '414', 'method' => 'crop'),
					array('width' => '260', 'height' => '260', 'method' => 'crop'),
				),
				'siempre_jpg' => true,
				'margenes'    => false,
				'controller'  => $controller_config["script"]
			)
		);

		$campos[] =	array(
			'key'    => 'galeria',
			'label'  => '',
			'type'   => 'gallery',
			'filter' => false,
			'list'   => false,
			'titulo' => 'Galería',
			'comentario' => 'Las fotos deben tener al menos 484px de ancho por 414px de alto para verse bien. <br />Podés reordenar las fotos
					agarrandolas y arrastrándolas.',
			'properties' => array(
				'name'  => 'galeria',
				'sizes' => array(
					array('width' => 484, 'height' => 414, 'method' => 'crop'),
				),
				'marca_de_agua' => false,
				'auto_crear' => false,
				'controller'    => $controller_config['script']
			)
		);

		$campos[] =	array(
			'key'	=> 'categoria_id',
			'label'	=> 'Categoría',
			'type'	=> 'form_dropdown',
			'filter' => 'match',
			'list'	=> true,
			'class'	=> 'form-full label-up',
			'properties' => array(
				'name'    => 'categoria_id',
				'es_filtro'	=> true,
				'options' => array("" => "Elija una categoría") + $this->getOptions('categorias_model', 'nombre')
			)
		);

		$campos[] =	array(
			'key'	=> 'marca_id',
			'label'	=> 'Marca',
			'type'	=> 'form_dropdown',
			'filter' => 'match',
			'list'	=> true,
			'class'	=> 'form-full label-up',
			'properties' => array(
				'name'    => 'marca_id',
				'options' => array("" => "Elija una marca") + $this->getOptions('marcas_model', 'nombre')
			)
		);

		foreach ([
			'aplicaciones' => 'Aplicaciones',
			'gas' => 'Gas',
			'polvo' => 'Polvo',
			'ip' => 'Grado de protección',
			'video_1' => 'Enlace a video #1',
			'video_2' => 'Enlace a video #2',
			'video_3' => 'Enlace a video #3',
		] as $key => $label) {
			$campos[] = array(
				'key'	=> $key,
				'titulo'	=> $key === 'aplicaciones' ? 'Características' : '',
				'label'	=> $label,
				'type'	=> 'form_input',
				'filter' => false,
				'list'	=> false,
				'class'	=> 'form-half label-up cl-b',
				'properties' => array(
					'name'      => $key,
					'maxlength' => 255,
				)
			);
		}

		foreach ($this->filtrosProducto() as $i => $grupo) {
			$campos[] = array(
				'titulo'	=> $i === 0 ? 'Filtros' : '',
				'key'	=> 'filtros_' . $grupo->id,
				'label'	=> $grupo->nombre,
				'type'	=> 'child_relation_list',
				'filter' => false,
				'list'	=> false,
				'properties' => array(
					'controlador_hermano' => 'filtros',
					'controlador_hijo' => 'productos_filtros',
					'fk_propia' => 'producto_id',
					'fk_hermano' => 'filtro_id',
					'campo_descriptor' => 'nombre',
					'custom_filter' => 'grupo_id=' . $grupo->id,
					'filtros' => ['categoria_id' => $grupo->categoria_id],
				)
			);
		}

		$campos[] = array(
			'key'	=> 'descripcion_gral',
			'titulo'	=> 'Descripción general',
			'label'	=> false,
			'type'	=> 'form_textarea',
			'filter' => false,
			'list'	=> false,
			'class'	=> 'form-full clear label-up',
			'properties' => array(
				'name'  => 'descripcion_gral',
				'id'    => 'descripcion_gral',
				'class' => 'tinymce-basico',
			)
		);

		$campos[] = array(
			'key'	=> 'descripcion_productos',
			'titulo'	=> 'Descripción productos',
			'label'	=> false,
			'type'	=> 'form_textarea',
			'filter' => false,
			'list'	=> false,
			'class'	=> 'form-full clear label-up',
			'properties' => array(
				'name'  => 'descripcion_productos',
				'id'    => 'descripcion_productos',
				'class' => 'tinymce-producto',
			)
		);

		foreach ([
			(object) [
				'key' => 'ficha_tecnica_id',
				'label' => 'Ficha técnica',
				'allowed_types' => 'pdf'
			],
			(object) [
				'key' => 'certificado_id',
				'label' => 'Certificados',
				'allowed_types' => 'pdf|zip'
			],
		] as $i => $c) {
			$campos[] = array(
				'titulo'	=> $i === 0 ? 'Descargas' : '',
				'key'    => $c->key,
				'label'  => $c->label,
				'type'   => 'form_upload',
				'filter' => false,
				'list'	=> false,
				'class'  => 'form-full label-up',
				'comentario'  => 'Extensiones permitidas: ' . str_replace('|', ', ', $c->allowed_types),
				'properties' => array(
					'name'          => $c->key,
					'allowed_types' => $c->allowed_types,
					'thumbnail' => false,
				)
			);
		}

		for ($i = 1; $i <= 3; $i++) {
			$key = 'imagen' . $i . '_id';
			$campos[] = array(
				'key'    => $key,
				'titulo'  => $i === 1 ? 'Imágenes' : '' ,
				'label'  => 'Imagen #' . $i,
				'type'   => 'jcropimage',
				'filter' => false,
				'list'   => false,
				'class'  => 'form-third label-up',
				'comentario'  => 'Medidas: 510x260 (pixeles ancho x alto)',
				'properties' => array(
					'id'       => $key,
					'name'     => $key,
					'quantity' => 1,
					'sizes'    => array(
						//TODO: Definir medidas
						array('width' => '510', 'height' => '260', 'method' => 'crop'),
					),
					'siempre_jpg' => false,
					'margenes'    => false,
					'controller'  => $controller_config["script"]
				)
			);
		}

		
		for ($i = 1; $i <= 3; $i++) {
			$key = 'cad' . $i . '_id';
			$campos[] = array(
				'key'    => $key,
				'titulo'  => $i === 1 ? 'CAD' : '' ,
				'label'  => 'CAD #' . $i,
				'type'   => 'form_upload',
				'filter' => false,
				'list'	=> false,
				'class'  => 'form-third label-up',
				'comentario'  => 'Extensiones permitidas: dwg, zip',
				'properties' => array(
					'name'          => $key,
					'allowed_types' => 'dwg|zip',
					'thumbnail' => false,
				)
			);
		}

		for ($i = 1; $i <= 3; $i++) {
			$key = 'ies' . $i . '_id';
			$campos[] = array(
				'key'    => $key,
				'titulo'  => $i === 1 ? 'Curvas IES' : '' ,
				'label'  => 'Curva IES #' . $i,
				'type'   => 'form_upload',
				'filter' => false,
				'list'	=> false,
				'class'  => 'form-third label-up',
				'comentario'  => 'Extensiones permitidas: ies, zip',
				'properties' => array(
					'name'          => $key,
					'allowed_types' => 'ies|zip',
					'thumbnail' => false,
				)
			);
		}

		for ($i=1; $i<=20; $i++) { 
			$key = 'presentacion_3d'.$i.'_id';
			$campos[] = array(
				'key'    => $key,
				'titulo'  => $i === 1 ? 'CAD 3D' : '' ,
				'label'  => 'CAD 3D #'.$i,
				'type'   => 'form_upload',
				'filter' => false,
				'list'	=> false,
				'class'  => 'form-third label-up',
				'comentario'  => 'Extensiones permitidas: .obj',
				'properties' => array(
					'name'          => $key,
					'allowed_types' => 'obj',
					'thumbnail' => false,
				)
			);
		}

		for ($i = 1; $i <= 3; $i++) {
			$key = 'otros' . $i . '_id';
			$campos[] = array(
				'key'    => $key,
				'titulo'  => $i === 1 ? 'Otros' : '' ,
				'label'  => 'Otros #' . $i,
				'type'   => 'form_upload',
				'filter' => false,
				'list'	=> false,
				'class'  => 'form-third label-up',
				'comentario'  => 'Extensiones permitidas: pdf, zip',
				'properties' => array(
					'name'          => $key,
					'allowed_types' => 'pdf|zip',
					'thumbnail' => false,
				)
			);
		}

		$this->cargar_config(array_merge($controller_config, [
			'campos_form' => $campos
		]));
	}

	private function filtrosProducto()
	{
		$this->load->model('grupo_filtros_model');
		return $this->grupo_filtros_model->get_query_list();
	}
}
