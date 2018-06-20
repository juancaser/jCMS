<?php
if(!defined('IMPARENT')){exit();} // No direct access
if(!defined('JSTORE')){exit();} // No direct access
$title = 'Email Templates';


$filter = '';
$url = BACKEND_DIRECTORY.'/components.php?comp='.$_REQUEST['comp'].'&mod='.$_REQUEST['mod'];					
$numrows = jcms_db_get_row("SELECT COUNT(*) AS _count FROM #_store_email_template".($filter!=''? ' AND '.$filter : ''))->_count;
$sql = "SELECT ID,etpl_title AS title,etpl_content AS content,etpl_type AS tpl_type,etpl_meta,DATE_FORMAT(etpl_date_added,'%Y/%m/%d') AS date_added FROM #_store_email_template".($filter!=''? ' AND '.$filter : '');
$templates = jcms_db_pagination($sql,$numrows,15,$url);
$tpl_count = count($templates->results);
?>
<form id="template-viewer" method="post" action="<?php echo BACKEND_DIRECTORY;?>/components.php"> 
    <table class="viewer" cellpadding="0" cellspacing="0" border="0">
    	<tr>
        	<td class="sidebar">
                <div class="box">
                    <div class="title">Templates</div>
                    <div class="content">
	                    <p style="margin-bottom:5px;"><input class="button" type="button" value="New Template" onclick="window.location='<?php echo BACKEND_DIRECTORY;?>/components.php?comp=<?php echo JSTORE_ID;?>&mod=emailer&opt=edit';" /></p>
                    </div>
                </div>                
            </td>
        	<td class="content">
            	<h2>Templates</h2>
                <?php _d($action_message->message,'<div class="messagebox '.$action_message->class.'">','</div>');?>
                <table class="table-lists" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <th class="col1"><input type="checkbox" class="check_all" /></th>
                        <th class="col2">Template Name</th>
                        <th class="col3">Type</th>
                        <th class="col4">Date Added</th>
                    </tr>
                    <?php if($tpl_count > 0):?>
						<?php for($i=0;$i < $tpl_count;$i++): $tpl = $templates->results[$i];?>
                        <tr class="list">
                            <td class="col1"><input type="checkbox" name="chk[]" class="chk" value="<?php echo $tpl->ID;?>" /></td>
                            <td class="col2">
                            	<span class="title"><a href="<?php echo BACKEND_DIRECTORY;?>/components.php?comp=<?php echo $_REQUEST['comp'];?>&mod=emailer&opt=edit&id=<?php echo $tpl->ID;?>"><?php echo $tpl->title;?></a></span>
                                <div class="control">
                                	<a href="<?php echo BACKEND_DIRECTORY;?>/components.php?comp=<?php echo $_REQUEST['comp'];?>&mod=emailer&opt=edit&id=<?php echo $tpl->ID;?>">Edit</a> | 
                                    <a href="<?php echo BACKEND_DIRECTORY;?>/components.php?comp=<?php echo $_REQUEST['comp'];?>&mod=emailer&opt=edit&chk[]=<?php echo $tpl->ID;?>">Delete</a>
                                 </div>
                            </td>
                            <td class="col3"><?php echo ($tpl->tpl_type == 'html' ? 'HTML' : 'TEXT');?></td>
                            <td class="col4"><?php echo $tpl->date_added;?></td>
                        </tr>
                        <?php endfor;?>
                    <?php else:?>
                        <tr class="notify">
                            <td colspan="3">No email template available.</td>
                        </tr>
                    <?php endif;?>
                    <tr>
                        <th class="col1"><input type="checkbox" class="check_all" /></th>
                        <th class="col2">Template Name</th>
                        <th class="col3">Type</th>
                        <th class="col4">Date Added</th>
                    </tr>
                </table>
                <div style="margin-top:5px;" class="left"><?php if($_REQUEST['id']!=''): ?>
                <?php
				$back = BACKEND_DIRECTORY.'/components.php?comp='.JSTORE_ID.'&mod=item';
				if($parent->parent > 0){
					$back = $back.'&id='.$parent->parent;
				}
                ?>                
                <input type="button" value="Back" onclick="window.location='<?php echo $back;?>';" />
                <?php endif; ?>&nbsp;<input type="submit" value="Delete" /></div>
                <div id="pagination" class="right" style="margin-top:5px;">
					<?php echo $tpl_count->pages; ?>
                </div>
                <div class="clear"></div>

            </td>
        </tr>
    </table>
    <input type="hidden" name="comp" value="<?php echo JSTORE_ID;?>" />
    <input type="hidden" name="mod" value="item" />
    <input type="hidden" name="action" value="delete" />
</form>