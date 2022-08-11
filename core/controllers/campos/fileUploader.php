<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class FileUploader extends MY_Controller {

	public function index(){


		/**
		 * PHP Server-Side Example for Fine Uploader (traditional endpoint handler).
		 * Maintained by Widen Enterprises.
		 *
		 * This example:
		 *  - handles chunked and non-chunked requests
		 *  - supports the concurrent chunking feature
		 *  - assumes all upload requests are multipart encoded
		 *  - supports the delete file feature
		 *
		 * Follow these steps to get up and running with Fine Uploader in a PHP environment:
		 *
		 * 1. Setup your client-side code, as documented on http://docs.fineuploader.com.
		 *
		 * 2. Copy this file and handler.php to your server.
		 *
		 * 3. Ensure your php.ini file contains appropriate values for
		 *    max_input_time, upload_max_filesize and post_max_size.
		 *
		 * 4. Ensure your "chunks" and "files" folders exist and are writable.
		 *    "chunks" is only needed if you have enabled the chunking feature client-side.
		 *
		 * 5. If you have chunking enabled in Fine Uploader, you MUST set a value for the `chunking.success.endpoint` option.
		 *    This will be called by Fine Uploader when all chunks for a file have been successfully uploaded, triggering the
		 *    PHP server to combine all parts into one file. This is particularly useful for the concurrent chunking feature,
		 *    but is now required in all cases if you are making use of this PHP example.
		 */

		// Include the upload handler class
		require_once(APPPATH.'third_party/fineuploader/php/handler.php');

		$uploader = new UploadHandler();

		if(!$controlador_id = $this->input->get('controlador_id')){
			header("HTTP/1.0 500 ID no definido");
			exit;
		}

		if(!$campo = $this->input->get('campo')){
			header("HTTP/1.0 500 Falta definir el nombre del campo.");
			exit;
		}
		if(!$modelo = $this->input->get('modelo')){
			header("HTTP/1.0 500 Falta definir el modelo relacionado al archivo.");
			exit;
		}
		if(!$controlador = $this->input->get('controlador')){
			header("HTTP/1.0 500 Falta definir el controlador relacionado al archivo.");
			exit;
		}

		// Specify the list of valid extensions, ex. array("jpeg", "xml", "bmp")
		$uploader->allowedExtensions = array(); // all files types allowed by default
		if(isset($_GET['allowed_types']) AND $_GET['allowed_types']){
			$uploader->allowedExtensions = explode('|', urldecode($_GET['allowed_types']));
		}

		// Specify max file size in bytes.
		$uploader->sizeLimit = null;
		if(isset($_GET['max_size']) AND $_GET['max_size']){
			$uploader->sizeLimit = $_GET['max_size'] * 1024;
		}

		// Specify the input name set in the javascript.
		$uploader->inputName = "qqfile"; // matches Fine Uploader's default inputName value by default

		// If you want to use the chunking/resume feature, specify the folder to temporarily save parts.
		$uploads_folder = FILES_RESOURCES_PATH.$controlador.'/';
		$chunks_folder = $uploads_folder."chunks";
		if(!$this->crear_carpeta($uploads_folder) OR !$this->crear_carpeta($chunks_folder)){
			header("HTTP/1.0 500 No se pudo crear la carpeta para subir el archivo.");
			exit;
		}
		$uploader->chunksFolder = $chunks_folder;


		// This will retrieve the "intended" request method.  Normally, this is the
		// actual method of the request.  Sometimes, though, the intended request method
		// must be hidden in the parameters of the request.  For example, when attempting to
		// delete a file using a POST request. In that case, "DELETE" will be sent along with
		// the request in a "_method" parameter.
		function get_request_method() {
		    global $HTTP_RAW_POST_DATA;

		    if(isset($HTTP_RAW_POST_DATA)) {
		    	parse_str($HTTP_RAW_POST_DATA, $_POST);
		    }

		    if (isset($_POST["_method"]) && $_POST["_method"] != null) {
		        return $_POST["_method"];
		    }

		    return $_SERVER["REQUEST_METHOD"];
		}
		$method = get_request_method();

		if ($method == "POST") {
		    header("Content-Type: text/plain");

		    
		    // Assumes you have a chunking.success.endpoint set to point here with a query parameter of "done".
		    // For example: /myserver/handlers/endpoint.php?done
		    if (isset($_GET["done"])) {
		        $result = $uploader->combineChunks($uploads_folder);
		    }
		    // Handles upload requests
		    else {
		        // Call handleUpload() with the name of the folder, relative to PHP's getcwd()
		        $result = $uploader->handleUpload($uploads_folder);
		    }

	        // To return a name used for uploaded file you can use the following line.
	        $result["uploadName"] = $uploader->getUploadName();

		    // Si se subió todo el archivo actualizo la BD
		    if($result["uploadName"]!= null){
	            $archivo = new stdClass();

	            $nombre = $this->security->sanitize_filename($result['uploadName']);

	            $archivo->extension = substr($nombre,strripos($nombre,'.')+1);
	            $archivo->nombre = str_replace('.'.$archivo->extension, '', $nombre);
	            $archivo->titulo = $archivo->nombre;
	            $archivo->controlador = $controlador;
	            $archivo->id = $this->archivos_model->save_item($archivo, false);

	            // Agrego el archivo a la respuesta
	            $result["archivo"] = $archivo;

	            // Relaciono el archivo con la tabla del controlador
	            $item = new stdClass();
	            $item->id = $controlador_id;
	            $item->$campo = $archivo->id;
	            $this->$modelo->save_item($item, true, false);

	            // Renombro la carpeta del archivo
	            if(! rename($uploads_folder . $result["uuid"], $uploads_folder . $archivo->id)){
	            	log_message('error', 'FileUploader: No se pudo renombrar la carpeta con el archivo.');
	            }
	        }

		    echo json_encode($result);
		}
		// for delete file requests
		else if ($method == "DELETE") {
		    $result = $uploader->handleDelete($uploads_folder);
		    echo json_encode($result);
		}
		else {
		    header("HTTP/1.0 405 Method Not Allowed");
		}

	}

	public function cargar_vista($vista = 'cargar', $datos = array(), $devolver = false){

		$vista = $this->input->get('vista') ? $this->input->get('vista') : $vista;
		$datos = $this->input->get('datos') ? $this->input->get('datos') : $datos;
		$devolver = $this->input->get('devolver') ? $this->input->get('devolver') : $devolver;

    	return $this->load->view('campos/fineuploader_'.$vista, $datos, $devolver);
	}
}