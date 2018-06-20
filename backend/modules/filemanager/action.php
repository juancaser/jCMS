<?php
if(!defined('JCMS')){exit();} // No direct access
define('PAGE_URL',BACKEND_DIRECTORY.'/pages.php');
if($_REQUEST['action'] == 'add'){ // Add New Page			
		$_REQUEST['slug'] = make_slug($_REQUEST['slug']);
		
		/* Custom Fields */
		$custom_fields = array();
		if(count($_REQUEST['custom_fields']) > 0){
			$i = 1;
			foreach($_REQUEST['custom_fields'] as $key => $value){
				if(!array_key_exists($value['key'],$custom_fields)){
					$custom_fields['field'.$i] = array('key' => $value['key'],'value' => $value['value']);
					$i++;
				}				
			}
			$_REQUEST['meta']['custom_fields'] = $custom_fields;
			unset($_REQUEST['custom_fields']);
		}
		$_REQUEST['meta'] = serialize($_REQUEST['meta']);
		
		$p = update_page($_REQUEST);
		
		if(!$p){
			$msg = 'Error occured while saving, please try again';
			$class = 'error';
			$redirect = PAGE_URL.'?'.($_REQUEST['id'] !='' ? 'mod=edit&id='.$_REQUEST['id'] : 'mod=add');
		}else{
			$msg = ($p->page_type == 'post' ? 'Post' : 'Page').' successfully '.($p->status == 'draft' ? 'saved to draft' : ($_REQUEST['ID'] !='' ? 'updated' : 'published'));
			$class = 'success';
			$redirect = PAGE_URL.'?mod=edit'.($p->page_type == 'post' ? '&type=post' : '').'&id='.$p->ID;
		}		
		set_global_mesage('action_message',$msg,$class);
		redirect($redirect,'js');
		
}elseif($_REQUEST['action'] == 'trashed'){ // Trashed Pages
		$ids = $_REQUEST['chk'];
		if(trashed_page($ids)){
			$msg = count($ids).' Pages/Posts have been moved to trash bin';
			$class = 'success';
		}else{
			$msg= 'Error occured while moving some items to trash bin, please try again';
			$class = 'error';
		}
		set_global_mesage('action_message',$msg,$class);
		if($_REQUEST['mod'] == 'post'){
			redirect(PAGE_URL.'?mod=post','js');
		}else{
			if($_REQUEST['type'] == 'draft'){
				redirect(PAGE_URL.'?type=draft','js');
			}else{
				redirect(PAGE_URL,'js');
			}
		}
}elseif($_REQUEST['action'] == 'delete'){ // Delete Pages
		$ids = $_REQUEST['chk'];
		if(trashed_page($ids)){
			$msg = count($ids).' Draft have successfully deleted!';
			$class = 'success';
		}else{
			$msg= 'Error occured while deleting '.(count($ids) > 0 ? 'some draft' : 'the draft').', please try again';
			$class = 'error';
		}
		set_global_mesage('action_message',$msg,$class);
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