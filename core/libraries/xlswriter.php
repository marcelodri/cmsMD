<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class XLSWriter {

	var $objPHPExcel;

	function __construct(){

		require_once(APPPATH.'../libs/phpexcel/PHPExcel.php');

		$this->objPHPExcel = new PHPExcel();

	}

	function getHandler(){
		return $this->objPHPExcel;
	}

}


