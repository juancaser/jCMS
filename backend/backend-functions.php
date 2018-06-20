<?php
global $pathinfo;
$backend_page = array(); // backend page cache
$msgbox = array();
$current_component = array();
$modal_box = array();
$page_sidebar_box = array();

$userinfo = $_SESSION['__BACKEND_USER']['info']; // Current User

// Current Page URL without query strtings
define('CURRENT_PAGE',BACKEND_DIRECTORY.'/'.($pathinfo->script !=''?$pathinfo->script:$pathinfo->filename));

// Load Specific CSS per page if it exists
function load_page_css(){
	global $pathinfo;
	$components = get_components();
	if(array_key_exists($_REQUEST['comp'],$components)){			
		// Main CSS
		if(file_exists(GBL_ROOT.'/components/'.$_REQUEST['comp'].'/component.css')){
			echo '<link type="text/css" href="'.get_siteinfo('url',false).'/components/'.$_REQUEST['comp'].'/component.css" rel="stylesheet" />'."\n";
		}

		// Module CSS
		if(file_exists(GBL_ROOT.'/components/'.$_REQUEST['comp'].'/modules/backend/'.($_REQUEST['mod']!='' ? $_REQUEST['mod'] : 'main').'/module.css')){
			echo '<link type="text/css" href="'.get_siteinfo('url',false).'/components/'.$_REQUEST['comp'].'/modules/backend/'.($_REQUEST['mod']!='' ? $_REQUEST['mod'] : 'main').'/module.css" rel="stylesheet" />'."\n";
		}
		
		// JS
		if(file_exists(GBL_ROOT.'/components/'.$_REQUEST['comp'].'/component.js')){
			echo '<script type="text/javascript" src="'.get_siteinfo('url',false).'/components/'.$_REQUEST['comp'].'/component.js"></script>'."\n";
		}
	}else{
		if(file_exists(GBL_ROOT_BACKEND.'/modules/'.$pathinfo->filename.'/'.$pathinfo->filename.'.css')){ // CSS
			echo '<link type="text/css" href="'.BACKEND_DIRECTORY.'/modules/'.$pathinfo->filename.'/'.$pathinfo->filename.'.css" rel="stylesheet" />'."\n";
		}
	}
}

/* Backend Action Functions */
/* Header */
function backend_head($id = ''){
	do_action(($id!=''?$id:__FUNCTION__));
}

/* Footer */
function backend_foot($id = ''){
	do_action(($id!=''?$id:__FUNCTION__));
}

/* Backend Template Functions */
/* Header Template */
function the_backend_header(){
	include('backend-header.php');
}

/* Footer Template */
function the_backend_footer(){
	include('backend-footer.php');
}

/* Backend Title*/
function backend_title($echo = true){
	global $backend_page,$config;
	if($echo){
		echo apply_filters(__FUNCTION__,$backend_page->title).' &#8212; Backend Control Panel';
	}else{
		return apply_filters(__FUNCTION__,$backend_page->title).' &#8212; Backend Control Panel';
	}
	
}

/* Load Page Module */
function load_page_module($module,$name = ''){
	global $backend_page,$config;
	$mod_name = GBL_ROOT_BACKEND.'/modules/'.$module.'/'.($name!=''?$name:'main').'.php';

	if(file_exists($mod_name)){
		ob_start();
		include($mod_name);	
		$content = ob_get_clean();
		set_backend_page($title,$content);
	}else{
		set_backend_page('Module Not Found','Module Not Found');
	}
}

/* Load default page*/
function load_page_default($name){
	global $backend_page,$config;
	ob_start();
	include('default/'.$name.'.php');
	$content = ob_get_clean();
	set_backend_page($title,$content);
}

/* Set Page */
function set_backend_page($title,$content = ''){
	global $backend_page;	

	$backend_page = (object) array(
							'title' => $title,
							'content' => $content 
						);
}


