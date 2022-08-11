<?php

class Admins_model extends MY_Model {

	public $fields = array(
		'id',
		'activo',
		'username',
		'email',
		'password',
		'nombre',
		'perfil',
		'order',
	);

	public $table = 'admins';

	public function get_user($username, $password = NULL) {
		$query = $this->db->get_where($this->table, array(
			'username' => $username,
			'password' => $password,
		));
		return $query->row_array();
	}

	public function save_item($o, $isUpdate, $validar = true) {
		if ($validar) {
			if (!(isset($o->nombre) ? $o->nombre : false)) {
				throw new Exception('El nombre completo es obligatorio.');
				return false;
			}

			if (!(isset($o->username) ? $o->username : false)) {
				throw new Exception('El nombre de usuario es obligatorio.');
				return false;
			}
		}
		return parent::save_item($o, $isUpdate);
	}

	public function sql_query_list($filters, $orders, $paginator = array(), $fields = '*') {
		$CI = &get_instance();
		// Oculta usuarios superadmin a los otros usuarios
		if ('superadmin' !== $CI->session->userdata('perfil')) {
			$filters[] = ' AND perfil <> "superadmin"';
		}
		
		return parent::sql_query_list($filters, $orders, $paginator, $fields);
	}
}