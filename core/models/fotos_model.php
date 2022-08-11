<?php

class Fotos_model extends MY_Model {

	var $fields = array(
	 'filename',
	 'extension',
	 'epigrafe',
	 'galerias_id',
	 'orden');

	var $table = 'fotos';

	 function __construct()
    {
        parent::__construct();
    }

    function fotos_galeria($galeria_id){
    	
    	$this->db->where('galerias_id', $galeria_id);
    	$this->db->order_by('orden ASC, id ASC');
		$query = $this->db->get( $this->table );

		$fotos = array();
		foreach ($query->result() as $row) {
		    $fotos[] = $row;
		}
		return $fotos;
    }

}
