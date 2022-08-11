<?php

class Archivos_model extends MY_Model {

	var $fields = array('nombre','titulo', 'extension', 'controlador', 'titulo', 'orden');

	var $table = 'archivos';

	 function __construct()
    {
        parent::__construct();
    }

}