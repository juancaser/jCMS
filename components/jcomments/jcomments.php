<?php
if(!defined('IMPARENT')){exit();} // No direct access

define('JCOMMENTS',true);
define('JCOMMENTS_ROOT',dirname(__FILE__));
define('JCOMMENTS_ID','jcomments');
define('JCOMMENTS_VERSION','1.0');

global $config,$pathinfo,$page,$bb_code;

$bb_code = array(
			'b' => '<span style="font-weight:bold;">%s</span>',
			'i' => '<span style="font-style:italic;">%s</span>',
			'u' => '<span style="text-decoration:underline;">%s</span>',
			'u' => '<span style="text-decoration:underline;">%s</span>',
		);

/* Categories valid DB fields */
function _comments_db_fields(){
	$db_fields = array(
		'ID',
		'comment_type',
		'page_id',
		'comment_message',
		'comment_status',
		'comment_author',
		'commented_date',
		'meta',
		'ipaddress'
	); 
	return $db_fields;
}
function make_comments_fields($fields){
	$f = join(',',$fields);
	$f = str_replace('message','comment_message AS message',$f);
	$f = str_replace('page','page_id AS page',$f);
	$f = str_replace('status','comment_status AS status',$f);
	$f = str_replace('author','comment_author AS author',$f);
	$f = str_replace('comment_date','commented_date AS comment_date',$f);
	$f = str_replace('ip','ipaddress AS ip',$f);
	
	return $f;
}

function add_comment($fields = ''){
}

function delete_comment($fields = ''){
}

function get_comments($type,$page_id,$sort = 'ASC',$sort_by = 'c.commented_date',$limit = '5'){
	$sql = "SELECT c.*,DATE_FORMAT(c.commented_date,'%M %e, %Y at %h:%i:%s %p') AS comment_date,u.display_name AS author FROM #_comments AS c LEFT JOIN #_users AS u ON u.ID=c.comment_author WHERE c.comment_type='".$type."' AND  c.page_id='".$page_id."' AND c.comment_status='published' ORDER BY ".$sort_by." ".$sort." LIMIT 0,".$limit;
	$comments = jcms_db_get_rows($sql);
	return $comments;
}

function get_comment_author($comment_id){
	$sql = "SELECT c.comment_author,u.display_name AS author FROM #_comments AS c LEFT JOIN #_users AS u ON u.ID=c.comment_author WHERE c.comment_status='published' AND c.ID='".$comment_id."'";
	$author = jcms_db_get_row($sql);
	return ($author->author !='' ? $author->author : $author->comment_author);
}

function convert_bb($text){	
	global $bb_code;
	foreach($bb_code as $code => $value){
		preg_match_all('|\['.$code.'\](.*)\[/'.$code.'\]|U',$text,$q,PREG_SET_ORDER);
		$new = str_replace('%s',$q[0][1],$value);
		$text = str_replace('['.$code.']'.$q[0][1].'[/'.$code.']',$new,$text);
	}
	return $text;
}

function sanitize_comment_message($text,$echo = true){
	$message = $text;
	
	//preg_match_all('|\[comment id="(.*)"\](.*)\[/comment\]|U',$message,$q,PREG_SET_ORDER);	
	preg_match_all('|\[comment id="(.*)"\]|U',$message,$q,PREG_SET_ORDER);
	for($i=0;$i <= count($q);$i++){
		$reply_author = get_comment_author($q[$i][1]);	
		//$message = str_replace('[comment id="'.$q[$i][1].'"]'.$q[$i][2].'[/comment]','<blockquote class="comment-reply"><div class="comment-author">Posted by '.($reply_author !='' ? $reply_author : 'Anonymous').'</div><div class="comment-message">'.$q[$i][2].'</div></blockquote>',$message);		
		$message = str_replace('[comment id="'.$q[$i][1].'"]','<blockquote class="comment-reply"><div class="comment-author">Posted by '.($reply_author !='' ? $reply_author : 'Anonymous').'</div><div class="comment-message">',$message);		
		$message = str_replace('[/comment]','</div></blockquote>',$message);
	}
	$message = convert_bb($message);
	if($echo){
		echo $message;
	}else{
		return $message;
	}
}

function sanitize_comment_date($date,$echo = true){
	$today = strtotime(date('Y-m-d'));
	$comment_date = strtotime(date('Y-m-d',strtotime($date)));
	
	if($today == $comment_date){ // Today
		$the_date = '<strong>Today</strong> at '.date('h:i:s A',strtotime($date));
	}else{ // Past
		$gd_a = getdate($today);
		$gd_b = getdate($comment_date);
		$a_new = mktime( 12, 0, 0, $gd_a['mon'], $gd_a['mday'], $gd_a['year'] );
		$b_new = mktime( 12, 0, 0, $gd_b['mon'], $gd_b['mday'], $gd_b['year'] );
		if(round(abs($a_new - $b_new ) / 86400) == 1){ // Yesterday
			$the_date = '<strong>Yesterday</strong> at '.date('h:i:s A',strtotime($date));
		}else{
			$the_date = 'on '.date('F d, Y \a\t h:i:s A',strtotime($date));
		}
	}
	if($echo){
		echo $the_date;
	}else{
		return $the_date;
	}
}

function sanitize_author_name($author,$website = '',$echo = true){
	$website = (trim($website)=='NA' ? '' : trim($website));
	if($website!=''){
		$ps = parse_url($website);
		$website = ($ps['scheme']!=''?$ps['scheme']:'http').'://'.str_replace(array('http://','https://'),'',$website);
	}

	if($website!= ''){
		$this_author = '<a target="_blank" href="javascript:window.open(\''.$website.'\');" rel="nofollow" title="Click here to go to '.$author.'\'s website.">'.$author.'</a>';
	}else{
		$this_author = $author;
	}
	
	if($echo){
		echo $this_author;
	}else{
		return $this_author;
	}
}

function count_comments($page_id,$type,$status = 'published'){
	$sql = "SELECT COUNT(ID) as comment_count FROM #_comments WHERE comment_type='".$type."' AND  page_id='".$page_id."' AND comment_status='".$status."'";
	return jcms_db_get_row($sql)->comment_count;
}

/* jComments */
if(!jcms_db_is_table_exists('#_comments')){	
	$structure = "ID BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY, comment_type VARCHAR(100) NOT NULL, page_id BIGINT(50) NOT NULL, comment_message LONGTEXT NOT NULL, comment_status VARCHAR(20) NOT NULL, comment_author VARCHAR(100) NOT NULL, commented_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, meta LONGTEXT NOT NULL, ipaddress VARCHAR(100) NOT NULL";
	jcms_db_create_table('#_comments',$structure);
}

if(function_exists('register_component')){
	register_component('jcomments','jComments',JCOMMENTS_VERSION,'Comments','Comment components of jCMS, jComments v'.JCOMMENTS_VERSION);
}

?>