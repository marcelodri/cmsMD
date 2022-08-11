<?php

class Marcas_model extends MY_Model {

	public $fields = array(
		'id',
		'nombre',
		'logo_id',
		'orden',
	);

	public $table = 'marcas';

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
			if (!(isset($o->logo_id) ? $o->logo_id : false)) {
				throw new Exception('El logo es obligatorio.');
			}

			// No permito crear una marca repetida
			if ($isUpdate) {
				$this->db->where('id !=', $o->id);
			}
			$this->db->where('LOWER(nombre)', mb_strtolower($o->nombre, 'utf-8'));
			$this->db->from($this->table);
			if ($this->db->count_all_results() > 0) {
				throw new Exception('Esta marca ya existe.');
			}
		}
		return parent::save_item($o, $isUpdate, $validar);
	}

	public function delete_item($id) {
		// No permito borrar si hay productos relacionadas a esta marca
		$this->db->where('marca_id', $id);
		$this->db->from('productos');
		if ($this->db->count_all_results() > 0) {
			throw new Exception('Hay productos asignados a esta marca. Elimínelos primero si quiere borrarla.');
		}
		
		// Esto va siempre
		parent::delete_item($id);
	}
}