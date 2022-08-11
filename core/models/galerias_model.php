<?php

class Galerias_model extends MY_Model {
	
	var $fields = array('nombre');
	
	var $table = 'galerias';
	
	 function __construct()
    {
        parent::__construct();
    }

}
