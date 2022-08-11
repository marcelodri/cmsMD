
//Cargo el html de la imagen
function cargarImagen( basepath, name, controlador, controlador_id, foto_id ){

	var altos 	= $("#jcrop_"+name).attr("data-jcrop-alto");
	var anchos 	= $("#jcrop_"+name).attr("data-jcrop-ancho");

	$.ajax({
		url: basepath+'index.php?c=jcrop_ajax&m=load_image&nombre='+name+'&controlador='+controlador+'&controlador_id='+controlador_id+'&foto_id='+foto_id+'&altos='+altos+'&anchos='+anchos,
		context: document.body,

		beforeSend: function(){
		$("#jcrop_"+name).html('<img src="'+basepath+'core/images/ajax-loader.gif"/>');
		},

		error: function(){
		$("#jcrop_"+name).html('<br><span class="error">Ocurrió un error al intentar cargar la imagen.</span>');
		},

		success: function(data){
			$("#jcrop_"+name).html(data);
		}
	});

}

//Cargo la imagen en la base de datos
function nuevaImagen( basepath, name, controlador, controlador_id ){

	$.ajax({
	  url: basepath+'index.php?c=jcrop_ajax&m=new_image&controlador='+controlador+'&controlador_id='+controlador_id,
	  context: document.body,
	  beforeSend: function(){
		$("#jcrop_"+name).html('<img src="'+basepath+'core/images/ajax-loader.gif"/>');
	  },
	  error: function(){
		$("#jcrop_"+name).html('<br><span class="error">Ocurrió un error al intentar cargar la imagen.</span>');
	  },
	  success: function(foto_id){
		//cargarImagen( basepath, name, controlador, controlador_id, foto_id );
		cargarUploader( basepath, name, controlador, controlador_id );
	  }
	});
}


//Cargo el input para subir fotos
function cargarUploader(basepath, name, controlador, controlador_id ){

	// Obtengo los tamaños a redimensionar ya cargados en un input
	var alto 	= $("#jcrop_"+name).attr("data-jcrop-alto");
	var ancho 	= $("#jcrop_"+name).attr("data-jcrop-ancho");
	var foto_id = $("input[name='"+name+"']").val();

	$.ajax({
	  url: basepath+'index.php?c=jcrop_ajax&m=load_uploader&nombre='+name+'&controlador='+controlador+'&controlador_id='+controlador_id+'&alto='+alto+'&ancho='+ancho,
	  context: document.body,
	  success: function(html){
		$("#uploader_"+name).html(html);
	  }
	});


}


function cargarJcrop(basepath, name, controlador, controlador_id, ext ){

	var alto 	= $("#jcrop_"+name).attr("data-jcrop-alto");
	var ancho 	= $("#jcrop_"+name).attr("data-jcrop-ancho");
	var margenes 	= $("#jcrop_'.$nombre.'").attr("data-jcrop-margenes");

	$.ajax({
	  url: basepath+'index.php?c=jcrop_ajax&m=load_jcrop&nombre='+name+'&controlador='+controlador+'&controlador_id='+controlador_id+'&alto='+alto+'&ancho='+ancho+'&ext='+ext+'&margenes='+margenes,
	  context: document.body,
	  success: function(html){
		$("#uploader_"+name).html(html);
	  },
	  error: function(){
		alert('Ocurrió un error al intentar editar la imagen.');
	  }
	});
}

function recortarImagen(basepath, name, controlador, controlador_id, ext, boton){

	var alto  = $("#jcrop_"+name).attr("data-jcrop-alto");
	var ancho = $("#jcrop_"+name).attr("data-jcrop-ancho");
	var x1 	  = $("#"+name+"_x1").val();
	var y1 	  = $("#"+name+"_y1").val();
	var h 	  = $("#"+name+"_h").val();
	var w 	  = $("#"+name+"_w").val();

	$.ajax({
	  url: basepath+'index.php?c=jcrop_ajax&m=crop_photo&nombre='+name+'&controlador='+controlador+'&controlador_id='+controlador_id+'&alto='+alto+'&ancho='+ancho+'&x1='+x1+'&y1='+y1+'&w='+w+'&h='+h+'&ext='+ext,
	  context: document.body,
	  success: function(html){
		$("#uploader_"+name).html(html);
		$.fancybox.close('#fancybox_'+name);
	  },
	  beforeSend: function(){
	  	$(boton).html('Cargando <img style="padding: 8px 0 0 4px;" src="'+basepath+'core/images/ajax-loader.gif"/>');
	  },
	  error: function(){
	  	$.fancybox.close('#fancybox_'+name);
		$("#jcrop_"+name).html('<spam class="error" style="float:left; margin:0;">Ocurrió un error al intentar recortar la imagen.</spam>');

	  }
	});
}

//Borro la imagen
function borrarImagen( basepath, name, controlador, controlador_id, foto_id){

	$.ajax({
	  url: basepath+'index.php?c=jcrop_ajax&m=delete_photo&nombre='+name+'&controlador='+controlador+'&controlador_id='+controlador_id+'&foto_id='+foto_id,
	  context: document.body,
	  beforeSend: function(){
		$("#jcrop_"+name).html('<img src="'+basepath+'core/images/ajax-loader.gif"/>');
	  },
	  success: function(html){
		cargarImagen( basepath, name, controlador, controlador_id, foto_id );
	  },
	  error: function(){
	  	$("#jcrop_"+name).html('<spam class="error" style="float:left; margin:0;">Ocurrió un error al intentar borrar la imagen.</spam>');
	  }

	});


}


function changeEpigraph(id){

	var DOCUMENT_ROOT = $('#document_root').val();

	$.ajax({
	  url: DOCUMENT_ROOT+'index.php?c=jcrop_ajax&m=get_epigraph&id='+id,
	  context: document.body,
	  success: function(data){
		var oldCredits = data;
		var newCredits = prompt('Escriba los nuevos créditos para la foto:',oldCredits);

		if(newCredits!=oldCredits && newCredits!==null){

			$.ajax({
			  url: DOCUMENT_ROOT+'index.php?c=jcrop_ajax&m=set_epigraph&id='+id,
			  type: 'POST',
			  context: document.body,
			  data: 'epigrafe='+newCredits,
			  error: function(){
				alert('Hubo un error al guardar los créditos.');
			  },
			});

		}
	  },
	  error: function(){
		alert('Hubo un error al obtener los créditos antiguos.');
	  },
	});
}

