<?php

class Productos_filtros_model extends MY_Model {

	public $fields = array(
		'id',
		'producto_id',
		'filtro_id',
		'orden',
	);

	public $table = 'productos_filtros';

	public function save_item($o, $isUpdate, $validar = true) {
		if ($validar) {
			if (!(isset($o->producto_id) ? $o->producto_id : false)) {
				throw new Exception('El producto es obligatorio.');
			}
			if (!(isset($o->filtro_id) ? $o->filtro_id : false)) {
				throw new Exception('El filtro es obligatorio.');
			}

			// No permito crear un filtro repetido
			if ($isUpdate) {
				$this->db->where('id !=', $o->id);
			}
			$this->db->where('producto_id', $o->producto_id);
			$this->db->where('filtro_id', $o->filtro_id);
			$this->db->from($this->table);
			if ($this->db->count_all_results() > 0) {
				throw new Exception('Este filtro ya existe en este producto.');
			}
		}
		return parent::save_item($o, $isUpdate, $validar);
	}

	public function actualizarRelacion($padre_id, $hijos){
		$fk_padre = 'producto_id';
		$fk_hijo = 'filtro_id';
		$relaciones = $this->get_query_list(array(' AND '.$fk_padre.'='.intval($padre_id)));
		foreach($relaciones as $r){
			if(! in_array($r->{$fk_hijo}, $hijos)){
				$this->delete_item($r->id);
			}else{
				unset($hijos[$r->{$fk_hijo}]);
			}
		}

		$relaciones = array();
		foreach($hijos as $h){
			$relaciones[] = array(
				$fk_hijo => intval($h),
				$fk_padre=>intval($padre_id)
			);
		}

		// Genero las nuevas relaciones
		if($relaciones){
			$this->db->insert_batch($this->table, $relaciones); 
		}
	}

}