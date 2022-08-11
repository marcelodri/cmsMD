<?php

use PHPMailer\PHPMailer\PHPMailer;

class Usuarios_model extends MY_Model {

	public $fields = array(
		'id',
		'activo',
		'username',
		'email',
		'password',
		'nombre',
		'perfil',

		// Específicos de usuarios
    'cuil',
    'telefono',
    'empresa',
    'actividad',
    'cargo',
    'direccion',
    'localidad',
    'provincia',
    'pais',
    'fecha',
    'activo',
    'ficha',
    'certificado',
    'plano',
    'curvas',
    '3d',

		'order',
	);

	// Comparte tabla con admins
	public $table = 'admins';

	public function save_item($o, $isUpdate, $validar = true) {
		// Quito espacios de más
		foreach ($this->fields as $campo) {
			if (isset($o->{$campo})) {
				$o->{$campo} = trim($o->{$campo});
			}
		}

		if ($validar) {
			$obligatorios = array(
				'email' => 'Email',
			);
			foreach ($obligatorios as $key => $label) {
				if (!(isset($o->{$key}) ? $o->{$key} : false)) {
					throw new Exception('El campo "'.$label.'" es obligatorio.');
				}
			}
			if ($isUpdate) {
				$this->db->where('id !=', $o->id);
			}
			$this->db->where('LOWER(email)', mb_strtolower($o->email, 'utf-8'));
			$this->db->from($this->table);
			if ($this->db->count_all_results() > 0) {
				throw new Exception('Ya existe un usuario con este email.');
			}
		}

		if (!$isUpdate) {
			$o->fecha = date('Y-m-d');
		}

		if($isUpdate AND isset($o->activo) AND $o->activo){
			$u = $this->get_item($o->id);
			if(!$u->activo){
				try {
					$this->enviarMailActivacion($u);
				}catch(Exception $e){
					throw new Exception('No se pudo enviar el email de activación, vuelva a intentarlo por favor.');
				}
			}
		}

		$o->username = $o->email;
		$o->perfil = 'cliente';

		return parent::save_item($o, $isUpdate, $validar);
	}

	public function sql_query_list($filters, $orders, $paginator = array(), $fields = '*') {
		$filters[] = ' AND perfil = "cliente"';
		return parent::sql_query_list($filters, $orders, $paginator, $fields);
	}


	private function enviarMailActivacion($u){
		$this->enviarMail(
			$u->email,
			'Delga SAIC y F. - Acceso de usuario habilitado',
			'Estimado '.$u->nombre.',<br/>Su usuario ha sido habilitado, ya puede acceder a la totalidad contenidos y descargas.<br/> Ante cualquier consulta puede contactarnos a <a href="mailto:delgasa@delga.com">delgasa@delga.com</a> o por medio del chat en nuestro portal. <br/><br/><b>DELGA SAIC y F</b><br/><br/>'
		);
	}
	public function enviarMail($email, $asunto, $cuerpo){
		$PHPMailer = new PHPMailer();

		$PHPMailer->isSMTP();
		// $PHPMailer->SMTPDebug = 4;
		$PHPMailer->Debugoutput = 'html';
		$this->config->load('email', true);
		$PHPMailer->Host = $this->config->item('host', 'email');
		$PHPMailer->Port = $this->config->item('port', 'email');
		$PHPMailer->SMTPSecure = $this->config->item('smtp_secure', 'email');
		$PHPMailer->SMTPAuth = true;
		$PHPMailer->Username = $this->config->item('username', 'email');
		$PHPMailer->Password = $this->config->item('password', 'email');

		$PHPMailer->CharSet  = "UTF-8";
		$PHPMailer->IsHTML(true);
		$PHPMailer->Subject  = $asunto;
		$PHPMailer->From     = $this->config->item('username', 'email');
		$PHPMailer->FromName = 'Web DELGA SAIC y F';

		$PHPMailer->AddAddress($email);

		$PHPMailer->Body = $cuerpo;

		// Se realizan hasta 3 intentos de envio del correo
		for($i=0; $i<3; $i++){
			if($PHPMailer->Send()){
				break;
			}
			sleep(2);
		}
    }
}