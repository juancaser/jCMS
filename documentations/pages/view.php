<?php
$type = array('Pages','Page');
if($_REQUEST['mod'] == 'post'){
	$type = array('Posts','Post');
}elseif($_REQUEST['mod'] == 'draft'){
	$type = array('Drafts','Draft');
}
?>
<h2>Viewing of <?php echo $type[0];?></h2>
<p>The <strong>View Pages Screen</strong> provides the facility to manage all the Pages in a site. Via this Screen, Pages can be edited, deleted, and viewed.</p>
<p>The available Immediate Actions are described below:</p>
<ul>
	<li><strong>Edit</strong> - Initiated by click on the Title or clicking on the Edit option just below the Title, causes the Edit Pages screen to display.</li>
</ul>
<h3>For more information on:</h3>
<ul>
		<li><a onclick="load_documentation('add');" href="javascript:void(0);">Add New <?php echo $type[1];?></a></li>
	<li><a onclick="load_documentation('edit');" href="javascript:void(0);">Edit <?php echo $type[1];?></a></li>
</ul>

<br />
