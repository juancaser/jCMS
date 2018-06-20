<?php
if(!defined('JCMS')){exit();} // No direct access
 // Add / Update Page	
if($_REQUEST['action'] == 'save'){
		if($_REQUEST['slug'] !=''){
			/* Custom Fields */
			$custom_fields_data = array();
			if(count($_REQUEST['custom_fields_data']) > 0){
				$i = 1;
				foreach($_REQUEST['custom_fields_data'] as $key => $value){
					if(!array_key_exists($value['key'],$custom_fields_data)){
						$custom_fields_data['field'.$i] = array('key' => $value['key'],'value' => nl2br($value['value'],true));
						$_REQUEST['meta'][$value['key']] = nl2br($value['value'],true);
						$i++;
					}				
				}
				$_REQUEST['meta']['custom_fields_data'] = $custom_fields_data;
				unset($_REQUEST['custom_fields']);
			}
			$_REQUEST['meta']['featured_page']['custom'] = htmlentities($_REQUEST['meta']['featured_page']['custom']);
			$_REQUEST['content'] = ($_REQUEST['content']!=''?htmlentities($_REQUEST['content']):'');
			$_REQUEST['meta'] = serialize($_REQUEST['meta']);	
			$p = update_page($_REQUEST);
			
			if(!$p){
				$msg = 'Error occured while saving, please try again';
				$class = 'error';
				$redirect = PAGE_URL.'?mod=edit'.($_REQUEST['type'] !='' ? '&type='.$_REQUEST['type'] : '').($_REQUEST['id'] > 0 ? '&id='.$_REQUEST['id'] : '');
			}else{
				$msg = ($p->page_type == 'post' ? 'Post' : 'Page').' successfully '.($p->status == 'draft' ? 'saved' : ($_REQUEST['id'] > 0 ? 'updated' : 'published'));
				$class = 'success';
				$redirect = PAGE_URL.'?mod=edit'.($p->page_type == 'post' ? '&type=post' : '').'&id='.$p->ID;
			}
		}else{
				$msg = 'Error occured while saving, please try again';
				$class = 'error';
				$redirect = PAGE_URL.'?mod=edit'.($_REQUEST['type'] !='' ? '&type='.$_REQUEST['type'] : '').($_REQUEST['id'] > 0 ? '&id='.$_REQUEST['id'] : '');
		}
		set_global_mesage('page_action_message',$msg,$class);
		redirect($redirect,'js');
		
}

if($_REQUEST['action'] == 'trashed'){ // Trashed Pages
		$ids = $_REQUEST['chk'];
		if(trashed_page($ids)){
			$msg = count($ids).' Pages/Posts have been moved to trash bin';
			$class = 'success';
		}else{
			$msg= 'Error occured while moving some items to trash bin, please try again';
			$class = 'error';
		}
		set_global_mesage('page_action_message',$msg,$class);
		if($_REQUEST['mod'] == 'post'){
			redirect(PAGE_URL.'?mod=post','js');
		}else{
			if($_REQUEST['type'] == 'draft'){
				redirect(PAGE_URL.'?type=draft','js');
			}else{
				redirect(PAGE_URL,'js');
			}
		}
}

// Delete Pages
if($_REQUEST['action'] == 'delete'){
		$ids = $_REQUEST['chk'];
		if(trashed_page($ids)){
			$msg = count($ids).' Draft have successfully deleted!';
			$class = 'success';
		}else{
			$msg= 'Error occured while deleting '.(count($ids) > 0 ? 'some draft' : 'the draft').', please try again';
			$class = 'error';
		}
		set_global_mesage('page_action_message',$msg,$class);
		if($_REQUEST['mod'] == 'post'){
			redirect(PAGE_URL.'?mod=post','js');
		}else{
			if($_REQUEST['type'] == 'draft'){
				redirect(PAGE_URL.'?type=draft','js');
			}else{
				redirect(PAGE_URL,'js');
			}
		}			
}
?>