<?php

class Categorias_model extends MY_Model {

	public $fields = array(
		'id',
		'nombre',
		'color',
		'icono_id',
		'orden',
	);

	public $table = 'categorias';

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
			if (!(isset($o->color) ? $o->color : false)) {
				throw new Exception('El color es obligatorio.');
			}
			if (!(isset($o->icono_id) ? $o->icono_id : false)) {
				throw new Exception('El ícono es obligatorio.');
			}

			// No permito crear una categoría repetida
			if ($isUpdate) {
				$this->db->where('id !=', $o->id);
			}
			$this->db->where('LOWER(nombre)', mb_strtolower($o->nombre, 'utf-8'));
			$this->db->from($this->table);
			if ($this->db->count_all_results() > 0) {
				throw new Exception('Esta categoría ya existe.');
			}
		}
		return parent::save_item($o, $isUpdate, $validar);
	}

	public function delete_item($id) {
		// No permito borrar si hay productos relacionadas a esta categoría
		$this->db->where('categoria_id', $id);
		$this->db->from('productos');
		if ($this->db->count_all_results() > 0) {
			throw new Exception('Hay productos asignados a esta categoría. Elimínelos primero si quiere borrarla.');
		}
		
		// Esto va siempre
		parent::delete_item($id);
	}
}