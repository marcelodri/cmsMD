<?php

$html_boton_csv = ( isset($csv_btn) AND $csv_btn) ?
	'<a '.
		'class="boton boton--csv" '.
		'title="Descargar .CSV" '.
		'href="'.base_url().$controller.'/exportcsv'.( (isset($_GET) AND  !empty($_GET)) ? '?'.http_build_query($_GET) : '').'" '.
		'target="_blank"'.
	'>CSV</a>' : '';

return $html_boton_csv;
