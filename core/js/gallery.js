function newGallery(basepath,name,marca_de_agua){

	$.ajax({
	  url: basepath+'index.php?c=gallery_ajax&m=new_gallery',
	  context: document.body,
	  cache: false,
	  beforeSend: function(){
		$("#gallery_"+name).html('<img src="'+basepath+'core/images/ajax-loader.gif"/>');
	  },
	  error: function(){
		$("#gallery_"+name).html('<br><span class="error">No se pudo crear la galeria.</span>');
	  },
	  success: function(galerias_id){
		loadGallery(basepath,galerias_id,name,marca_de_agua);
		loadUploader(basepath,galerias_id,name,marca_de_agua);
		$("#"+name+"_id").val(galerias_id);
	  }
	});

}

function loadGallery(basepath,galerias_id,name,marca_de_agua){

	if(marca_de_agua==undefined | marca_de_agua=='' ){
		marca_de_agua = false;
	}

	if(galerias_id>0){

		$.ajax({
		  url: basepath+'index.php?c=gallery_ajax&m=load_gallery&galerias_id='+galerias_id+'&name='+name+'&marca_de_agua='+marca_de_agua,
		  context: document.body,
		  cache: false,
		  beforeSend: function(){
			$("#gallery_"+name).html('<img src="'+basepath+'core/images/ajax-loader.gif"/>');
		  },
		  error: function(){
			$("#gallery_"+name).html('<br><span class="error">Ocurrio un error al intentar cargar la galeria.</span>');
		  },
		  success: function(data){
			$("#gallery_"+name).html(data);
			loadUploader(basepath,galerias_id,name,marca_de_agua);
			fancyInit();
		  }
		});

	// Si no hay un ID de galeria significa que no se creo ninguna para este objeto, le doy la opcion de crearla
	}else{
		$("#gallery_"+name).html("No hay una galería creada. &nbsp; &nbsp;<a class='boton' href='javascript:newGallery(\""+basepath+"\",\""+name+"\",\""+marca_de_agua+"\");'>Crear galería</a>");
	}

}

function loadUploader(basepath,galerias_id,name,marca_de_agua){

	// Obtengo los tamaños a redimensionar ya cargados en un input
	var sizes = $("#gallery_sizes_"+name).val();

	$.ajax({
	  url: basepath+'index.php?c=gallery_ajax&m=load_uploader&galerias_id='+galerias_id+'&sizes='+sizes+'&name='+name+'&marca_de_agua='+marca_de_agua,
	  context: document.body,
	  cache: false,
	  success: function(html){
		$("#uploader_"+name).html(html);
	  }
	});


}

function deletePhoto(basepath,id,galerias_id,name){

	$.ajax({
	  url: basepath+'index.php?c=gallery_ajax&m=delete_photo&id='+id+'&galerias_id='+galerias_id,
	  context: document.body,
	  cache: false,
	  beforeSend: function(){
		$("#gallery_"+name).html('<img src="'+basepath+'core/images/ajax-loader.gif"/>');
	  },
	  success: function(html){
		loadGallery(basepath,galerias_id,name);
	  },
	  error: function(){
		alert('Ocurrió un error al intentar borrar la imagen.');
	  },
	});


}

function changeEpigraph(basepath,id){

	$.ajax({
	  url: basepath+'index.php?c=gallery_ajax&m=get_epigraph&id='+id,
	  context: document.body,
	  cache: false,
	  success: function(data){
		var oldEpigrafe = data;
		var newEpigrafe = prompt('Escriba el nuevo epígrafe para la foto:',oldEpigrafe);

		if(newEpigrafe!=oldEpigrafe && newEpigrafe!=null){

			$.ajax({
			  url: basepath+'index.php?c=gallery_ajax&m=set_epigraph&id='+id,
			  type: 'POST',
			  context: document.body,
			  cache: false,
			  data: 'epigrafe='+newEpigrafe,
			  error: function(){
				alert('Ocurrió un error al guardar el epígrafe.');
			  },
			});

		}
	  },
	  error: function(){
		alert('Ocurrió un error al obtener el epígrafe antiguo.');
	  },
	});

}
