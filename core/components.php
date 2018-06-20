<?php
if(!defined('IMPARENT')){exit();} // No direct access
$components = array(); // Component Cache

/* Register Component */
function register_component($id,$internal_name,$version,$name,$description = '',$meta = ''){
	global $components;
	if(!in_array($id,$components)){
		$components[$id] = array(
								 'id' => $id,
								 'internal-name' => $internal_name,
								 'version' => $version,
								 'name' => $name,
								 'description' => $description,
								 'meta' => $meta
								);
	}
}

/* Add Component Module */
function add_component_module($component_id,$module_id,$module_name = '',$meta = ''){
	global $components;
	if(array_key_exists($component_id,$components)){
		$components[$component_id]['modules'][$module_id] = array(
													'id' => $module_id,
													'name' => $module_name,
													'meta' => $meta
												);
	}
}


/* Load Component Module */
function load_component_module($component_id,$module_id,$output_buffer = false){
	global $components;
	if(array_key_exists($component_id,$components) && array_key_exists($module_id,$components[$component_id]['modules'])){
		$module = GBL_ROOT.'/components/'.$component_id.'/modules/'.$module_id.'.php';
		if(file_exists($module)){
			if($output_buffer){
				ob_start();
				include($module);
				$t = ob_get_clean();
				return $t;
			}else{
				include($module);
			}
		}else{
			return false;
		}
	}else{
		return false;
	}
}

/* Get all components*/
function get_components(){
	global $components;
	return $components;
}

/* Get all component*/
function get_component($id){
	global $components;
	if(array_key_exists($id,$components)){
		return $components[$id];
	}else{
		return;
	}
}

/* Component Init */
function _load_components(){
	global $config;
	if(count($config->components) > 0){
		$components = $config->components;
		for($i=0;$i < count($components);$i++){
			if(file_exists(GBL_ROOT.'/components/'.$components[$i].'/'.$components[$i].'.php')){				
				require_once(GBL_ROOT.'/components/'.$components[$i].'/'.$components[$i].'.php');
			}			
		}
	}
}

_load_components(); // Load and initialize all components
?>