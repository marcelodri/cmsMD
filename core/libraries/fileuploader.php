<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class FileUploader {

	public function newUploader($allowedExtensions, $sizeLimit){

		require_once(APPPATH.'third_party/qqUploader/qqUploadedFileXhr.php');

		return new qqFileUploader($allowedExtensions, $sizeLimit);;
	}
}

