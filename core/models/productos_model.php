<?php

class Productos_model extends MY_Model
{

	public $fields = array(
		'id',
		'activo',
		'codigo',
		'titulo',
		'descripcion_corta',
		'foto_id',
		'galeria_id',
		'categoria_id',
		'marca_id',
		'aplicaciones',
		'gas',
		'polvo',
		'ip',
		'video_1',
		'video_2',
		'video_3',
		'descripcion_gral',
		'descripcion_productos',
		'ficha_tecnica_id',
		'certificado_id',
		'imagen1_id',
		'imagen2_id',
		'imagen3_id',
		'presentacion_3d1_id',
		'presentacion_3d2_id',
		'presentacion_3d3_id',
		'presentacion_3d4_id',
		'presentacion_3d5_id',
		'presentacion_3d6_id',
		'presentacion_3d7_id',
		'presentacion_3d8_id',
		'presentacion_3d9_id',
		'presentacion_3d10_id',
		'presentacion_3d11_id',
		'presentacion_3d12_id',
		'presentacion_3d13_id',
		'presentacion_3d14_id',
		'presentacion_3d15_id',
		'presentacion_3d16_id',
		'presentacion_3d17_id',
		'presentacion_3d18_id',
		'presentacion_3d19_id',
		'presentacion_3d20_id',
		'cad1_id',
		'cad2_id',
		'cad3_id',
		'ies1_id',
		'ies2_id',
		'ies3_id',
		'otros1_id',
		'otros2_id',
		'otros3_id',
		'keywords',
		'orden',
	);

	public $table = 'productos';

	public function save_item($o, $isUpdate, $validar = true)
	{
		// Quito espacios de más
		foreach ($this->fields as $campo) {
			if (isset($o->{$campo})) {
				$o->{$campo} = trim($o->{$campo});
			}
		}

		if (isset($o->keywords)) {
			$o->keywords = implode(',', array_map('trim', explode(',', $o->keywords)));
		}
		if (isset($o->unidades_por_caja)) {
			$o->unidades_por_caja = (int) $o->unidades_por_caja;
		}

		if ($validar) {
			$obligatorios = array(
				'titulo' => 'Título',
				'descripcion_corta' => 'Descripción corta',
				'foto_id' => 'Foto principal',
				'categoria_id' => 'Categoría',
				'marca_id' => 'Marca',
			);
			foreach ($obligatorios as $key => $label) {
				$valor = $o->{$key} ?? false;
				if (!$valor) {
					throw new Exception('El campo "' . $label . '" es obligatorio.');
				}
			}

			// No permito crear un producto repetido
			if ($isUpdate) {
				$this->db->where('id !=', $o->id);
			}
			$this->db->where('LOWER(titulo)', mb_strtolower($o->titulo, 'utf-8'));
			$this->db->where('categoria_id', $o->categoria_id);
			$this->db->from($this->table);
			if ($this->db->count_all_results() > 0) {
				throw new Exception('El producto "' . $o->titulo . '" ya existe.');
			}
		}
		return parent::save_item($o, $isUpdate, $validar);
	}
}
