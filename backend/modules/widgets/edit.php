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
                            <p><input class="button" type="submit" value="Update" /></p>
                        <?php else:?>
                            <p><input class="button" type="submit" value="Save" /></p>
                        <?php endif; ?>
                        <?php if($user->ID > 0 && $user->status ==''): ?>
                        <p><input class="button" type="submit" value="Activate" id="activate" /></p>
                        <?php endif; ?>
                        <p><input class="button" type="button" value="Back" onclick="window.location='<?php echo BACKEND_DIRECTORY;?>/users.php';" /></p>
                    </div>
                </div>
            </td>
        	<td class="content">
            	<h2><?php echo $title;?></h2>
			<?php _d($action_message->message,'<div class="messagebox '.$action_message->class.'">','</div>');?>                
                <table id="user-fields" cellpadding="0" cellspacing="0" border="0">
                	<tr>
                    	<td class="col1"><label for="display_name">Display Name</label></td>
                    	<td class="col2"><input class="required fields" type="text" name="display_name" id="display_name" value="<?php echo $user->display_name;?>" /></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
	<input type="hidden" name="action" value="save" />
	<input type="hidden" name="mod" value="edit" />
</form>