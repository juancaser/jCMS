<?php
if(!defined('IMPARENT')){exit();} // No direct access
if(count($_REQUEST['cat_id']) > 1){
	$title = 'Delete Categories';
}else{
	$title = 'Delete Category';
}

if($_REQUEST['from'] == 'view'){
	$return_link = BACKEND_DIRECTORY.'/components.php?comp='.$_REQUEST['comp'].'&mod='.$_REQUEST['mod'].($_REQUEST['sub_cat']!=''?'&sub_cat='.$_REQUEST['sub_cat']:'');
}elseif($_REQUEST['from'] == 'edit'){
	$return_link = BACKEND_DIRECTORY.'/components.php?comp='.$_REQUEST['comp'].'&mod='.$_REQUEST['mod'].'&opt=edit&cat_id='.$_REQUEST['cat_id'].($_REQUEST['sub_cat']!=''?'&sub_cat='.$_REQUEST['sub_cat']:'');
}

/* Add Sidebar */
function _sidebar(){
	$filter = '';
	if($_REQUEST['sub_cat']!=''){
		$filter.= " c.cat_parent='".$_REQUEST['sub_cat']."'";
	}else{
		$filter.= " c.cat_parent='0'";	
	}

	$cat_count = jcms_db_get_row("SELECT COUNT(*) AS prod_count FROM #_store_product_categories AS c".($filter!=''? ' WHERE'.$filter : ''))->prod_count;
	$content.='<div style="padding:4px 0;">';
	$content.='	<input style="width:99%;" type="button" value="'.($_REQUEST['sub_cat'] !='' ? 'Add Sub-Category' : 'Add New Category').'" onclick="window.location=\''.get_siteinfo('url',false).'/backend/components.php?comp='.$_REQUEST['comp'].'&mod='.$_REQUEST['mod'].'&opt=edit'.($_REQUEST['sub_cat'] !='' ? '&parent='.$_REQUEST['sub_cat'] : '').'\';" />';
	if($cat_count > 0){	
		$content.='	<input style="margin-top:5px;width:99%;" type="submit" value="Delete" />';
	}
	$content.='</div>';
	add_page_sidebar('components','categories',_l('txt_category','Category',false),$content,'margin-right:20px;margin-bottom:10px;');
}
_sidebar();

if($_REQUEST['action'] == 'delete'){
	if(delete_categories($_REQUEST['cat_id'])){
		set_global_mesage('components_action','Category successfully deleted.','success');
	}else{
		set_global_mesage('components_action','Error occured while deleting. Please try again.','error');
	}
	redirect(BACKEND_DIRECTORY.'/components.php?comp='.$_REQUEST['comp'].'&mod='.$_REQUEST['mod'],'js');
}

if($_REQUEST['cat_id'] !='' || is_array($_REQUEST['cat_id'])){
	if(is_array($_REQUEST['cat_id'])){
		$_ids = "'".join("','",$_REQUEST['cat_id'])."'";
		$categories = jcms_db_get_rows("SELECT ID,cat_title FROM #_store_product_categories WHERE ID IN (".$_ids.")");
	}else{
		$category = get_category($_REQUEST['cat_id']);	
	}	
}
$has_sub = false;
?>
<div id="category-delete">
	<?php if($_REQUEST['cat_id']!=''):?>
        <p>Are you sure you want to permanently delete this from product categories?
	    <ul>
       	<?php for($i=0;$i < count($categories);$i++): $cat = $categories[$i];?>
            	<?php
				if(count_child_category($cat->ID) > 0){
					$has_sub = true;
				}
				$active = count_child_category($cat->ID);
				$disabled = count_child_category($cat->ID,'disabled');
                ?>
        		<li><label><input type="checkbox" name="cat_id[]" value="<?php echo $cat->ID;?>" checked="checked" /> 
                <span style="font-weight:bold;color:#D54E21;"><?php echo $cat->cat_title;?></span>
                <span style="color:#787878;font-style:italic;">(<?php echo ($active > 0  ? $active.' of '.$disabled.' active' : 'No');?> sub-categories)</span>
                </label></li>
            <?php endfor;?>
        </ul></p>
        <?php if($has_sub):?>
        <p style="margin-top:10px;">Any categories associated with will be transfered to Main Category level.</p>
        <?php endif;?>
        <p style="margin-top:10px;">
            <input type="submit" value="Confirmed Delete" />&nbsp;<input type="button" value="Cancel" onclick="window.location='<?php echo $return_link;?>';" />
            <input type="hidden" name="action" value="delete" /></p>
    <?php else:?>
        <p>You want to delete something?</p>
        <p><input type="button" value="Get me outta here!" onclick="window.location='<?php echo $return_link;?>';" /></p>
    <?php endif;?>
</div>