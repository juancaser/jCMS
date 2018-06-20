<?php
include('backend-load.php'); // Backend bootstrap loader
$action_message = get_global_mesage('component_action_message');

$components = get_components();
if(array_key_exists($_REQUEST['comp'],$components)){				
	define('COMPONENTS_URL',BACKEND_DIRECTORY.'/components.php?comp='.$_REQUEST['comp']);
	$mod = 'main';
	$opt = 'main';
	
	if($_REQUEST['mod']!=''){$mod = $_REQUEST['mod'];}
	if($_REQUEST['opt']!=''){$opt = $_REQUEST['opt'];}	
	
	if(file_exists(GBL_ROOT.'/components/'.$_REQUEST['comp'].'/modules/backend/'.$mod.'/action.php')){
		include(GBL_ROOT.'/components/'.$_REQUEST['comp'].'/modules/backend/'.$mod.'/action.php');
	}
	
	// Lets load the backend modules only
	$module_root = '';
	$module_root = GBL_ROOT.'/components/'.$_REQUEST['comp'].'/modules/backend/'.$mod.'/'.$opt.'.php';
	if(file_exists($module_root)){					
		ob_start();
		include($module_root);
		$content = ob_get_clean();
		$backend_page = (object) array(
							'title'=> $title,
							'content'=> $content
						);
	}else{
		load_page_default('components');
	}
	
}else{
	$backend_page = (object) array('title'=> 'Components');
}
the_backend_header();
?>
<div class="page-wrapper">	
    <div class="inner">
    	<?php if(array_key_exists($_REQUEST['comp'],$components)):?>
	        <?php //display_page();
			include($module_root);
			?>
    	<?php else:?>
        	<p style="padding-top:20px;">Installed jCMS components. This page is only read-only, to add or disabled you must change it thru<code>configuration.php</code>found on root directory.</p>        	
        	<ul id="component-lists">
            	<?php foreach($components as $key => $value):?>
            	<li class="left" style="margin-left:10px;"><a href="<?php echo BACKEND_DIRECTORY;?>/components.php?comp=<?php echo $value['id'];?>"><?php echo $value['internal-name'];?> v<?php echo $value['version'];?></a></li>
            	<?php endforeach;?>
            </ul>
            <div class="clear" style="padding-bottom:20px;"></div>
    	<?php endif;?>		
    </div>
</div>
<?php the_backend_footer(); ?>