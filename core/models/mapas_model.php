<?php

class Mapas_model extends MY_Model {

	public $fields = array(
    'id',
    'activo',
    'lat',
    'lng',
    'streetview',
    'busqueda'
  );

	public $table = 'mapas';
}
