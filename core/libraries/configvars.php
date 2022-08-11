<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Configvars {

	var $config = array();

    function __construct()
    {
    	if( !defined('ONE_DAY') ){
        	define('ONE_DAY', 60*60*24);
        }

		$config["meses_corto"][1] = "Ene";
		$config["meses_corto"][2] = "Feb";
		$config["meses_corto"][3] = "Mar";
		$config["meses_corto"][4] = "Abr";
		$config["meses_corto"][5] = "May";
		$config["meses_corto"][6] = "Jun";
		$config["meses_corto"][7] = "Jul";
		$config["meses_corto"][8] = "Ago";
		$config["meses_corto"][9] = "Sep";
		$config["meses_corto"][10] = "Oct";
		$config["meses_corto"][11] = "Nov";
		$config["meses_corto"][12] = "Dic";

		$config["days"]["Monday"] = "Lunes";
		$config["days"]["Tuesday"] = "Martes";
		$config["days"]["Wednesday"] = "Miercoles";
		$config["days"]["Thursday"] = "Jueves";
		$config["days"]["Friday"] = "Viernes";
		$config["days"]["Saturday"] = "Sabado";
		$config["days"]["Sunday"] = "Domingo";

		$this->config = $config;

    }

    function get_config(){
		return $this->config;
	}
}

?>
