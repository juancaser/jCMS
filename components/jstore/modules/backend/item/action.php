<?php
if(!defined('IMPARENT')){exit();} // No direct access
global $config;
switch($_REQUEST['action']){
	case 'save':
		$main = array('250','400');
		$thumb = array('100','100');
		$_REQUEST['item_name'] = htmlentities($_REQUEST['item_name'],ENT_QUOTES);
		$_REQUEST['item_description'] = htmlentities(stripslashes($_REQUEST['item_description']),ENT_QUOTES);
		$_REQUEST['item_excerpt'] = ($_REQUEST['item_excerpt']!=''?htmlentities(stripslashes($_REQUEST['item_excerpt']),ENT_QUOTES) : '');
		
		if($_REQUEST['item_price']['sample']['active'] == 'yes' && 
			$_REQUEST['item_price']['sample']['max_order'] > 0 &&
				$_REQUEST['item_price']['sample']['price'] > 0){
			$_REQUEST['item_price']['has_sample'] = 'yes';
		}else{
			$_REQUEST['item_price']['has_sample'] = 'no';
		}
		if(count($_REQUEST['item_price']['bulk']) > 0){
			$bulk = array();
			$i= 1;
			for($i=0;$i <= (count($_REQUEST['item_price']['bulk']) - 1);$i++){
				$b = explode('|',$_REQUEST['item_price']['bulk'][$i]);
				$bulk[$b[0]]['quantity'] = $b[0];
				$bulk[$b[0]]['price'][$b[2]] = $b[1];
				$bulk[$b[0]]['description'] = $b[3];
			}

			ksort($bulk);
			$_REQUEST['item_price']['bulk'] = $bulk;
		}else{
			$_REQUEST['item_price']['bulk'] = '';
		}
		
		$_REQUEST['item_price'] = serialize($_REQUEST['item_price']);
		
		if(count($_REQUEST['meta']['color']['option']) > 0){
			$color = array();
			$i= 1;
			foreach($_REQUEST['meta']['color']['option'] as $p){
				if($p['hex'] !=''){
					$color[$i] = array(
								'hex'=>$p['hex'],
								'type'=>$p['type'],
								'label'=>$p['label']
							);
					$i++;
				}
			}
			$_REQUEST['meta']['color']['option'] = $color;
		}else{
			$_REQUEST['meta']['color']['option'] = '';
		}
		
		if(count($_REQUEST['meta']['print_location']) > 0){
			$loc = array();
			$i= 1;
			foreach($_REQUEST['meta']['print_location'] as $p){
				if($p['label'] !=''){
					$loc[$i] = array(
								'label'=>$p['label'],
								'fee'=>$p['fee']
							);
					$i++;
				}
			}
			$_REQUEST['meta']['print_location'] = $loc;
		}else{
			$_REQUEST['meta']['print_location'] = '';
		}

		if($_REQUEST['meta']['crop_image'] == 'yes'){			
			if($_REQUEST['meta']['media']['main']!=''){			
				$_REQUEST['item_product_image'] = timthumb($_REQUEST['meta']['media']['main'],$main[0],$main[1],1,false);
			}else{
				$_REQUEST['item_product_image'] = '';
			}
			if($_REQUEST['meta']['media']['thumb']!=''){
				$_REQUEST['item_product_image_thumb'] = timthumb($_REQUEST['meta']['media']['thumb'],$thumb[0],$thumb[1],1,false);
			}else{
				$_REQUEST['item_product_image_thumb'] = '';
			}
		}else{
			$_REQUEST['item_product_image'] = $_REQUEST['meta']['media']['main'];
			$_REQUEST['item_product_image_thumb'] = $_REQUEST['meta']['media']['thumb'];
		}
		
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
		$_REQUEST['cat_meta']['featured_page']['custom'] = htmlentities($_REQUEST['cat_meta']['featured_page']['custom'], ENT_QUOTES);
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