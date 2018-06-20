<?php
if(!defined('IMPARENT')){exit();} // No direct access

/** Variables
 */
$template_load_action = false;
$actions = array();

$meta = array();
$enqued_scripts = array();
$scripts = array();
$enqued_styles = array();
$styles = array();
$locale_string = array();
$widgets = array();

$filters = array(); // Filter Cache

/* Get path information */
function get_pathinfo($base_name = '',$object = true){
	$uri = parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);
	$pi = pathinfo($uri);
	$basename = $base_name;
	$dirname = ($basename !='' ? substr($uri,strlen($basename)+1,strlen($uri)) : $uri);
	$extension = $pi['extension'];
	$filename = (basename($uri,'.'.$extension) == $base_name ? '' : basename($uri,'.'.$extension));	
	$script = (basename($uri) == $base_name ? '' : basename($uri));
	$dirs = explode('/',substr($dirname,1,strlen($dirname)));
	$pathinfo = array(
			'basename' => '/'.$basename,
			'dirname' => $dirname,
			'filename' => $filename,
			'extension' => $extension,
			'script' => $script,
			'dirs' => $dirs
		);
	
	if($object){
		$pathinfo = (object) $pathinfo;
	}
	return $pathinfo;
}

/* Add action */
function add_action($id,$callback_function,$priority = 'low',$load_on = 'global'){
	global $actions;
	if(function_exists($callback_function)){
		$actions[$id][] = array(
				'id'=>$id,
				'callback_function'=>$callback_function,
				'priority'=>$priority,
				'load_on'=>$load_on
			);
		return true;
	}else{
		return false;
	}
}

/* Do action */
function do_action($id,$arguments = ''){
	global $actions;
	$pathinfo = get_pathinfo();

	if(count($actions[$id]) > 0){
		// High Priority	
		for($i=0;$i < count($actions[$id]);$i++){
			$action = $actions[$id][$i];
			if($action['priority'] == 'high'){
				if($action['load_on'] == 'global'){
					call_user_func_array($action['callback_function'],((!empty($arguments) && is_array($arguments)) ? $arguments : array()));
				}elseif($action['load_on'] == $pathinfo->filename || $action['load_on'] == $pathinfo->script){
					call_user_func_array($action['callback_function'],((!empty($arguments) && is_array($arguments)) ? $arguments : array()));
				}
			}
		}
		
		// Low Priority
		for($i=0;$i < count($actions[$id]);$i++){
			$action = $actions[$id][$i];
			if($action['priority'] == 'low'){
				if($action['load_on'] == 'global'){
					call_user_func_array($action['callback_function'],((!empty($arguments) && is_array($arguments)) ? $arguments : array()));
				}elseif($action['load_on'] == $pathinfo->filename || $action['load_on'] == $pathinfo->script){
					call_user_func_array($action['callback_function'],((!empty($arguments) && is_array($arguments)) ? $arguments : array()));
				}
			}
		}
		return true;
	}else{
		return false;
	}
}

/* Add Filter */
function add_filter($id,$callback_function){
	global $filters;
	if(function_exists($callback_function)){
		$filters[$id] = $callback_function;
		return true;
	}else{
		return false;
	}
}

/* Apply filter */
function apply_filters($id,$default){
	global $filters;
	if($filters[$id]!=''){
		return call_user_func($filters[$id]);
	}else{
		return $default;
	}

}




/* Register and make the style global */
function register_style($id,$style,$version = '',$browser_dependencies = 'all',$show = 'all'){
	global $styles;
	$styles[$id] = array(
			 'id'=>$id,
			 'style'=>$style,
			 'browser_dependencies'=>$browser_dependencies,
			 'version'=>$version,
			 'show'=>$show
			);
}

/* Enque style */
function enque_style($id,$style = '',$version = '',$browser_dependencies = 'all',$show = 'all'){
	global $styles,$enqued_styles;
	// Check if style is registered 
	if(array_key_exists($id,$styles)){
		$enqued_styles[$id] = $styles[$id];
	}elseif(!array_key_exists($id,$enqued_styles)){
		$enqued_styles[$id] = array(
					'id'=>$id,
					'style'=>$style,
					'browser_dependencies'=>$browser_dependencies,
					'version'=>$version,
					'show'=>$show
				);
	}
}

