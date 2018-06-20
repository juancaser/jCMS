<?php
if(!defined('JCMS')){exit();} // No direct access
$userinfo = $_SESSION['__BACKEND_USER']['info'];
if($_REQUEST['id'] > 0){
	$user = get_user($_REQUEST['id']);
	//print_r($user);
}
?>
<script type="text/javascript">
	$(document).ready(function(){
		$('#user_name').wrap('<div id="ajax_user_name"/>');
		$('#user_name').blur(function(){			
			var defaultValue = $(this).attr('defaultValue');
			$this = $(this);
			if($this.val() != defaultValue){
				$.ajax({
					type: 'POST',
					url: '<?php get_siteinfo('url');?>/backend/ajax.php',
					data: 'action=check_user&data=' + $(this).val(),
					beforeSend: function(data){
						$('#ajax_user_name > .overlay').remove();
						$('#ajax_user_name > .msg').remove();
						$('#ajax_user_name > .loader').remove();	
						$('#ajax_user_name').prepend('<div class="overlay"></div>');
						$('#ajax_user_name').append('<img class="loader" src="<?php get_siteinfo('url');?>/backend/images/loader-1.gif" />');
					},success: function(data){
						$('#ajax_user_name > .overlay').remove();
						$('#ajax_user_name > .msg').remove();
						var obj = $.parseJSON(data);
						if(obj.status == '1'){							
							$('#ajax_user_name > .loader').attr('src','<?php get_siteinfo('url');?>/backend/images/icon-error-16.png');
							$('#ajax_user_name').append('<span class="msg">NAME ALREADY EXISTS</span>');
							$('#user_name').addClass('error');
						}else{
							$('#ajax_user_name > .loader').attr('src','<?php get_siteinfo('url');?>/backend/images/icon-check-16.png');
							$('#ajax_user_name').append('<span class="msg">OK</span>');
							$('#user_name').val(obj.slug);
							$('#user_name').removeClass('error');
						}
					}
				});
			}else if($this.val() == ''){
				$('#ajax_user_name > .overlay').remove();
				$('#ajax_user_name > .msg').remove();
				$('#ajax_user_name > .loader').remove();
			}
		});
		$('#activate').click(function(){
			$('#status').val('1');
			$('#users-editor').submit();
		});
    });