/* Display Page Title */
function display_title($echo = true){
	global $backend_page;	
	$dta = $backend_page->title;
	if($echo){
		echo $dta;
	}else{
		return $dta;
	}
}
/* Display Page */
function display_page($echo = true){
	global $backend_page,$config;
	
	$dta = $backend_page->content;
	if($echo){
		echo $dta;
	}else{
		return $dta;
	}
}


function show_gallery($type){
	$content = 'Gallery';
	set_modalbox('gallery','Browse Gallery',$content,500,600);
}

function set_modalbox($id,$title,$content,$h = '200',$w = '200'){
	global $modal_box;
	$modal_box = (object) array('id' => $id,'title' => $title,'content' => $content,'h' => $h,'w' => $w);	
}
function display_modalbox(){
	global $modal_box;
	if($modal_box->id!='' && $modal_box->content!=''){
		$id = $modal_box->id;
		$title = $modal_box->title;
		$content = $modal_box->content;
		$h = $modal_box->h;
		$w = $modal_box->w;
		echo '<div id="modal-box"><div style="'.((is_numeric($h) && is_numeric($w)) ? 'width:'.$w.'px;height:'.$h.'px; ' : '').'" id="'.$id.'" class="window">'.($title!=''?'<div class="title">'.$title.'</div>':'').'<div class="content" style="'.(is_numeric($h) ? 'height:'.($h - 85).'px; ' : '').'">'.$content.'</div><div class="button_container"><input type="button" class="close" value="Close" /></div></div><div id="mask"></div></div>';
	}	
	
}

/* Create Bread Crumb */
function page_breadcrumb($id,$seperator = '&raquo;', $echo = true){
	$crumb = '';
	$page = get_page($id);	
	$crumb = $page->title;
	if($page->parent_page > 0){
		$parent_page = $page->parent_page;
		$cr = array();
		do{
			$parent = get_page($parent_page);
			$cr[] = '<a href="'.BACKEND_DIRECTORY.'/pages.php'.($_REQUEST['mod']!='' ? '?mod='.$_REQUEST['mod'].'&' : '?').'id='.$parent->ID.'">'.$parent->title.'</a>';
			$parent_page = $parent->parent_page;
		} while ($cat_parent > 0);
		krsort($cr);
		$crumb = implode(' '.trim($seperator).' ',$cr).' '.trim($seperator).' '.$crumb;
	}
	$crumb = '<a href="'.BACKEND_DIRECTORY.'/pages.php'.($_REQUEST['mod']!='' ? '?mod='.$_REQUEST['mod'] : '').'">Main</a> '.trim($seperator).' '.$crumb;
	if($echo){
		echo $crumb;
	}else{
		return $crumb;
	}
}

