<?php

define('UPLOADER_BASE', base_url().'core/third_party/fineuploader/');

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <base href="<?= UPLOADER_BASE.'js/'?>">

    <!-- https://docs.fineuploader.com/quickstart/01-getting-started.html -->
    <script src="fine-uploader.js"></script>
    <link href="fine-uploader.css" rel="stylesheet"/>

    <!-- Optional, but useful for live-debugging -->
    <!-- <script src="fine-uploader.js.map"></script> -->

    <!-- For the row-based UI layout -->
    <link href="fine-uploader-new.css" rel="stylesheet"/>

	<!-- Contenido de templates/default.html -->
	<script type="text/template" id="qq-template">
	    <div class="qq-uploader-selector qq-uploader" qq-drop-area-text="Drop files here">
	        <div class="qq-total-progress-bar-container-selector qq-total-progress-bar-container">
	            <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-total-progress-bar-selector qq-progress-bar qq-total-progress-bar"></div>
	        </div>
	        <div class="qq-upload-drop-area-selector qq-upload-drop-area" qq-hide-dropzone>
	            <span class="qq-upload-drop-area-text-selector"></span>
	        </div>
	        <div class="qq-upload-button-selector qq-upload-button">
	            <div>Upload a file</div>
	        </div>
	            <span class="qq-drop-processing-selector qq-drop-processing">
	                <span>Processing dropped files...</span>
	                <span class="qq-drop-processing-spinner-selector qq-drop-processing-spinner"></span>
	            </span>
	        <ul class="qq-upload-list-selector qq-upload-list" aria-live="polite" aria-relevant="additions removals">
	            <li>
	                <div class="qq-progress-bar-container-selector">
	                    <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-progress-bar-selector qq-progress-bar"></div>
	                </div>
	                <span class="qq-upload-spinner-selector qq-upload-spinner"></span>
	                <span class="qq-upload-file-selector qq-upload-file"></span>
	                <span class="qq-edit-filename-icon-selector qq-edit-filename-icon" aria-label="Edit filename"></span>
	                <input class="qq-edit-filename-selector qq-edit-filename" tabindex="0" type="text">
	                <span class="qq-upload-size-selector qq-upload-size"></span>
	                <button type="button" class="qq-btn qq-upload-cancel-selector qq-upload-cancel">Cancel</button>
	                <button type="button" class="qq-btn qq-upload-retry-selector qq-upload-retry">Retry</button>
	                <button type="button" class="qq-btn qq-upload-delete-selector qq-upload-delete">Delete</button>
	                <span role="status" class="qq-upload-status-text-selector qq-upload-status-text"></span>
	            </li>
	        </ul>

	        <dialog class="qq-alert-dialog-selector">
	            <div class="qq-dialog-message-selector"></div>
	            <div class="qq-dialog-buttons">
	                <button type="button" class="qq-cancel-button-selector">Close</button>
	            </div>
	        </dialog>

	        <dialog class="qq-confirm-dialog-selector">
	            <div class="qq-dialog-message-selector"></div>
	            <div class="qq-dialog-buttons">
	                <button type="button" class="qq-cancel-button-selector">No</button>
	                <button type="button" class="qq-ok-button-selector">Yes</button>
	            </div>
	        </dialog>

	        <dialog class="qq-prompt-dialog-selector">
	            <div class="qq-dialog-message-selector"></div>
	            <input type="text">
	            <div class="qq-dialog-buttons">
	                <button type="button" class="qq-cancel-button-selector">Cancel</button>
	                <button type="button" class="qq-ok-button-selector">Ok</button>
	            </div>
	        </dialog>
	    </div>
	</script>


    <title>Fine Uploader Gallery UI</title>
</head>
<body>
    <div id="uploader"></div>
    <script>
        // Some options to pass to the uploader are discussed on the next page
        var uploader = new qq.FineUploader({
            element: document.getElementById("uploader"),
            debug: true,
            multiple: false,
            request: {
                endpoint: "<?= UPLOADER_BASE.'php/endpoint.php'?>"
            },
            chunking: {
                enabled: true,
                concurrent: {
                    enabled: true
                },
                success: {
                    endpoint: "<?= UPLOADER_BASE.'php/endpoint.php?done'?>"
                }
            }
        });

    </script>
</body>
</html>