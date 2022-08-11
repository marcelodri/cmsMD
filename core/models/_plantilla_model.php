<?php

class _Plantilla_model extends MY_Model {

	public $fields = array(
		'id',
		'orden',
	);

	public $table = '_plantilla';

	public function save_item($o, $isUpdate, $validar = true) {
		// Quito espacios de más
		foreach ($this->fields as $campo) {
			if (isset($o->{$campo})) {
				$o->{$campo} = trim($o->{$campo});
			}
		}

		// Agrego http a todas las urls
		$urls = array('enlace');
		foreach ($urls as $url) {
			if (isset($o->{$url})) {
				$o->{$url} = prep_url(trim($o->{$url}));
			}
		}

		// Creo automáticamente la URI
		if (isset($o->titulo)) {
			include_once(APPPATH . 'helpers/cadenaUrl.php');
			$o->uri = cadenaUrl(mb_strtolower($o->titulo, 'utf-8'));
		}

		if ($validar) {
			if (!(isset($o->nombre) ? $o->nombre : false)) {
				throw new Exception('El nombre es obligatorio.');
			}

			// No permito crear una característica repetida
			if ($isUpdate) {
				$this->db->where('id !=', $o->id);
			}
			$this->db->where('LOWER(nombre)', mb_strtolower($o->nombre, 'utf-8'));
			$this->db->where('tipo', $o->tipo);
			$this->db->from($this->table);
			if ($this->db->count_all_results() > 0) {
				throw new Exception('Esta característica ya existe.');
			}
		}
		return parent::save_item($o, $isUpdate, $validar);
	}

	public function delete_item($id) {
		// Elimino todas las relaciones con los demás objetos
		$this->db->where('comedor_id', $id);
		$this->db->delete('comedores_banners');

		$this->db->where('comedor_id', $id);
		$this->db->delete('comedores_promos');

		$this->db->where('comedor_id', $id);
		$this->db->delete('menus_semanales');

		// No permito borrar si hay elementos relacionadas a esta categoría
		$this->db->where('categoria_id', $id);
		$this->db->from('subcategorias');
		if ($this->db->count_all_results() > 0) {
			throw new Exception('Hay subcategorías asignadas a esta categoría. Elimínelas primero si quiere borrarla.');
		}
		
		// Esto va siempre
		parent::delete_item($id);

		// No permito eliminar un elemento
		throw new Exception('no tiene permisos para eliminar este elemento.');
	}
}