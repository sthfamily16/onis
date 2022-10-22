	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
	<script src="/js/error.js"></script>

	<?php
		if(isset($arrjs))
		{
			$strHtml = '';

			foreach($arrjs as $strJs) $strHtml = '<script src="'. $strJs .'"></script>'; 
			
			echo $strHtml;
		}
	?>
</body>
</html>