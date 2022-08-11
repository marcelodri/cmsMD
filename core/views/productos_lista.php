<?php

/*
* Agrego botón para importar XLS
*/

$buscador = include('includes/lista/buscador.php');

$listado_cabecera = $this->load->view(
    'includes/lista/cabecera',
    array(
        'campos_form' => $campos_form,
        'ordenar'     => $ordenar,
        'controller'  => $controller
    ), true);

$listado_cuerpo = '';
foreach($items as $item){
    $campos = $this->load->view('includes/lista/campos', array('campos_form' => $campos_form, 'item' => $item), true);
    $acciones = include('includes/lista/acciones.php');
	$listado_cuerpo .=
    '<tr class="item" data-id="'.$item->id.'">'.
        ($ordenar ? '<td class="js-orden grip"></td>' : '').
        $campos.
        '<td class="bottons">'.$acciones.'</td>'.
    '</tr>';
}

$boton_csv = include('includes/lista/boton_csv.php');
$boton_xml = include('includes/lista/boton_xml.php');

?>

 <div id="wrapper">
    <div id="content">

        <div class="<?php echo ($buscador!='') ? 'border-bottom' : ''?>">
            <?php echo $buscador?>
        </div>

        <div class="mensaje-feedback" style="display: none;"><?php echo isset($message) ? $message : ''; ?></div>

    	<?= $name ? '<h1>Listado de '.$name.'</h2>' : '<br><br>' ?>
        <table class="list <?=$ordenar ? 'lista-ordenable' : ''?>" width="100%" style="margin-bottom:30px;">
            <thead>
               <?php echo $listado_cabecera; ?>
            </thead>
            <tbody>
				<?php echo ($listado_cuerpo!='') ? $listado_cuerpo : '<td colspan="'.count($campos_form).'">No hay datos para mostrar.</td>'; ?>
            </tbody>
        </table>

        <input type="hidden" name="sort_order" id="sort_order" value="<?php echo implode('-',$orden); ?>"/>
        <input type="hidden" name="orden_inicio" id="orden_inicio" value="<?php echo intval($orden_inicio); ?>"/>

        <?php if($ordenar){?>
        <p>Agarrá y arrastrá las filas para ordenarlas.</p>
        <?php }?>

        <div class="pager">
            <?php echo $paginator ?>
        </div>

        <div class="lista_acciones-gral">
            <?php if($add_button){
                echo '<a class="boton" href="'.base_url().$controller.'/add/'.$uriParameters.'">'.$add_button_label.'</a>';
            }?>
            <?php echo $boton_csv; ?>
            <?php echo $boton_xml; ?>
            <?php echo '<a '.
                            'class="boton" '.
                            'href="'.base_url().$controller.'/importar'.( (isset($_GET) AND  !empty($_GET)) ? '?'.http_build_query($_GET) : '').'" '.
                        '>Importar</a>'; ?>
        </div>
    </div>
</div>

<?php

/// Scripts JS ///
// Ordenar listado con  drag and drop
if($ordenar){
    echo '<script src="'.base_url().'core/js/vistas/lista/ordenar.js"></script>';
}

// Editar checkboxs con AJAX
echo '<script src="'.base_url().'core/js/vistas/lista/checkbox_ajax.js"></script>';
?>