/* Generate style */
function generate_styles($show = 'all'){
	global $enqued_styles;
	foreach($enqued_styles as $key => $value){
		if($value['style']!=''){
			if($show == 'all'){
				if($value['show'] == 'all'){
					echo '<link id="'.$value['id'].'" href="'.$value['style'].'" rel="stylesheet" type="text/css" />'."\n";
				}
			}else{
				if($show == $value['show']){
					echo '<link id="'.$value['id'].'" href="'.$value['style'].'" rel="stylesheet" type="text/css" />'."\n";
				}
			}
		}
	}
}

/* Register and make the script global */
function register_script($id,$script, $dependencies = '',$version = '',$show = 'all'){
	global $scripts;
	$scripts[$id] = array(
					 'id'=>$id,
					 'script'=>$script,
					 'dependencies'=>$dependencies,
					 'version'=>$version,
					 'show'=>$show
				);
}

/* enque scripts  */
function enque_script($id,$script = '',$dependencies = '',$version = '',$show = 'all'){
	global $scripts,$enqued_scripts;
	// Check if script is registered 
	if(array_key_exists($id,$scripts)){
		$enqued_scripts[$id] = $scripts[$id];
	}elseif(!array_key_exists($id,$enqued_scripts)){
		$enqued_scripts[$id] = array(
					 'id'=>$id,
					 'script'=>$script,
					 'dependencies'=>$dependencies,
					 'version'=>$version,
					 'show'=>$show
				);
	}
}

/* generate script */
function generate_scripts($show = 'all'){
	global $enqued_scripts;
	$loaded_script = array();
	// Load first the script that dont have dependencies
	foreach($enqued_scripts as $key => $value){		
		if($value['dependencies']=='' && $value['script']!=''){
			$loaded_script[] = $key;
			if($show == 'all'){
				if($value['show'] == 'all'){
					echo '<script type="text/javascript" src="'.$value['script'].($value['version']!= '' ? '?v='.$value['version'] : '').'"></script>'."\n";
				}
			}else{
				if($value['show'] == $show){
					echo '<script type="text/javascript" src="'.$value['script'].($value['version']!= '' ? '?v='.$value['version'] : '').'"></script>'."\n";
				}
			}
		}
	}
	
	// Load the script that have dependencies
	foreach($enqued_scripts as $key => $value){		
		if(in_array($value['dependencies'],$loaded_script)){
			if($value['script']!=''){
				if($show == 'all'){
					echo '<script type="text/javascript" src="'.$value['script'].($value['version']!= '' ? '?v='.$value['version'] : '').'"></script>'."\n";
				}else{
					if($value['show'] == $show){
						echo '<script type="text/javascript" src="'.$value['script'].($value['version']!= '' ? '?v='.$value['version'] : '').'"></script>'."\n";
					}
				}				
			}
		}
	}
}

/* add meta tags */
function add_meta($args = ''){
	global $meta,$config;
	// Default Arguments
	$default = array(
		'type' => 'http-equiv',
		'type_value' => 'Content-Type',
		'content' => $config->content_type.'; charset='.$config->charset
	);		
	$args = fill_arguments($default,$args);
	$meta[] = (object) array('type' => $args['type'],'type_value' => $args['type_value'],'content' => $args['content']);
}
add_meta(); // Add  default
/* Generate Meta information */
function generate_meta(){
	global $meta;
	$loaded = array();
	for($i= 0;$i <= count($meta);$i++){
		$m = $meta[$i];
		if(!in_array($m->type_value,$loaded) && $m->type!='' && $m->type_value!='' && $m->content!=''){
			echo '<meta '.$m->type.'="'.$m->type_value.'" content="'.trim($m->content).'"/>'."\n";	
			$loaded[] = $m->type;
		}
	}
}



/* Redirection */
function redirect($to,$type = 'header'){
    if($type == 'header'){
        header('Location: '.$to);
    }elseif($type == 'js'){
        echo '<script type="text/javascript">window.location=\''.$to.'\';</script>';   
    }
}

/* Get site information */
function get_siteinfo($key,$echo = true){
	global $config;	
	$value = '';
	
	switch($key){
		case 'name':
			$value = (defined('SITE_NAME') ? SITE_NAME : $config->site_name);
			break;	
		case 'description':
			$value = (defined('SITE_DESCRIPTION') ? SITE_DESCRIPTION : $config->site_description);
			break;	
		/* URL */
		case 'template_directory':
			$value = (defined('SITE_URL') ? SITE_URL : $config->site_url).'/template';
			break;
		case 'backend_directory':
			$value = (defined('SITE_URL') ? SITE_URL : $config->site_url).'/backend';  
			break;

		case 'home':
		case 'url':
		default:
			$value = (defined('SITE_URL') ? SITE_URL : $config->site_url);  
			break;
	}
	
	if($echo){
		echo $value;
	}else{
		return $value;
	}
}

