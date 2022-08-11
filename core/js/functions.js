function areYouSurePrompt(url){
	if(confirm("¿Está seguro que desea realizar la acción?")){
		document.location.href = url;
	}
}

function eliminarAjax(id){
	if(confirm("¿Está seguro que desea eliminar este elemento?")){
		$.ajax({
			url: DOCUMENT_ROOT + CONTROLLER + '/delete/' + id,
			success: function(){
				$('[data-id='+id+']').closest('tr').animate({'opacity': 0}, 700, function(){ $(this).remove(); });
				toastr.success('Se eliminó el elemento seleccionado.');
			},
			error: function(jqXHR, textStatus, errorThrown){
				var error = jqXHR.responseText || 'Hubo un problema. Volvé a intentarlo.';
				toastr.error(error, 'Error');
			},
		});
	}
}

/* INICIALIZACION */

function fancyInit(){

	$(".fancyBox").fancybox({
         'width' : '98%',
         'height' : '90%',
         'autoScale' : true,
         'transitionIn' : 'none',
         'transitionOut' : 'none',
         'type' : 'iframe'
     });
}

$(function() {

	var URI_PARAMETERS 	= $('#uriParameters').val();

	$('.exportButton').click(function() {
		$('form.export').attr('action',$(this).attr('href'));
		$('form.export').submit();
		return false;
	});

	// bind form using 'ajaxForm'
	$('form.registro').ajaxForm({

		beforeSubmit: function(){
			$('.info').html('<img src="'+DOCUMENT_ROOT+'/core/images/ajax-loader.gif">&nbsp;&nbsp;Guardando...');
			$('.info').show();
			$("html, body").animate({ scrollTop: 0 }, "slow");
		},

		success: function (responseText, statusText, xhr, $form)  {
			window.location.replace( RETURN_URL );
		},

		error: function(x,e) {
			var mensaje =  x.responseText || 'Hubo un problema';
			$('.info').hide();
			$('.error').html(mensaje);
			$('.error').show();
			$("html, body").animate({ scrollTop: 0 }, "slow");
		}
	});

	$('form.registro').bind('form-pre-serialize', function(e) {
	    tinyMCE.triggerSave();
	});

	// Inicializo los fancybox

	fancyInit();

    // Inicializo los editores WYSIWYG

    tinymce.init({
		selector: "textarea.tinymce-basico",
		theme: "modern",
		plugins: [
			 "autolink link searchreplace image lists hr anchor pagebreak spellchecker",
			 "searchreplace wordcount visualblocks visualchars fullscreen insertdatetime media nonbreaking",
			 "save table paste textcolor jbimages code"
	   ],
	  relative_urls: false,//Para que funcione jbimages >> http://justboil.me/
	  convert_urls: false,
	  image_list : "/cms/core/js/tinymce/plugins/jbimages/tinymce-image-list.php",//Importa un listado de las imágenes que se subieron (para que funcione modificar las url en tinymce-image-list.php)
	 // content_css : "core/css/estilos-wysiwyg/articulos.css",//Estilos personalizados

	  menu : {
        edit   : {title : 'Editar'  , items : 'undo redo | cut copy paste pastetext | selectall'},
        insert : {title : 'Insertar', items : 'link jbimages'},
        view   : {title : 'Ver'  , items : 'fullscreen code'},
        format : {title : 'Formato', items : 'bold italic underline | removeformat'},
		},
		 media_filter_html: false,
	    contextmenu: "link | paste",
	    toolbar: "undo redo  | bold italic underline | link ",

		image_caption: true,
	  	image_advtab: true,
	  	image_description: true,
	  	image_dimensions: false,
	  	image_title: true,
	  	imagetools_toolbar: "rotateleft rotateright | flipv fliph | editimage imageoptions",

		valid_elements : "a[href|target=_blank],strong/b,em,u,ul,ol,li,br,p[style],img[*],span[style|class]",

		force_p_newlines : false,
		force_br_newlines : true,
		convert_newlines_to_brs : false,
		remove_linebreaks : true,
	 });

	tinymce.init({
		selector: "textarea.tinymce-novedad",
		theme: "modern",
		plugins: [
			 "autolink link searchreplace image lists hr anchor pagebreak spellchecker",
			 "searchreplace wordcount visualblocks visualchars fullscreen insertdatetime media nonbreaking",
			 "save table paste textcolor jbimages code"
	   ],
	  relative_urls: false,//Para que funcione jbimages >> http://justboil.me/
	  convert_urls: false,
	  image_list : "/cms/core/js/tinymce/plugins/jbimages/tinymce-image-list.php",//Importa un listado de las imágenes que se subieron (para que funcione modificar las url en tinymce-image-list.php)
	 // content_css : "core/css/estilos-wysiwyg/articulos.css",//Estilos personalizados

	  menu : {
       // file   : {title : 'File'  , items : 'newdocument'},
        edit   : {title : 'Editar'  , items : 'undo redo | cut copy paste pastetext | selectall'},
        insert : {title : 'Insertar', items : 'link jbimages'},
        view   : {title : 'Ver'  , items : 'fullscreen code'},
        format : {title : 'Formato', items : 'bold italic underline | removeformat'},
        //table  : {title : 'Tabla' , items : 'inserttable tableprops deletetable | cell row column'},
        //tools  : {title : 'Herramientas' , items : 'spellchecker code'}
		},
		 media_filter_html: false,
		// extended_valid_elements: '@[flashvars|type|src|width|height],embed',//Evita que se rompan los embed de picasa
		// entity_encoding : "raw",//Evita que se rompan las url con caracteres especiales (Ej:&)
	  contextmenu: "link | paste",
	  toolbar: "undo redo  | bold italic underline | bullist numlist | link ",
	  /* style_formats: [
			{title: 'Grande', inline: 'span', styles: {font: '22px/30px Arial,sans-serif'}},
			{title: 'Mediano', inline: 'span', styles: {font: '18px/24px Arial,sans-serif'}},
			{title: 'Normal',inline: 'span', styles: {font: '14px/20px Arial,sans-serif'}},
			{title: 'Chico', inline: 'span', styles: {font: '12px/16px Arial,sans-serif'}},
		],*/

		image_caption: true,
	  	image_advtab: true,
	  	image_description: true,
	  	image_dimensions: false,
	  	image_title: true,
	  	imagetools_toolbar: "rotateleft rotateright | flipv fliph | editimage imageoptions",

		valid_elements : "a[href|target=_blank],strong/b,em,u,ul,ol,li,br,p,img[*],span[style|class]",

		//Fuerza el uso de <br> en vez de <p>
		force_br_newlines : true,
        force_p_newlines : false,
        forced_root_block : '',
	 });

	tinymce.init({
		selector: "textarea.tinymce",
		theme: "modern",
		plugins: [
			 "autolink link searchreplace image lists hr anchor pagebreak spellchecker",
			 "searchreplace wordcount visualblocks visualchars fullscreen insertdatetime media nonbreaking",
			 "save table paste textcolor jbimages code"
	   ],
	  relative_urls: false,//Para que funcione jbimages >> http://justboil.me/
	  convert_urls: false,
	  image_list : "/cms/core/js/tinymce/plugins/jbimages/tinymce-image-list.php",//Importa un listado de las imágenes que se subieron (para que funcione modificar las url en tinymce-image-list.php)
	 // content_css : "core/css/estilos-wysiwyg/articulos.css",//Estilos personalizados
	  menu : {
       // file   : {title : 'File'  , items : 'newdocument'},
        edit   : {title : 'Editar'  , items : 'undo redo | cut copy paste pastetext | selectall'},
        insert : {title : 'Insertar', items : 'link | hr'},
        view   : {title : 'Ver'  , items : 'fullscreen code'},
        format : {title : 'Formato', items : 'bold italic underline | formats | removeformat'},
        //table  : {title : 'Tabla' , items : 'inserttable tableprops deletetable | cell row column'},
        //tools  : {title : 'Herramientas' , items : 'spellchecker code'}
		},
		 media_filter_html: false,
		 extended_valid_elements: '@[flashvars|type|src|width|height],embed',//Evita que se rompan los embed de picasa
		// entity_encoding : "raw",//Evita que se rompan las url con caracteres especiales (Ej:&)
	  //contextmenu: "link inserttable | cell row column deletetable | paste",
	  toolbar: "undo redo | styleselect | bold italic underline| alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link ",
	   /*style_formats: [
			{title: 'Grande', inline: 'span', styles: {font: '22px/30px Arial,sans-serif'}},
			{title: 'Mediano', inline: 'span', styles: {font: '18px/24px Arial,sans-serif'}},
			{title: 'Normal',inline: 'span', styles: {font: '14px/20px Arial,sans-serif'}},
			{title: 'Chico', inline: 'span', styles: {font: '12px/16px Arial,sans-serif'}},
		],*/
		//valid_elements : "a[href|target=_blank],strong/b,em,u,ul,ol,li,br,p,img,span[style|class],table,tr,td,th,tbody",
		//Fuerza el uso de <br> en vez de <p>
		force_br_newlines : true,
        force_p_newlines : false,
        forced_root_block : '',
	 });

	// Cargo el color
	$('.colorpicker_selector').each(function(){
		if( $(this).val() ){
			$(this).css('backgroundColor', '#' + $(this).val());
		}
	});

	//http://www.eyecon.ro/colorpicker/
	$(".colorpicker_selector").ColorPicker({

		onSubmit: function(hsb, hex, rgb, el) {
			$(el).val(hex);
			$(el).ColorPickerHide();
		},

		onBeforeShow: function () {
			$(this).ColorPickerSetColor(this.value);
			$('.colorpicker_selector').css('backgroundColor', '#' + this.value);
		},

		onShow: function (colpkr) {
			$(colpkr).fadeIn(500);
			return false;
		},

		onHide: function (colpkr) {
			$(colpkr).fadeOut(500);
			return false;
		},

		onChange: function (hsb, hex, rgb) {
			$('.colorpicker_selector').css('backgroundColor', '#' + hex);
			$('.colorpicker_selector').val(hex);
		}
	}).bind('keyup', function(){
		$(this).ColorPickerSetColor(this.value);
	});




});



		//Devuelve el HTML para un select según la opción seleccionado en otro select
		function ajaxListarOpciones(uniqueId, listaHija, listaPadre, modeloHijo, modeloHijoFK, campoDescriptorHijo, seleccionados){
			var selectorPadre = '[name="'+listaPadre+'"][data-unique='+uniqueId+']';
			var selectorHija = '[name="'+listaHija+'"][data-unique='+uniqueId+']';
	
			var listaPadreValor = $(selectorPadre).val();
	
			if( seleccionados=='' ){
				$(selectorHija).html('<option value="">---</option>');
	
			}else{
				 ajaxListarOpcionesHTML(uniqueId, listaHija, modeloHijo, modeloHijoFK, campoDescriptorHijo, seleccionados, listaPadreValor);
			}
	
			$(selectorPadre).change(function(e) {
				var listaPadreValor = $(this).val();
				$(selectorHija).html('<option value="">Cargando...</option>');
	
				if (listaPadreValor == "") {
					 $(selectorHija).html('<option value="">---</option>');
	
				}else{
					ajaxListarOpcionesHTML(uniqueId, listaHija, modeloHijo, modeloHijoFK, campoDescriptorHijo, seleccionados, listaPadreValor);
				}
	
			});
		}
	
		function ajaxListarOpcionesOnChange(uniqueId, listaHija, listaPadre, modeloHijo, modeloHijoFK, campoDescriptorHijo, seleccionados){
			var selectorPadre = '[name="'+listaPadre+'"][data-unique='+uniqueId+']';
			var selectorHija = '[name="'+listaHija+'"][data-unique='+uniqueId+']';
	
			$(selectorPadre).change(function(e) {
				var listaPadreValor = $(this).val();
				$(selectorHija).html('<option value="">Cargando...</option>');
	
				if (listaPadreValor == "") {
					 $(selectorHija).html('<option value="">---</option>');
	
				}else{
					ajaxListarOpcionesHTML(uniqueId, listaHija, modeloHijo, modeloHijoFK, campoDescriptorHijo, seleccionados, listaPadreValor);
				}
	
			}).trigger('change');
		}
	
	
		function ajaxListarOpcionesHTML(uniqueId, listaHija, modeloHijo, modeloHijoFK, campoDescriptorHijo, seleccionados, listaPadreValor){
			var selectorHija = '[name="'+listaHija+'"][data-unique='+uniqueId+']';
	
			listaPadreValor = listaPadreValor!='' ? listaPadreValor : 0;
	
			$.ajax({
				url: DOCUMENT_ROOT + modeloHijo +'/getHTMLOptions/'+ modeloHijo +'_model/'+ campoDescriptorHijo + '/'+ modeloHijoFK + '=' + listaPadreValor + '/' + seleccionados,
				success: function(output) {
					output = (output=='') ? '<option value="">No hay opciones </option>' : '<option value="">Seleccione una opción: </option>' + output;
					console.log(output);
					$(selectorHija).html(output).trigger("change");
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(xhr.status + " "+ thrownError);
				}
			});
	
		}
	
		//Devuelve el HTML para un select según la opción seleccionado en otro select
		function ajaxListarOpcionesMultiple(listaHija, listaPadre, modeloHijo, modeloHijoFK, campoDescriptorHijo, seleccionados){
	
			var listaPadreValor = $('[name="'+listaPadre+'"]').val();
	
			listaPadreValor = listaPadreValor!='' ? listaPadreValor : 0;
	
			if( seleccionados=='' ){
				//$('[name="'+listaHija+'"]').html('<option value="">---</option>');
	
			}else{
				 ajaxListarOpcionesHTMLmultiple(listaHija, modeloHijo, modeloHijoFK, campoDescriptorHijo, seleccionados, listaPadreValor);
			}
	
			$('[name="'+listaPadre+'"]').change(function(e) {
				var listaPadreValor = $(this).val();
				$('[name="'+listaHija+'"]').html('<option value="">Cargando...</option>');
	
				if (listaPadreValor == "") {
					 $('[name="'+listaHija+'"]').html('<option value=""></option>');
	
				}else{
					ajaxListarOpcionesHTMLmultiple(listaHija, modeloHijo, modeloHijoFK, campoDescriptorHijo, seleccionados, listaPadreValor);
				}
			});
		}
	

	//Devuelve el HTML para un select según la opción seleccionado en otro select
	function ajaxListarOpcionesMultiple(listaHija, listaPadre, modeloHijo, modeloHijoFK, campoDescriptorHijo, seleccionados){

	  var listaPadreValor = $('[name="'+listaPadre+'"]').val();

	  listaPadreValor = listaPadreValor!='' ? listaPadreValor : 0;

	  if( seleccionados=='' ){
	  	//$('[name="'+listaHija+'"]').html('<option value="">---</option>');

	  }else{
	  	 ajaxListarOpcionesHTMLmultiple(listaHija, modeloHijo, modeloHijoFK, campoDescriptorHijo, seleccionados, listaPadreValor);
	  }

	  $('[name="'+listaPadre+'"]').change(function(e) {
	    var listaPadreValor = $(this).val();
	    $('[name="'+listaHija+'"]').html('<option value="">Cargando...</option>');

	    if (listaPadreValor == "") {
	       $('[name="'+listaHija+'"]').html('<option value="">---</option>');

	    }else{
	      ajaxListarOpcionesHTMLmultiple(listaHija, modeloHijo, modeloHijoFK, campoDescriptorHijo, seleccionados, listaPadreValor);
	    }

	  });
	}


	function ajaxListarOpcionesHTMLmultiple(listaHija, modeloHijo, modeloHijoFK, campoDescriptorHijo, seleccionados, listaPadreValor){

	  DOCUMENT_ROOT = $('#document_root').val();

	  listaPadreValor = listaPadreValor!='' ? listaPadreValor : 0;

	  $.ajax({url: DOCUMENT_ROOT + modeloHijo +'/getHTMLOptions/'+ modeloHijo +'_model/'+ campoDescriptorHijo + '/'+ modeloHijoFK + '=' + listaPadreValor + '/' + seleccionados,
	         success: function(output) {
	            //alert(output);
	            output = (output=='') ? '<option value="">No hay opciones </option>' : output;
	            $('[name="'+listaHija+'"]').html(output).trigger("change");
	        },
	      error: function (xhr, ajaxOptions, thrownError) {
	        alert(xhr.status + " "+ thrownError);
	      }});

	}

	function ajaxListarOpcionesSimpleHTML(listaHija, modeloLocal, idLocal, campo, modeloHijo, campoDescriptorHijo){

	  DOCUMENT_ROOT = $('#document_root').val();
	  //alert(DOCUMENT_ROOT + modeloLocal +'/getHTMLOptions/'+ modeloLocal + '_model/' + idLocal + '/' + campo + '/' + modeloHijo + '_model/' + campoDescriptorHijo);
	  $.ajax({url: DOCUMENT_ROOT + modeloLocal +'/getHTMLOptionsSimple/'+ modeloLocal + '_model/' + idLocal + '/' + campo + '/' + modeloHijo + '_model/' + campoDescriptorHijo,
	         success: function(output) {
	            //alert(output);
	            output = (output=='') ? '<option value="">No hay opciones </option>' : '<option value="">Seleccione una opción: </option>' + output;
	            $('[name="'+listaHija+'"]').html(output).trigger("change");
	        },
	      error: function (xhr, ajaxOptions, thrownError) {
	        alert(xhr.status + " "+ thrownError);
	      }});

	}


