<?php
if(!defined('IMPARENT')){exit();} // No direct access
class JCMS_DB{    
    var $con_link;
    var $db_prefix;
    var $db_name;
	public $has_error = false;
    var $db_error_reporting = true;
	public function JCMS_DB(){
	 // Do Nothing
	}
	
    public function _construct($dbname = '',$dbhost = 'localhost',$dbuser = 'root',$dbpassword = '',$dbprefix = '',$message = ''){		
        $con = mysql_connect($dbhost, $dbuser, $dbpassword);
		if(!$con){			
			$this->has_error = true;			
			trigger_error("Unable to connect to database '".$dbhost."'!, Please make sure you have the correct user and password.", E_USER_ERROR);
		}
		
		$db = mysql_select_db($dbname,$con);
		if(!$db){
			$this->has_error = true;
			trigger_error("'".$dbname."' not found!, Please make sure you have the right access for the database.", E_USER_ERROR);
		}
		
		$this->con_link = $con;
		$this->db_prefix = $dbprefix;
		$this->db_name = $dbname;
    }
    
    public function query($_query,$array = false){
		if(!$this->has_error){
			unset($_resource);
			unset($_result);
			$_query = str_replace('#_',$this->db_prefix,$_query);
			$_resource = mysql_query($_query,$this->con_link);			
			if(is_resource($_resource)){
				if($array){
					$_result = array();
					while($_res = mysql_fetch_object($_resource)){
						$_result[] = $_res;
					}
				}else{
					$_result = mysql_fetch_object($_resource);
				}
				return $_result;
			}else{
				return false;
			}
		}else{
			return false;
		}
    }
	
    public function create_table($table_name,$structure){		
		if(!$this->has_error && !$this->is_table_exists($table_name)){
			$table_name = str_replace('#_',$this->db_prefix,$table_name);
			$sql = "CREATE TABLE IF NOT EXISTS ".$table_name." (".$structure.");";
			$res = mysql_query($sql,$this->con_link);
			if($this->is_table_exists($table_name)){
				return true;
			}else{
				return false;
			}
		}
    }
	function insert(){
	}
	
	
	public function do_query($query){
		if(!$this->has_error){			
			unset($result);
			$query = str_replace('#_',$this->db_prefix,$query);
			$result = mysql_query($query,$this->con_link);
			return $result;
		}else{
			return false;
		}
	}
	
	public function insert_row($table_name,$fields){
		if(count($fields) > 0){
			$field = ''; // Field
			$field_value = ''; // Field Value
			
			$table_name = str_replace('#_',$this->db_prefix,$table_name);
			
			foreach($fields as $key => $value){
				$field.=(!empty($field) ? "," :"").mysql_escape_string($key);
				$field_value.=(!empty($field_value) ? "," :"")."'".mysql_escape_string($value)."'";
			}
			
			$sql = "INSERT INTO ".$table_name." (".$field.") VALUES (".$field_value.")";
			$res = mysql_query($sql,$this->con_link);

			$lid = mysql_insert_id();
			return $lid;
		}else{
			return false;
		}
	}
	
	public function get_rows($query,$union = false){
		if(!$this->has_error && substr($query,0,7) == 'SELECT '){			
			unset($res);
			unset($rslt);
			
			$query = str_replace('#_',$this->db_prefix,$query);
			
			$res = mysql_query($query,$this->con_link);			
			
			if(is_resource($res)){
				$rslt = array();
				while($_res = mysql_fetch_object($res)){
					$rslt[] = $_res;
				}
				return $rslt;
			}else{
				return false;
			}
		}else{
			if(!$this->has_error && $union){
				unset($res);
				unset($rslt);
				
				$query = str_replace('#_',$this->db_prefix,$query);
				
				$res = mysql_query($query,$this->con_link);			
				
				if(is_resource($res)){
					$rslt = array();
					while($_res = mysql_fetch_object($res)){
						$rslt[] = $_res;
					}
					return $rslt;
				}else{
					return false;
				}
			}else{
				return false;
			}			
		}
	}
	
