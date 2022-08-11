<?php

Class Gallery_ajax extends CI_Controller {

	// No recortar imagen cuando la original es vertical
	const NO_RECORTAR_RV = true;

	function index()
	{

        echo "Nothing to view here";

    }

    function new_gallery(){

        $g = new stdClass();
        $g->nombre = '';
        $id = $this->galerias_model->save_item($g, false);


        if($id > 0){

			if( @mkdir(GALLERIES_RESOURCES_PATH.$id, DIR_WRITE_MODE, true) ){
				echo $id;
			}else{
				$error = error_get_last();
				log_message('GALLERY_AJAX: '.$error['message']);
				header("HTTP/1.0 404 Not Found");
				header("Status: 404 Not Found");
			}

		}else{
			log_message('GALLERY_AJAX: El ID de la galería no es correcto.');
			header("HTTP/1.0 404 Not Found");
			header("Status: 404 Not Found");
		}

    }

    function load_uploader(){

		$galerias_id 	= $this->input->get('galerias_id');
		$sizes 			= $this->input->get('sizes');
		$name 			= $this->input->get('name');
		$marca_de_agua 	= $this->input->get('marca_de_agua');

		$this->load->helper('miscelanea_helper');

		echo '<label></label><div id="file-uploader-'.$name.'">
					<noscript>
						<p>Please enable JavaScript to use file uploader.</p>
						<!-- or put a simple form for upload here -->
					</noscript>
				</div>

				<script>
					jQuery(function(){
						var uploader = new qq.FileUploader({
							element: document.getElementById(\'file-uploader-'.$name.'\'),
							action: \''.base_url().'index.php?c=gallery_ajax&m=upload_photo&galerias_id='.$galerias_id.'&marca_de_agua='.$marca_de_agua.'\',
							onComplete: function(id, fileName, responseJSON){ loadGallery("'.base_url().'",'.$galerias_id.',"'.$name.'","'.$marca_de_agua.'") },
							params: {
								sizes: \''.$sizes.'\'
							},
							sizeLimit: '.file_upload_max_size().',
							messages: {
					            typeError: "{file} tiene una extensión inválida. Dole están permitidas las siguientes: {extensions}.",
					            sizeError: "{file} es muy grande, el tamaño máximo permitido es {sizeLimit}.",
					            minSizeError: "{file} es muy chico, el tamaño mínimo es {minSizeLimit}.",
					            emptyError: "{file} está vacío, por vafor seleccioná el archivo de nuevo.",
					            onLeave: "Los archivos se están subiendo, si te vas ahora la transferencia va a ser cancelada."
					        },

					        dragText: "Arrastrá y soltá las fotos acá para subirlas",
					        uploadButtonText: "Subir fotos",
					        cancelButtonText: "Cancelar",
					        failUploadText: "Subida fallida",

						});

					});
				</script>';


	}

    function load_gallery(){

		$galerias_id = $this->input->get('galerias_id');
		$name = $this->input->get('name');

		if($galerias_id>0){

			$html = "";

            $sql = 'SELECT * FROM fotos WHERE galerias_id = '.$galerias_id.' ORDER BY orden ASC';
			$query = $this->db->query($sql);

			$items = $query->result_array();

            if(count($items)>0){

				foreach($items as $i=>$foto){
					$html .= "<li class='img-thumb-box' id='orden_".$foto["id"]."'>
								<img style='height:100px; width:auto;' title='".$foto["epigrafe"]."' src='".base_url()."resources/galleries/".$foto["galerias_id"]."/".$foto["filename"]."_thumbnail.".$foto["extension"]."'>
								<a class='delete' href='javascript:deletePhoto(\"".base_url()."\",".$foto["id"].",".$foto["galerias_id"].",\"".$name."\");'>Delete</a>
								<a class='editar-alt' href='javascript:changeEpigraph(". $foto["id"] .");'><i class='fa fa-tag fa-lg' aria-hidden='true'></i></a>
							</li>";
				}
				echo $html;
			}else{
				echo 'No hay fotos cargadas a la galería.';
			}

		}else{
			log_message('GALLERY_AJAX: El ID de la galería no es correcto.');
			header("HTTP/1.0 404 Not Found");
			header("Status: 404 Not Found");
		}

    }

    function delete_photo(){

		$galerias_id = $this->input->get('galerias_id');
		$id = $this->input->get('id');

		if($id>0){

			$html = "";

			$sql = 'SELECT * FROM fotos WHERE id = '.$id.' LIMIT 1';
			$query = $this->db->query($sql);
            $result = $query->result_array();

            if(count($result)>0){

				$path = GALLERY_RESOURCES_PATH.$galerias_id.'/'.$result[0]['filename'].'*';
		        $files = glob($path);
		        if(is_array($files)){
					foreach($files as $file){
						unlink($file);
					}
				}

				$this->db->where('id',$id);
				$this->db->delete('fotos');

			}else{
				log_message('GALLERY_AJAX: En la base de datos no existe la foto que se quiere eliminar.');
				header("HTTP/1.0 404 Not Found");
				header("Status: 404 Not Found");
			}

		}else{
			log_message('GALLERY_AJAX: El ID de la galería no es correcto.');
			header("HTTP/1.0 404 Not Found");
			header("Status: 404 Not Found");
		}

    }

    function upload_photo(){

		$galerias_id = $this->input->get('galerias_id');
		$marca_de_agua = $this->input->get('marca_de_agua') == 'false' ?  false : $this->input->get('marca_de_agua');


		if($galerias_id>0){

			$validExtensions = array('jpeg','jpg','png','bmp','gif');
			$fileinfo 		 = pathinfo($this->input->get('qqfile'));
			$ext 			 = $fileinfo['extension'];

			if( in_array(strtolower($ext), $validExtensions) ){

				$input	  = fopen("php://input", "r");
				$temp 	  = tmpfile();
				$realSize = stream_copy_to_stream($input, $temp);
				fclose($input);

				$path = GALLERIES_RESOURCES_PATH.$galerias_id.'/';

				// Si no existe la carpeta la creo
				if( !file_exists($path) ){
					if(!@mkdir($path, DIR_WRITE_MODE, true)){
						$error = error_get_last();
						log_message('GALLERY_AJAX: '.$error['message']);
					}
				}

				$randname = md5(time()+rand());
				$filepath = $path.$randname.'.'.$ext;

				$target = fopen($filepath, "w");
				fseek($temp, 0, SEEK_SET);
				stream_copy_to_stream($temp, $target);
				fclose($target);

				if( file_exists($filepath) ){

					$f = new stdClass();
					$f->filename = $randname;
					$f->extension = $ext;
					$f->epigrafe = "";
					$f->orden = 999999;
					$f->galerias_id = $galerias_id;
					$foto_id = $this->fotos_model->save_item($f,false);

				}else{
					return array('error' => 'No se pudo guardar la imagen.');
				}

			}else{
				return array('error' => 'El archivo que intenta subir no tiene una extensión válida.');
			}

		}else{
			return array('error' => 'La galería es inválida.');
		}

		/* Genero los tamaños */
		$sizes = unserialize(base64_decode($this->input->get('sizes')));

		$this->generate_sizes( $galerias_id, $sizes, $foto_id, $marca_de_agua );

		return array('success'=>true);

	}

	function generate_sizes( $galerias_id, $sizes, $foto_id, $marca_de_agua = false, $sobreescribir = false ){

		if( $galerias_id<=0 ){
			return array('error' => 'La galería no es válida.');
		}

		if( $foto_id<=0 ){
			return array('error' => 'La foto no es válida.');
		}

		$sql = 'SELECT * FROM fotos WHERE id = '.$foto_id.' ORDER BY orden ASC LIMIT 1';
		$query_foto = $this->db->query($sql);

		if( $query_foto->num_rows <= 0 ){
			return array('error' => 'La foto no es válida.');
		}

		$foto = $query_foto->row();
		$randname = $foto->filename;
		$ext 	  = $foto->extension;

		$path = GALLERIES_RESOURCES_PATH.$galerias_id.'/';
		$filepath = $path.$randname.'.'.$ext;

		foreach($sizes as $size){

			$filepath_new_image = $path.$randname.'_'.$size["width"].'x'.$size["height"].'.'.$ext;

			if( !$sobreescribir AND file_exists($filepath_new_image) ){
				continue;
			}


			//// RECORTAR ///
			if($size["method"] == 'crop'){

				//Primero ajustamos el tamaño de la imagen redimensionándola
				$config['image_library']  = 'gd2';
				$config['source_image']	  = $filepath;
				$config['new_image']	  = $filepath_new_image;
				$config['maintain_ratio'] = TRUE;
				$config['width']		  = $size["width"];
				$config['height']		  = $size["height"];

				//Guardamos las medidas de la imagen original
				list($original_width, $original_height) = getimagesize($filepath);

				

				// Como no vamos a recortar las imágenes verticales mantenemos 
				// el alto y ajustamos el ancho
				$figura_original_RV = ($original_height/$original_width)>1;
				if($figura_original_RV AND self::NO_RECORTAR_RV){
					$config['master_dim'] = 'height';
				
				// Para evitar que al recortar queden espacios en negro vemos qué lado nos conviene mantener fijo
				}else{
					$config['master_dim'] = $this->calcular_master_dim(
						$original_width,
						$original_height,
						$config['width'],
						$config['height']
					);
				}

				$this->image_lib->initialize($config);

				if ( ! $this->image_lib->resize() ){
					return array('error' => $this->image_lib->display_errors());
				}
				$this->image_lib->clear();

				//Guardamos las medidas de la imagen redimensionada
				list($redimensionada_width, $redimensionada_height) = getimagesize($filepath_new_image);

				if (!($figura_original_RV AND self::NO_RECORTAR_RV)) {

					//Después recortamos la imagen redimensionada
					$config['image_library'] 	= 'gd2';
					$config['source_image']		= $filepath_new_image;
					$config['new_image'] 		= $filepath_new_image;
					$config['maintain_ratio'] 	= FALSE;
					$config['width']   			= $size["width"];
					$config['height']			= $size["height"];

					//Sobra alto o ancho. Si sobra alto recortamos alto
					if(($redimensionada_height > $size["height"]) AND ($redimensionada_width == $size["width"])){
						$config['x_axis']	= 0;
						$config['y_axis']	= ($redimensionada_height - $size["height"])/2;

					//Sino sobra alto sobra ancho, entonces recortamos ancho
					}elseif(($redimensionada_width > $size["width"]) AND ($redimensionada_height == $size["height"])){
						$config['x_axis']	= ($redimensionada_width - $size["width"])/2;
						$config['y_axis']	= 0;

					//No recortamos
					}else{
						$config['x_axis']	= 0;
						$config['y_axis']	= 0;
					}

					$this->image_lib->initialize($config);

					if( ! $this->image_lib->crop() ){
						return array('error' => $this->image_lib->display_errors());
					}
					$this->image_lib->clear();
				}


			//// REDIMENSIONAR ///
			}else{

				if(isset($size["master_dim"]) AND $size["master_dim"]=='fit'){

					list($original_width, $original_height) = getimagesize($filepath);
					$config['master_dim'] = $this->calcular_master_dim_resize(
						$original_width,
						$original_height,
						$size['width'],
						$size['height']
					);

					$config['image_library']  = 'gd2';
					$config['source_image']   = $filepath;
					$config['new_image']      = $filepath_new_image;
					$config['maintain_ratio'] = true;
					$config['width']          = $size["width"];
					$config['height']         = $size["height"];

					$this->image_lib->initialize($config);

					if( ! $this->image_lib->resize() ){
						return array('error' => $this->image_lib->display_errors());
					}
					$this->image_lib->clear();

					//Creo una imagen blanca y la uso de fondo
					$path_fondo = $path.'fondo_'.$size["width"].'x'.$size["height"].'.jpg';

					$fondo = imagecreatetruecolor( $size["width"], $size["height"] );
					$blanco = imagecolorallocate ( $fondo, 255, 255, 255 );
					imagefilledrectangle( $fondo, 0, 0, $size["width"], $size["height"], $blanco);
					imagejpeg( $fondo, $path_fondo, 100 );

					$config['source_image']		= $path_fondo;
					$config['new_image']		= $filepath_new_image;
					$config['wm_type'] 			= 'overlay';
					$config['wm_overlay_path']	= $filepath_new_image;
					$config['wm_opacity']		= 100;
					$config['wm_vrt_alignment'] = 'middle';
					$config['wm_hor_alignment'] = 'center';
					$config['wm_padding'] 		= '0';

					// Deshabilito la transparencia
					$config['wm_x_transp'] 		= -9999;
					$config['wm_y_transp'] 		= -9999;


					$this->image_lib->initialize($config);
					$this->image_lib->watermark();
					$this->image_lib->clear();

				}else{

					$config['image_library']  = 'gd2';
					$config['source_image']   = $filepath;
					$config['new_image']      = $filepath_new_image;
					$config['master_dim'] 	  = isset($size["master_dim"]) 		? $size["master_dim"] 		: 'auto';
					$config['maintain_ratio'] = isset($size["maintain_ratio"]) 	? $size["maintain_ratio"] 	: true;
					$config['width']          = $size["width"];
					$config['height']         = $size["height"];

					$this->image_lib->initialize($config);

					if( ! $this->image_lib->resize() ){
						return array('error' => $this->image_lib->display_errors());
					}
					$this->image_lib->clear();
				}
			}

			//// MARCA DE AGUA ///
			if( is_string($marca_de_agua) AND $marca_de_agua!=''  ){
				$config['source_image']		= $filepath_new_image;
				$config['wm_type'] 			= 'overlay';
				$config['wm_overlay_path']	= IMAGES_RESOURCES_PATH.$marca_de_agua.'_'.$size["width"].'x'.$size["height"].'.png';
				$config['wm_opacity']		= 100;
				$config['wm_vrt_alignment'] = 'middle';
				$config['wm_hor_alignment'] = 'center';
				$config['wm_padding'] 		= '0';

				$this->image_lib->initialize($config);
				$this->image_lib->watermark();
				$this->image_lib->clear();
			}

		}

		/*Genero Thumbnail*/
		$config['image_library'] 	= 'gd2';
		$config['source_image']		= $filepath;
		$config['new_image'] 		= $path.$randname.'_thumbnail.'.$ext;
		$config['maintain_ratio'] 	= true;
		$config['master_dim'] 		= 'height';
		$config['width']	 		= 100;
		$config['height']			= 100;

		$this->image_lib->initialize($config);

		if( ! $this->image_lib->resize()) {
			echo $this->image_lib->display_errors();
		}
		$this->image_lib->clear();


		return array('success'=>true);
	}

	function calcular_master_dim($ancho_original, $alto_original, $ancho_nuevo, $alto_nuevo){

		$R = ($alto_nuevo/$ancho_nuevo);//Proporción de la imagen a generar
		$RO = ($alto_original/$ancho_original);//Proporción de la imagen a original

		//Según la proporción de los lados definimos qué tipo de figura es
		if($R == 1){
			$figura_generar = 'C';//Cuadrado
		}elseif( $R > 1 ){
			$figura_generar = 'RV';//Rectángulo Vertical
		}else{
			$figura_generar = 'RH';//Rectángulo Horizontal
		}

		if($RO == 1){
			$figura_original = 'C';//Cuadrado
		}elseif( $RO > 1 ){
			$figura_original = 'RV';//Rectángulo Vertical
		}else{
			$figura_original = 'RH';//Rectángulo Horizontal
		}

		//Cuando las figuras son distintas tenemos que respetar el lado de la figura original que coincide con el lado más grande de la figura a generar
		if($figura_generar != $figura_original){

			if($alto_nuevo > $ancho_nuevo){
				$master_dim = 'height';

			}elseif($ancho_nuevo > $alto_nuevo){
				$master_dim = 'width';

			}else{//Si son iguales los lados de la figura a generar

				if($figura_original == 'RV'){
					$master_dim = 'width';

				}elseif($figura_original == 'RH'){
					$master_dim = 'height';

				}else{
					$master_dim = 'width';//Puede ser cualquiera
				}
			}

		//Cuando las figuras son iguales el lado a respetar depende de la relación entre las proporciones de las figuras
		}else{
			if($R > $RO){
				$master_dim = 'height';

			//Si es menor (No pueden ser iguales, si no serían figuras iguales)
			}else{
				$master_dim = 'width';
			}
		}

		return $master_dim;
    }

    function calcular_master_dim_resize($ancho_original, $alto_original, $ancho_nuevo, $alto_nuevo){

		$R = ($alto_nuevo/$ancho_nuevo);//Proporción de la imagen a generar
		$RO = ($alto_original/$ancho_original);//Proporción de la imagen a original

		//Según la proporción de los lados definimos qué tipo de figura es
		if($R == 1){
			$figura_generar = 'C';//Cuadrado
		}elseif( $R > 1 ){
			$figura_generar = 'RV';//Rectángulo Vertical
		}else{
			$figura_generar = 'RH';//Rectángulo Horizontal
		}

		if($RO == 1){
			$figura_original = 'C';//Cuadrado
		}elseif( $RO > 1 ){
			$figura_original = 'RV';//Rectángulo Vertical
		}else{
			$figura_original = 'RH';//Rectángulo Horizontal
		}

		//Cuando las figuras son distintas tenemos que respetar el lado de la figura original que coincide con el lado más grande de la figura a generar
		if($figura_generar != $figura_original){

			if($figura_generar == 'RV'){
				$master_dim = 'width';

			}elseif($figura_generar == 'RH'){
				$master_dim = 'height';

			}elseif($figura_generar == 'C'){

				if($figura_original == 'RV'){
					$master_dim = 'height';

				}elseif($figura_original == 'RH'){
					$master_dim = 'width';
				}
			}

		//Cuando las figuras son iguales el lado a respetar depende de la relación entre las proporciones de las figuras
		}elseif($figura_generar == $figura_original){
			
			if($R > $RO){
				$master_dim = 'width';

			}elseif($R < $RO){
				$master_dim = 'height';

			}else{
				$master_dim = 'auto';
			}
		}

		return $master_dim;
    }

    function get_epigraph(){

		$id = $this->input->get('id');

		if($id>0){

			$f = $this->fotos_model->get_item($id);
			echo $f->epigrafe;

		}else{
			log_message('GALLERY_AJAX: El ID del epígrafe no puede ser cero.');
			header("HTTP/1.0 404 Not Found");
			header("Status: 404 Not Found");
		}

    }

    function set_epigraph(){

		$id = $this->input->get('id');

		if($id>0){

			$f = $this->fotos_model->get_item($id);
			$f->epigrafe = $this->input->post('epigrafe');
			$this->fotos_model->save_item($f,true);

		}else{
			log_message('GALLERY_AJAX: El ID del epígrafe no puede ser cero.');
			header("HTTP/1.0 404 Not Found");
			header("Status: 404 Not Found");
		}
    }

    function sort(){

		$orden = $this->input->post('orden');

		foreach($orden as $i=>$fotos_id){
			$f = $this->fotos_model->get_item($fotos_id);
			$f->orden = $i;
			$this->fotos_model->save_item($f,true);

		}
	}
}