/* Paginated Lists */
function paginated_pagelists($id,$type = 'page',$show = 15,$parent = 0,$filter = ''){

	$page_url = BACKEND_DIRECTORY.'/pages.php'.($type !='page' ? '?mod='.$type.'&' : '?');

	$numrows = jcms_db_get_row("SELECT COUNT(*) AS _count FROM ".$GLOBALS['page_tbl_name']." AS pg WHERE pg.page_type='".($type == 'post' ? 'post' : 'page')."' AND pg.parent_page='".($parent > 0 ? $parent : 0)."'".($filter!=''?' AND '.$filter : ''))->_count;
	$rowsperpage = $show;
	$totalpages = ceil($numrows / $rowsperpage);
	if(isset($_REQUEST['pg']) && is_numeric($_REQUEST['pg'])){
	   $currentpage = (int) $_REQUEST['pg'];
	}else{
	   $currentpage = 1;
	}
	
	if($currentpage > $totalpages){
	   $currentpage = $totalpages;
	}
	if($currentpage < 1){$currentpage = 1;}
	$offset = ($currentpage - 1) * $rowsperpage;

	$sql = "SELECT pg.ID,pg.title,u.display_name AS author,DATE_FORMAT(pg.date_created,'%Y/%m/%d') AS published_date,
		DATE_FORMAT(pg.date_modified,'%Y/%m/%d') AS modified_date 
			FROM ".$GLOBALS['page_tbl_name']." AS pg LEFT JOIN ".$GLOBALS['users_tbl_name']." AS u 
				ON u.ID=pg.author WHERE pg.page_type='".($type == 'post' ? 'post' : 'page')."' AND pg.parent_page='".($parent > 0 ? $parent : 0)."' LIMIT ".$offset.",".$rowsperpage;
	$lists = jcms_db_get_rows($sql);
	$lists_count = count($lists);
	
	$l = '';
	$l.= '<div id="'.$id.'" class="paginated-lists">';
	if($parent > 0){
		$parent_list = get_page($parent);
		$l.= ' <div class="parent">'.$parent_list->title.'</div>';
		$l.= ' <div class="breadcrumb">'.page_breadcrumb($parent_list->ID,'&raquo;',false).'</div>';
	}else{
		$l.= ' <div class="parent">Click on the title to view sub-pages</div>';
	}	
	if($lists_count > 0){
		$l.= '	<ul class="lists">';
		for($i=0;$i < $lists_count;$i++){
			$list = $lists[$i];
			$l.='<li class="list"><a href="'.$page_url.'id='.$list->ID.'">';
			$l.='<h4>'.$list->title.'</h4>';
			$l.=($list->author!='' ? '<span class="author"><span>By </span> '.$list->author.'</span>' : '');
			$l.=($list->published_date!='' ? '<span class="published_date"><span> | Published Date:</span> '.$list->published_date.'</span>' : '');
			$l.=($list->modified_date!='' ? '<span class="modified_date"><span> | Last Modified:</span> '.$list->modified_date.'</span>' : '');
			$l.='</a></li>'."\n";
		}
		$l.= '	</ul>';
		$l.= '	<div class="pagination-pages">';
	
			if($totalpages > 1){
				$url = PAGE_URL.'?'.($_REQUEST['id'] !='' ? 'id='.$_REQUEST['id'].'&' : '').($_REQUEST['type'] !='' ? 'type='.$_REQUEST['type'].'&' : '');
				$range = 3;
				if ($currentpage > 1) {
					$l.='<a class="first" href="'.PAGE_URL.'"><<</a>';
					$prev = ($currentpage - 1);
					$l.='<a class="prev" href="'.($prev == 1 ? PAGE_URL : $url.'pg='.$prev).'"><</a>';
				}				
				for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++){
					if (($x > 0) && ($x <= $totalpages)) {
						if ($x == $currentpage) {
							$l.='<span class="current-page">'.$x.'</span>';
						} else {
							$l.='<a class="page-num" href="'.($x == 1 ? PAGE_URL : $url.'pg='.$x).'">'.$x.'</a>';
						}
					}
				}
				if ($currentpage != $totalpages) {
					$l.='<a class="next" href="'.$url.'pg='.($currentpage + 1).'">></a>';
					$l.='<a class="last" href="'.$url.'pg='.$totalpages.'">>></a>';
				}
			}
		$l.= '	<div style="clear:both;"></div>';
		$l.= '	</div>';		
	}else{
		$l.= ' <p>No '.($type == 'post' ? 'posts' : 'pages').' available.</p>';
	}
	$l.= '</div>';
	echo $l;
}

