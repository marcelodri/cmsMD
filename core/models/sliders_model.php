<?php

class Sliders_model extends MY_Model {

	public $fields = array(
		'id',
		'titulo',
		'bajada',
		'cuerpo',
		'imagen_id',
		'adjunto_id',
		'orden',
	);

	public $table = 'sliders';

	public function save_item($o, $isUpdate, $validar = true) {
		// Quito espacios de más
		foreach ([
			'titulo'
		] as $campo) {
			if (isset($o->{$campo})) {
				$o->{$campo} = trim($o->{$campo});
			}
		}

		if ($validar) {
			if (!(isset($o->titulo) ? $o->titulo : false)) {
				throw new Exception('El título es obligatorio.');
			}
			// if (!(isset($o->bajada) ? $o->bajada : false)) {
			// 	throw new Exception('La bajada es obligatoria.');
			// }
		}
		return parent::save_item($o, $isUpdate, $validar);
	}
}