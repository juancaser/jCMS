<?php
if(!defined('JCMS')){exit();} // No direct access
global $action_message;
$userinfo = $_SESSION['__USER']['info'];
$non_editable = array(
					'.htaccess',
					'Thumbs.db',
					'.DS_Store',
					'php.ini'
				);
$back = '';
$fm = FILEMANAGER_URL;
$currrent = $fm.($_REQUEST['dir']!=''?'?dir='.$_REQUEST['dir'].'&fn='.$_REQUEST['fn'] : '');
if($_REQUEST['dir']!=''){
	$upload_path = rawurlencode($_REQUEST['dir']);
}
$max = 0;
$dirs = array();
$filetype_name = array(
					'file' => 'File',
					'dir' => 'Directory',
				);
				
if($_REQUEST['action'] == 'delete'){ // Delete
	if(rmdir(rawurldecode($_REQUEST['dir']).'/'.rawurldecode($_REQUEST['name']))){
		$msg = '<span class="bold">"'.$_REQUEST['name'].'"</span> had been successfully deleted.';
		$class = 'success';
	}else{
		$msg = 'Error occured while delete <span class="bold">"'.$_REQUEST['name'].'"</span>, please try again.';
		$class = 'error';
	}
	set_global_mesage('filemanager_action',$msg,$class);
	redirect(rawurldecode($_REQUEST['prev']),'js');
}elseif($_REQUEST['action'] == 'rename'){ // Rename
	if(rename(rawurldecode($_REQUEST['dir']).'/'.rawurldecode($_REQUEST['old']),rawurldecode($_REQUEST['dir']).'/'.rawurldecode($_REQUEST['new']))){
		$msg = '<span class="bold">"'.$_REQUEST['old'].'"</span> had been successfully renamed to <span class="bold">"'.$_REQUEST['new'].'"</span>.';
		$class = 'success';
	}else{
		$msg = 'Error occured while renaming <span class="bold">"'.$_REQUEST['old'].'"</span>, please try again.';
		$class = 'error';
	}
	set_global_mesage('filemanager_action',$msg,$class);
	redirect(rawurldecode($_REQUEST['prev']),'js');
}elseif($_REQUEST['action'] == 'create-folder'){ // Rename
	if(!file_exists(rawurldecode($_REQUEST['dir']).'/'.rawurldecode($_REQUEST['name']))){
		if(mkdir(rawurldecode($_REQUEST['dir']).'/'.rawurldecode($_REQUEST['name']))){
			$msg = '<span class="bold">"'.$_REQUEST['name'].'"</span> had been successfully creaated.';
			$class = 'success';
		}else{
			$msg = 'Error occured while creating <span class="bold">"'.$_REQUEST['name'].'"</span>, please try again.';
			$class = 'error';
		}
	}else{
		$msg = '<span class="bold">"'.$_REQUEST['name'].'"</span> already exists! please try another name.';
		$class = 'error';
	}
	set_global_mesage('filemanager_action',$msg,$class);
	redirect(rawurldecode($_REQUEST['prev']),'js');
}


