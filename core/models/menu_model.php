<?php

class menu_model extends MY_Model {

	var $fields = array(
		'nombre',
		'perfil',
		'controlador',
		'tipo',
		'listar',
		'categoria',
		'grupo',
		'orden'
	);

	var $table = 'menu';
}