// Filtra los campos
$(document).ready(function(){
	resetearFiltros();
	$('[data-filtro]').each(function(){
		filtrar( $(this) );
		$(this).change(function(){
			resetearFiltros();
			filtrar( $(this) );
		});
	});
});

// Reseteo estado de los filtros sobre los campos
function resetearFiltros() {
	$('[data-filtro]').each(function(){
		$('[data-filtro-'+$(this).attr('name')+']').data('filtrado', false);
	});
}

function filtrar( $filtro ) {

	var valor;
	var tipoFiltro  = $filtro.attr('name');

	// Deshabilito/habilito y oculto/muestro los campos que tienen activado el filtro
	$('[data-filtro-'+ tipoFiltro +']').each(function(){
		var valores =  $(this).data('filtro-' + tipoFiltro).toString().split(' ');

		var filtrar = false;
		if($filtro.attr('type') == 'checkbox'){
			valor = $filtro.attr('checked')=='checked' ? $filtro.val() : 0;
		}else{
			valor = $filtro.val();
		}

		console.log("Filtrar por " + tipoFiltro + " = " + valor);
		filtrar = $.inArray(valor, valores) == -1;

		if(filtrar){

			$(this).data('filtrado', true);

			// Para los campos de la vista "form"
			$(this).find('input, select, textarea').prop('disabled',true);
			$(this).parents('.form__campo ').css({'position':'absolute','left':-9999999});
			$(this).parents('.form__campo ').prev('.form__titulo').css({'position':'absolute','left':-9999999});

			// Para los campos del buscador de la vista "lista"
			if( $(this).parents('.filtro').length ){
				$(this).find('input, select').prop('disabled',true);
				$(this).parent('fieldset').css({'position':'absolute','left':-9999999});
			}

			$filtro.data('filtrado', true);

		}else{
			// Si los oculta uno de los filtros ya no los muestro
			if($(this).data('filtrado')){
				return;
			}

			// Para los campos de la vista "form"
			$(this).find('input, select, textarea').prop('disabled',false);
			$(this).parents('.form__campo ').css({'position':'static','left':0});
			$(this).parents('.form__campo ').prev('.form__titulo').css({'position':'static','left':0});

			// Para los campos del buscador de la vista "lista"
			if( $(this).parents('.filtro').length ){
				$(this).find('input, select').prop('disabled',false);
				$(this).parent('fieldset').css({'position':'static','left':0});
			}
		}
	});
}