/* Pagination Page*/
function _pagination($totalpages,$rowsperpage,$url){
	// Prepare URL for pages
	$u = parse_url($url);
	if($u['query']!=''){
		$url = $url.'&';
	}else{
		$url = $url;
	}
	if($rowsperpage > $totalpages){
		$range = 3;
		if ($currentpage > 1) {
			echo '<a class="first" href="'.$url.'"><<</a>';
			$prev = ($currentpage - 1);
			if($prev == 1){
				$url_qstr = $url_qstr;
				echo '<a class="prev" href="'.$url.'pg='.$prev.'"><</a>';
			}else{
				echo '<a class="prev" href="'.$url.'pg='.$prev.'"><</a>';
			}
			
		}				
		for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++){
			if (($x > 0) && ($x <= $totalpages)) {
				if ($x == $currentpage) {
					echo '<span class="current-page">'.$x.'</span>';
				} else {
					if($x == 1){
						echo '<a class="page-num" href="'.$url.'">'.$x.'</a>';
					}else{
						echo '<a class="page-num" href="'.$url.'pg='.$x.'">'.$x.'</a>';
					}							
				}
			}
		}
		if ($currentpage != $totalpages) {
			echo '<a class="next" href="'.$url.'pg='.($currentpage + 1).'">></a>';
			echo '<a class="last" href="'.$url.'pg='.$totalpages.'">>></a>';
		}
	}
}



/* Backend Actions */
/*
function backend_actions(){
	global $msgbox,$config,$pathinfo,$backend_page;
	$b = parse_url(basename($_SERVER['REQUEST_URI']));
	if(!isset($_SESSION['__BACKEND_USER']) && $pathinfo->script!='login.php'){
		redirect(BACKEND_DIRECTORY.'/login.php','js');
	}elseif(isset($_SESSION['__BACKEND_USER']) && $pathinfo->script=='login.php'){
		redirect(BACKEND_DIRECTORY,'js');
	}elseif($_REQUEST['action']!=''){ // Actions		
		switch($_REQUEST['action']){
			case 'user_auth':
				if(user_login($_REQUEST['user_name'],$_REQUEST['user_password'],'backend')){
					redirect(BACKEND_DIRECTORY,'js');
				}else{
					$msgbox = (object) array(
										'content' => 'Invalid User Name/Password',
										'type' => 'error'
									);
				}
				break;
		}
	}else{

	}
}
*/

/* Make Sidebar Box */
function make_box($id,$title = '',$content = '',$style = '',$echo = true){
	$box = '
	<div id="'.$id.'" class="box"'.($style!=''?' style="'.$style.'"' : '').'>
		'.($title!='' ? '<div class="title">'.$title.'</div>' : '').'
		<div class="content">'.$content.'</div>
	</div>
	';
	if($echo){
		echo $box;
	}else{
		return $box;
	}
}

function trashed_items($view = 'all'){
	
	$pages = jcms_db_get_row("SELECT COUNT(*) AS item_count FROM ".$GLOBALS['page_tbl_name']." WHERE status='trash'")->item_count;
	$media = jcms_db_get_row("SELECT COUNT(*) AS item_count FROM ".$GLOBALS['media_tbl_name']." WHERE trash='1'")->item_count;
	if($view == 'page'){
		return (int) $pages;
	}elseif($view == 'media'){
		return (int) $media;
	}else{
		return (int) ($pages + $media);
	}
}


/* Add Backend Menu */
function _navigation_action(){
	$components = get_components();
	$dd = '';
	if(count($components) > 0 && is_array($components)){		
		foreach($components as $key => $value){
			echo '
			<li class="component '.$key.($_REQUEST['comp'] == $key ? ' active' : '').'"><a href="'.BACKEND_DIRECTORY.'/components.php?comp='.$key.'">'.$value['name'].'</a>'."\n";
			$modules = $value['modules'];
			if(count($modules) > 0){
				foreach($modules as $mod_key => $mod_value){
					$meta = $mod_value['meta'];
					$show_on_menu  = true;
					if(is_array($meta)){
						$show_on_menu = $meta['show_on_menu'];
					}
					if($show_on_menu){
						$dd.='<li class="'.$key.($_REQUEST['mod'] == $mod_value['id'] ? ' active' : '').'"><a href="'.BACKEND_DIRECTORY.'/components.php?comp='.$key.'&mod='.$mod_value['id'].'">'.$mod_value['name'].'</a></li>'."\n";
					}					
				}
				echo '<ul class="sub-menu">'."\n";
				echo $dd;
				echo '</ul>'."\n";
			}			
			echo '
			</li>'."\n";
		}
	}
}

