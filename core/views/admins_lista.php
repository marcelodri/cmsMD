<?php

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

    // No muestro los usuarios superadministradores a los usuarios normales
    if( $item->username=='synadmin' AND $this->session->userdata('username')!='synadmin'){
       continue;
    }

    $campos = $this->load->view('includes/lista/campos', array('campos_form' => $campos_form, 'item' => $item), true);
    $acciones   = include('includes/lista/acciones.php');
    $listado_cuerpo .= '<tr class="item">'.$campos.'<td class="bottons">'.$acciones.'</td></tr>';
}

$boton_csv = include('includes/lista/boton_csv.php');
$boton_xml = include('includes/lista/boton_xml.php');

?>

 <div id="wrapper">
    <div id="content">

         <div class="border-bottom">
            <?php echo $buscador?>
        </div>

        <h1>Listado de <?php echo $name ?></h2>
        <table class="list" width="100%" style="margin-bottom:30px;">
            <thead>
               <?php echo $listado_cabecera; ?>
            </thead>
            <tbody>
                <?php echo ($listado_cuerpo!='') ? $listado_cuerpo : '<td colspan="'.count($campos_form).'">No hay datos para mostrar.</td>'; ?>
            </tbody>
        </table>

        <div class="pager">
            <?php echo $paginator ?>
        </div>


        <form target="_blank" class="export" method="post" id="form_export" action="">
            <input type="hidden" name="sql_serialized" value="<?php echo $sql_serialized ?>"/>
        </form>


        <div align="left">
            <?php echo $boton_csv; ?>
            <?php echo $boton_xml; ?>
        </div>

        <div class="lista_acciones-gral">
            <?php echo ($add_button) ? '<input type="button" class="boton" value="'.$add_button_label.'" onclick="document.location.href = \''.base_url().$controller.'/add/'.$uriParameters.'\'">' : ''; ?>
        </div>

    </div>

</div>