function archivoCambiarTitulo(id){

	var DOCUMENT_ROOT = $('#document_root').val();
	var CONTROLLER = $('#controller').val();

	$.ajax({
	  url: DOCUMENT_ROOT+'index.php?c='+CONTROLLER+'&m=get_file_title&id='+id,
	  context: document.body,
	  success: function(data){
		var tituloViejo = data;
		var tituloNuevo = prompt('Escriba el nuevo título para el archivo:', tituloViejo);

		if(tituloNuevo!=tituloViejo && tituloNuevo!==null){

			$.ajax({
			  url: DOCUMENT_ROOT+'index.php?c='+CONTROLLER+'&m=set_file_title&id='+id,
			  type: 'POST',
			  context: document.body,
			  data: 'titulo='+tituloNuevo,
			  error: function(){
				alert('Hubo un error al actualizar el título.');
			  },
			});

		}
	  },
	  error: function(){
		alert('Hubo un error al intentar obtener el título del archivo.');
	  },
	});
}


jQuery.fn.selectText = function(){
    /*this.find('iframe').each(function() {
        if($(this).prev().length == 0 || !$(this).prev().hasClass('p_copy')) {
            $('<p class="p_copy" style="position: absolute; z-index: -1;"></p>').insertBefore($(this));
        }
        $(this).prev().html($(this).val());
    });*/
    var doc = document;
    var element = this[0];
   // console.log(this, element);
    if (doc.body.createTextRange) {
        var range = document.body.createTextRange();
        range.moveToElementText(element);
        range.select();
    } else if (window.getSelection) {
        var selection = window.getSelection();
        var range = document.createRange();
        range.selectNodeContents(element);
        selection.removeAllRanges();
        selection.addRange(range);
    }
};

