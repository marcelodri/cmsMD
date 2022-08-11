<?php

class Grupo_filtros_model extends MY_Model
{

	public $fields = array(
		'id',
		'nombre',
		'categoria_id',
		'orden',
	);

	public $table = 'grupo_filtros';

	public function save_item($o, $isUpdate, $validar = true)
	{
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
			if (!(isset($o->categoria_id) ? $o->categoria_id : false)) {
				throw new Exception('La categoría es obligatoria.');
			}

			// No permito crear un filtro repetido
			if ($isUpdate) {
				$this->db->where('id !=', $o->id);
			}
			$this->db->where('LOWER(nombre)', mb_strtolower($o->nombre, 'utf-8'));
			$this->db->where('categoria_id', $o->categoria_id);
			$this->db->from($this->table);
			if ($this->db->count_all_results() > 0) {
				throw new Exception('Este grupo de filtros ya existe en esta categoría.');
			}
		}
		return parent::save_item($o, $isUpdate, $validar);
	}

	public function delete_item($id)
	{
		// No permito borrar si hay productos relacionadas a este filtro
		$this->db->from('productos_filtros');
		$this->db->join('filtros', 'filtros.id=productos_filtros.filtro_id');
		$this->db->join('grupo_filtros', 'filtros.grupo_id=grupo_filtros.id');
		$this->db->where('grupo_filtros.id', $id);
		if ($this->db->count_all_results() > 0) {
			throw new Exception('Hay productos asignados a este filtro. Elimínelos o actualícelos primero si quiere borrarlo.');
		}

		// No permito borrar si hay filtros en este grupo
		$this->db->from('filtros', 'filtros.id=productos_filtros.filtro_id');
		$this->db->join('grupo_filtros', 'filtros.grupo_id=grupo_filtros.id');
		$this->db->where('grupo_filtros.id', $id);
		if ($this->db->count_all_results() > 0) {
			throw new Exception('Hay filtros asignados a este grupo. Elimínelos o actualícelos primero si quiere borrarlo.');
		}

		// Esto va siempre
		parent::delete_item($id);
	}
}
