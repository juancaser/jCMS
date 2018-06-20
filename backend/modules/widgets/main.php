<?php
if(!defined('JCMS')){exit();} // No direct access
$userinfo = $_SESSION['__USER']['info'];
global $users_group;
$filter = '';

$url = BACKEND_DIRECTORY.'/widgets.php';

$numrows = jcms_db_get_row("SELECT COUNT(*) AS _count FROM ".$GLOBALS['widgets_tbl_name'].($filter!=''? ' WHERE '.$filter : ''))->_count;
$sql = "SELECT *,DATE_FORMAT(date_added,'%Y/%m/%d') AS date_added FROM ".$GLOBALS['widgets_tbl_name'].($filter!=''? ' WHERE '.$filter : '');
$widgets = jcms_db_pagination($sql,$numrows,15,$url);
$widget_count = count($widgets->results);
?>
<form id="users-viewer" method="post" action="<?php echo BACKEND_DIRECTORY;?>/pages.php"> 
    <table class="viewer" cellpadding="0" cellspacing="0" border="0">
    	<tr>
        	<td class="sidebar">
                <div class="box">
                    <div class="title">Widgets</div>
                    <div class="content">
	                   <p style="margin-bottom:5px;"><input class="button" type="button" value="Add Widget" onclick="window.location='<?php echo CURRENT_PAGE;?>?mod=edit';" /></p>
                    </div>
                </div>                
            </td>
        	<td class="content">
            	<h2><?php echo $title;?></h2>
                 <?php _d($action_message->message,'<div class="messagebox '.$action_message->class.'">','</div>');?>
                <table class="table-lists" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <th class="col1"><input type="checkbox" class="check_all" /></th>
                        <th class="col2">Name</th>
                        <th class="col3">Date Added</th>
                    </tr>

                    <?php if($widget_count > 0):?>
				    <?php for($i=0;$i < $widget_count;$i++): $widget = $widgets->results[$i];?>
                        <tr class="list">
                            <td class="col1"><input type="checkbox" name="chk[]" class="chk" value="<?php echo $widget->ID;?>" /></td>
                            <td class="col2">
                            	<span class="title"><a href="javsacript:void(0);"><?php echo $widget->widget_name;?></a></span>
                                <div class="control">
                                	<a href="<?php echo BACKEND_DIRECTORY;?>/users.php?mod=edit&id=<?php echo $widget->ID;?>">Edit</a> | 
                                    <a href="<?php echo BACKEND_DIRECTORY;?>/users.php?action=delete&chk[]=<?php echo $widget->ID;?>">Delete</a>
                                 </div>
                            </td>
                            <td class="col3"><?php echo $widget->date_added;?></td>
                        </tr>
                        <?php endfor;?>
                    <?php else:?>
                        <tr class="notify">
                            <td colspan="3">No widget found.</td>
                        </tr>
                    <?php endif;?>
                    <tr>
                        <th class="col1"><input type="checkbox" class="check_all" /></th>
                        <th class="col2">Name</th>
                        <th class="col3">Date Added</th>

                    </tr>
                </table>
                <div style="margin-top:5px;" class="left"><input type="submit" value="Delete" /></div>
                <div id="pagination" class="right" style="margin-top:5px;">
					<?php echo $widget->pages; ?>
                </div>
                <div class="clear"></div>
            </td>
        </tr>
    </table>
    <input type="hidden" name="action" value="delete" />
</form>