
<!-- https://docs.fineuploader.com/quickstart/01-getting-started.html -->
<script src="<?= base_url().'core/third_party/fineuploader/js/'?>fine-uploader.js"></script>
<link href="<?= base_url().'core/third_party/fineuploader/js/'?>fine-uploader.css" rel="stylesheet"/>

<!-- Optional, but useful for live-debugging -->
<!-- <script src="fine-uploader.js.map"></script> -->

<!-- For the row-based UI layout -->
<link href="<?= base_url().'core/third_party/fineuploader/js/'?>fine-uploader-new.css" rel="stylesheet"/>

<!-- Contenido de templates/default.html -->
<script type="text/template" id="qq-template">
    <div class="qq-uploader-selector qq-uploader" qq-drop-area-text="Arrastrá y soltá archivos acá">
        <div class="qq-total-progress-bar-container-selector qq-total-progress-bar-container">
            <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-total-progress-bar-selector qq-progress-bar qq-total-progress-bar"></div>
        </div>
        <div class="qq-upload-drop-area-selector qq-upload-drop-area" qq-hide-dropzone>
            <span class="qq-upload-drop-area-text-selector"></span>
        </div>
        <div class="qq-upload-button-selector qq-upload-button">
            <div>Subí un archivo</div>
        </div>
            <span class="qq-drop-processing-selector qq-drop-processing">
                <span>Procesando el archivo...</span>
                <span class="qq-drop-processing-spinner-selector qq-drop-processing-spinner"></span>
            </span>
        <ul class="qq-upload-list-selector qq-upload-list" aria-live="polite" aria-relevant="additions removals">
            <li>
                <div class="qq-progress-bar-container-selector">
                    <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-progress-bar-selector qq-progress-bar"></div>
                </div>
                <span class="qq-upload-spinner-selector qq-upload-spinner"></span>
                <span class="qq-upload-file-selector qq-upload-file"></span>
                <span class="qq-edit-filename-icon-selector qq-edit-filename-icon" aria-label="Editar nombre"></span>
                <input class="qq-edit-filename-selector qq-edit-filename" tabindex="0" type="text">
                <span class="qq-upload-size-selector qq-upload-size"></span>
                <button type="button" class="qq-btn qq-upload-cancel-selector qq-upload-cancel">Cancelar</button>
                <button type="button" class="qq-btn qq-upload-retry-selector qq-upload-retry">Reintentar</button>
                <button type="button" class="qq-btn qq-upload-delete-selector qq-upload-delete">Eliminar</button>
                <span role="status" class="qq-upload-status-text-selector qq-upload-status-text"></span>
            </li>
        </ul>

        <dialog class="qq-alert-dialog-selector">
            <div class="qq-dialog-message-selector"></div>
            <div class="qq-dialog-buttons">
                <button type="button" class="qq-cancel-button-selector">Cerrar</button>
            </div>
        </dialog>

        <dialog class="qq-confirm-dialog-selector">
            <div class="qq-dialog-message-selector"></div>
            <div class="qq-dialog-buttons">
                <button type="button" class="qq-cancel-button-selector">No</button>
                <button type="button" class="qq-ok-button-selector">Si</button>
            </div>
        </dialog>

        <dialog class="qq-prompt-dialog-selector">
            <div class="qq-dialog-message-selector"></div>
            <input type="text">
            <div class="qq-dialog-buttons">
                <button type="button" class="qq-cancel-button-selector">Cancelar</button>
                <button type="button" class="qq-ok-button-selector">Aceptar</button>
            </div>
        </dialog>
    </div>
</script>

<div id="<?= 'fineuploader-'.$campo ?>" class="campo-fineuploader">
    <input type="hidden" name="<?= $campo ?>" value="">
</div>


<script>
    // Some options to pass to the uploader are discussed on the next page
    var uploader = new qq.FineUploader({
        element: document.getElementById("<?= 'fineuploader-'.$campo ?>"),
        debug: true,
        multiple: false,
        validation: {
            allowedExtensions: [<?php
            $allowedExtensions = explode('|',$allowed_types); 
            foreach($allowedExtensions as $i =>$ext){
                $allowedExtensions[ $i ] = "'".$ext."'";
            }
            echo implode(',',$allowedExtensions);
            ?>]
        },
        messages: {
            typeError: "{file} no tiene una extensión válida. Extensiones permitidas: {extensions}.",
            sizeError: "{file} is too large, maximum file size is {sizeLimit}.",
            minSizeError: "{file} is too small, minimum file size is {minSizeLimit}.",
            emptyError: "{file} is empty, please select files again without it.",
            noFilesError: "No files to upload.",
            tooManyItemsError: "Too many items ({netItems}) would be uploaded.  Item limit is {itemLimit}.",
            maxHeightImageError: "Image is too tall.",
            maxWidthImageError: "Image is too wide.",
            minHeightImageError: "Image is not tall enough.",
            minWidthImageError: "Image is not wide enough.",
            retryFailTooManyItems: "Retry failed - you have reached your file limit.",
            onLeave: "The files are being uploaded, if you leave now the upload will be canceled.",
            unsupportedBrowserIos8Safari: "Unrecoverable error - this browser does not permit file uploading of any kind due to serious bugs in iOS8 Safari.  Please use iOS8 Chrome until Apple fixes these issues."
        },
        showMessage: function(message) {
            alert(message);
        },
        request: {
            endpoint: "<?= base_url().'campos/fileUploader'.
                '?controlador_id='.$controlador_id.
                '&modelo='.$modelo.
                '&controlador='.$controlador.
                '&campo='.$campo.
                '&allowed_types='.$allowed_types.
                '&max_size='.$max_size ?>",
        },
        chunking: {
            enabled: true,
            concurrent: {
                enabled: true
            },
            success: {
                endpoint: "<?= base_url().'campos/fileUploader'.
                '?controlador_id='.$controlador_id.
                '&modelo='.$modelo.
                '&controlador='.$controlador.
                '&campo='.$campo.
                '&allowed_types='.$allowed_types.
                '&max_size='.$max_size.
                '&done'?>"
            }
        },

        callbacks: {
            onComplete: function(id, name, responseJSON, xhr){

                if(responseJSON.error !== undefined){
                    alert(responseJSON.error);
                }else{

                    $uploader = $("#<?= 'fineuploader-'.$campo ?>");

                    var datos = <?= json_encode(array(
                        'controlador' => $controlador,
                        'controlador_id' => $controlador_id,
                        'modelo' => $modelo,
                        'campo' => $campo,
                        'allowed_types' => $allowed_types,
                        'max_size' => $max_size,
                    )); ?>

                    datos.archivo = responseJSON.archivo;
                    
                    $.ajax({
                      url: '<?= base_url() ?>campos/fileUploader/cargar_vista/',
                      data:{
                        'vista': 'mostrar',
                        'datos': datos,
                        'devolver': false
                      },

                      beforeSend: function(){
                        $uploader.html("<img src=\"<?= base_url()?>core/images/ajax-loader.gif\"/>");
                      },

                      error: function(jqXHR, textStatus, errorThrown){
                        alert(jqXHR.responseText || 'Se perdió la conexión con el servidor. Asegurate de estar conectado a internet.');
                      },
                      
                      success: function(html){
                        $uploader.html(html);
                      }
                    });
                }
            }
        }
        
    });
</script>