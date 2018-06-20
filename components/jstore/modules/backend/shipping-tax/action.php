<?php
if(!defined('IMPARENT')){exit();} // No direct access
global $config;
switch($_REQUEST['action']){
	case 'save':
		$_REQUEST['item_name'] = htmlentities($_REQUEST['item_name'],ENT_QUOTES);
		$_REQUEST['item_description'] = htmlentities($_REQUEST['item_description'],ENT_QUOTES);
		$_REQUEST['item_excerpt'] = ($_REQUEST['item_excerpt']!=''?htmlentities($_REQUEST['item_excerpt'],ENT_QUOTES) : '');
		/*// Images
		$allowed_image = array('image/gif','image/jpeg','image/pjpeg','image/png');
		if(in_array($_FILES['file']['type'],$allowed_image) && $_FILES['file']['name'] !=''){
			if($_FILES['file']['error'] > 0){
				set_global_mesage('component_action_message','Invalid Image','error');
				redirect(BACKEND_DIRECTORY.'/components.php?comp='.$_REQUEST['comp'].'&mod='.$_REQUEST['mod'].'&opt='.$_REQUEST['opt'],'js');
			}else{
				$upload_path = GBL_ROOT_CONTENT.'/uploads/'.date('Y').'/'.date('m');
				$upload_url = get_option('site_url').'/content/uploads/'.date('Y').'/'.date('m');

				if(!file_exists($upload_path.'/'.$_FILES['file']['name'])){
					move_uploaded_file($_FILES['file']['tmp_name'],$upload_path.'/'.$_FILES['file']['name']);					
				}
				if(!in_array($upload_url.'/'.$_FILES['file']['name'],$_REQUEST['meta']['product_image'])){
					$_REQUEST['meta']['product_image'][] = $upload_url.'/'.$_FILES['file']['name'];
				}
				$_REQUEST['item_product_image'] = ($_REQUEST['item_product_image']!=''?$_REQUEST['item_product_image']:($_FILES['file']['name'] !=''?$upload_url.'/'.$_FILES['file']['name']:''));
			}
		}*/		
		/*$items = array();
		foreach($_REQUEST['meta']['items'] as $key => $value){
			if($value['description'] !='' && $value['quantity'] !='' && $value['price'] !=''){
				$items[] = $value;
			}			
		}
		$_REQUEST['meta']['items'] = (count($items) > 0 ? $items : '');*/
		$_REQUEST['meta'] = serialize($_REQUEST['meta']);		
		$product_info = update_product_info($_REQUEST);		
		if($product_info->ID > 0){
			if($_REQUEST['id'] > 0){
				$msg = 'Product updated successfully';
			}else{
				$msg = 'New product added successfully';
			}			
			$class = 'success';
		}else{
			$msg = 'Error occured while saving, please try again';
			$class = 'error';
		}
		set_global_mesage('component_action_message',$msg,$class);
		redirect(BACKEND_DIRECTORY.'/components.php?comp='.$_REQUEST['comp'].'&mod='.$_REQUEST['mod'].'&opt='.$_REQUEST['opt'].($product_info->ID !='' ? '&id='.$product_info->ID : ''),'js');
	
		break;
		
	case 'delete':
		$msg = '';
		$class='success';
		
		if(!delete_categories($_REQUEST['chk']['category'])){
			$class = 'error';
		}
		if(!delete_product_info($_REQUEST['chk']['product'])){
			$class = 'error';
		}
		set_global_mesage('component_action_message',($class == 'error' ? 'Error occured while delete some data.' : 'Categories/Products successfully deleted.'),$class);
		redirect(BACKEND_DIRECTORY.'/components.php?comp='.$_REQUEST['comp'].'&mod='.$_REQUEST['mod'],'js');
		break;
		
		
	case 'save-category':
		$_REQUEST['cat_title'] = htmlentities($_REQUEST['cat_title'],ENT_QUOTES);
		$_REQUEST['cat_description'] = htmlentities($_REQUEST['cat_description'],ENT_QUOTES);
		$_REQUEST['cat_excerpt'] = ($_REQUEST['cat_excerpt']!=''?htmlentities($_REQUEST['cat_excerpt'],ENT_QUOTES):'');
		/*$allowed_image = array('image/gif','image/jpeg','image/pjpeg','image/png');
		if(in_array($_FILES['file']['type'],$allowed_image) && $_FILES['file']['name'] !=''){
			if($_FILES['file']['error'] > 0){
				set_global_mesage('component_action_message','Invalid Image','error');
				redirect(BACKEND_DIRECTORY.'/components.php?comp='.$_REQUEST['comp'].'&mod='.$_REQUEST['mod'].'&opt='.$_REQUEST['opt'].($product_category->ID !='' ? '&id='.$product_category->ID : ''),'js');
			}else{
				$upload_path = GBL_ROOT_CONTENT.'/uploads/store/'.date('Y').'/'.date('m');
				$upload_url = get_option('site_url').'/content/uploads/store/'.date('Y').'/'.date('m');
				if(!file_exists($upload_path)){
					mkdir(GBL_ROOT_CONTENT.'/uploads/store', 0755);
					mkdir(GBL_ROOT_CONTENT.'/uploads/store/'.date('Y'), 0755);
					mkdir(GBL_ROOT_CONTENT.'/uploads/store/'.date('Y').'/'.date('m'), 0755);
				}

				if(!file_exists($upload_path.'/'.$_FILES['file']['name'])){
					move_uploaded_file($_FILES['file']['tmp_name'],$upload_path.'/'.$_FILES['file']['name']);					
				}
				$_REQUEST['cat_image'] = $upload_url.'/'.$_FILES['file']['name'];
			}
		}*/		
		$_REQUEST['cat_meta'] = serialize($_REQUEST['cat_meta']);
		$product_category = update_category($_REQUEST);		
		if($product_category->ID > 0){
			if($_REQUEST['id'] > 0){
				$msg = 'Product category updated successfully';
			}else{
				$msg = 'New product category added successfully';
			}			
			$class = 'success';
		}else{
			$msg = 'Error occured while saving, please try again';
			$class = 'error';
		}
		set_global_mesage('component_action_message',$msg,$class);
		redirect(BACKEND_DIRECTORY.'/components.php?comp='.$_REQUEST['comp'].'&mod='.$_REQUEST['mod'].'&opt='.$_REQUEST['opt'].($product_category->ID !='' ? '&id='.$product_category->ID : ''),'js');
		break;
}
?>