if($_REQUEST['dir']!='' && $_REQUEST['fn']!=''){
	$dir = GBL_ROOT.rawurldecode($_REQUEST['dir']);
	$title = '['.rawurldecode($_REQUEST['fn']).'] Content File Manager';
}elseif($_REQUEST['edit']!=''){
	$msg = 'No viewer specified';
	$msg_class = 'error';
	
	$dir = GBL_ROOT;
	$url = get_siteinfo('url',false);
	$title = 'Content File Manager';
}else{	
	//$dir = GBL_ROOT_CONTENT.'/uploads';
	$dir = GBL_ROOT;
	$url = get_siteinfo('url',false);
	$title = '[ROOT] Content File Manager';
}
if($_REQUEST['fn']!=''){
	$back = str_replace('/'.$_REQUEST['fn'],'',$dir);
	$back = str_replace('//','/',$back);
	if($back != GBL_ROOT || $back != '/'){
		$back = str_replace(GBL_ROOT,'',$back);
		$_a = explode('/',$back);
		$_fn = $_a[count($_a)-1];
	}else{
		$back = '';
	}
	$b = $fm.(($back!='' && $_fn!='')?'?dir='.rawurlencode($back).'&fn='.rawurlencode($_fn):'');	
}
if($_REQUEST['prev']!=''){
	$b = rawurldecode($_REQUEST['prev']);
}
// Load Directory
if(is_dir($dir)){
    if($dh = opendir($dir)) {
        while(($file = readdir($dh))!==false){
			if(!in_array($file,array('.','..','Thumbs.db','.DS_Store'))){				
				$fp = $dir.'/'.$file;
				$fp = str_replace('//','/',$fp);
				$pi = pathinfo($file);
				$ft = filetype($fp);
				// Extensions and Icons
				$ext = '';
				if($pi['filename'] !=''){
					if(filetype($fp) == 'dir'){
						$ext  = 'folder';
					}elseif(in_array($pi['extension'],array('png','jpg','jpeg','bmp','gif'))){
						$ext  = 'image';
					}elseif(file_exists(GBL_ROOT.'/backend/modules/filemanager/icon/icon_'.$pi['extension'].'.gif')){
						$ext = $pi['extension'];
					}else{
						$ext = 'txt';							
					}
				}else{
					$ext = 'generic';
				}		
				$dirs[$ft][$file] = array(
							'name' => $file,
							'type' => $ft,
							'fn' => $pi['filename'],
							'extension' => ($pi['filename'] !='' ? ($pi['extension']!=''?$pi['extension']:'folder') : 'txt'),
							'icon' => BACKEND_DIRECTORY.'/modules/filemanager/icon/icon_'.$ext.'.gif',
							'size' => ($ft == 'dir' ? 'DIR' : number_format(filesize($fp))),
							'modified_date' => date('m/d/Y h:i:s A', fileatime($fp)),
							'path' => $fp
						);
			}
        }
        closedir($dh);
    }
}
//print_r($dirs);
ksort($dirs);
ksort($dirs['dir']);
ksort($dirs['file']);
$dir_count = (count($dirs['dir']) + count($dirs['file']));
?>
<script type="text/javascript">
function open_file(_path,_filename,_type){	
	if(_type == 'image'){		
		console.log('<?php get_siteinfo('url');?>'+_path);
		/*window.open( "http://www.google.com/" )*/
	}
	if(_type == 'text'){
	}
	if(_type == 'dir'){
		var url = '<?php echo $fm;?>?dir=' + _path + '&fn=' + _filename + '&prev=<?php echo rawurlencode($currrent);?>';
		window.location = url;
	}
}
function rename_file(_old,_path){
	var _new = prompt("Enter new filename:",_old);
	if(_new != null || _new!=_old){
		var c = confirm('Are you sure you want to rename this?');
		if(c == true){
			var url = '<?php echo $fm;?>?dir=<?php echo rawurlencode($dir);?>&old=' + _old + '&new=' + _new + '&prev=<?php echo rawurlencode($currrent);?>&action=rename';
			window.location = url;
		}
	}
}
function create_folder(){
	var f = prompt("Enter folder name:");
	if(f != null){
		var url = '<?php echo $fm;?>?dir=<?php echo rawurlencode($dir);?>&name=' + f + '&prev=<?php echo rawurlencode($currrent);?>&action=create-folder';
		window.location = url;
	}
}
function delete_file(_name){
	var c = confirm('Are you sure you want to delete this?');
	if(c == true){
		var url = '<?php echo $fm;?>?dir=<?php echo rawurlencode($dir);?>&name=' + _name + '&prev=<?php echo rawurlencode($currrent);?>&action=delete';
		window.location = url;
	}
}
</script>
<table id="gallery-viewer" cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
        <td width="100%" style="vertical-align:top;">
        	<?php load_documentations(); ?>
        	<h2><?php echo $title;?></h2>
            <?php			
			// Breadcrumb
			if(GBL_ROOT != $dir){
				$c = str_replace(GBL_ROOT.'/','',$dir);
				$c = explode('/',$c);
				$d = '';
				$_c = array();
				for($i=0;$i < count($c);$i++){					
					if($c[$i]!=''){
						$d.= '/'.$c[$i];
						$_c[] = array('p' => $d,'fn' => $c[$i]);
					}
				}
				$d = '';
				for($i=0;$i < count($_c);$i++){
					if($_c[$i]['fn'] == $_REQUEST['fn']){
						$d.= '<span class="sep">&raquo;</span><span style="font-weight:bold;">'.$_c[$i]['fn'].'</span>';
					}else{
						$d.= '<span class="sep">&raquo;</span><a href="javascript:open_file('.chr(39).rawurlencode($_c[$i]['p']).chr(39).','.chr(39).rawurlencode($_c[$i]['fn']).chr(39).','.chr(39).'dir'.chr(39).')">'.$_c[$i]['fn'].'</a>';						
					}
				}
				;
				echo '<div id="breadcrumb"><a href="'.$fm.'">ROOT</a>'.$d.'</div>';
			}
            ?>
            
            <?php _d($action_message->message,'<div class="messagebox '.$action_message->class.'">','</div>');?>
        	<table id="directory" cellpadding="0" cellspacing="0" border="0">
            	<tr>
                	<th class="col1">Name</th>
                    <th class="col2">Size</th>
                    <th class="col3">Last Modified</th>
                    <th class="col4">Action</th>
                </tr>
			</table>
            <div id="direxplorer">
                <table id="directory" cellpadding="0" cellspacing="0" border="0" style="border:none;">
					<?php if($_REQUEST['dir']!=''):?>
                        <tr><td colspan="4" class="col6"><a href="<?php echo $b;?>" style="background:url('<?php get_siteinfo('url');?>/backend/modules/filemanager/icon/icon_back.gif') no-repeat 0 3%;padding-left:20px;color:#333333;">Previous</a></td></tr>
                    <?php endif;?>
                    <?php if($dir_count > 0):?>
                        <?php foreach($dirs as $dkey => $dval):?>
							<?php foreach($dval as $key => $val):// Dirs ?>
                            <tr class="dir <?php echo $val['type'];?>">
                            	<?php							
								$path = rawurldecode($val['path']);
								$fnp = str_replace(GBL_ROOT,'',$path);
								$tp = 'txt';
								if(in_array($val['extension'],array('png','jpg','jpeg','bmp','gif'))){
									$tp ='image';
								}elseif($val['type'] == 'dir'){
									$tp ='dir';
								}
								$t = 'javascript:open_file(\''.$fnp.'\',\''.$val['fn'].'\',\''.$tp.'\');';
								$d = 'javascript:delete_file(\''.$val['name'].'\');';
                                ?>
                                <td class="col1"><img src="<?php echo $val['icon'];?>" /> <a href="<?php echo $t;?>"><?php echo $val['name'];?></a></td>
                                <td class="col2"><?php echo $val['size'];?></td>
                                <td class="col3"><?php echo $val['modified_date'];?></td>
                                <td class="col4">
                                <?php if(!in_array($val['name'],$non_editable)):?>
                                    <?php $action = $fm.'?file='.rawurlencode(str_replace(GBL_ROOT.'/','',$val['path'])).'&dir='.rawurlencode($currrent).'&fn='.rawurlencode($val['fn']);?>
                                    <a href="javascript:rename_file('<?php echo rawurlencode($val['name']);?>','<?php echo rawurlencode(str_replace(GBL_ROOT.'/','',$val['path']));?>');">Rename</a> | <a href="<?php echo $d;?>">Delete</a>
                                <?php endif;?>
                                </td>
                            </tr>
                            <?php endforeach;?>
						<?php endforeach;?>
                    <?php else:?>
                        <tr>
                            <td colspan="4" class="col5">Unable to load directory. Please <strong>Reload</strong> the page.</td>
                        </tr>
                    <?php endif;?>
                </table>                
			</div>
            <div style="padding-top:10px;"></div>
            <input type="button" value="NEW FOLDER" style="font-size:12px;" onclick="create_folder();" />&nbsp;
            <input type="button" value="UPLOAD FILES" style="font-size:12px;" onclick="window.location='<?php echo $fm;?>?mod=upload<?php echo ($upload_path!=''?'&path='.$upload_path : '');?>';" />
        </td>
    </tr>
</table>