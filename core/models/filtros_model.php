<?php

class Filtros_model extends MY_Model {

	public $fields = array(
		'id',
		'nombre',
		'grupo_id',
		'orden',
	);

	public $table = 'filtros';

	public function save_item($o, $isUpdate, $validar = true) {
		// Quito espacios de más
		foreach ($this->fields as $campo) {
			if (isset($o->{$campo})) {
				$o->{$campo} = trim($o->{$campo});
			}
		}
		if ($validar) {
			if (!(isset($o->nombre) ? $o->nombre : false)) {
				throw new Exception('El nombre es obligatorio.');
			}
			if (!(isset($o->grupo_id) ? $o->grupo_id : false)) {
				throw new Exception('El grupo es obligatorio.');
			}

			// No permito crear un filtro repetido
			if ($isUpdate) {
				$this->db->where('id !=', $o->id);
			}
			$this->db->where('LOWER(nombre)', mb_strtolower($o->nombre, 'utf-8'));
			$this->db->where('grupo_id', $o->categoria_id);
			$this->db->from($this->table);
			if ($this->db->count_all_results() > 0) {
				throw new Exception('Este filtro ya existe en este grupo.');
			}
		}
		return parent::save_item($o, $isUpdate, $validar);
	}

	public function delete_item($id) {
		// No permito borrar si hay productos relacionadas a este filtro
		$this->db->where('filtro_id', $id);
		$this->db->from('productos_filtros');
		if ($this->db->count_all_results() > 0) {
			throw new Exception('Hay productos asignados a este filtro. Elimínelos o actualícelos primero si quiere borrarlo.');
		}
		
		// Esto va siempre
		parent::delete_item($id);
	}
}