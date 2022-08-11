<?php

Class Gallery_manager extends MY_Controller {

	var $galerias = array();

    function __construct(){
        parent::__construct();
	    $this->controller_config["script"] 	= "gallery_manager";
	}

	function index()
	{
		$popup = (isset($_GET["isPopup"]) && $_GET["isPopup"]=="true") ? true : false;

        $header_data = $this->_generar_data('cabecera');
        $menu_data   = $this->_generar_data('menu');

        $this->load_header_only_view( $header_data );
        $this->load_menu_only_view( $menu_data );
		$this->load->view('gallery_manager');
		$this->load->view('includes/footer');
	}

	function crearMedidas( $marca_de_agua = false, $sobreescribir = false )
	{
		set_time_limit( 1200 ); //20 min
		ob_implicit_flush(true);

		$html = '';

		$controlador = $this->input->get('controlador');
		$sobreescribir = $this->input->get('sobreescribir') ? true : $sobreescribir;

		$this->cargar_controlador( $controlador );
		$this->cargar_controlador( 'gallery_ajax' );

		$campos = $this->$controlador->controller_config["campos_form"];

		//Obtengo todos campos de tipo galería de un controlador
		foreach($campos AS $campo){
			if($campo['type'] == 'gallery'){
				$this->galerias[] = $campo;
			}
		}

		//Recorro los campos tipo galería y..
		$hay_galerias = false;
		foreach($this->galerias AS $campo_galeria){

			$hay_galerias = true;

			$html .= '<h1>Campo de galería: '.$campo_galeria['key'].'</h1>'."\r\n";

			//Obtengo todas las galerías asociadas a ese campo galería
			$sql = 'SELECT * FROM galerias WHERE id IN (SELECT DISTINCT '.$campo_galeria['key'].'_id FROM '.$controlador.') ORDER BY id ASC';
			$query_galerias = $this->db->query($sql);

			if( $query_galerias->num_rows <= 0 )
				continue;

			foreach( $query_galerias->result() AS $galeria){


				$html .= '<h2>Galería ID: '.$galeria->id.'</h2>'."\r\n";

				//Obtengo todas las fotos de cada galería
				$sql = 'SELECT * FROM fotos WHERE galerias_id = '.$galeria->id.' ORDER BY orden ASC';
				$query_fotos = $this->db->query($sql);

				if( $query_fotos->num_rows <= 0 ){
					$html .= '<p>No hay fotos en esta galería.</p>';
					continue;
				}

				//Reviso si existen los tamaños para cada foto y las creo
				foreach( $query_fotos->result() AS $foto){

					$html .=  '<h3>Foto ID: '. $foto->id.'</h3>'."\r\n".'<p>';
					foreach($campo_galeria['properties']['sizes'] AS $size){

						$foto_nombre = $foto->filename.'_'.$size["width"].'x'.$size["height"].'.'.$foto->extension;

						$html.= $foto_nombre.' ';
						if( ! file_exists(APPPATH.'../resources/galleries/'.$galeria->id.'/'.$foto_nombre) ){
							$html .= '<span style="color:green;">Generada</span><br>';
						}else{
							$html .= '<span style="color:red;">Ya existía</span><br>';
						}
					}
					$html .=  '</p>';

					$this->gallery_ajax->generate_sizes(  $galeria->id, $campo_galeria['properties']['sizes'], $foto->id, $marca_de_agua, $sobreescribir );

				}
			}
		}

		if($hay_galerias){
			echo $html;
		}else{
			echo 'No hay galerías para este controlador.';
		}


		ob_implicit_flush(false);
    }

    function galerias_huerfanas()
	{
		$galerias_huerfanas = array();
		include("../includes/inicio.php");
		$sql = 'SELECT id FROM galerias WHERE id NOT IN (SELECT galerias_id FROM inmuebles) AND id NOT IN (SELECT galerias_id FROM desarrollos)  AND id NOT IN (SELECT planos_id FROM desarrollos)';
		$result = $mysqli->query($sql);

		if( $result->num_rows>0 ){

			while($g = $result->fetch_object()){
				$galerias_huerfanas[] = $g->id;
			}
		}

		//print_r($galerias_huerfanas);

		foreach( $galerias_huerfanas AS $i ){
			if(  in_array($i, array(74,75,72,1,2,3,4,5,6,11,30,31,63,64,65,66,67,68,69,70,71,76)) ) continue;
			echo '<h3 style="width:100%; clear:both;">Galer&iacute;a Nro: '.$i.'</h3>';
			echo '<div>'.$this->listar_archivos(APPPATH.'../resources/galleries/'.$i, $i).'</div></br>';
		}

    }

    function listar_archivos($carpeta, $galeria){

	    if(is_dir($carpeta)){
	        if($dir = opendir($carpeta)){
	        	$nombres = array();
	            while(($archivo = readdir($dir)) !== false){

	            	 $nombre =  substr($archivo,0,32);
	            	 if( !in_array( $nombre, $nombres )){
		                if($archivo != '.' && $archivo != '..' && $archivo != '.htaccess'){
		                   echo '<img src="'.RESOURCES_URL.'galleries/'.str_replace('/','',substr($carpeta,-2)).'/'.$archivo.'" style="height:200px; width:auto;"/>';
		                }
		                /* Grabar foto en BD
						$f = new fotos_model;
						$f->filename = $nombre;
						$f->extension = 'jpg';
						$f->epigrafe = "";
						$f->galerias_id = $galeria;
						$f->save();
						echo 'Grabada foto '.$nombre.'.jpg<br />';
						*/
	                	$nombres[] = $nombre;
	                }

	            }
	            closedir($dir);
	        }
	    }
	}

    function cargar_controlador( $controller )
    {
        require_once(FCPATH . APPPATH . 'controllers/' . $controller . '.php');
        $this->$controller = new $controller();
    }

}