/* Fill arguments */
function fill_arguments($default,$new){
	$args = array();
	if(!is_array($new)){
		parse_str($new,$new);
	}
		
	foreach($default as $key => $value){
		if(array_key_exists($key,$new)){
			if($value == $new[$key]){
				$args[$key] = $value;
			}else{
				$args[$key] = $new[$key];
			}	
		}else{
			$args[$key] = $value;
		}		
	}
	return $args;	
}


/* Register Widget */
function register_widget($id,$title,$callback){
	global $widgets;
	if(!array_key_exists($id,$widgets)){
		$widgets[$id]	= (object) array(
						'id' => $id,
						'title' => $title,
						'calback' => $callback
					);
	}
	
}
/* Get Widget*/

function jcms_error_handler($errno, $errstr, $errfile, $errline){
	/*$err_txt = '';
	//error_log("<b>Error:</b> [$errno] $errstr", 3,GBL_ROOT_CORE.'/errors.log');
    if (!(error_reporting() & $errno)) {
        // This error code is not included in error_reporting
        return;
    }

    switch ($errno) {
		case E_USER_ERROR:
			$err_txt='<log type="E_USER_ERROR" no="'.$errno.'" date="'.date('Y-m-d h:i:s A').'">';
			$err_txt.='<message>'.$errstr.'</message>';
			if($errfile!='' && $errline!=''){
				$err_txt.='<file>'.$errfile.'</file>';
				$err_txt.='<line>'.$errline.'</line>';
			}
			$err_txt.='<system>PHP '.PHP_VERSION.' ('.PHP_OS.')</system>';
			$err_txt.='</log>'."\n";
			error_log($err_txt, 3,GBL_ROOT_CORE.'/errors.log');
			header('HTTP/1.1 500 Internal Server Error');
			exit(1);
			break;
	
		case E_USER_WARNING:
			$err_txt='<log type="E_USER_WARNING" no="'.$errno.'" date="'.date('Y-m-d h:i:s A').'">';
			$err_txt.='<message>'.$errstr.'</message>';
			if($errfile!='' && $errline!=''){
				$err_txt.='<file>'.$errfile.'</file>';
				$err_txt.='<line>'.$errline.'</line>';
			}
			$err_txt.='<system>PHP '.PHP_VERSION.' ('.PHP_OS.')</system>';
			$err_txt.='</log>'."\n";
			error_log($err_txt, 3,GBL_ROOT_CORE.'/errors.log');
			break;
	
		case E_USER_NOTICE:
			$err_txt='<log type="E_USER_NOTICE" no="'.$errno.'" date="'.date('Y-m-d h:i:s A').'">';
			$err_txt.='<message>'.$errstr.'</message>';
			if($errfile!='' && $errline!=''){
				$err_txt.='<file>'.$errfile.'</file>';
				$err_txt.='<line>'.$errline.'</line>';
			}
			$err_txt.='<system>PHP '.PHP_VERSION.' ('.PHP_OS.')</system>';
			$err_txt.='</log>'."\n";
			error_log($err_txt, 3,GBL_ROOT_CORE.'/errors.log');
			break;
	
		default:
			$err_txt='<log type="UNKNOWN" no="'.$errno.'" date="'.date('Y-m-d h:i:s A').'">';
			$err_txt.='<message>'.$errstr.'</message>';
			if($errfile!='' && $errline!=''){
				$err_txt.='<file>'.$errfile.'</file>';
				$err_txt.='<line>'.$errline.'</line>';
			}
			$err_txt.='<system>PHP '.PHP_VERSION.' ('.PHP_OS.')</system>';
			$err_txt.='</log>'."\n";
			error_log($err_txt, 3,GBL_ROOT_CORE.'/errors.log');
			break;
    }

    //Don't execute PHP internal error handler
    return true;*/
}

/* Display */
function _d($str = '',$before = '',$after = '',$echo = true){
	if($str!=''){
		$t = $before.$str.$after;
		if($echo){
			echo $t;
		}else{
			return $t;
		}
	}
}

