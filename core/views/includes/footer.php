<?php

	$footer_url = "http://www.synapsis.com.ar/";
	$footer_url_txt = "www.synapsis.com.ar";

?>
	<div id="footer">
		<div id="credits">
			<!-- <a href="<?php echo $footer_url ?>"><?php echo $footer_url_txt ?></a> -->
		</div>
	</div>
	</div>
</div>

<!-- Hace que los textarea crezcan con el texto-->
<script type="text/javascript" src="<?php echo base_url() ?>core/js/jquery.autosize.js"></script>
<script>
	$('textarea').autosize();
</script>

<!--Restrinjo los campos de los forms-->
<script>
	$('.js-constrain').change(function(){

		//Solo números
		if( $(this).hasClass('numeric') ){
			$(this).val( $(this).val().replace(/\D/g,'') );
		}

	});
</script>

</body>
</html>
