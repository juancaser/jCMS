<?php
include('backend-load.php'); // Backend bootstrap loader
if(isset($_SESSION['__BACKEND_USER'])){
	unset($_SESSION['__BACKEND_USER']);	
	/*if(!isset($_COOKIE['_jcms']) || $_COOKIE['_jcms'] != ''){
		setcookie ("_jcms", "", time() - 3600);
	}*/	
	redirect(BACKEND_DIRECTORY.'/login.php','js');
}else{	
	redirect(BACKEND_DIRECTORY,'js');
}

?>