/* Get IP Address*/
function get_ipaddress(){
	if( isset($_SERVER["REMOTE_ADDR"])){
		$ip = $_SERVER["REMOTE_ADDR"];
	}elseif(isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
		$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	}elseif(isset($_SERVER["HTTP_CLIENT_IP"])){
		$ip = $_SERVER["HTTP_CLIENT_IP"];
	}
	return $ip;
}

/* Get Localization */
function get_localization($id){
	return $var;
}

/* Check if target site is online */
function is_site_online($hostname,$port = 80,$socket_timeout = ''){
	$socket_timeout = ($socket_timeout!=''?$socket_timeout:ini_get('default_socket_timeout'));
	if(function_exists('fsockopen')){
		$fp = fsockopen($hostname, $port, $errno, $errstr, $socket_timeout);
		if(!$fp){
			return false;
		}else{
			return true;
		}
	}else{
		return false;
	}
}

/* Localization */
function _l($id,$default = '',$echo = true){
	global $locale_string;
	if($default!=''){
		$locale = get_localization($id);		
		$d = ($locale !='' ? $locale : $default);
		
		if(!array_key_exists($id,$locale_string)){
			$locale_string[$id] = $d;
		}
		if($echo){
			echo $d;
		}else{
			return $d;
		}
	}else{
		if(array_key_exists($id,$locale_string)){
			$d = $locale_string[$id];
			if($echo){
				echo $d;
			}else{
				return $d;
			}
		}
	}
}

/* Localization */
function _l2($value,$echo = true){
	global $config;
	if(strtolower($config->locale) != 'en-us'){
		if(is_array($value)){
			if(!is_object($value)){
				$value = (object) $value;
			}
			
			$t = get_localization($value->text);
			foreach($value as $key => $val){
				if($key!='text'){
					$t = str_replace($key,$val,$t);
				}				
			}
		}else{
			$t = get_localization($value);	
		}		
	}else{
		if(is_array($value)){
			if(!is_object($value)){
				$value = (object) $value;
			}

			$t = $value->text;
			foreach($value as $key => $val){
				if($key!='text'){
					$t = str_replace($key,$val,$t);
				}				
			}
		}else{
			$t = $value;	
		}		
	}
	
	if($echo){
		echo $t;
	}else{
		return $t;
	}	
}

/* Set Global Message */
function set_global_mesage($id,$message,$class = ''){
	if(!array_key_exists($id,$_SESSION['gbl_message'])){
		$_SESSION['gbl_message'][$id] = (object) array('id' => $id,'message' => $message,'class' => $class);
	}
}

/* Get Global Message */
function get_global_mesage($id,$clear_after = true,$default = ''){
	if(array_key_exists($id,$_SESSION['gbl_message'])){
		$def = $_SESSION['gbl_message'][$id];
		if($clear_after){
			unset($_SESSION['gbl_message'][$id]);
		}
	}else{
		$def = (object) array('message' => $default,'class' => '');
	}
	return $def;
}

/* Generate Excerpt */
function make_excerpt($content,$length = 100,$more = ''){	
	if(strlen($content) > $length){		
		$content = strip_tags($content,'<p>');
		$content = substr($content,0,$length).$more;
	}
	return $content;
}

/* Make URL Slug */
function make_slug($string,$limit = '140'){
	/*$slug = str_replace('&','-',$string);
	$slug = preg_replace('/[^A-Za-z0-9-]+/', '-',$slug);		
	$slug = str_replace(' ','',$slug);
	$slug = str_replace('--','-',$slug);
	$slug = str_replace('--','-',$slug);

	if(substr($slug,1,strlen($slug)) == '-'){
		$slug = trim(substr($slug,2,strlen($slug)));
	}
	if(substr($slug,strlen($slug)-1,strlen($slug)) == '-'){
		$slug = trim(substr($slug,0,strlen($slug)-1));
	}
	if($limit == 0){ // No Limit
		$slug = strtolower($slug);
	}else{
		$slug = substr(strtolower($slug),0,$limit);
	}*/
	$seperator = '-';
    $string = str_replace('&','and',$string);
	$string = strtolower($string);
    $string = preg_replace("/[^a-z0-9_\s-]/", $seperator, $string);
    $string = preg_replace("/[\s-]+/", " ", $string);
    $string = preg_replace("/[\s_]/", $seperator, $string);
    return $string;

}
/**
 * Nicetime
 */
