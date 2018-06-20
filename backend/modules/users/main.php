<?php
if(!defined('JCMS')){exit();} // No direct access
$userinfo = $_SESSION['__USER']['info'];
global $users_group;

$filter = '';

$url = BACKEND_DIRECTORY.'/users.php';
$numrows = jcms_db_get_row("SELECT COUNT(*) AS _count FROM ".$GLOBALS['users_tbl_name'].($filter!=''? ' WHERE '.$filter : ''))->_count;
$sql = "SELECT *,DATE_FORMAT(date_registered,'%Y/%m/%d') AS date_registered  FROM ".$GLOBALS['users_tbl_name'].($filter!=''? ' WHERE '.$filter : '');
$users = jcms_db_pagination($sql,$numrows,15,$url);
$user_count = count($users->results);
?>
<form id="users-viewer" method="post" action="<?php echo BACKEND_DIRECTORY;?>/users.php"> 
    <table class="viewer" cellpadding="0" cellspacing="0" border="0">
    	<tr>
        	<td class="sidebar">
                <div class="box">
                    <div class="title">Users</div>
                    <div class="content">
	                    <p style="margin-bottom:5px;"><input class="button" type="button" value="Add User" onclick="window.location='<?php echo CURRENT_PAGE;?>?mod=edit';" /></p>
                    </div>
                </div>                
            </td>
        	<td class="content">
            	<?php load_documentations(); ?>
            	<h2><?php echo $title;?></h2>
                 <?php _d($action_message->message,'<div class="messagebox '.$action_message->class.'">','</div>');?>
                <table class="table-lists" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <th class="col1"><input type="checkbox" class="check_all" /></th>
                        <th class="col2">Name</th>
                        <th class="col3">Date Joined</th>
                    </tr>

                    <?php if($user_count > 0):?>
                    	<?php $oe = 'odd';?>
						<?php for($i=0;$i < $user_count;$i++): $user = $users->results[$i];?>
                            <tr class="<?php echo ($user->status == '' ? 'for-activation' : ($user->status == '0' ? 'disabled' : 'list')).' '.$oe;?>">
                                <td class="col1"><?php if($user->user_name != 'admin'){?><input type="checkbox" name="chk[]" class="chk" value="<?php echo $user->ID;?>" /><?php }?></td>
                                <td class="col2">
                                    <span class="title"><a href="javascript:void(0);"><?php echo $user->display_name;?></a> (<?php echo $user->user_name;?>)</span>
                                    <div class="author"><strong>Group:</strong> <em><?php echo $users_group[$user->user_group];?></em></div>
                                    <div class="email"><strong>Email:</strong> <em><?php echo $user->email_address;?></em></div>
                                    <div class="control">
                                        <a href="<?php echo BACKEND_DIRECTORY;?>/users.php?mod=edit&id=<?php echo $user->ID;?>">Edit</a> | 
                                        <a href="<?php echo BACKEND_DIRECTORY;?>/users.php?action=delete&chk[]=<?php echo $user->ID;?>">Delete</a>
                                     </div>
                                </td>
                                <td class="col3"><?php echo $user->date_registered;?>
                                    <div class="status"><?php echo ($user->status == '' ? 'For Activation' :($user->status == '1' ? 'Active' : 'Disabled'));?></div>
                                </td>
                            </tr>
                            <?php $oe = ($oe == 'odd' ? 'even' : 'odd');?>
                        <?php endfor;?>
                    <?php else:?>
                        <tr class="notify">
                            <td colspan="3">No page found.</td>
                        </tr>
                    <?php endif;?>
                    <tr>
                        <th class="col1"><input type="checkbox" class="check_all" /></th>
                        <th class="col2">Name</th>
                        <th class="col3">Date Joined</th>

                    </tr>
                </table>
                <div style="margin-top:5px;" class="left"><input type="submit" value="Delete" /></div>
                <div id="pagination" class="right" style="margin-top:5px;">
					<?php echo $users->pages; ?>
                </div>
                <div class="clear"></div>
            </td>
        </tr>
    </table>
    <input type="hidden" name="action" value="delete" />
</form>