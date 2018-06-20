<?php
if(!defined('JCMS')){exit();} // No direct access
if(!defined('JSTORE')){exit();} // No direct access

$userinfo = $_SESSION['__USER']['info'];


if($_REQUEST['id'] > 0){
	$tpl = get_etpl($_REQUEST['id']);
	$meta = trim($tpl->etpl_meta);	
	$meta = (object) unserialize($meta);
}
$title = ($_REQUEST['id'] > 0 ? 'Update ' : 'New ').' Email Template';
?>
<script type="text/javascript">
	$(document).ready(function(){
    });
</script>
<form id="template-editor" method="post" action="<?php echo BACKEND_DIRECTORY;?>/components.php"> 
    <table class="editor" cellpadding="0" cellspacing="0" border="0">
    	<tr>
        	<td class="sidebar">
                <div class="box">
                    <div class="title">Template</div>
                    <div class="content">
						<?php if($_REQUEST['id'] > 0):?>
                        <p><input type="submit" class="button" value="Update" /></p>
                        <?php else:?>                        
                        <p><input type="submit" class="button" value="Save" /></p>                    
                        <?php endif;?>
                        <p><input type="button" class="button" value="Back" onclick="window.location='<?php echo CURRENT_PAGE;?>?comp=<?php echo $_REQUEST['comp'];?>&mod=<?php echo $_REQUEST['mod'];?>';" /></p>
                        <div class="option">
                            <p><label for="etpl_type">Type</label>
                            <select name="template[etpl_type]" id="etpl_type">
                                    <option value="text"<?php echo ($tpl->etpl_type == 'text' ? ' selected="selected"' : '');?>>Text</option>
                                    <option value="html"<?php echo ($tpl->etpl_type == 'html' ? ' selected="selected"' : '');?>>HTML</option>
                            </select></p>
                        </div>
                    </div>
                </div>
            </td>
        	<td class="content">
            	<h2><?php echo $title;?></h2>
                <?php _d($action_message->message,'<div class="messagebox '.$action_message->class.'">','</div>');?>
                <div id="form-messagebox"></div>
                <p><label for="etpl_title">Template Name</label>
                    <div class="etpl_title">
                        <input class="autocomplete-off required check-slug" type="text" name="template[etpl_title]" id="etpl_title" value="<?php echo $tpl->etpl_title;?>" autocomplete="off" />
                    </div>                    
                </p>
                <p><label for="etpl_content">Content</label>
                <?php
				$content = stripslashes(html_entity_decode($tpl->etpl_content));
				if($tpl->etpl_type == 'text'){
					$content = strip_tags($content);
				}
				
                ?>
                <textarea name="template[etpl_content]" id="etpl_content"><?php echo $content;?></textarea></p>
            </td>
        </tr>
    </table>
    <?php if($_REQUEST['id'] > 0):?>
    <input type="hidden" name="template[id]" value="<?php echo $_REQUEST['id'];?>" />
    <?php endif;?>    
    <input type="hidden" name="action" value="save" />
    <input type="hidden" name="form" value="components" />
    <input type="hidden" name="comp" value="<?php echo JSTORE_ID;?>" />
    <input type="hidden" name="mod" value="<?php echo $_REQUEST['mod'];?>" />
    <input type="hidden" name="opt" value="edit" />
</form>