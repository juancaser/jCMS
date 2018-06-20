<?php
$type = array('Pages','Page');
if($_REQUEST['mod'] == 'post'){
	$type = array('Posts','Post');
}elseif($_REQUEST['mod'] == 'draft'){
	$type = array('Drafts','Draft');
}
?>
<h2>Add New <?php echo $type[1];?></h2>
