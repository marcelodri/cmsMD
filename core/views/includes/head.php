<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?= ENVIRONMENT === 'desarrollo' ? 'class="skin-gris"' : ''?>>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Administración de Sitio WEB</title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>core/css/theme.css?v=<?= filemtime(APPPATH.'css/theme.css')?>" />
	<link href="<?php echo base_url() ?>core/js/fileuploader/fileuploader.css" rel="stylesheet" type="text/css" />

<!-- 	<link rel="icon" href="<?php echo base_url() ?>core/css/img/favicon.gif" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url() ?>core/css/img/favicon.gif" type="image/x-icon" /> -->

	<link rel="stylesheet" media="screen" type="text/css" href="<?php echo base_url() ?>core/js/colorpicker/css/colorpicker.css" />
	
	<script type="text/javascript" src="<?php echo base_url() ?>core/js/jquery.min.js"></script>

	<script> $.ajaxSetup({ cache:false }); </script>
	<script>
		// Variables globales
		var DOCUMENT_ROOT = '<?=base_url()?>';
    	var CONTROLLER    = '<?=$this->router->fetch_class()?>';
    	var RETURN_URL    = '<?=isset($return_url) ? $return_url.$uriParameters : ''?>';
	</script>
	<script type="text/javascript" src="<?php echo base_url() ?>core/js/gallery.js"></script>
	<script type="text/javascript" src="<?php echo base_url() ?>core/js/images.js"></script>
	<script type="text/javascript" src="<?php echo base_url() ?>core/js/fileuploader/fileuploader.js"></script>
	<script type="text/javascript" src="<?php echo base_url() ?>core/js/jquery.form.js"></script>
	<script type="text/javascript" src="<?php echo base_url() ?>core/js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
	<script type="text/javascript" src="<?php echo base_url() ?>core/js/colorpicker/js/colorpicker.js"></script>

	<script src="https://use.fontawesome.com/f8732745ec.js"></script>

	<!-- Jcrop -->
	<script src="<?php echo base_url() ?>core/js/jcrop/js/jquery.Jcrop.js"></script>
	<link rel="stylesheet" href="<?php echo base_url() ?>core/js/jcrop/css/jquery.Jcrop.css" type="text/css" />

	<script type="text/javascript" src="<?php echo base_url() ?>core/js/functions.js?v=<?= filemtime(APPPATH.'js/functions.js')?>"></script>
	<link rel="stylesheet" href="<?php echo base_url() ?>core/js/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />
	<script type="text/javascript" src="<?php echo base_url() ?>core/js/tinymce/tinymce.min.js"></script>

	<!-- Calendario -->
	<script type="text/javascript" src="<?php echo base_url() ?>core/js/jquery-ui.js"></script>
	<script>
		/* Inicialización en español para la extensión 'UI date picker' para jQuery. */
		/* Traducido por Vester (xvester@gmail.com). */
		(function( factory ) {
			if ( typeof define === "function" && define.amd ) {

				// AMD. Register as an anonymous module.
				define([ "../datepicker" ], factory );
			} else {

				// Browser globals
				factory( jQuery.datepicker );
			}
		}(function( datepicker ) {

		datepicker.regional['es'] = {
			closeText: 'Cerrar',
			prevText: '&#x3C;Ant',
			nextText: 'Sig&#x3E;',
			currentText: 'Hoy',
			monthNames: ['enero','febrero','marzo','abril','mayo','junio',
			'julio','agosto','septiembre','octubre','noviembre','diciembre'],
			monthNamesShort: ['ene','feb','mar','abr','may','jun',
			'jul','ago','sep','oct','nov','dic'],
			dayNames: ['domingo','lunes','martes','miércoles','jueves','viernes','sábado'],
			dayNamesShort: ['dom','lun','mar','mié','jue','vie','sáb'],
			dayNamesMin: ['D','L','Ma','Mi','J','V','S'],
			weekHeader: 'Sm',
			dateFormat: 'yy/mm/dd',
			firstDay: 1,
			isRTL: false,
			showMonthAfterYear: false,
			yearSuffix: ''};
		datepicker.setDefaults(datepicker.regional['es']);

		return datepicker.regional['es'];

		}));
	</script>
	<link rel="stylesheet" href="<?php echo base_url() ?>core/css/jqueryui/jquery-ui.css">

	<link href="<?php echo base_url() ?>core/js/node_modules/toastr/build/toastr.min.css" rel="stylesheet"/>
	<script src="<?php echo base_url() ?>core/js/node_modules/toastr/build/toastr.min.js"></script>
	<script>
		toastr.options = {
			"closeButton": false,
			"debug": false,
			"newestOnTop": false,
			"progressBar": false,
			"positionClass": "toast-bottom-right",
			"preventDuplicates": false,
			"onclick": null,
			"showDuration": "300",
			"hideDuration": "1000",
			"timeOut": "1000",
			"extendedTimeOut": "1000",
			"showEasing": "swing",
			"hideEasing": "linear",
			"showMethod": "fadeIn",
			"hideMethod": "fadeOut"
		}
	</script>

	<!--[if IE]>
	<link rel="stylesheet" type="text/css" href="css/ie-sucks.css" />
	<![endif]-->
</head>

<body class="<?php echo (isset($_GET['modo']) AND $_GET['modo']=='simple') ? 'vista-modo_simple' : '' ?>">