<?php

/**
* Jcrop_ajax: Clase para recortar fotos
* v.1.7 (Creo. A la 1.6 le agregué soporte para poder recortar fotos sin haber grabado en la BD el objeto con el que se va a relacionar)
*
*/
Class Jcrop_ajax extends CI_Controller {

	function index()
	{
        echo "Nothing to view here";
    }

    function load_image(){

		$foto_id 		= $this->input->get('foto_id');
		$nombre 		= $this->input->get('nombre');
		$controlador 	= $this->input->get('controlador');
		$controlador_id = $this->input->get('controlador_id');

		$anchos = explode(',', $this->input->get('anchos') );
		$ancho_principal = $anchos[0];

		$altos  = explode(',', $this->input->get('altos') );
		$alto_principal  = $altos[0];

		// Si todos tienen un lado automático no hay que recortar nada
		$cargar_jcrop = false;
		foreach($anchos as $i => $anch){
			if($anchos[$i] != 'auto' AND $altos[$i] != 'auto'){
				$cargar_jcrop = true;
			}
		}
		unset($anch);

		$cargada = FALSE;
		if( $foto_id>0 ){

			$sql = 'SELECT * FROM fotos WHERE id = '.$foto_id;
			$query = $this->db->query($sql);

	        //Muestro la imagen o doy la opción de cargarla
	        if( $query->num_rows() >0 AND $foto_id>0){
	        	$cargada = TRUE;
				$foto = $query->row();
				// Construyo el nombre de la foto
				if($alto_principal=='auto' AND $ancho_principal=='auto'){
					$medidas_foto = '';
				}else{
					$medidas_foto = '_'.$alto_principal.'x'.$ancho_principal;
				}
				$nombre_foto = $foto->filename.$medidas_foto.'.'.$foto->extension;

				$ruta_foto = $controlador.'/'.$controlador_id.'/'.$nombre_foto;
				$html_foto_cargada = '<img style="max-height:100px" src="'.IMAGES_RESOURCES_URL.$ruta_foto.'?v='.filemtime(IMAGES_RESOURCES_PATH.$ruta_foto).'">';

				if($cargar_jcrop){
					$html_foto_cargada = '<a href="javascript:cargarJcrop( \''.base_url().'\', \''.$nombre.'\', \''.$controlador.'\', \''.$controlador_id.'\', \''.$foto->extension.'\' );" style="display:block; ">'.
						$html_foto_cargada.'</a>';
				}
				$html_foto_cargada = '<div class="img-thumb-box">'.
										$html_foto_cargada.'
									 	<a class="delete" href="javascript:borrarImagen( \''.base_url().'\', \''.$nombre.'\', \''.$controlador.'\', '.$controlador_id.', '.$foto_id.');"><i class="fa fa-times fa-lg" aria-hidden="true"></i></a>
									 	<a class="editar-alt" href="javascript:changeEpigraph('.$foto_id.');"><i class="fa fa-tag fa-lg" aria-hidden="true"></i></a>
									 </div>
									 <input name="'.$nombre.'" value="'.$foto_id.'" type="hidden"/>';

			}

		}

		$html_btn_cargar_foto = '<script>
			    					$(document).ready(function() {
			    						cargarUploader( \''.base_url().'\', \''.$nombre.'\', \''.$controlador.'\', '.$controlador_id.');
			    					});
								</script>';

        echo ($cargada) ? $html_foto_cargada : $html_btn_cargar_foto;

    }


    function load_uploader(){

		$nombre			= $this->input->get('nombre');
		$controlador 	= $this->input->get('controlador');
		$controlador_id = $this->input->get('controlador_id');
		$anchos 		= $this->input->get('ancho');
		$altos 			= $this->input->get('alto');
		$medida 		= 0;

		$this->load->helper('miscelanea_helper');

		//Documentación: http://valums-file-uploader.github.io/file-uploader/
		echo '<div id="file-uploader-'.$nombre.'" style="width:auto;">
					<noscript>
						<p>Please enable JavaScript to use file uploader.</p>
						<!-- or put a simple form for upload here -->
					</noscript>
				</div>

				<script>
					jQuery(function(){

						var siempre_jpg = $("#jcrop_'.$nombre.'").attr("data-jcrop-siempre-jpg");
						var margenes = $("#jcrop_'.$nombre.'").attr("data-jcrop-margenes");

						var uploader = new qq.FileUploader({

							element: document.getElementById(\'file-uploader-'.$nombre.'\'),

							 // path to server-side upload script
							action: \''.base_url().'index.php?c=jcrop_ajax&m=upload_photo&nombre='.$nombre.'&controlador='.$controlador.'&controlador_id='.$controlador_id.'&alto='.$altos.'&ancho='.$anchos.'&siempre_jpg=\'+siempre_jpg+\'&margenes=\'+margenes,

							// additional data to send, name-value pairs
							//params: {},

							sizeLimit: '.file_upload_max_size().',
							messages: {
					            typeError: "{file} tiene una extensión inválida. Dole están permitidas las siguientes: {extensions}.",
					            sizeError: "{file} es muy grande, el tamaño máximo permitido es {sizeLimit}.",
					            minSizeError: "{file} es muy chico, el tamaño mínimo es {minSizeLimit}.",
					            emptyError: "{file} está vacío, por vafor seleccioná el archivo de nuevo.",
					            onLeave: "Los archivos se están subiendo, si te vas ahora la transferencia va a ser cancelada."
					        },

					        dragText: "Arrastrá y soltá las fotos acá para subirlas",
					        uploadButtonText: "Subir foto",
					        cancelButtonText: "Cancelar",
					        failUploadText: "Subida fallida",

							onComplete: function(id, fileName, responseJSON){

								var siempre_jpg = parseInt($("#jcrop_'.$nombre.'").attr("data-jcrop-siempre-jpg"));

								console.log("Foto subida: "+fileName);
								console.log("siempre_jpg: "+siempre_jpg);

								if( siempre_jpg==1 ){
									var ext = fileName.indexOf(".jpeg") !== -1 ? "jpeg" : "jpg";
								}else{
									var ext = fileName.substring(fileName.length-3, fileName.length);
								}

								//console.log("Extensión: "+ext);'."\n\r";

								// Las medidas que tienen algún lado automático no se recortan
								// Si todas tienen auto en alguna de las 2 medidas no abro la UI para recortar
								// (las imágenes ya se redimensionaron)
								$recortar = false;

								$anchos = explode(',', $anchos);
								$altos = explode(',', $altos);

								$tot = count($anchos);
								for($i=0; $i<$tot; $i++){
									if($anchos[$i]!='auto' AND $altos[$i]!='auto'){
										$recortar = true;
										break;
									}
								}

								if(! $recortar){
									echo '$.ajax({
										url: "'.base_url().'index.php?c=jcrop_ajax&m=save_photo&nombre='.$nombre.'&controlador='.$controlador.'&controlador_id='.$controlador_id.'&ext="+ext,
										context: document.body,

										beforeSend: function(){
											$("#jcrop_"+name).html("<img src=\"\'+basepath+\'core/images/ajax-loader.gif\"/>");
										},

										error: function(){
											$("#jcrop_"+name).html("<br><span class=\"error\">Ocurrió un error al intentar cargar la imagen.</span>");
										},

										success: function(id){
											var foto_id = id;
											console.log("foto_id: "+foto_id);
											$("#uploader_'.$nombre.'").html("");
											cargarImagen( "'.base_url().'", "'.$nombre.'", "'.$controlador.'", "'.$controlador_id.'", foto_id );
										}
									});';

								}else{
									echo 'console.log("cargarJcrop");
									cargarJcrop( "'.base_url().'", "'.$nombre.'", "'.$controlador.'", "'.$controlador_id.'", ext );';
								}

								echo '
							}
						});

					});
				</script>';

	}

	 function load_jcrop(){

		$nombre			= $this->input->get('nombre');
		$controlador 	= $this->input->get('controlador');
		$controlador_id = $this->input->get('controlador_id');
		$ancho 			= $this->input->get('ancho');
		$alto 			= $this->input->get('alto');
		$ext 			= $this->input->get('ext');
		$margenes 		= $this->input->get('margenes');
		$medida 		= 0;

		$anchos = explode(',', $ancho );
		$altos  = explode(',', $alto );

		// Separo las imágenes que se recortan de las que no
		// Las medidas que tienen algún lado automático no se recortan
		$imgs_recortar = array();
		$imgs_redimensionar = array();
		$tot = count($anchos);
		for($i=0; $i<$tot; $i++){
			if($anchos[$i]!='auto' AND $altos[$i]!='auto'){
				$imgs_recortar[] = array(
					'ancho' => $anchos[$i],
					'alto' => $altos[$i],
				);
			}else{
				$imgs_redimensionar[] = array(
					'ancho' => $anchos[$i],
					'alto' => $altos[$i],
				);
			}
		}

		$ancho_principal = $imgs_recortar[0]['ancho'];
		$alto_principal  = $imgs_recortar[0]['alto'];

		//Compruebo que exista la imagen redimensionada que vamos a recortar.
		//Esto lo agrego para poder extender sistemas viejos con este nuevo método de carga de imágenes.
		$resized_filepath = IMAGES_RESOURCES_PATH.$controlador.'/'.$controlador_id.'/'.$medida.'_'.$nombre.'_redimensionada.'.$ext;
		$original_filepath = IMAGES_RESOURCES_PATH.$controlador.'/'.$controlador_id.'/'.$medida.'_'.$nombre.'.'.$ext;
		if( !file_exists($resized_filepath) ){

			if( !$this->create_resized_photo( $resized_filepath, $original_filepath, $anchos, $altos, $margenes) ){
				echo '<span class="error">Hubo un problema al preparar la imagen para recortar.</span>';
				return FALSE;
			}
		}

		echo '
		<div style="display:none;">
			<div id="fancybox_'.$nombre.'" style="width: auto;">
				<img src="'.IMAGES_RESOURCES_URL.$controlador.'/'.$controlador_id.'/'.$medida.'_'.$nombre.'_redimensionada.'.$ext.'?v='.rand().'" id="'.$nombre.'-target" />
				<a class="boton" style="display:inline-block; margin:10px auto 10px auto;" onclick="recortarImagen(\''.base_url().'\', \''.$nombre.'\', \''.$controlador.'\', \''.$controlador_id.'\', \''.$ext.'\', this)" >Recortar</a>
			</div>
		</div>
		<script>
			$.fancybox({
                \'href\': \'#fancybox_'.$nombre.'\',
	         	\'hideOnOverlayClick\':false,
     			\'hideOnContentClick\':false,
	         	\'scrolling\' : true,
	         	 afterShow: function() {
	         	 	$(this).addClass(".popup-fotos");
        			$(".fancybox-close").hide();
        		}
            });
		</script>

		<div class="inline-labels" style="display:none; width:100%; margin-bottom:20px;">
			<label>X1 <input type="text" size="4" id="'.$nombre.'_x1" name="'.$nombre.'_x1" /></label>
			<label>Y1 <input type="text" size="4" id="'.$nombre.'_y1" name="'.$nombre.'_y1" /></label>
			<label>X2 <input type="text" size="4" id="'.$nombre.'_x2" name="'.$nombre.'_x2" /></label>
			<label>Y2 <input type="text" size="4" id="'.$nombre.'_y2" name="'.$nombre.'_y2" /></label>
			<label>W  <input type="text" size="4" id="'.$nombre.'_w" name="' .$nombre.'_w" /></label>
			<label>H  <input type="text" size="4" id="'.$nombre.'_h" name="' .$nombre.'_h" /></label>
		</div>

		<script>
			// JCROP //
			jQuery(function($){

				//http://deepliquid.com/content/Jcrop_Manual.html

			    var jcrop_api;

			    $("#'.$nombre.'-target").Jcrop({
			    	 onChange:   showCoords,
					onSelect:   showCoords,
					bgColor:     "black",
					bgOpacity:   .4,
					setSelect:   [ 0, 0, '.$ancho_principal.', '.$alto_principal.' ], // [ x, y, x2, y2 ]
					allowSelect: false,
					allowResize:true,
					aspectRatio: '.$ancho_principal.'/'.$alto_principal.',
					minSize: [ '.$ancho_principal.', '.$alto_principal.' ],
					maxSize: [ 0, 0 ],
					boxWidth: 1002
			    },function(){
			      jcrop_api = this;
			    });

			    $("#coords").on("change","input",function(e){
			      var x1 = $("#'.$nombre.'_x1").val(),
			          x2 = $("#'.$nombre.'_x2").val(),
			          y1 = $("#'.$nombre.'_y1").val(),
			          y2 = $("#'.$nombre.'_y2").val();
			      jcrop_api.setSelect([x1,y1,x2,y2]);
			    });

			  });

			  // Simple event handler, called from onChange and onSelect
			  // event handlers, as per the Jcrop invocation above
			  function showCoords(c)
			  {
			    $("#'.$nombre.'_x1").val(c.x);
			    $("#'.$nombre.'_y1").val(c.y);
			    $("#'.$nombre.'_x2").val(c.x2);
			    $("#'.$nombre.'_y2").val(c.y2);
			    $("#'.$nombre.'_w").val(c.w);
			    $("#'.$nombre.'_h").val(c.h);
			  };

		</script>';

	}


	function upload_photo(){

		$nombre			= $this->input->get('nombre');
		$controlador 	= $this->input->get('controlador');
		$controlador_id = $this->input->get('controlador_id');
		$anchos 		= $this->input->get('ancho');
		$altos 			= $this->input->get('alto');
		$margenes 		= $this->input->get('margenes');
		$medida 		= 0;
		$siempre_jpg 	= $this->input->get('siempre_jpg') ? 1 : 0;


		 	 ///////////////////////////////////////////////////////////
			/////// ---- Preparo la imagen para recortar --- //////////
		   ///////////////////////////////////////////////////////////


			if( $controlador_id != 0 ){


		  ///////////////////////////////////
		 ///  Guardo la imagen original  ///
		///////////////////////////////////

				$randname = $medida.'_'.$nombre;

				// Si no existe la carpeta la creo
				$path = IMAGES_RESOURCES_PATH.$controlador.'/'.$controlador_id.'/';

				if(!file_exists($path)){
					if(! @mkdir($path, DIR_WRITE_MODE, true)){
						log_message('error', 'Jcrop error: No se pudo crear la carpeta: '.$path);
					}
				}

				//Valido el tamaño
				/*$size = filesize($path.$this->input->get('qqfile'));
				if ( $size > (1024 * 1024 * 2)   ){
					log_message('error', 'Jcrop error: El archivo que se intenta subir es muy grande. Tamaño:'.$size);
					return FALSE;
				}*/

				//Valido la extensión
				$fileinfo = pathinfo($path.$this->input->get('qqfile'));
				$ext = strtolower($fileinfo['extension']);
				$validExtensions = array('jpeg','jpg','png','bmp','gif');
				if ( !in_array($ext, $validExtensions)  ){
					log_message('error', 'Jcrop error: El archivo que se intenta subir no tiene una extensión válida. Extensión:'.$ext);
					return FALSE;
				}

				$input = fopen("php://input", "r");
		        $temp = tmpfile();
		        stream_copy_to_stream($input, $temp);
		        fclose($input);

		        $original_filepath = $path.$randname.'.'.$ext;

		        $target = fopen($original_filepath, "w");
		        fseek($temp, 0, SEEK_SET);
		        stream_copy_to_stream($temp, $target);
		        fclose($target);


				//Reviso que se halla guardado
				if( !file_exists($original_filepath)){
					log_message('error', 'Jcrop error: No se pudo subir la foto original en: '.$original_filepath);
					return FALSE;
				}


				//Convierto la imagen a JPG
		        if( $siempre_jpg==1 AND !in_array( strtolower($ext), array('jpg','jpeg')) ){
		        	$new_original_filepath = $path.$randname.'.jpg';

		        	if(strtolower($ext) == 'gif'){
		        		imagejpeg(imagecreatefromgif(($original_filepath)), $new_original_filepath, 100);

		        	}elseif(strtolower($ext) == 'png'){
			        	// Convierto la imagen a jpg con fondo blanco
			        	// http://stackoverflow.com/a/2570015
			        	$input_file = $original_filepath;
			        	$output_file = $new_original_filepath;
		        		$input = imagecreatefrompng($input_file);
			        	list($width, $height) = getimagesize($input_file);
			        	$output = imagecreatetruecolor($width, $height);
			        	$white = imagecolorallocate($output,  255, 255, 255);
			        	imagefilledrectangle($output, 0, 0, $width, $height, $white);
			        	imagecopy($output, $input, 0, 0, 0, 0, $width, $height);
			        	imagejpeg($output, $output_file);
		        	}

		        	$original_filepath = $new_original_filepath;
		        	$ext = 'jpg';
		        }else{
		        	$ext = strtolower($ext);
		        }


			  //////////////////////////////////////////
			 ///  Guardo una imagen redimensionada  ///
			//////////////////////////////////////////

			  	// Las medidas que tienen algún lado automático no se recortan
			  	// Si todas tienen auto en alguna de las 2 medidas no genero la imagen redimensioanada
			  	$recortar = false;

			  	$anchos = explode(',', $anchos);
			  	$altos = explode(',', $altos);

			  	$tot = count($anchos);
			  	for($i=0; $i<$tot; $i++){
			  		if($anchos[$i]!='auto' AND $altos[$i]!='auto'){
			  			$recortar = true;
			  			break;
			  		}
			  	}

			  	if($recortar){
			  		$resized_filepath = $path.$randname.'_redimensionada.'.$ext;

			  		if( !$this->create_resized_photo( $resized_filepath, $original_filepath, $anchos, $altos, $margenes) ){
			  			return FALSE;
			  		}

			  	// Si no hay imágenes para recortar entonces genero ahora las nuevas imágenes
			  	}else{
			  		$_GET['ext'] = $ext;
			  		$this->crop_photo();
			  	}

			}else{
				echo 'El controlador no es válido.';
				log_message('error', 'Jcrop error: No se pudo subir la foto porque el número de controlador no es válido.');
				return FALSE;
			}
	}



  //Genero una nueva imagen para recortar
  function create_resized_photo( $resized_filepath, $original_filepath, $anchos, $altos, $margenes = false){

  	if( !file_exists($original_filepath) ){
  		log_message('error', 'Jcrop error: No se pudo subir la foto original: '.$original_filepath);
  		return FALSE;
  	}

	// Separo las imágenes que se recortan de las que no
	// Las medidas que tienen algún lado automático no se recortan
	$imgs_recortar = array();
	$imgs_redimensionar = array();
	$tot = count($anchos);
	for($i=0; $i<$tot; $i++){
		if($anchos[$i]!='auto' AND $altos[$i]!='auto'){
			$imgs_recortar[] = array(
				'ancho' => $anchos[$i],
				'alto' => $altos[$i],
			);
		}else{
			$imgs_redimensionar[] = array(
				'ancho' => $anchos[$i],
				'alto' => $altos[$i],
			);
		}
	}

	$ancho_principal = $imgs_recortar[0]['ancho'];
	$alto_principal  = $imgs_recortar[0]['alto'];

  	//Guardamos las medidas de la imagen original
	list($original_width, $original_height) = getimagesize($original_filepath);

	$config = array();
	$config['image_library']  = 'gd2';
	$config['source_image']	  = $original_filepath;
	$config['new_image']	  = $resized_filepath;
	$config['maintain_ratio'] = true;
	$config['quality'] 		  = '100%';

	//Solo redimensiono la imagen si no es lo suficientemente grande como para recortarla
	if( $original_width<$ancho_principal OR $original_height<$alto_principal ){

		$config['width']	= $ancho_principal;
		$config['height']	= $alto_principal;

		////Defino cuál va a ser el lado que voy a mantener (el "master_dim") >>

		//Para evitar que al recortar queden espacios en negro vemos qué lado nos conviene mantener fijo
		$R = ($alto_principal/$ancho_principal);//Proporción de la imagen a generar
		$RO = ($original_height/$original_width);//Proporción de la imagen a original

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

			if($alto_principal > $ancho_principal){
				$config['master_dim'] = 'height';

			}elseif($ancho_principal > $alto_principal){
				$config['master_dim'] = 'width';

			}else{//Si son iguales los lados de la figura a generar

				if($figura_original == 'RV'){
					$config['master_dim'] = 'width';

				}elseif($figura_original == 'RH'){
					$config['master_dim'] = 'height';

				}else{
					$config['master_dim'] = 'width';//Puede ser cualquiera
				}
			}
		}else{//Cuando las figuras son iguales el lado a respetar depende de la relación entre las proporciones de las figuras
			if($R > $RO){
				$config['master_dim'] = 'height';

			}else{//Si es menor (No pueden ser iguales, si no serían figuras iguales)
				$config['master_dim'] = 'width';
			}
		}

	}else{

		$config['width']	= $original_width;
		$config['height']	= $original_height;

	}

	////<< Fin defino cuál va a ser el lado que voy a mantener

	$this->image_lib->initialize($config);

	if ( ! $this->image_lib->resize()){
		echo $this->image_lib->display_errors();
		log_message('error', 'Jcrop error: No existe la foto original.');
		return FALSE;
	}

	$this->image_lib->clear();


	//La imagen redimensionada ya es más grande que la imagen que se quiere generar,
	//pero de esta manera solo va a poder recortar un pedazo de la imagen original.
	//Tenemos que lograr que la imagen redimensionada entre entera dentro de la imagen a generar.
	//Esto solo lo voy a hacer (por ahora) con las imágenes JPG

	// $ext no existe acá, hay que revisarlo
	/*if( in_array($ext, array('jpg','jpeg')) ){
		list($ancho_redim, $alto_redim) = getimagesize($resized_filepath);

		$nuevo_ancho_redim = $ancho_principal;
		$nuevo_alto_redim  = $alto_principal;

		while($nuevo_ancho_redim < $ancho_redim OR $nuevo_alto_redim < $alto_redim){

			if($nuevo_ancho_redim < $ancho_redim){

				//log_message('error', 'Jcrop debug: ( WN_'.$nuevo_ancho_redim.' < WR_'.$ancho_redim.' )');
				//log_message('error', 'Jcrop debug: WN='.$ancho_redim.' HN=('.$nuevo_alto_redim.' * '.$ancho_redim.') / '.$nuevo_ancho_redim);

				$nuevo_alto_redim  = ($nuevo_alto_redim * $ancho_redim) / $nuevo_ancho_redim;
				$nuevo_ancho_redim = $ancho_redim;


			}elseif($nuevo_alto_redim < $alto_redim){
				//log_message('error', 'Jcrop debug: ( HN_'.$nuevo_alto_redim.' < HR_'.$alto_redim.' )');
				//log_message('error', 'Jcrop debug: HN='.$alto_redim.' WN=('.$nuevo_ancho_redim.' * '.$alto_redim.') / '.$nuevo_alto_redim);

				$nuevo_ancho_redim = ($nuevo_ancho_redim * $alto_redim) / $nuevo_alto_redim;
				$nuevo_alto_redim  = $alto_redim;

			}
		}

		//Creo una imagen blanca
		$fondo = imagecreatetruecolor( $nuevo_ancho_redim, $nuevo_alto_redim );
		$blanco = imagecolorallocate ( $fondo, 255, 255, 255 );
		imagefilledrectangle( $fondo, 0, 0, $nuevo_ancho_redim, $nuevo_alto_redim, $blanco);

		//Coloco la imagen redimensionada sobre la blanca
		$img 	 = imagecreatefromjpeg($resized_filepath);
	    $fondo_x = ($nuevo_ancho_redim/2) - ($ancho_redim/2);
		$fondo_y = ($nuevo_alto_redim/2) - ($alto_redim/2);
		$img_x   = 0;
		$img_y   = 0;
		$img_w   = $ancho_redim;
		$img_h   = $alto_redim;

		imagecopymerge( $fondo, $img,  $fondo_x,  $fondo_y, $img_x, $img_y, $img_w, $img_h, 100 );
		imagejpeg( $fondo, $resized_filepath );
	}*/

	//Genero un margen alrededor de la imagen para dar más flexibilidad al recortar
	//Para eso creo una imagen blanca más grande y coloco la otra imagen sobre esta
	if( $margenes ){

		//Leo las medidas de la imagen redimensionada
		list($ancho, $alto) = getimagesize($resized_filepath);

		$fondo_ancho = $ancho +(($ancho/3) *2);
		$fondo_alto  = $alto+(($alto/3)*2);

		//Creo una imagen blanca
		$fondo = imagecreatetruecolor( $fondo_ancho, $fondo_alto );
		$blanco = imagecolorallocate ( $fondo, 255, 255, 255 );
		imagefilledrectangle( $fondo, 0, 0, $fondo_ancho, $fondo_alto, $blanco);

		//Superpongo la imagen con la imagen blanca
		// NOTA: Esta funcion puede fallar silenciosamente si la imagen es grande y no hay suficiente memoria para procesarla
	    $img 	 = imagecreatefromjpeg($resized_filepath);
	    $fondo_x = $ancho/3;
		$fondo_y = $alto/3;
		$img_x   = 0;
		$img_y   = 0;
		$img_w   = $ancho;
		$img_h   = $alto;

		imagecopymerge( $fondo, $img,  $fondo_x,  $fondo_y, $img_x, $img_y, $img_w, $img_h, 100 );
		imagejpeg( $fondo, $resized_filepath, 100 );
	}

	return TRUE;

  }



	function crop_photo(){

		$nombre			= $this->input->get('nombre');
		$controlador 	= $this->input->get('controlador');
		$controlador_id = $this->input->get('controlador_id');
		$anchos 		= $this->input->get('ancho');
		$altos 			= $this->input->get('alto');
		$ext 			= $this->input->get('ext');
		$x1 			= $this->input->get('x1');
		$y1 			= $this->input->get('y1');
		$w 				= $this->input->get('w');
		$h 				= $this->input->get('h');
		$medida 		= 0;

		$anchos = explode(',', $anchos);
		$altos = explode(',', $altos);

		// Separo las imágenes que se recortan de las que no
		// Las medidas que tienen algún lado automático no se recortan
		$imgs_recortar = array();
		$imgs_redimensionar = array();
		$tot = count($anchos);
		for($i=0; $i<$tot; $i++){
			if($anchos[$i]!='auto' AND $altos[$i]!='auto'){
				$imgs_recortar[] = array(
					'ancho' => $anchos[$i],
					'alto' => $altos[$i],
				);
			}else{
				$imgs_redimensionar[] = array(
					'ancho' => $anchos[$i],
					'alto' => $altos[$i],
				);
			}
		}

		//log_message('error','imgs_recortar: '.count($imgs_recortar).' imgs_redimensionar: '.count($imgs_redimensionar));

		if( $controlador_id != 0 ){

			if(count($imgs_recortar) > 0){

				  ///////////////////////////////////////////////////////////////
				 ///  Guardo la imagen recortada (en todos las proporciones) ///
				///////////////////////////////////////////////////////////////

				$ancho_principal = $imgs_recortar[0]['ancho'];
				$alto_principal  = $imgs_recortar[0]['alto'];

				// Recorto la imagen proporcinalmente, del tamaño que se quiere conseguir o más grande
				$randname = $medida.'_'.$nombre;
				$path = IMAGES_RESOURCES_PATH.$controlador.'/'.$controlador_id.'/';

				$resized_filepath  = $path.$randname.'_redimensionada.'.$ext;
				$temp_cropped_filepath  = $path.$randname.'_'.$alto_principal.'x'.$ancho_principal.'_temp.'.$ext;

				$config = array();
				$config['image_library']  	= 'gd2';
				$config['source_image']	  	= $resized_filepath;
				$config['new_image']	  	= $temp_cropped_filepath;
				$config['maintain_ratio'] 	= FALSE;
				$config['x_axis'] 			= $x1;
				$config['y_axis'] 			= $y1;
				$config['width']	   	 	= $w;
				$config['height']    		= $h;
				$config['quality']    		= '100%';

				$this->image_lib->initialize($config);

				if ( ! $this->image_lib->crop()){
					echo $this->image_lib->display_errors();
					return FALSE;
				}
				$this->image_lib->clear();

				//A partir de esta imagen genero todos los tamaños de la imagen
				foreach($imgs_recortar as $img_rec){

					$ancho = $img_rec['ancho'];
					$alto  = $img_rec['alto'];

					$new_filepath  = $path.$randname.'_'.$alto.'x'.$ancho.'.'.$ext;
					
					$R = ($alto/$ancho); //Proporción de la imagen a generar
					$RO = ($alto_principal/$ancho_principal); //Proporción de la imagen a original

					// Si la foto original y las foto a generar tienen la misma proporción la creo redimensionándola
					if($R == $RO){
						$config = array();
						$config['image_library']  	= 'gd2';
						$config['source_image']	  	= $temp_cropped_filepath;
						$config['new_image']	  	= $new_filepath;
						$config['width']	   	 	= $ancho;
						$config['height']    		= $alto;
						$config['master_dim'] = 'auto';
						$config['maintain_ratio'] = TRUE;

						$this->image_lib->initialize($config);

						if ( ! $this->image_lib->resize()){
							echo $this->image_lib->display_errors();
							return FALSE;
						}

					// Si no la recorto (usando la mayor parte de la imagen)
					}else{

						// Primero ajustamos el tamaño de la imagen redimensionándola
						$config['image_library']  	= 'gd2';
						$config['source_image']	  	= $temp_cropped_filepath;
						$config['new_image']	  	= $new_filepath;
						$config['width']	   	 	= $ancho;
						$config['height']    		= $alto;
						$config['maintain_ratio'] = TRUE;

						// Para evitar que al recortar queden espacios en negro vemos qué lado nos conviene mantener fijo
						require_once(FCPATH . APPPATH . 'controllers/gallery_ajax.php');
						$this->gallery_ajax = new gallery_ajax();
						$config['master_dim'] = $this->gallery_ajax->calcular_master_dim(
								$ancho_principal,
								$alto_principal,
								$ancho,
								$alto
							);

						$this->image_lib->initialize($config);

						if ( ! $this->image_lib->resize() ){
							return array('error' => $this->image_lib->display_errors());
						}
						$this->image_lib->clear();

						// Guardamos las medidas de la imagen redimensionada
						list($redimensionada_width, $redimensionada_height) = getimagesize($new_filepath);

						// Después recortamos la imagen redimensionada
						$config['image_library'] 	= 'gd2';
						$config['source_image']		= $new_filepath;
						$config['new_image'] 		= $new_filepath;
						$config['maintain_ratio'] 	= FALSE;
						$config['width']   			= $ancho;
						$config['height']			= $alto;

						// Sobra alto o ancho. Si sobra alto recortamos alto
						if(($redimensionada_height > $alto) AND ($redimensionada_width == $ancho)){
							$config['x_axis']	= 0;
							$config['y_axis']	= ($redimensionada_height - $alto)/2;

						// Si no sobra alto sobra ancho, entonces recortamos ancho
						}elseif(($redimensionada_width > $ancho) AND ($redimensionada_height == $alto)){
							$config['x_axis']	= ($redimensionada_width - $ancho)/2;
							$config['y_axis']	= 0;

						// No recortamos
						}else{
							$config['x_axis']	= 0;
							$config['y_axis']	= 0;
						}

						$this->image_lib->initialize($config);

						if( ! $this->image_lib->crop() ){
							echo $this->image_lib->display_errors();
							return FALSE;
						}
						$this->image_lib->clear();
					}
					
				}

				// Elimino la imagen temporal, ya no va a hacer falta
				if( file_exists($temp_cropped_filepath) ){
					unlink($temp_cropped_filepath);
				}
			}


			if(count($imgs_redimensionar) > 0){

				  /////////////////////////////////////////////
				 ///  Guardo las imágenes redimensionadas  ///
				/////////////////////////////////////////////

				$original_filepath = IMAGES_RESOURCES_PATH.$controlador.'/'.$controlador_id.'/'.$medida.'_'.$nombre.'.'.$ext;
				foreach($imgs_redimensionar as $img_red){
					$ancho = $img_red['ancho'];
					$alto  = $img_red['alto'];
					$randname = $medida.'_'.$nombre;
					$path = IMAGES_RESOURCES_PATH.$controlador.'/'.$controlador_id.'/';
					$new_filepath  = $path.$randname.'_'.$alto.'x'.$ancho.'.'.$ext;
					
					// Si los 2 lados son automáticos la imagen no se procesa
					if($img_red['ancho'] == 'auto' AND $img_red['alto'] == 'auto'){
						if(copy($original_filepath, $new_filepath)){
							echo 'No se pudo guardar la foto.';
							log_message('error', 'Jcrop error: $original_filepath: '.$original_filepath);
							log_message('error', 'Jcrop error: $new_filepath: '.$new_filepath);
							return FALSE;
						}
					}else{
						$config = array();
						$config['image_library']  = 'gd2';
						$config['source_image']   = $original_filepath;
						$config['new_image']      = $new_filepath;
						$config['maintain_ratio'] = true;
						$config['width']	   	  = ($img_red['ancho'] != 'auto') ? $img_red['ancho'] : 99999;
						$config['height']    	  = ($img_red['alto']  != 'auto') ? $img_red['alto']  : 99999;

						if($img_red['alto']=='auto' AND $img_red['ancho']=='auto'){
							$config['master_dim'] = 'auto';
						}else{
							$config['master_dim'] = ($img_red['ancho']=='auto') ? 'height' : 'width';
						}

						$this->image_lib->initialize($config);

						if ( ! $this->image_lib->resize()){
							echo $this->image_lib->display_errors();
							log_message('error', 'Jcrop error: $original_filepath: '.$original_filepath);
							log_message('error', 'Jcrop error: $new_filepath: '.$new_filepath);
							return FALSE;
						}
						$this->image_lib->clear();
					}
				}
			}

			  /////////////////////////////////////////////
			 ///  Grabo la foto en la BD y la muestro  ///
			/////////////////////////////////////////////
			if( file_exists($new_filepath) ){

				$foto_id = $this->save_photo(array(
					'nombre' => $nombre,
					'controlador' => $controlador,
					'controlador_id' => $controlador_id,
					'ext' => $ext
				));

	        }else{
	        	log_message('error', 'Jcrop error: No se encontró la última foto generada: '.$new_filepath);
	        	return FALSE;
	        }

		    echo '<script>
					$(document).ready(function() {
						cargarImagen( "'.base_url().'", "'.$nombre.'", "'.$controlador.'", '.$controlador_id.', '.$foto_id.');
					});
				</script>
				<input name="'.$nombre.'" value="'.$foto_id.'" type="hidden"/>';

		}else{
			echo 'El controlador no es válido.';
			return FALSE;
		}
	}


	function delete_photo(){

		$nombre			= $this->input->get('nombre');
		$controlador 	= $this->input->get('controlador');
		$controlador_id = $this->input->get('controlador_id');
		$foto_id 		= $this->input->get('foto_id');

		if( $foto_id>0 ){

			$sql = 'SELECT * FROM fotos WHERE id = '.$foto_id.' LIMIT 1';
			$query = $this->db->query($sql);


            if( $query->num_rows()>0 ){

				//Elimino las fotos de la carpeta resources
				$foto = $query->row();
				$path = IMAGES_RESOURCES_PATH.$controlador.'/'.$controlador_id.'/'.$foto->filename.'*';
		        $files = glob($path);
		        if(is_array($files)){
					foreach($files as $file){
						unlink($file);
					}
				}

				//Elimino la foto de la base de datos (de la tabla fotos y de la tabla del controlador específico)
				$this->db->where('id',$foto_id);
				$this->db->delete('fotos');

				$this->db->where('id',$controlador_id);
				$this->db->update( $controlador, array($nombre=>0) );

			}else{
				header("HTTP/1.0 404 Not Found");
				header("Status: 404 Not Found");
			}

		}else{

			header("HTTP/1.0 404 Not Found");
			header("Status: 404 Not Found");
		}

    }


    function save_photo($datos = array()){

    	$ajax = empty($datos);
    	if(! $ajax){
    		$nombre 		= $datos['nombre'];
    		$controlador 	= $datos['controlador'];
    		$controlador_id = $datos['controlador_id'];
    		$ext 			= $datos['ext'];

    	}else{
    		$nombre			= $this->input->get('nombre');
			$controlador 	= $this->input->get('controlador');
			$controlador_id = $this->input->get('controlador_id');
			$ext 			= $this->input->get('ext');
    	}

		$this->db->select($nombre);
		$this->db->from($controlador);
		$this->db->where( 'id', $controlador_id );
		$query = $this->db->get();
		$foto = $query->row();
		$foto_id = $foto ? $foto->$nombre : 0;

		// ¿Este IF no podría quitarse?
		// Estaría bueno que siempre se grabara la nueva foto
		if( $foto_id <= 0 ){

			//Guardo la foto en la BD
			$f = array(
				'filename' => '0_'.$nombre,
				'extension' => $ext,
				'epigrafe' => '',
				'galerias_id' => '-1',
				'orden' => 999999
			);
			$this->db->insert('fotos', $f);
			$foto_id = $this->db->insert_id();

			// Si el controlador no tiene ID lo guardo más adelante
			if($controlador_id > 0){
	        	$c = array(
					$nombre => $foto_id
				);
				$this->db->where('id', $controlador_id);
				$this->db->update( $controlador, $c );
			}

			//echo 'UPDATE '.$controlador.' SET '.$nombre.'='.$foto_id.' WHERE id='.$controlador_id.'';
		}

		if( $ajax ){
			echo $foto_id;

		}else{
			return $foto_id;
		}

    }

	function get_epigraph(){

		$id = $this->input->get('id');

		if($id>0){

			$f = $this->fotos_model->get_item($id);
			echo $f->epigrafe;

		}else{
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

