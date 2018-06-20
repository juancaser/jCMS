<?php if(!defined('JCMS')){exit();} // No direct access ?>
<?php if(file_exists(GBL_ROOT_TEMPLATE.'/backend/layout.php')){
	include(GBL_ROOT_TEMPLATE.'/backend/layout.php');    
}else{
	echo '<p>No page layout defined.</p>';
} ?>
