<?php 
if (isset($_GET['showinfo'])){
	if ($_GET['showinfo'] == 'yes'){
		echo phpinfo();
	}
} ?>