	public function get_row($query){
		if(!$this->has_error && substr($query,0,7) == 'SELECT '){			
			unset($res);
			unset($rslt);
			
			$query = str_replace('#_',$this->db_prefix,$query);
			
			$res = mysql_query($query,$this->con_link);			
			
			if(is_resource($res)){
				$rslt = mysql_fetch_object($res);
				return $rslt;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	
    public function is_table_exists($table_name){
	   if(!$this->has_error){
		  $table_name = str_replace('#_',$this->db_prefix,$table_name);
		  
		  $res = mysql_query("SHOW TABLES LIKE '".$table_name."'",$this->con_link);			
		  $tbl = mysql_fetch_assoc($res);
		  if($tbl['Tables_in_'.$this->db_name.' ('.$table_name.')'] == $table_name){
			 return true;
		  }else{
			 return false;
		  }
	   }else{
		  return false;
	   }
    }
	
    /** Added 12/12/2010 - Jan
	* PAGINATION
	*/
    public function pagination($query,$numrows,$rowsperpage = 5,$url,$page_name = 'pg'){
		$pagination = array();
		// Prepare URL for pages
		$u = parse_url($url);
		$has_query = true;
		if($u['query']!=''){
			$url = $url.'&';			
		}else{
			$url = $url;
			$has_query = false;
		}
		
		// Offset Page
		$totalpages = ceil($numrows / $rowsperpage);
		if(isset($_REQUEST[$page_name]) && is_numeric($_REQUEST[$page_name])){
		   $currentpage = (int) $_REQUEST[$page_name];
		}else{
		   $currentpage = 1;
		}
		
		if($currentpage > $totalpages){
		   $currentpage = $totalpages;
		}
		if($currentpage < 1){$currentpage = 1;}
		$offset = ($currentpage - 1) * $rowsperpage;
		
		// Pagination page		
		$pages = '';
		$page_name = (!$has_query ? '?' : '').$page_name;
		if($totalpages > 1){
			$range = 3;
			if ($currentpage > 1) {
				$pages.='<a class="first" href="'.$url.'">First</a>';
				$prev = ($currentpage - 1);
				$pages.='<a class="prev" href="'.$url.$page_name.'='.$prev.'">&larr;</a>';
			}
			for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++){
				if (($x > 0) && ($x <= $totalpages)) {
					if ($x == $currentpage) {
						$pages.='<span class="current-page">'.$x.'</span>';
					} else {
						$pages.='<a class="page-num" href="'.$url.($x > 1 ? $page_name.'='.$x : '').'">'.$x.'</a>';
					}
				}
			}
			if ($currentpage != $totalpages) {
				$pages.='<a class="next" href="'.$url.$page_name.'='.($currentpage + 1).'">&rarr;</a>';
				$pages.='<a class="last" href="'.$url.$page_name.'='.$totalpages.'">Last</a>';
			}
		}
		
		// Querying
		$query = $query." LIMIT ".$offset.",".$rowsperpage;
		$query = str_replace('#_',$this->db_prefix,$query);		
		$res = mysql_query($query,$this->con_link);
		if(is_resource($res)){
			$rslt = array();
			while($_res = mysql_fetch_object($res)){
				$rslt[] = $_res;
			}			
			$pagination = (object) array(
							'results' => $rslt,
							'pages' => $pages,
							'query' => $query
						);
			return $pagination;
		}else{
			return false;
		}
    }
	
    public function db_info(){
		$db = mysql_query("SELECT VERSION()",$this->con_link);
		$version = mysql_fetch_row($db);
		$info = (object) array(
							 'db' => 'MySQL',
							 'version' => $version[0]
						);
		return $info;
    }
	
    public function close(){
		if(!$this->has_error){
        	mysql_close($this->con_link);
		}
    }    
    
    private function _json_encode($array){	
        $_temp = '';
        if(is_object($array)){
            $array = $this->_object2array($array);
        }
        foreach($array as $key => $value){            
            if(is_array($value)){
                $_temp.=($_temp!='' ? ',':'').chr(34).$key.chr(34).':'.$this->_json_encode($value);
            }else{
                $_temp.=($_temp!='' ? ',':'').chr(34).$key.chr(34).':'.chr(34).$value.chr(34);
            }            
        }
        return '{'.$_temp.'}';
    }
    
    private function _object2array($array){
        $_temp = array();
        if(is_array($array)){
            if(is_object($array)){
                foreach($array as $key => $value){
                    if(is_array($value)){
                        $_temp[$key] = $this->_object2array($value);
                    }else{
                        $_temp[$key] = $value;
                    }
                }
            }            
        }        
    }	
	
	
	
	function array2json($arr) {
		if(function_exists('json_encode')) return json_encode($arr); //Lastest versions of PHP already has this functionality.
		$parts = array();
		$is_list = false;
	
		//Find out if the given array is a numerical array
		$keys = array_keys($arr);
		$max_length = count($arr)-1;
		if(($keys[0] == 0) and ($keys[$max_length] == $max_length)) {//See if the first key is 0 and last key is length - 1
			$is_list = true;
			for($i=0; $i<count($keys); $i++) { //See if each key correspondes to its position
				if($i != $keys[$i]) { //A key fails at position check.
					$is_list = false; //It is an associative array.
					break;
				}
			}
		}
	
		foreach($arr as $key=>$value) {
			if(is_array($value)) { //Custom handling for arrays
				if($is_list) $parts[] = array2json($value); /* :RECURSION: */
				else $parts[] = '"' . $key . '":' . array2json($value); /* :RECURSION: */
			} else {
				$str = '';
				if(!$is_list) $str = '"' . $key . '":';
	
				//Custom handling for multiple data types
				if(is_numeric($value)) $str .= $value; //Numbers
				elseif($value === false) $str .= 'false'; //The booleans
				elseif($value === true) $str .= 'true';
				else $str .= '"' . addslashes($value) . '"'; //All other things
				// :TODO: Is there any more datatype we should be in the lookout for? (Object?)
	
				$parts[] = $str;
			}
		}
		$json = implode(',',$parts);
		
		if($is_list) return '[' . $json . ']';//Return numerical JSON
		return '{' . $json . '}';//Return associative JSON
	}
}

/* Alias Functions */

/* DB Constructor */
function _db_construct(){	
	$config = $GLOBALS['config'];
	$jcms_db = new JCMS_DB();
	$jcms_db->_construct($config->dbname,$config->dbhost,$config->dbuser,$config->dbpassword,$config->dbprefix);	
	$GLOBALS['jcms_db'] = $jcms_db;
}

/* Run a Query */
function jcms_db_query($query){
    if(!isset($GLOBALS['jcms_db']))
	    _db_construct();
	    
    $jcms_db = $GLOBALS['jcms_db'];
    $ret = $jcms_db->do_query($query);
    if(!$ret){
	   return false;
    }else{
	   return $ret;   
    }
    
}

/* Get DB Table Columns */
function jcms_db_get_columns($table_name,$field_name = false){
    if(!isset($GLOBALS['jcms_db']))
	   _db_construct();
    
    $jcms_db = $GLOBALS['jcms_db'];
    $res = $jcms_db->do_query("SHOW COLUMNS FROM ".$table_name);
    $cols = array();
    if(mysql_num_rows($res) > 0) {
	   while ($row = mysql_fetch_object($res)){
		  if($field_name){
			 if($row->Extra != 'auto_increment'){
				$cols[] = $row->Field;	
			 }			 
		  }else{
			 $cols[] = $row;
		  }
	   }
	   return $cols;
    }else{
	   return false;
    }
}

/* Create Table */
function jcms_db_create_table($table_name,$structure){		
	if(!isset($GLOBALS['jcms_db']))
			_db_construct();
		
	$jcms_db = $GLOBALS['jcms_db'];
	$ret = $jcms_db->create_table($table_name,$structure);
	return $ret;
}

/* Insert DB Row */
function jcms_db_insert_row($table_name,$fields){
	if(!isset($GLOBALS['jcms_db']))
			_db_construct();
		
	$jcms_db = $GLOBALS['jcms_db'];
	$ret = $jcms_db->insert_row($table_name,$fields);
	return $ret;
}

/* Get DB Rows */
function jcms_db_get_rows($query,$union = false){
	if(!isset($GLOBALS['jcms_db']))
			_db_construct();
		
	$jcms_db = $GLOBALS['jcms_db'];
	$ret = $jcms_db->get_rows($query,$union);
	return $ret;
}

/* Get DB Rows */
function jcms_db_get_row($query){
	if(!isset($GLOBALS['jcms_db']))
			_db_construct();
		
	$jcms_db = $GLOBALS['jcms_db'];
	$ret = $jcms_db->get_row($query);
	return $ret;
}

/* Get DB Rows with Pagination */
function jcms_db_pagination($query,$numrows,$rowsperpage = 5,$url,$page_name = 'pg'){
	if(!isset($GLOBALS['jcms_db']))
			_db_construct();
		
	$jcms_db = $GLOBALS['jcms_db'];
	$ret = $jcms_db->pagination($query,$numrows,$rowsperpage,$url,$page_name);
	return $ret;
}

/* Check if DB Table Exists */
function jcms_db_is_table_exists($table_name){
	if(!isset($GLOBALS['jcms_db']))
			_db_construct();
		
	$jcms_db = $GLOBALS['jcms_db'];
	$ret = $jcms_db->is_table_exists($table_name);
	return $ret;
}

/* DB Info*/
function jcms_db_info(){
	if(!isset($GLOBALS['jcms_db']))
			_db_construct();
		
	$jcms_db = $GLOBALS['jcms_db'];
	$ret = $jcms_db->db_info();
	return $ret;
}

/* Close DB */
function jcms_db_close(){
	if(!isset($GLOBALS['jcms_db']))
			_db_construct();
		
	$jcms_db = $GLOBALS['jcms_db'];
	$jcms_db->close();
}

/**
 * DB Utility
 */
/* Add Table Data */
function jcms_db_add($table_name,$data){
    $tbl_field  = jcms_db_get_columns($table_name,true);
    $fields = array();
    foreach($data as $key => $value){
	   if(in_array($key,$tbl_field)){
		  $fields[$key] = $value;
	   }				
    }
    $id = jcms_db_insert_row($table_name,$fields);
    if($id > 0){
	   return $id;
    }else{
	   return false;
    }
}

/* Update DB Data */
function jcms_db_update($table_name,$data,$filter){    
    $tbl_field  = jcms_db_get_columns($table_name,true);
    $fields = '';
    foreach($data as $key => $value){
	   if(in_array($key,$tbl_field)){
		  $fields.=(!empty($fields) ? ',' : '').$key."='".mysql_escape_string($value)."'";
	   }				
    }
    if(jcms_db_query("UPDATE ".$table_name." SET ".$fields." WHERE ".$filter)){
        return true;
    }else{
        return false;
    }
}

/* Delete DB Data */
function jcms_db_delete($table_name,$filter){
    if(jcms_db_query("DELETE FROM ".$table_name." WHERE ".$filter)){
        return true;
    }else{
        return false;
    }
}

/* Truncate DB table */
function jcms_db_truncate($table_name){
    if(jcms_db_query("TRUNCATE TABLE ".$table_name)){
        return true;
    }else{
        return false;
    }
}

/* Drop DB table */
function jcms_db_drop($table_name){    
    if(jcms_db_query("DROP TABLE ".$table_name)){
        return true;
    }else{
        return false;
    }
}

add_action('jcms_init','_db_construct','high');
add_action('jcms_close ','jcms_db_close','high');
?>