function timezone_lists(){
	$tz = array();
	$continent = array();
	$timezone_identifiers = DateTimeZone::listIdentifiers();
	for($i=0; $i < count($timezone_identifiers); $i++){
		$tzi = $timezone_identifiers[$i];
		$ti = explode('/',$tzi);
		if(!array_key_exists($c,$continent)){
			$continent[strtolower($ti[0])] = trim($ti[0]);
		}
		if($ti[0] == 'UTC'){
			$tz[strtolower($ti[0])][] = (object) array('ID' => $tzi,'name' => 'UTC');
		}else{
			$tz[strtolower($ti[0])][] = (object) array('ID' => $tzi,'name' => str_replace('_',' ',$ti[1]));
		}
		
	}
	$t = (object) array('continent' => $continent,'cities' => $tz);
	return $t;
}
function load_documentations(){
	include('documentation.php');
}


/* Load all Actions */
add_action('jcms_backend_init','backend_actions','high');
add_action('jcms_backend_init','_db_construct','high');
add_action('jcms_backend_init','_db_table_construct','high');
//add_action('jcms_backend_init','addons','high');

add_action('backend_head','generate_styles','high');
add_action('backend_head','generate_scripts','high');
add_action('backend_head','load_page_css','high');
//add_action('backend_head','load_component_cssjs','high');
add_action('jcms_backend_close ','jcms_db_close','high');
add_action('backend_top_navigation','_navigation_action');

enque_script('jquery');
enque_script('jquery-tiny-mce');


if($_REQUEST['comp']!='' && $pathinfo->script=='components.php'){ // Lad Components
	$components = get_components();
	if(array_key_exists($_REQUEST['comp'],$components)){				
		define('COMPONENTS_URL',BACKEND_DIRECTORY.'/components.php?comp='.$_REQUEST['comp']);
		$mod = 'main';
		$opt = 'main';
		
		if($_REQUEST['mod']!=''){$mod = $_REQUEST['mod'];}
		if($_REQUEST['opt']!=''){$opt = $_REQUEST['opt'];}	
		
		// Lets load the backend modules only
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
		
	}
}
/* Trash Functions */
if($_REQUEST['action'] == 'restore' && $pathinfo->script=='trash.php'){
	if(!trashed_page($_REQUEST['restore'],false)){
		$msg  = 'Error occured while restoring some of Post/Page items, please try again';
		$class = "error";
	}else{
		$msg  = count($_REQUEST['restore']).' Post/Page item(s) had been succesfully restored';
		$class = "success";
	}
	set_global_mesage('trash_action',$msg,$class);
	redirect(BACKEND_DIRECTORY.'/trash.php?type=page','js');
}
if($_REQUEST['action'] == 'delete' && $pathinfo->script=='trash.php'){
	if(!delete_page($_REQUEST['restore'],false)){
		$msg  = 'Error occured while deleting some of Post/Page items, please try again';
		$class = "error";
	}else{
		$msg  = count($_REQUEST['restore']).' Post/Page item(s) had been succesfully deleted';
		$class = "success";
	}
	set_global_mesage('trash_action',$msg,$class);
	redirect(BACKEND_DIRECTORY.'/trash.php?type=page','js');
}

if($_REQUEST['action'] == 'user_auth' && $pathinfo->script=='login.php'){
	if(user_login($_REQUEST['user_name'],$_REQUEST['user_password'],'backend')){
		redirect(BACKEND_DIRECTORY,'js');
	}else{
		set_global_mesage('login','Invalid User Name/Password','error');
	}
}

?>