function get_nicetime($date){
    if(empty($date)){
        return 'No date provided';
    }
   
	$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
	$lengths = array("60","60","24","7","4.35","12","10");
   
	$now = time();
	$unix_date = strtotime($date);
	// check validity of date
	if(empty($unix_date)){
		return 'Invalid date';
	}
	// is it future date or past date
	if($now > $unix_date){
		$difference = $now - $unix_date;
		$tense = 'ago';
	}else{
		$difference = $unix_date - $now;
		$tense = 'from now';
	}
	
	for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++){
		$difference /= $lengths[$j];
	}
	$difference = round($difference);
	if($difference != 1) {
		$periods[$j].= "s";
	}
	return ($difference > 0 ? $difference.' ' : '').$periods[$j].' '.$tense;
}


/**
Validate an email address.
Provide email address (raw input)
Returns true if the email address has the email 
address format and the domain exists.
*/
function validEmail($email,$check_dns = true){
	$isValid = true;
	$atIndex = strrpos($email, "@");
	if(is_bool($atIndex) && !$atIndex){
		$isValid = false;
	}else{
		$domain = substr($email, $atIndex+1);
		$local = substr($email, 0, $atIndex);
		$localLen = strlen($local);
		$domainLen = strlen($domain);
		if($localLen < 1 || $localLen > 64){
			// local part length exceeded
			$isValid = false;
		}elseif($domainLen < 1 || $domainLen > 255){
			// domain part length exceeded
			$isValid = false;
		}elseif($local[0] == '.' || $local[$localLen-1] == '.'){
			// local part starts or ends with '.'
			$isValid = false;
		}elseif(preg_match('/\\.\\./', $local)){
			// local part has two consecutive dots
			$isValid = false;
		}elseif(!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)){
			// character not valid in domain part
			$isValid = false;
		}elseif(preg_match('/\\.\\./', $domain)){
			// domain part has two consecutive dots
			$isValid = false;
		}elseif(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',str_replace("\\\\","",$local))){
			// character not valid in local part unless 
			// local part is quoted
			if(!preg_match('/^"(\\\\"|[^"])+"$/',str_replace("\\\\","",$local))){
				$isValid = false;
			}
		}
		// Check DNS Record
		if($check_dns){
			if($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A"))){
				// domain not found in DNS
				$isValid = false;
			}
		}
	}
	return $isValid;
}