</script>
<form id="users-editor" method="post" action="<?php echo BACKEND_DIRECTORY;?>/users.php"> 
    <table class="editor" cellpadding="0" cellspacing="0" border="0">
    	<tr>
        	<td class="sidebar">
                <div class="box">
                    <div class="title">Users</div>
                    <div class="content">
                        <?php if($_REQUEST['id']!=''): ?>
                            <p><input class="button" type="button" value="Update" onclick="document.getElementById('users-editor').submit();" /></p>
                        <?php else:?>
                            <p><input class="button" type="button" value="Add" onclick="document.getElementById('users-editor').submit();" /></p>
                        <?php endif; ?>
                        <?php if($user->ID > 0 && $user->status ==''): ?>
                        <p><input class="button" type="submit" value="Activate" id="activate" /></p>
                        <?php endif; ?>
                        <p><input class="button" type="button" value="Back" onclick="window.location='<?php echo BACKEND_DIRECTORY;?>/users.php';" /></p>
                    </div>
                </div>
            </td>
        	<td class="content">
            	<?php load_documentations(); ?>               
            	<h2><?php echo $title;?></h2>                
                <?php if($user->ID > 0 && $user->status == ''): ?>
                	<div class="messagebox success">This user profile is waiting for activation.</div>
                <?php elseif($user->status == '0'): ?>
                	<div class="messagebox success">This user profile is currently disabled.</div>
                <?php else:?>
					<?php _d($action_message->message,'<div class="messagebox '.$action_message->class.'">','</div>');?>                
                    <div id="form-messagebox"></div>
                <?php endif;?>
                <table id="user-fields" cellpadding="0" cellspacing="0" border="0">
                	<tr>
                    	<td class="col1"><label for="user_name">Username</label></td>
                    	<td class="col2">
						<?php if($user->ID > 0):?>
                            <?php if(in_array($userinfo->user_group,array('ghosts','admin'))):?>
                                <input class="required fields" type="text" name="user_name" id="user_name" value="<?php echo $user->user_name;?>" />
                            <?php else:?>
                                <span style="font-weight:bold;"><?php echo $user->user_name;?></span>
                                <input type="hidden" name="user_name" value="<?php echo $user->user_name;?>" />
                            <?php endif;?>
                        <?php else:?>
	                        <input class="required fields" type="text" name="user_name" id="user_name" value="" />
                        <?php endif;?>
                        </td>
                    </tr>
                	<tr>
                    	<td class="col1"><label for="display_name">Display Name</label></td>
                    	<td class="col2"><input class="required fields" type="text" name="display_name" id="display_name" value="<?php echo $user->display_name;?>" /></td>
                    </tr>
                	<tr>
                    	<td class="col1"><label for="email_address">Email Address</label></td>
                    	<td class="col2"><input class="required fields" type="text" name="email_address" id="email_address" value="<?php echo $user->email_address ;?>" /></td>
                    </tr>
                	<tr>
                    	<td class="col1"><label for="user_password">Password</label></td>
                    	<td class="col2"><input class="<?php echo ($user->ID > 0 ? '': 'required ');?>fields" type="password" name="user_password" id="user_password" /></td>
                    </tr>
					<?php if(in_array($userinfo->user_group,array('ghosts','admin'))):?>
                        <tr>
                            <td><label for="user_group">User Group</label></td>
                            <td><select name="user_group" id="user_group" class="required fields" style="width:160px;"><?php
                            global $users_group;
                            foreach($users_group as $key => $value){
                                echo '<option value="'.$key.'"'.($user->user_group == $key ? ' selected="selected"' : (($user->user_group == '' && $key == 'users') ? ' selected="selected"' : '')).'>'.$value.'</option>';
                            }
                            ?></select></td>
                        </tr>
                    <?php else:?>
                        <input type="hidden" name="user_group" value="<?php echo ($user->user_group!=''?$user->user_group:'users');?>" />
                    <?php endif;?>
                	<tr>
                    	<td><label for="user_sex">Sex</label></td>
                    	<td><select name="user_sex" id="user_sex" class="required fields" style="width:90px;">
                        <option value="male"<?php echo ($user->user_sex  == 'male' ? ' selected="selected"' : '');?>>Male</option>
                        <option value="female"<?php echo ($user->user_sex  == 'female' ? ' selected="selected"' : '');?>>Female</option>
                        </select></td>
                    </tr>
					<?php if(in_array($userinfo->user_group,array('ghosts','admin'))):?>
                        <tr>
                            <td><label>Backend Access</label></td>
                            <td>
                                <label><input type="radio" value="yes" name="backend_access"<?php echo ($user->backend_access  == 'yes' ? ' checked="checked"' : '');?> /> Yes</label>
                                <label><input type="radio" value="no" name="backend_access"<?php echo ($user->backend_access  == 'no' ? ' checked="checked"' : (($user->backend_access  == '' || $user->backend_access  != 'yes') ? ' checked="checked"' : ''));?> /> No</label>
                            </td>
                        </tr>
                    <?php else:?>
                        <input type="hidden" name="backend_access" value="no" />
                    <?php endif;?>
                    <?php if(in_array($userinfo->user_group,array('ghosts','admin'))):?>
                        <tr>
                            <td><label for="status">Status</label></td>
                            <td><select name="status" id="status" class="required fields" style="width:110px;">
                            <option value="">For Active</option>
                            <option value="1"<?php echo ($user->status  == '1' ? ' selected="selected"' : '');?>>Active</option>
                            <option value="0"<?php echo ($user->status  == '0' ? ' selected="selected"' : '');?>>Disabled</option>
                            </select></td>
                        </tr>
                    <?php else:?>                    	
                    	<input type="hidden" id="status" name="status" value="" />
                    <?php endif;?>
                </table>
            </td>
        </tr>
    </table>
    <?php if($user->ID > 0):?>
    <input type="hidden" name="id" value="<?php echo $user->ID;?>" />
    <?php endif;?>
    <input type="hidden" name="action" value="save" />
    <input type="hidden" name="mod" value="edit" />
    <input type="hidden" name="ip_address" value="<?php echo ($user->ip_address !='' ? $user->ip_address : get_ipaddress()); ?>" />
</form>