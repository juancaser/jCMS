<?php
$type = array('System Settings','System Settings');
if($_REQUEST['mod'] == 'sysinfo'){
	$type = array('System Information','System Information');
}
?>
<h2><?php echo $type[0];?></h2>
<?php if($_REQUEST['mod'] == ''):?>
<p>System Settings.</p>
<?php elseif($_REQUEST['mod'] == 'sysinfo'):?>
<p>File uploader.</p>
<?php endif;?>
<h3>For more information on:</h3>
<ul>
	<li><a onclick="load_documentation('settings');" href="javascript:void(0);">Updating your website settings</a></li>
	<li><a onclick="load_documentation('info');" href="javascript:void(0);">System Information</a></li>
</ul>