/* Load CAPTCHA LIBRARY*/
function captcha($options = '',$public_key = '',$private_key = ''){	
	if(get_option('recaptcha') == 'yes'){
		if((get_option('recaptcha_public_key') && get_option('recaptcha_private_key')) || ($public_key!='' && $private_key!='')){		
			include('recaptchalib.php');
			$theme = array('red','white','blackglass','clean','custom');
			$lang = array('en','nl','fr','de','pt','ru','es','tr');
			$custom_trans = array('instructions_visual','instructions_audio','play_again','cant_hear_this','visual_challenge','audio_challenge','refresh_btn','help_btn','incorrect_try_again');
			$display = '';
			$_option = '';
			$status = 'SUCCESS';
			$public_key = ($public_key!='' ? $public_key : get_option('recaptcha_public_key'));
			$private_key = ($private_key!='' ? $private_key : get_option('recaptcha_private_key'));
			$a = 1;
			
			if($_POST['captcha_verify'] || $_REQUEST['captcha_verify']){
				$resp = recaptcha_check_answer($private_key,$_SERVER['REMOTE_ADDR'],$_POST['recaptcha_challenge_field'],$_POST['recaptcha_response_field']);			
				if(!$resp->is_valid){
					$status = 'FAILED';
					$message = 'Captcha code wasn\'t entered correctly. Go back and try it again.';
				}
			}
			
			if(count($options) > 0 && is_array($options)){
				$_option = '
					<script type="text/javascript">
					var RecaptchaOptions = {';				
				foreach($options as $key => $value){				
					switch($key){
						case 'theme':
							if(in_array($value,$theme)){
								$_option.='theme : \''.$value.'\''.(count($options) == $a ? '' : ',');						
							}
							break;
						case 'lang':
							if(in_array($value,$lang)){
								$_option.='lang : \''.$value.'\''.(count($options) == $a ? '' : ',');						
							}
							break;
						case 'custom_translations':
							if(count($value) > 0){
								$i = 1;
								$_option.='custom_translations : {';
								foreach($value as $key2 => $value2){
									if(in_array($key2,$custom_trans)){
										$_option.=$key2.': "'.$value2.'"'.(count($value) == $i ? '' : ',');
									}
									$i++;
								}
	
								$_option.='}'.(count($options) == $a ? '' : ',');	
							}						
							break;
						case 'custom_theme_widget':
							$_option.='custom_theme_widget : \''.$value.'\''.(count($options) == $a ? '' : ',');
							break;
						case 'tabindex':
							$_option.='tabindex : '.$value;
							break;
					}
					$a++;				
				}		
				$_option.= '};
					</script>';
			}elseif(get_option('recaptcha_option') !=''){
				$recaptcha_option = (object) unserialize(get_option('recaptcha_option'));
				if(count($recaptcha_option) > 0){
					$_option = '
						<script type="text/javascript">
						var RecaptchaOptions = {';	
					
					if($recaptcha_option->theme!=''){
						$_option.='theme : \''.$recaptcha_option->theme.'\',';
					}
					if($recaptcha_option->lang!=''){
						$_option.='lang : \''.$recaptcha_option->lang.'\',';
					}
					if($recaptcha_option->tabindex!=''){
						$_option.='tabindex : '.$recaptcha_option->tabindex;
					}
					
					$_option.= '};
						</script>';
	
				}
				
			}
			$display.=$_option.recaptcha_get_html($public_key);
			$captcha = (object) array(
						'display' => $display,
						'message' => $message,
						'status' => $status
					);
			return $captcha;
		}
	}
}


/* Create Upload Folder*/
function init_upload_folder(){
	$path = GBL_ROOT_CONTENT.'/uploads/'.date('Y').'/'.date('m');
	if(!file_exists($path)){
		mkdir($path, 0755,true);
	}

	if(!file_exists($path)){
		mkdir(GBL_ROOT_CONTENT.'/uploads/'.date('Y'), 0755);
		mkdir(GBL_ROOT_CONTENT.'/uploads/'.date('Y').'/'.date('m'), 0755);
	}
}

/* Load library */
function load_library(){
	if($handle = opendir(GBL_ROOT_CORE.'/lib')){
		while(false !== ($file = readdir($handle))){
			if($file != '.' && $file != '..'){
				$info = pathinfo($file);
				if($info['extension'] == 'php'){
					include(GBL_ROOT_CORE.'/lib/'.$file);
				}
			}
		}
		closedir($handle);
	}
}


function init_ga(){
	if(get_option('ga_tracker')!='' && get_option('ga') == 'yes'){
		$c = "
		<script type='text/javascript'>
		
		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', '".get_option('ga_tracker')."']);
		  _gaq.push(['_trackPageview']);
		
		  (function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();
		
		</script>
		";
		echo $c;
	}
}

/**
 * Timthumb
 */
function timthumb($src,$w=100,$h=100,$zc = 1,$echo = true){
	if($echo){
		echo get_siteinfo('url',false).'/core/timthumb.php?src='.$src.'&h='.$h.'&w='.$w.'&zc='.$zc;	
	}else{
		return get_siteinfo('url',false).'/core/timthumb.php?src='.$src.'&h='.$h.'&w='.$w.'&zc='.$zc;	
	}	
}

/* Site Cache */
function get_cache(){
	$cache_dir = GBL_ROOT_CORE.'/cache/page';
	if(!file_exists($cache_dir)){// Create if the folder doesnt exists
		}
}

/* Clean up magic quotes*/
if (get_magic_quotes_gpc()) {
    function stripslashes_deep($value){
        $value = is_array($value) ?
                    array_map('stripslashes_deep', $value) :
                    stripslashes($value);

        return $value;
    }

    $_POST = array_map('stripslashes_deep', $_POST);
    $_GET = array_map('stripslashes_deep', $_GET);
    $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
    $_REQUEST = array_map('stripslashes_deep', $_REQUEST);
}



load_library(); // Load all libraries
init_upload_folder(); // Upload folder

add_action('the_head','generate_styles','high');
add_action('the_head','generate_scripts','high');
add_action('the_head','init_ga','low');
add_action('the_meta','generate_meta','high');



/* Error Handler */
set_error_handler('jcms_error_handler');

$pathinfo = get_pathinfo();

// Check if internet we are local or live
if(!isset($_SESSION['NET']) || !$_SESSION['NET']){	
	// This is based upon the principle that the particular connection to a website 
	// is more often prone to failure than google.com. 
	/*if(is_site_online('www.google.com')){
		$_SESSION['NET'] = true;
	}else{
		$_SESSION['NET'] = false;
	}*/
}
define('NET',($_SESSION['NET'] ? 'ONLINE' : 'OFFLINE'))

?>