function copiarHTML( url, codigo ){
  	$.ajax({
  		'async': false,
	  	url: url,
        success: function(output) {
        	if($('.js-copy-content').length == 0) {
        		elem = codigo ? 'textarea' : 'div';
    	        $("body").append('<'+elem+' style="position: absolute; left: -9999999px;" class="js-copy-content"></'+elem+'>');
    	    }
        	$('.js-copy-content').html(output);

        },
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status + " "+ thrownError);
		}
	});
	$('.js-copy-content').selectText();
	try {
		if( window.clipboardData ){
			window.clipboardData.setData('text', '');
		}

		var copiado = document.execCommand("copy");
		var selection = window.getSelection();
		selection.removeAllRanges();

		alert('¡Copiado!');

	} catch (err) {
		console.log(err);
		alert('Hubo un problema. No se pudo copiar.');
	}
	  return false;
}


/* Acciones en lote */
$(document).on('ready', function(){
	// Marco o desmarco todos los checkboxs
	$('.js-AEL-todos').on('change', function(){
		var $todos = $(this);
		$.each($('.js-AEL-item'), function(i, item){
			if( $todos.attr('checked') ){
				$(item).attr('checked', true);
			}else{
				$(item).removeAttr('checked');
			}
		});
	});

	// Marco o desmarco todos los checkboxs
	$('.js-AEL-aplicar').on('click', function(){
		var accion = $('.js-AEL-accion').val();
		if(!accion){
			alert('Elegí alguna acción.');
			return;
		}

		var functionName = 'accionEnLote_' + accion;
		if(typeof window[functionName] !== "function"){
			alert('Acción inválida.');
			return;
		}

		window[functionName]();
	});
});