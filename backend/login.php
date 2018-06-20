<?php
include('backend-load.php'); // Backend bootstrap loader

$login_message = get_global_mesage('login');

set_backend_page('Login');
the_backend_header(); ?>

<div id="login">
	<script type="text/javascript">
		function submit_form(){
			document.getElementById('frmLogin').submit();
		}
    </script>
	<form id="frmLogin" class="login" method="post" action="<?php echo CURRENT_PAGE;?>">
    	<div class="login-wrapper">
            <div class="login-header">
            	<?php
				$blogo = '';
				if(file_exists(GBL_ROOT_TEMPLATE.'/backend-logo.gif')){
					$blogo = get_option('site_url').'/template/backend-logo.gif';
				}
                ?>
	            <a href="<?php echo get_option('site_url');?>" title="<?php echo get_option('site_name').' - '.get_option('site_description');?>" class="logo" style="background-image:url('<?php echo $blogo;?>');"></a>
	            <div class="name">Backend Control Panel</div>
                <div class="clear"></div>
            </div>
            <div class="login-container">
				<?php
                $user_name = '';
                if($_COOKIE['_jcms'] != ''){
                    $user = get_user($_COOKIE['_jcms']);
                    $user_name = $user->user_name;
                    print_r($user_name);
                }
                ?>
                <?php _d($login_message->message,'<div class="messagebox '.$login_message->class.'">','</div>');?>
                <p><label for="user_name"><?php _l('username','Email Address/User name');?></label><input type="text" name="user_name" id="user_name" autocomplete="off" value="<?php echo $user_name;?>" /></p>
                <p><label for="user_password"><?php _l('password','Password');?></label><input type="password" name="user_password" id="user_password" /></p>
                <p style="text-align:right;padding-top:20px;"><input onclick="window.location='<?php echo get_option('site_url');?>';" class="button" type="button" value="Back to Homepage" />&nbsp;<input class="button" type="button" value="Login" onclick="submit_form();" /></p>
            </div>
        </div>        
        <input type="hidden" name="form" value="login" />        
        <input type="hidden" name="action" value="user_auth" />
    </form>
</div>
<div id="login_copyright">&copy; <?php echo date('Y');?> <?php echo get_option('site_name');?></div>

<?php the_backend_footer(); ?>