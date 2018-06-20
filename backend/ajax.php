<?php
define('IMPARENT',true);
define('JCMS',true);

/* Start Session */
session_start();

/* Load Config */
include('../configuration.php');
$config = new Configuration();

/* Set Timezone */
if(function_exists('date_default_timezone_set') && isset($config->timezone))
	date_default_timezone_set($config->timezone);

/** FUNCTIONS */
include(GBL_ROOT_CORE.'/general.php');  // Database
include(GBL_ROOT_CORE.'/db.class.php');  // Database
include(GBL_ROOT_CORE.'/setup.php'); // Setup
include(GBL_ROOT_CORE.'/options.php'); // Setup
if(get_option('ga') == 'yes' && get_option('ga_email')!='' && get_option('ga_password')!=''){
	include(GBL_ROOT_CORE.'/gapi.class.php'); // Google API
	$ga = new gapi(get_option('ga_email'),get_option('ga_password'));
}


define('BACKEND_DIRECTORY',get_option('site_url').'/backend'); //  Backend Directory


/* Functions */
/* Generate Pernalink */
function _generate_page_permalink($id){
	global $config;
	if($config->mod_rewrite){ // ON	
		
		$cp = jcms_db_get_row("SELECT ID,slug,page_type,parent_page FROM ".$GLOBALS['page_tbl_name']." WHERE ".(is_numeric($id) ? "ID='".$id."'" : "slug='".$id."'")." AND status='published'");		
		
		$filename = $cp->slug.($cp->page_type == 'post' ? '.html' : '');
		
		if($cp->parent_page != '0'){
			$dir = array();
			$parent_page = $cp->parent_page;
			do{
				$parent = jcms_db_get_row("SELECT ID,slug,parent_page FROM ".$GLOBALS['page_tbl_name']." WHERE ID='".$parent_page."' AND status='published' AND page_type='page'");

				$dir[] = $parent->slug;
				$parent_page = $parent->parent_page;
			} while ($parent_page > 0);
			krsort($dir);
			$filename = implode('/',$dir).'/'.$filename;
		}
	
		return get_option('site_url').'/'.$filename;
		
	}elseif(!$config->mod_rewrite){ // OFF
		return;
	}
}


_db_construct();
_db_table_construct();

$_month = array(
			'01'=>'January',
			'02'=>'February',
			'03'=>'March',
			'04'=>'April',
			'05'=>'May',
			'06'=>'June',
			'07'=>'July',
			'08'=>'August',
			'09'=>'September',
			'10'=>'October',
			'11'=>'November',
			'12'=>'December'
		);

/* AJAX */
if($_REQUEST['action'] == 'make_slug'){
	
	$slug = make_slug($_REQUEST['data'],140);

	$page_slug = jcms_db_get_row("SELECT TRUE AS isfound FROM ".$GLOBALS['page_tbl_name']." WHERE slug='".$slug."'");	
	if($page_slug->isfound || $page_slug->isfound == '1'){
		echo $slug.'-'.date('Ymdhis');
	}else{
		echo $slug;
	}
}

if($_REQUEST['action'] == 'check_user'){
	$slug = make_slug($_REQUEST['data']);
	$user = jcms_db_get_row("SELECT TRUE AS isfound FROM ".$GLOBALS['users_tbl_name']." WHERE user_name='".mysql_escape_string(trim($slug))."'");		
	if($user->isfound || $user->isfound == '1'){
		$status = 1;
	}else{
		$status = 0;
	}
	echo '{"status":"'.$status.'","slug":"'.$slug.'"}';
}

