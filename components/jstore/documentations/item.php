<?php
$type = array('My Products','My Products');
if($_REQUEST['opt'] == 'edit'){
	if($_REQUEST['id'] > 0){
		$type = array('Updating Product Item','Updating Product Item');
	}else{
		$type = array('Adding New Product Item','Adding New Product Item');
	}	
}elseif($_REQUEST['opt'] == 'category-edit'){
	if($_REQUEST['id'] > 0){
		$type = array('Updating Product Category','Updating Product Category');
	}else{
		$type = array('Adding New Product Category','Adding New Product Category');
	}	
}
?>
<h2><?php echo $type[0];?></h2>
<?php if($_REQUEST['mod'] == ''):?>
<p>Pages are similar to Posts in that they have a title, body text, and associated metadata, the physical differences between posts and pages are that pages dont have extensions like <code>.html</code>, while Posts have those extensions. Pages can have a hierarchy. You can nest Pages under other Pages by making one the "Parent" of the other, creating a group of Pages.</p>
<p>Managing Pages is very similar to managing Posts.</p>
<?php elseif($_REQUEST['mod'] == 'post'):?>
<p>Posts are similar to Pages in that they have a title, body text, and associated metadata, the physical differences between posts and pages are that post have extensions like <code>.html</code>, while Page dont have. Post can be come a child to a certain page. You can nest Post under other Pages by making one the "Parent" of the other, creating a group of Post.</p>
<p>However you cant turn Post into a parent page, only page has that option.</p>
<p>Managing Posts is very similar to managing Pages.</p>
<?php elseif($_REQUEST['mod'] == 'draft'):?>
<p>Drafts are saved unpublished Posts/Pages.</p>
<?php endif;?>
<h3>For more information on:</h3>
<ul>
	<li><a onclick="load_documentation('view');" href="javascript:void(0);">Table of <?php echo $type[0];?></a></li>
	<li><a onclick="load_documentation('add');" href="javascript:void(0);">Add New <?php echo $type[1];?></a></li>
	<li><a onclick="load_documentation('edit');" href="javascript:void(0);">Edit <?php echo $type[1];?></a></li>
</ul>