if($_REQUEST['action'] == 'uploaded_images'){
	$list = '';
	$lists = '';
	$i = 1;
	
	$album_path = GBL_ROOT.'/content/uploads/';
	
	if($_REQUEST['dir']!=''){
		$path = $_REQUEST['dir'].'/';
	}else{
		$path = GBL_ROOT.'/content/uploads/'.date('Y').'/'.date('m').'/';		
	}
	
	// Album
	$album = '';
	$d = array();
	if($handle = opendir($album_path)){
		while (false !== ($file = readdir($handle))) {
			if(!in_array($file,array('.','..')) && filetype($album_path.$file) == 'dir'){
				$d[] = $file;
			}
		}
		closedir($handle);
	}
	$d2 = array();
	for($a=0;$a < count($d);$a++){
		$p = $album_path.$d[$a].'/';
		if($handle = opendir($p)){
			while (false !== ($file = readdir($handle))) {
				if(!in_array($file,array('.','..')) && filetype($p.$file) == 'dir'){
					$d2[$d[$a]][] = array('path' => $p.$file,'name' => (array_key_exists($file,$_month) ? $_month[$file] : $file));
				}
			}
			closedir($handle);			}	
	}
	if(count($d2) > 0 && $_REQUEST['dir']==''){
		echo '<div id="gallery-album"><label>Media Album: <select onChange="change_album(this);">';
			foreach($d2 as $key => $value){
				echo '<optgroup label="'.$key.'">'."/n";
				for($a=0;$a < count($d2[$key]);$a++){
						echo '<option value="'.$d2[$key][$a]['path'].'">'.$d2[$key][$a]['name'].'</option>'."/n";
				}
				echo '</optgroup>'."/n";
			}
		echo '</select></label></div>';
	}

	if($handle = opendir($path)){
		while (false !== ($file = readdir($handle))) {
			$extension = pathinfo($file);
			if(!in_array($file,array('.','..')) && in_array($extension['extension'],array('gif','jpg','png'))) {
				$lists.='<li id="img'.$i.'" class="gallery" style="margin-bottom:20px;">';
				$lists.='<img class="left" style="height:100px;width:100px;margin-right:5px;" src="'.str_replace(GBL_ROOT,get_siteinfo('url',false),$path).$file.'" />';
				$lists.='<div class="image-info left" style="padding:0;">';
				$lists.='<div><label>Direct Link<br/><textarea readonly="readonly" style="width:490px;padding:5px;height:40px;">'.str_replace(GBL_ROOT,get_siteinfo('url',false),$path).$file.'</textarea></label></div>';
				$lists.='<div style="padding-top:10px;"><input type="button" value="'.($_REQUEST['insert'] != '' ? $_REQUEST['insert'] : 'Insert to Post').'" onclick="insert_img_topost(\''.str_replace(GBL_ROOT,get_siteinfo('url',false),$path).$file.'\');" style="text-transform: uppercase; padding: 0px 10px; font-size: 12px;" />&nbsp;<input type="button" value="Delete" onclick="delete_img(\'img'.$i.'\',\''.str_replace(GBL_ROOT,get_siteinfo('url',false),$path).$file.'\');" style="text-transform: uppercase; padding: 0px 10px; font-size: 12px;" /></div>';
				$lists.='</div>';
				$lists.='<div class="clear"></div>';
				$lists.='</li>'."\n";
				$i++;
			}
		}
		closedir($handle);
		if($lists!=''){
			if($_REQUEST['dir']!=''){
				$list='<ul>'.$lists.'</ul>';
			}else{
				$list='<div id="gallery-images" class="dirs"><ul>'.$lists.'</ul></div>';
			}			
		}
	}
	if($list!=''){
		echo $list;
	}else{
		if($_REQUEST['dir']!=''){
			echo '<p class="messagebox">No images found on the gallery</p>';
		}else{
			echo '<div id="gallery-images" class="dirs"><p class="messagebox">No images found on the gallery</p></div>';
		}
		
	}
	
}

if($_REQUEST['action'] == 'delete_image'){
	$path = str_replace(get_siteinfo('url',false),GBL_ROOT,$_REQUEST['image']);
	if(unlink($path)){
		echo '1';
	}else{
		echo '0';
	}
}

if(get_option('ga') == 'yes' && $_REQUEST['action'] == 'get_ga' && $_REQUEST['report_id']){
	$results = '0';
	$garef = array(
			'visitors','newVisits','percentNewVisits',
			'entranceBounceRate','visitBounceRate','pageviews','avgTimeOnPage','exitRate'
		);
	$ga->requestReportData($_REQUEST['report_id'],array('browser','browserVersion'),$garef);
	$results = array(
		'visitors' => (string)$ga->getVisitors(),
		'newVisits' => (string)$ga->getNewVisits(),
		'percentNewVisits' => (string)sprintf('%.2F',$ga->getPercentNewVisits()).'%',
		'entranceBounceRate' => (string)sprintf('%.2F',$ga->getEntranceBounceRate()).'%',
		'visitBounceRate' => (string)sprintf('%.2F',$ga->getVisitBounceRate()).'%',
		'pageviews' => (string)$ga->getPageviews(),
		'avgTimeOnPage' => (string)sprintf('%.2F',$ga->getAvgTimeOnPage()),
		'exitRate' => (string)sprintf('%.2F',$ga->getExitRate()).'%',
		'datelastupdate' => (string)$ga->getUpdated()
	);
	
	/*switch($_REQUEST['ref']){
		case 'pageview':
			$results = '<span title="The total number of pageviews for your website.">'.$ga->getPageviews().'</span> / <span title="The number of different (unique) pages within a visit, summed up across all visits ">'.$ga->getUniquePageviews().'</span>';
			break;
		case 'visits':
			$results = $ga->getVisits();
			break;
		case 'visitBounceRate':
			$results = (string)sprintf('%.2F',$ga->getVisitBounceRate()).'%';
			break;
		case 'uniquePageviews':
			$results = number_format($ga->getUniquePageviews(),2,'',',');

			break;
		case 'exitRate':
			$results = (string)sprintf('%.2F',$ga->getexitRate()).'%';
			break;
	}*/
	echo json_encode($results);

}

jcms_db_close();
?>